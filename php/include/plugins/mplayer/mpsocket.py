#!/usr/bin/python
# -*- mode:python; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*-
# ex: filetype=python tabstop=2 softtabstop=2 shiftwidth=2 expandtab autoindent smartindent

# Modules
import sys
import subprocess
import Queue
import threading
import socket
import select
import time
POSIX = 'posix' in sys.builtin_module_names

# Parameters
DEBUG = False
mplayerExecutable = 'mplayer'
serverSocketAddressPort = ( 'localhost', 5000 )

# Utilities
def threadReadLines( fd, queue, prefix ):
  for line in iter( fd.readline, '' ):
    queue.put( prefix+line )

def socketReceiveLines( clientSocket ):
  lines = []
  while any( select.select( [ clientSocket ], [], [], 0.01 ) ):
    try:
      clientData = clientSocket.recv( 1024 ).strip()
    except socket.error:
      return False
    if clientData:
      lines.append( clientData )
    else:
      return False
  return lines

def queueReadLines( queue ):
  lines = []
  while True:
    try:
      line = queue.get_nowait()
    except Queue.Empty:
      break
    lines.append( line )
  return lines

# Start MPlayer in slave mode
# NOTE: Let's use threads to achieve non-blocking read of stdout/stderr
# ... process
mplayerExecutable = [ mplayerExecutable, '-nolirc', '-nojoystick', '-slave', '-quiet', '-idle' ]
for arg in sys.argv[1:]:
  mplayerExecutable.append( arg )
mplayerProcess = subprocess.Popen( mplayerExecutable, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, bufsize=1, close_fds=POSIX )
# ... queue
mplayerQueue = Queue.Queue()
# ... stdout thread
mplayerThreadStdout = threading.Thread( target=threadReadLines, args=( mplayerProcess.stdout, mplayerQueue, 'OUT:' ) )
mplayerThreadStdout.daemon = True
mplayerThreadStdout.start()
# ... stderr thread
mplayerThreadStderr = threading.Thread( target=threadReadLines, args=( mplayerProcess.stderr, mplayerQueue, 'ERR:' ) )
mplayerThreadStderr.daemon = True
mplayerThreadStderr.start()
if DEBUG:
  print '[DEBUG] MPlayer now launched in slave mode'

# Open TCP socket
serverSocket = socket.socket( socket.AF_INET, socket.SOCK_STREAM )
serverSocket.setsockopt( socket.SOL_SOCKET, socket.SO_REUSEADDR, 1 )
serverSocket.bind( serverSocketAddressPort )
serverSocket.listen( 5 )
if DEBUG:
  print '[DEBUG:CX] Listening on',serverSocketAddressPort

# Main loop
mplayerProcessAlive = True
while mplayerProcessAlive:
  clientSocket, clientSocketAddressPort = serverSocket.accept()
  if DEBUG:
  	print '[DEBUG:CX] New connection from',clientSocketAddressPort

  # Client connection loop
  clientSocket.setblocking( 0 )
  clientConnectionAlive = True
	while ( mplayerProcessAlive and clientConnectionAlive ):
    # Check MPlayer process status
    if ( mplayerProcess.poll() != None ):
      mplayerProcessAlive = False
      break

    # (non-blocking) read of client connection's input
    clientData = socketReceiveLines( clientSocket )
    if ( clientData == False ):
      clientConnectionAlive = False
      break
    for line in clientData:
      if DEBUG:
        print '[DEBUG:RX] IN:'+line
      if ( line == 'exit' ):
        clientConnectionAlive = False
        break
      mplayerProcess.stdin.write( line+'\n' )

    # (non-blocking) read of MPlayer process's output
    for line in queueReadLines( mplayerQueue ):
      if DEBUG:
        print '[DEBUG:TX] '+line,
      clientSocket.send( line )

    # Loop
    time.sleep( 0.01 )

  clientSocket.close()
  if DEBUG:
	  print '[DEBUG:CX] Connection closed',clientSocketAddressPort

# Close TCP socket	
serverSocket.shutdown( socket.SHUT_RDWR )
serverSocket.close()

# Terminate MPlayer process
# no need; if we get there, MPlayer is dead

