<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:

  /** PHP Universal Remote Control (PHP-URC)
   *
   * <P><B>COPYRIGHT:</B></P>
   * <PRE>
   * PHP Universal Remote Control (PHP-URC)
   * Copyright (C) 2008 Cedric Dufour
   * GNU General Public Licence (GPL), Version 3
   *
   * The PHP Universal Remote Control (PHP-URC) is free software:
   * you can redistribute it and/or modify it under the terms of the GNU General
   * Public License as published by the Free Software Foundation, Version 3.
   *
   * The PHP Universal Remote Control (PHP-URC) is distributed in the hope
   * that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
   * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU General Public License (LICENSE.TXT) for more details.
   * </PRE>
   *
   * @package PHP_URC_Plugins
   * @subpackage MPD
   * @author Cedric Dufour <http://cedric.dufour.name>
   * @version @version@
   */


  /** Load URC interface class */
require_once( URC_INCLUDE_PATH.'/urc.interface.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.player.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playlist.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.php' );


/** URC Music Player Daemon (MPD) interface
 *
 * @package PHP_URC_Plugins
 * @subpackage MPD
 */
class URC_INTERFACE_MPD extends URC_INTERFACE implements
URC_INTERFACE_PLAYER, URC_INTERFACE_PLAYLIST, URC_INTERFACE_PLAYITEM
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** MPD backend host
   * @var string */
  private $sSocketHost = null;

  /** MPD backend port
   * @var integer */
  private $iSocketPort = null;

  /** MPD backend password
   * @var string */
  private $sSocketPassword = null;

  /** MPD backend (socket) timeout [seconds]
   * @var float */
  private $fSocketTimeout = null;

  /** MPD backend error
   * @var boolean */
  private $bSocketError = false;

  /** MPD socket
   * @var resource */
  private $rSocket = null;

  /** Current status; player
   * @var array|mixed */
  private $amStatus = null;

  /** Current playitem: meta-data
   * @var array|mixed */
  private $asPlayitemMetadata = null;

  /** Current playlist: entries
   * @var array|string */
  private $asPlaylist = null;


  /*
   * FIELDS: static
   ********************************************************************************/

  /** Interface singleton
   * @var URC_INTERFACE_MPD */
  private static $oINTERFACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the interface
   */
  private function __construct()
  {
    $this->resetStatus();
    $this->resetPlayitemMetadata();
    $this->resetPlaylist();
  }

  /** Destructs the interface
   */
  public function __destruct()
  {
    if( isset( $this->rSocket ) )
    {
      $this->sendCommand( 'close' );
      @fclose( $this->rSocket );
    }
  }


  /*
   * METHODS: factory - OVERRIDE
   ********************************************************************************/

  /** Returns a (singleton) interface instance (<B>as reference</B>)
   *
   * @return URC_INTERFACE_MPD
   */
  public static function &useInstance()
  {
    if( is_null( self::$oINTERFACE ) ) self::$oINTERFACE = new URC_INTERFACE_MPD();
    return self::$oINTERFACE;
  }


  /*
   * METHODS: backend
   ********************************************************************************/

  /** Initializes the MPD backend parameters
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHost MPD service's hostname/IP
   * @param integer $iPort MPD service's port
   * @param string $sPassword MPD service's password
   * @param float $fTimeout Socket connection timeout [seconds]
   */
  public function initBackend( $sHost, $iPort, $sPassword, $fTimeout = 5 )
  {
    // Sanitize/validate input
    $sHost = trim( $sHost );
    if( empty( $sHost ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: missing host IP/name' );
    $iPort = (integer)$iPort;
    if( $iPort <= 0 or $iPort > 65536 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: invalid port ('.$iPort.')' );
    $sPassword = (string)$sPassword;
    $fTimeout = (float)$fTimeout;
    if( $fTimeout <= 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: invalid timeout ('.$fTimeout.')' );
    
    // Save the backend parameters
    $this->sSocketHost = $sHost;
    $this->iSocketPort = $iPort;
    $this->sSocketPassword = $sPassword;
    $this->fSocketTimeout = $fTimeout;
  }

  /** Connect the to the MPD backend
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   */
  public function connectBackend()
  {
    // Check previous error status
    if( $this->bSocketError )
      throw new URC_EXCEPTION( __METHOD__, 'Bailing out after previous error' );

    // Set error status (we'll clear it afterwards, if everything goes fine)
    $this->bSocketError = true;

    // Check the backend parameters
    if( !isset( $this->sSocketHost, $this->iSocketPort, $this->sSocketPassword, $this->fSocketTimeout ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (unitialized) backend parameters; Did you call \'initBackend\' first?' );

    // Open the control socket
    $iErrNo = null; $sErrStr = null;
    $this->rSocket = fsockopen( $this->sSocketHost, $this->iSocketPort, $iErrNo, $sErrStr, $this->fSocketTimeout );
    if( $this->rSocket === false or $iErrNo != 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to connect to the backend; Error: failed to connect ('.$sErrStr.')' );
    stream_set_timeout( $this->rSocket, $this->fSocketTimeout );
    //stream_set_blocking( $this->rSocket, 0 );  // non-blocking socket

    // Check the backend status
    if( is_null( $this->getResponse() ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to connect to the backend; Error: invalid response' );

    // Authenticate
    if( is_null( $this->getResponseForCommand( 'password', $this->sSocketPassword ) ) )
    {
      @fclose( $this->rSocket );
      throw new URC_EXCEPTION( __METHOD__, 'Failed to connect to the backend; Error: invalid password' );
    }

    // Clear error status
    $this->bSocketError = false;

    // Reset the internal state
    $this->resetStatus();
    $this->resetPlayitemMetadata();
    $this->resetPlaylist();
  }

  /** Sends a command to the MPD backend
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sCommand MPD command
   * @param array|mixed $amParameters MPD command's parameters
   */
  public function sendCommand( $sCommand, $amParameters = null )
  {
    // Connect backend
    if( !isset( $this->rSocket ) )
      $this->connectBackend();
    if( $this->rSocket === false )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (uninitialized) backend' );

    // Sanitize/validate input
    $sCommand = trim( $sCommand );
    if( !is_null( $amParameters ) )
      $amParameters = array_map( 'trim', is_scalar( $amParameters ) ? array( $amParameters ) : $amParameters );

    // Execute command
    if( is_array( $amParameters ) )
      $sCommand .= ' '.implode( ' ', $amParameters );
    if( fputs( $this->rSocket, $sCommand."\n" ) === false )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to send command to backend' );
  }

  /** Retrieve and parse a command's answer from the MPD backend
   *
   * <P><B>RETURNS:</B> A (potentially empty) <SAMP>array</SAMP> of <SAMP>string</SAMP> matching each line
   * of the response (except the 'OK' or 'ACK' status flags); <SAMP>null</SAMP> in case of invalid/error
   * response.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return array|string
   */
  public function getResponse()
  {
    // Check backend
    if( !isset( $this->rSocket ) or $this->rSocket === false )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (uninitialized) backend' );

    // Retrieve and parse result
    $asResponse = array();
    $fStartLoop = microtime(true);
    while( !feof( $this->rSocket ) and microtime(true) - $fStartLoop < $this->fSocketTimeout )
    {
      $sResult = trim( fgets( $this->rSocket, 1024 ) );
      if( !empty( $sResult ) )
      {
        if( preg_match( '/^ACK( |$)/', $sResult ) )
        {
          return null;
        }
        elseif( preg_match( '/^OK( |$)/', $sResult ) )
          return $asResponse;
        else
          array_push( $asResponse, $sResult );
      }
      else
      {
        //usleep( 10000 ); // non-blocking socket
      }
    }
    throw new URC_EXCEPTION( __METHOD__, 'Failed to get response from backend; Error: timeout' );
  }

  /** Sends a command to the MPD backend and returns the response immediately
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sCommand MPD command
   * @param array|mixed $amParameters MPD command's parameters
   */
  public function getResponseForCommand( $sCommand, $amParameters = null )
  {
    $this->sendCommand( $sCommand, $amParameters );
    return $this->getResponse();
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Resets (clears) the current status data
   */
  public function resetStatus()
  {
    $this->amStatus = null;
  }

  /** Retrieves and store the current status
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   */
  public function queryStatus( $bForceRefresh = false )
  {
    if( $bForceRefresh or !is_array( $this->amStatus ) )
    {
      // Initialize player status data
      $this->amStatus = array( 'state' => URC_INTERFACE::STATUS_UNKNOWN,
                               'loop' => URC_INTERFACE::STATUS_UNKNOWN,
                               'random' => URC_INTERFACE::STATUS_UNKNOWN,
                               'volume' => URC_INTERFACE::STATUS_UNKNOWN,
                               'length' => URC_INTERFACE::STATUS_UNKNOWN,
                               'position' => URC_INTERFACE::STATUS_UNKNOWN,
                               'channels' => URC_INTERFACE::STATUS_UNKNOWN,
                               'samplerate' => URC_INTERFACE::STATUS_UNKNOWN,
                               'samplebits' => URC_INTERFACE::STATUS_UNKNOWN,
                               'bitrate' => URC_INTERFACE::STATUS_UNKNOWN,
                               'playlist-position' => URC_INTERFACE::STATUS_UNKNOWN,
                               );

      // Retrieve status
      $asResponse = $this->getResponseForCommand( 'status' );
      if( is_null( $asResponse ) ) return;
      foreach( $asResponse as $sResponse )
      {
        list( $sTag, $sValue ) = array_map( 'trim', explode( ':', $sResponse, 2 ) );
        switch( strtolower( $sTag ) )
        {
        case 'state':
          switch( $sValue )
          {
          case 'stop':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_STOP; break;
          case 'pause':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_PAUSE; break;
          case 'play':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_PLAY; break;
          }
          break;

        case 'repeat':
          $this->amStatus['repeat'] = ( (integer)$sValue ? URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
          break;

        case 'random':
          $this->amStatus['random'] = ( (integer)$sValue ? URC_INTERFACE_PLAYER::PLAYBACK_RANDOM : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
          break;

        case 'volume':
          $iValue = ( is_numeric( $sValue ) ? $iValue = (integer)$sValue : -1 );
          if( $iValue < 0 or $iValue > 100 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['volume'] = $iValue;
          break;

        case 'time':
          list( $iElapsed, $iTotal ) = array_map( 'trim', explode( ':', $sValue, 2 ) );
          $iValue = ( is_numeric( $iTotal ) ? (integer)$iTotal : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['length'] = $iValue;
          $iValue = ( is_numeric( $iElapsed ) ? (integer)$iElapsed : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['position'] = $iValue;
          break;

        case 'audio':
          list( $iSampleRate, $iSampleBits, $iChannels ) = array_map( 'trim', explode( ':', $sValue, 3 ) );
          $iValue = ( is_numeric( $iChannels ) ? (integer)$iChannels : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['channels'] = $iValue;
          $iValue = ( is_numeric( $iSampleRate ) ? (integer)$iSampleRate : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['samplerate'] = $iValue;
          $iValue = ( is_numeric( $iSampleBits ) ? (integer)$iSampleBits : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['samplebits'] = $iValue;
          break;

        case 'bitrate':
          $iValue = ( is_numeric( $sValue ) ? (integer)$sValue : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['bitrate'] = 1000 * $iValue;
          break;

        case 'song':
          $iValue = ( is_numeric( $sValue ) ? (integer)$sValue : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['playlist-position'] = $iValue;
          break;
        }
      }
    }
  }

  /** Resets (clears) the current status data
   */
  public function resetPlayitemMetadata()
  {
    $this->asPlayitemMetadata = null;
  }

  /** Retrieves and store the current playitem's meta-data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   */
  public function queryPlayitemMetadata( $bForceRefresh = false )
  {
    if( $bForceRefresh or !is_array( $this->asPlayitemMetadata ) )
    {
      // Initialize player status data
      $this->asPlayitemMetadata = array( 'file' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'artist' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'album' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'title' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'date' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'genre' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'copyright' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'description' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'comment' => URC_INTERFACE::STATUS_UNKNOWN,
                                         'rating' => URC_INTERFACE::STATUS_UNKNOWN,
                                         );

      // Retrieve meta-data
      $asResponse = $this->getResponseForCommand( 'currentsong' );
      if( is_null( $asResponse ) ) return;
      $sTitle = $sName = null;
      foreach( $asResponse as $sResponse )
      {
        list( $sTag, $sValue ) = array_map( 'trim', explode( ':', $sResponse, 2 ) );
        switch( strtolower( $sTag ) )
        {
        case 'file':
          $this->asPlayitemMetadata['file'] = basename( $sValue );
          break;

        case 'artist':
          $this->asPlayitemMetadata['artist'] = $sValue;
          break;

        case 'album':
          $this->asPlayitemMetadata['album'] = $sValue;
          break;

        case 'title':
          $sTitle = $sValue;
          break;

        case 'name':
          $sName = $sValue;
          break;

        case 'date':
          $this->asPlayitemMetadata['date'] = $sValue;
          break;

        case 'genre':
          $this->asPlayitemMetadata['genre'] = $sValue;
          break;

        case 'copyright': // not documented, but who knows?
          $this->asPlayitemMetadata['copyright'] = $sValue;
          break;

        case 'description': // not documented, but who knows?
          $this->asPlayitemMetadata['description'] = $sValue;
          break;

        case 'comment':
          $this->asPlayitemMetadata['comment'] = $sValue;
          break;

        case 'rating': // not documented, but who knows?
          $this->asPlayitemMetadata['rating'] = $sValue;
          break;
        }
      }
      if( isset( $sName, $sTitle ) ) {
        $this->asPlayitemMetadata['title'] = $sName.': '.$sTitle;
      }
      elseif( isset( $sTitle ) ) {
        $this->asPlayitemMetadata['title'] = $sTitle;
      }
      elseif( isset( $sName ) ) {
        $this->asPlayitemMetadata['title'] = $sName;
      }
    }
  }

  /** Resets (clears) the current playlist data
   */
  public function resetPlaylist()
  {
    $this->asPlaylist = null;
  }

  /** Retrieves and store the current playlist data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   */
  public function queryPlaylist( $bForceRefresh = false )
  {
    if( $bForceRefresh or !is_array( $this->asPlaylist ) )
    {
      // Retrieve playlist
      $asResponse = $this->getResponseForCommand( 'playlist' );
      if( is_null( $asResponse ) ) return;

      // Initialize playlist data
      $this->asPlaylist = array();

      // Parse playlist
      foreach( $asResponse as $sResponse )
      {
        list( $iPosition, $sEntry ) = array_map( 'trim', explode( ':', $sResponse, 2 ) );
        array_push( $this->asPlaylist, $sEntry );
      }
    }
  }


  /*
   * METHODS: (media-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playerPlaybackGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['state'];
  }

  public function playerRepeatGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['repeat'];
  }

  public function playerRandomGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['random'];
  }

  public function playerVolumeGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['volume'];
  }

  public function playerMuteGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( !URC_INTERFACE::isStatusOK( $this->amStatus['volume'] ) )
      return $this->amStatus['volume'];
    return (boolean)( $this->amStatus['volume'] == 0 );
  }


  /*
   * METHODS: (media-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playerPlaybackSet( $sPlayback )
  {
    $sPlayback = trim( $sPlayback );
    $sCurrent = $this->playerPlaybackGet();
    $sReponse = null;
    $this->resetStatus();
    switch( $sPlayback )
    {
    case URC_INTERFACE_PLAYER::PLAYBACK_STOP:
      $sReponse = $this->getResponseForCommand( 'stop' );
      break;

    case URC_INTERFACE_PLAYER::PLAYBACK_PLAY:
      if( $sCurrent == URC_INTERFACE_PLAYER::PLAYBACK_PAUSE )
        $sReponse = $this->getResponseForCommand( 'pause', 0 );
      elseif( $sCurrent == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
        $sReponse = $this->getResponseForCommand( 'play' );
      break;
        
    case URC_INTERFACE_PLAYER::PLAYBACK_PAUSE:
      $sReponse = $this->getResponseForCommand( 'pause', 1 );
      break;
    }
    usleep( 500000 ); // let's give the backend some time to react
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playerPlaybackGet( true ) );
  }

  public function playerRepeatSet( $sRepeat )
  {
    $sReponse = $this->getResponseForCommand( 'repeat', $sRepeat == URC_INTERFACE_PLAYER::PLAYBACK_NORMAL ? 0 : 1 );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playerRepeatGet( true ) );
  }

  public function playerRandomSet( $sRandom )
  {
    $sResponse = $this->getResponseForCommand( 'random', $sRandom == URC_INTERFACE_PLAYER::PLAYBACK_NORMAL ? 0 : 1 );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playerRandomGet( true ) );
  }

  public function playerVolumeSet( $iVolume )
  {
    $iVolume = (integer)$iVolume; if( $iVolume < 0 ) $iVolume = 0; if( $iVolume > 100 ) $iVolume = 100;
    $sResponse = $this->getResponseForCommand( 'setvol', $iVolume );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playerVolumeGet( true ) );
  }

  public function playerMuteSet( $bMute )
  {
    if( $bMute )
    {
      $sResponse = $this->getResponseForCommand( 'setvol', 0 );
      return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playerMuteGet( true ) );
    }
    return $this->playerMuteGet();
  }


  /*
   * METHODS: (media-)playlist "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playlistGet( $bForceRefresh = false )
  {
    $this->queryPlaylist( $bForceRefresh );
    return ( is_array( $this->asPlaylist ) ? $this->asPlaylist : URC_INTERFACE::STATUS_ERROR );
  }

  public function playlistSizeGet( $bForceRefresh = false )
  {
    $this->queryPlaylist( $bForceRefresh );
    return ( is_array( $this->asPlaylist ) ? count( $this->asPlaylist ) : URC_INTERFACE::STATUS_ERROR );
  }

  public function playlistPositionGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return ( URC_INTERFACE::isStatusOk( $this->amStatus['playlist-position'] ) ? $this->amStatus['playlist-position']+1 : $this->amStatus['playlist-position'] );
  }


  /*
   * METHODS: (media-)playlist "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playlistClear()
  {
    $this->resetPlaylist();
    $this->resetStatus();
    $sReponse = $this->getResponseForCommand( 'clear' );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : URC_INTERFACE::STATUS_OK );
  }

  public function playlistEntryAdd( $sEntry )
  {
    $this->resetPlaylist();
    $sReponse = $this->getResponseForCommand( 'add', trim( $sEntry ) );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : URC_INTERFACE::STATUS_OK );
  }

  public function playlistEntryRemove( $iPosition )
  {
    $this->resetPlaylist();
    $this->resetStatus();
    $sReponse = $this->getResponseForCommand( 'delete', (integer)$iPosition );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : URC_INTERFACE::STATUS_OK );
  }

  public function playlistPositionSet( $iPosition )
  {
    $this->resetStatus();
    $sReponse = $this->getResponseForCommand( 'play', (integer)$iPosition-1 );
    usleep( 500000 ); // let's give the backend some time to react
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : URC_INTERFACE::STATUS_OK );
  }


  /*
   * METHODS: (media-)playitem "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemFileGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['file'];
  }

  public function playitemArtistGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['artist'];
  }

  public function playitemAlbumGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['album'];
  }

  public function playitemTitleGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['title'];
  }

  public function playitemDateGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['date'];
  }

  public function playitemGenreGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['genre'];
  }

  public function playitemCopyrightGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['copyright'];
  }

  public function playitemDescriptionGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['description'];
  }

  public function playitemCommentGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['comment'];
  }

  public function playitemRatingGet( $bForceRefresh = false )
  {
    $this->queryPlayitemMetadata( $bForceRefresh );
    return $this->asPlayitemMetadata['rating'];
  }

  public function playitemLengthGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['length'];
  }

  public function playitemPositionGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['position'];
  }


  /*
   * METHODS: (media-)playitem "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemPositionSet( $iPosition )
  {
    $this->queryStatus();
    if( $this->amStatus['state'] != URC_INTERFACE_PLAYER::PLAYBACK_PLAY )
      return URC_INTERFACE::STATUS_WARNING;
    if( !URC_INTERFACE::isStatusOk( $this->amStatus['playlist-position'] ) )
      return URC_INTERFACE::STATUS_ERROR;
    $sReponse = $this->getResponseForCommand( 'seek', array( $this->amStatus['playlist-position'], (integer)$iPosition ) );
    return ( is_null( $sReponse ) ? URC_INTERFACE::STATUS_ERROR : $this->playitemPositionGet( true ) );
  }


  /*
   * METHODS: (audio-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemAudioLanguageGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemAudioChannelsGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['channels'];
  }

  public function playitemAudioSampleRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['samplerate'];
  }

  public function playitemAudioSampleBitsGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['samplebits'];
  }

  public function playitemAudioBitRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    return $this->amStatus['bitrate'];
  }

  public function playitemAudioCodecGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }


  /*
   * METHODS: (audio-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

}
