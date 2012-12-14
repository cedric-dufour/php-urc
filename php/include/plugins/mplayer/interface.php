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
   * @subpackage MPLAYER
   * @author Cedric Dufour <http://cedric.dufour.name>
   * @version @version@
   */


  /** Load URC interface class */
require_once( URC_INCLUDE_PATH.'/urc.interface.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.player.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playlist.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.audio.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.video.php' );


/** URC MPlayer (Network) interface
 *
 * <P><B>WARNING:</B> You MUST launch MPlayer in slave mode along with a network
 * socket front-end for this interface to work.<BR/>
 * (see the provided <SAMP>mpsocket.py</SAMP> file attached)</P>
 *
 * @package PHP_URC_Plugins
 * @subpackage MPLAYER
 */
class URC_INTERFACE_MPLAYER extends URC_INTERFACE
implements URC_INTERFACE_PLAYER, URC_INTERFACE_PLAYLIST, URC_INTERFACE_PLAYITEM, URC_INTERFACE_PLAYITEM_AUDIO, URC_INTERFACE_PLAYITEM_VIDEO
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** MPlayer backend (socket) host
   * @var string */
  private $sSocketHost = null;

  /** MPlayer backend (socket) port
   * @var integer */
  private $iSocketPort = null;

  /** MPlayer backend (socket) timeout [seconds]
   * @var float */
  private $fSocketTimeout = null;

  /** MPlayer backend (socket) error
   * @var boolean */
  private $bSocketError = false;

  /** MPlayer socket
   * @var resource */
  private $rSocket = null;

  /** Current player: state
   * @var string */
  private $sPlayerState = null;

  /** Current player: repeat
   * @var string */
  private $sPlayerRepeat = null;

  /** Current player: volume
   * @var integer */
  private $sPlayerVolume = null;

  /** Current player: repeat
   * @var boolean */
  private $bPlayerMute = null;

  /** Current playitem: file
   * @var string */
  private $sPlayitemFile = null;

  /** Current playitem: meta-data
   * @var array|string */
  private $asPlayitemMetadata = null;

  /** Current playitem: length
   * @var integer */
  private $iPlayitemLength = null;

  /** Current playitem: position
   * @var integer */
  private $iPlayitemPosition = null;

  /** Current playitem: audio ID
   * @var integer */
  private $iPlayitemAudioID = null;

  /** Current playitem: audio channel(s)
   * @var float */
  private $sPlayitemAudioChannels = null;

  /** Current playitem: audio sample rate
   * @var integer */
  private $iPlayitemAudioSampleRate = null;

  /** Current playitem: audio bit rate
   * @var integer */
  private $iPlayitemAudioBitRate = null;

  /** Current playitem: audio codec
   * @var string */
  private $sPlayitemAudioCodec = null;

  /** Current playitem: video ID
   * @var integer */
  private $iPlayitemVideoID = null;

  /** Current playitem: video title
   * @var integer */
  private $iPlayitemVideoTitle = null;

  /** Current playitem: video angle
   * @var integer */
  private $iPlayitemVideoAngle = null;

  /** Current playitem: video frame rate
   * @var integer */
  private $iPlayitemVideoFrameRate = null;

  /** Current playitem: video width
   * @var integer */
  private $iPlayitemVideoFrameWidth = null;

  /** Current playitem: video height
   * @var integer */
  private $iPlayitemVideoFrameHeight = null;

  /** Current playitem: video bit rate
   * @var integer */
  private $iPlayitemVideoBitRate = null;

  /** Current playitem: video codec
   * @var string */
  private $sPlayitemVideoCodec = null;

  /** Current playitem: subtitle ID
   * @var integer */
  private $iPlayitemSubtitleID = null;

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Interface singleton
   * @var URC_INTERFACE_MPLAYER */
  private static $oINTERFACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the interface
   */
  private function __construct()
  {
    $this->resetPlayerStatus();
    $this->resetPlayitemMetadata();
  }

  /** Destructs the interface
   */
  public function __destruct()
  {
    if( isset( $this->rSocket ) )
    {
      $this->sendCommand( 'exit' );
      @fclose( $this->rSocket );
    }
  }


  /*
   * METHODS: factory - OVERRIDE
   ********************************************************************************/

  /** Returns a (singleton) interface instance (<B>as reference</B>)
   *
   * @return URC_INTERFACE_MPLAYER
   */
  public static function &useInstance()
  {
    if( is_null( self::$oINTERFACE ) ) self::$oINTERFACE = new URC_INTERFACE_MPLAYER();
    return self::$oINTERFACE;
  }


  /*
   * METHODS: network socket
   ********************************************************************************/

  /** Initializes the MPlayer backend parameters
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHost MPlayer backend hostname/IP
   * @param integer $iPort MPlayer backend port
   * @param float $fTimeout Socket connection timeout [seconds]
   */
  public function initBackend( $sHost, $iPort, $fTimeout = 5 )
  {
    // Sanitize/validate input
    $sHost = trim( $sHost );
    if( empty( $sHost ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: missing host IP/name' );
    $iPort = (integer)$iPort;
    if( $iPort <= 0 or $iPort > 65536 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: invalid port ('.$iPort.')' );
    $fTimeout = (float)$fTimeout;
    if( $fTimeout <= 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: invalid timeout ('.$fTimeout.')' );
    
    // Save the backend parameters
    $this->sSocketHost = $sHost;
    $this->iSocketPort = $iPort;
    $this->fSocketTimeout = $fTimeout;

    // Connect to the backend
    if( $bConnect )
      $this->connectBackend();
  }

  /** Connect the to the MPlayer backend
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
    if( !isset( $this->sSocketHost, $this->iSocketPort, $this->fSocketTimeout ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (unitialized) backend parameters; Did you call \'initBackend\' first?' );

    // Open the control socket
    $iErrNo = null; $sErrStr = null;
    $this->rSocket = fsockopen( $this->sSocketHost, $this->iSocketPort, $iErrNo, $sErrStr, $this->fSocketTimeout );
    if( $this->rSocket === false or $iErrNo != 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to connect to the backend; Error: failed to connect ('.$sErrStr.')' );
    stream_set_timeout( $this->rSocket, $this->fSocketTimeout );
    //stream_set_blocking( $this->rSocket, 0 ); // non-blocking socket

    // Wait for the backend to stabilize
    $this->syncBackend();

    // Clear error status
    $this->bSocketError = false;

    // Reset the internal state
    $this->resetPlayitemMetadata();
  }

  /** Sends a command to the MPlayer backend
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sCommand MPlayer command
   * @param array|mixed $amParameters MPlayer command's parameters
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
      $amParameters = ( array_map( 'trim', is_scalar( $amParameters ) ? array( $amParameters ) : $amParameters ) );

    // Execute command
    if( is_array( $amParameters ) )
      $sCommand .= ' '.implode( ' ', $amParameters );
    if( fputs( $this->rSocket, $sCommand."\n" ) === false )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to send command to backend' );
  }

  /** Retrieve and parse a command's answer from the MPlayer backend
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param boolean $bIgnoreErrors Ignore backend errors
   * @return string
   */
  public function getResponse( $bIgnoreErrors = false )
  {
    // Check backend
    if( !isset( $this->rSocket ) or $this->rSocket === false )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (uninitialized) backend' );

    // Retrieve and parse result
    $fStartLoop = microtime(true);
    while( !feof( $this->rSocket ) and microtime(true) - $fStartLoop < $this->fSocketTimeout )
    {
      $sResult = trim( fgets( $this->rSocket, 1024 ) );
      if( !empty( $sResult ) )
      {
        if( preg_match( '/^ERR:/', $sResult ) )
        {
          if( !$bIgnoreErrors ) return null;
        }
        elseif( preg_match( '/^OUT:ANS_/', $sResult ) )
          return preg_replace( '/^.*=/', null, $sResult );
      }
      else
      {
        //usleep( 10000 ); // non-blocking socket
      }
    }
    throw new URC_EXCEPTION( __METHOD__, 'Failed to get response from backend; Error: timeout' );
  }

  /** Synchronize (as best as it can) with the MPlayer backend
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   */
  public function syncBackend()
  {
    // Check backend
    if( !isset( $this->rSocket ) or $this->rSocket === false )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (uninitialized) backend' );

    // Send a pause command and wait for its result
    $this->sendCommand( 'pausing_keep_force get_property pause' );
    $this->getResponse( true );
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Resets (clears) the current player status
   */
  public function resetPlayerStatus()
  {
    $this->sPlayerState = null;
    $this->sPlayerRepeat = null;
    $this->iPlayerVolume = null;
    $this->bPlayerMute = null;
  }

  /** Resets (clears) the current playitem meta-data
   */
  public function resetPlayitemMetadata()
  {
    $this->sPlayitemFile = null;
    $this->asPlayitemMetadata = null;
    $this->iPlayitemLength = null;
    $this->iPlayitemPosition = null;
    $this->iPlayitemAudioID = null;
    $this->sPlayitemAudioChannels = null;
    $this->iPlayitemAudioSampleRate = null;
    $this->iPlayitemAudioBitRate = null;
    $this->sPlayitemAudioCodec = null;
    $this->iPlayitemVideoID = null;
    $this->iPlayitemVideoTitle = null;
    $this->iPlayitemVideoAngle = null;
    $this->iPlayitemVideoFrameRate = null;
    $this->iPlayitemVideoFrameWidth = null;
    $this->iPlayitemVideoFrameHeight = null;
    $this->iPlayitemVideoBitRate = null;
    $this->sPlayitemVideoCodec = null;
    $this->iPlayitemSubtitleID = null;
  }

  /** Retrieves and store the current playitem meta-data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   */
  public function queryPlayitemMetadata( $bForceRefresh = false )
  {
    if( $bForceRefresh or !is_array( $this->asPlayitemMetadata ) )
    {
      // Initialize playitem data
      $asPlayitemMetadata = array( 'artist' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'album' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'title' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'date' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'genre' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'copyright' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'description' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'comment' => URC_INTERFACE::STATUS_UNKNOWN,
                                   'rating' => URC_INTERFACE::STATUS_UNKNOWN );

      // Retrieve playitem
      $this->sendCommand( 'pausing_keep_force get_property metadata' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) ) return;
      $sMeta = null;
      foreach( explode( ',', $sResponse ) as $sData )
      {
        if( is_null( $sMeta ) )
          $sMeta = strtolower( trim( $sData ) );
        else
        {
          $sValue = trim( $sData );
          switch( $sMeta )
          {
          case 'artist':
            $asPlayitemMetadata['artist'] = $sValue; break;

          case 'album':
            $asPlayitemMetadata['album'] = $sValue; break;

          case 'title':
          case 'name':
            $asPlayitemMetadata['title'] = $sValue; break;

          case 'year':
          case 'date':
            $asPlayitemMetadata['date'] = $sValue; break;

          case 'genre':
            $asPlayitemMetadata['genre'] = $sValue; break;

          case 'copyright':
            $asPlayitemMetadata['copyright'] = $sValue; break;

          case 'description':
            $asPlayitemMetadata['description'] = $sValue; break;

          case 'comment':
            $asPlayitemMetadata['comment'] = $sValue; break;

          case 'rating':
            $asPlayitemMetadata['rating'] = $sValue; break;
          }
          $sMeta = null;
        }
      }

      // Save playitem
      $this->asPlayitemMetadata = $asPlayitemMetadata;
    }

  }


  /*
   * METHODS: (media-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playerPlaybackGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayerState ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property pause' );
      $sResponse = $this->getResponse();
      if( $sResponse == 'yes' )
        $this->sPlayerState = URC_INTERFACE_PLAYER::PLAYBACK_PAUSE;
      elseif( $sResponse == 'no' )
        if( $this->playitemFileGet() == URC_INTERFACE::STATUS_UNKNOWN )
          $this->sPlayerState = URC_INTERFACE_PLAYER::PLAYBACK_STOP;
        else
          $this->sPlayerState = URC_INTERFACE_PLAYER::PLAYBACK_PLAY;
      else
        $this->sPlayerState = URC_INTERFACE::STATUS_UNKNOWN;
    }
    return $this->sPlayerState;
  }

  public function playerRepeatGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayerRepeat ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property loop' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->sPlayerRepeat = URC_INTERFACE::STATUS_UNKNOWN;
      if( $sResponse < 0 )
        $this->sPlayerRepeat = URC_INTERFACE_PLAYER::PLAYBACK_NORMAL;
      else
        $this->sPlayerRepeat = URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL;
    }
    return $this->sPlayerRepeat;
  }

  public function playerRandomGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playerVolumeGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayerVolume ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property volume' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayerVolume = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayerVolume = (integer)$sResponse;
    }
    return $this->iPlayerVolume;
  }

  public function playerMuteGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->bPlayerMute ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property mute' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->bPlayerMute = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->bPlayerMute = ( $sResponse == 'yes' );
    }
    return $this->bPlayerMute;
  }


  /*
   * METHODS: (media-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playerPlaybackSet( $sPlayback )
  {
    $sPlayback = trim( $sPlayback );
    $this->resetPlayerStatus();
    $this->resetPlayitemMetadata();
    switch( $sPlayback )
    {
    case URC_INTERFACE_PLAYER::PLAYBACK_STOP:
      $this->sendCommand( 'stop' );
      $this->syncBackend();
      break;
        
    case URC_INTERFACE_PLAYER::PLAYBACK_PAUSE:
      $this->sendCommand( 'pausing_keep_force pause' );
      $this->syncBackend();
      break;

    case URC_INTERFACE_PLAYER::PLAYBACK_PLAY:
      // NOTE: MPlayer will resume playing as soon as ANY command is sent
      $this->sendCommand( 'get_property play' ); // this just generates an error (play property does not exist)
      $this->syncBackend();
      break;
    }
    return $this->playerPlaybackGet( true );
  }

  public function playerRepeatSet( $sRepeat )
  {
    $sRepeat = trim( $sRepeat );
    $bLoop = ( $sRepeat == URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL || $sRepeat == URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALBUM || $sRepeat == URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE );
    $this->sendCommand( 'pausing_keep_force set_property loop', $bLoop ? 0 : -1 );
    return $this->playerRepeatGet( true );
  }

  public function playerRandomSet( $sRandom )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playerVolumeSet( $iVolume )
  {
    $iVolume = (integer)$iVolume; if( $iVolume < 0 ) $iVolume = 0; if( $iVolume > 100 ) $iVolume = 100;
    $this->sendCommand( 'pausing_keep_force set_property volume', $iVolume );
    return $this->playerVolumeGet( true );
  }

  public function playerMuteSet( $bMute )
  {
    $this->sendCommand( 'pausing_keep_force set_property mute', $bMute ? 'yes' : 'no' );
    return $this->playerMuteGet( true );
  }


  /*
   * METHODS: (media-)playlist "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playlistGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playlistSizeGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playlistPositionGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }


  /*
   * METHODS: (media-)playlist "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playlistClear()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playlistEntryAdd( $sEntry )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playlistEntryRemove( $iPosition )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  /** Goes to the given playlist position (entry index)
   *
   * <P><B>WARNING:</B> Due to lack of proper resources in MPlayer slave-mode API,
   * This command CAN NOT be interpreted as provisioned in the generic interface.</P>
   * <P>Instead, the position will be set one item forward (backward) for positive
   * (negative) values.</P>
   */
  public function playlistPositionSet( $iPosition )
  {
    $iPosition = (integer)$iPosition;
    if( $iPosition < 0 )
      $this->sendCommand( 'pt_step', -1 );
    elseif( $iPosition > 0 )
      $this->sendCommand( 'pt_step', 1 );
    $this->syncBackend();
    $this->resetPlayitemMetadata();
    return URC_INTERFACE::STATUS_UNKNOWN;
  }


  /*
   * METHODS: (media-)playitem "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemFileGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayitemFile ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property filename' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->sPlayitemFile = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->sPlayitemFile = trim( $sResponse, " \t\n\r'\"" );
    }
    return $this->sPlayitemFile;
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
    if( $bForceRefresh or is_null( $this->iPlayitemLength ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property length' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) ) return URC_INTERFACE::STATUS_UNKNOWN;
      $this->iPlayitemLength = (integer)$sResponse;
    }
    return $this->iPlayitemLength;
  }

  public function playitemPositionGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemPosition ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property time_pos' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemPosition = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemPosition = (integer)$sResponse;
    }
    return $this->iPlayitemPosition;
  }


  /*
   * METHODS: (media-)playitem "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemPositionSet( $iPosition )
  {
    $iPosition = (integer)$iPosition; if( $iPosition < 0 ) $iPosition = 0;
    $this->sendCommand( 'set_property time_pos', $iPosition );
    return $this->playitemPositionGet( true );
  }


  /*
   * METHODS: (audio-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemAudioID ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property switch_audio' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemAudioID = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemAudioID = (integer)$sResponse;
    }
    return $this->iPlayitemAudioID;
  }

  public function playitemAudioLanguageGet( $bForceRefresh = false )
  {
    return self::STATUS_NOTIMPLEMENTED;
  }

  public function playitemAudioChannelsGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayitemAudioChannels ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property channels' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->sPlayitemAudioChannels = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->sPlayitemAudioChannels = trim( $sResponse );
    }
    return $this->sPlayitemAudioChannels;
  }

  public function playitemAudioSampleRateGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemAudioSampleRate ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property samplerate' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemAudioSampleRate = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemAudioSampleRate = (integer)$sResponse;
    }
    return $this->iPlayitemAudioSampleRate;
  }

  public function playitemAudioSampleBitsGet( $bForceRefresh = false )
  {
    return self::STATUS_NOTIMPLEMENTED;
  }

  public function playitemAudioBitRateGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemAudioBitRate ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property audio_bitrate' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemAudioBitRate = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemAudioBitRate = (integer)$sResponse;
    }
    return $this->iPlayitemAudioBitRate;
  }

  public function playitemAudioCodecGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayitemAudioCodec ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property audio_codec' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->sPlayitemAudioCodec = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->sPlayitemAudioCodec = trim( $sResponse );
    }
    return $this->sPlayitemAudioCodec;
  }


  /*
   * METHODS: (audio-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioToggle()
  {
    $this->sendCommand( 'pausing_keep_force step_property', 'switch_audio' );
    $this->syncBackend();
    return $this->playitemAudioGet( true );
  }


  /*
   * METHODS: (video-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemVideoGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoID ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property switch_video' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoID = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoID = (integer)$sResponse;
    }
    return $this->iPlayitemVideoID;
  }

  public function playitemVideoTitleGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoTitle ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property switch_title' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoTitle = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoTitle = (integer)$sResponse;
    }
    return $this->iPlayitemVideoTitle;
  }

  public function playitemVideoAngleGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoAngle ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property switch_angle' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoAngle = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoAngle = (integer)$sResponse;
    }
    return $this->iPlayitemVideoAngle;
  }

  public function playitemVideoFrameWidthGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoFrameWidth ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property width' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoFrameWidth = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoFrameWidth = (integer)$sResponse;
    }
    return $this->iPlayitemVideoFrameWidth;
  }

  public function playitemVideoFrameHeightGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoFrameHeight ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property height' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoFrameHeight = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoFrameHeight = (integer)$sResponse;
    }
    return $this->iPlayitemVideoFrameHeight;
  }

  public function playitemVideoFrameRateGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoFrameRate ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property fps' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoFrameRate = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoFrameRate = (integer)$sResponse;
    }
    return $this->iPlayitemVideoFrameRate;
  }

  public function playitemVideoPixelBitsGet( $bForceRefresh = false )
  {
    return self::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoBitRateGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemVideoBitRate ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property video_bitrate' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemVideoBitRate = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemVideoBitRate = (integer)$sResponse;
    }
    return $this->iPlayitemVideoBitRate;
  }

  public function playitemVideoCodecGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->sPlayitemVideoCodec ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property video_codec' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->sPlayitemVideoCodec = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->sPlayitemVideoCodec = trim( $sResponse );
    }
    return $this->sPlayitemVideoCodec;
  }

  public function playitemVideoSubtitleGet( $bForceRefresh = false )
  {
    if( $bForceRefresh or is_null( $this->iPlayitemSubtitleID ) )
    {
      $this->sendCommand( 'pausing_keep_force get_property sub' );
      $sResponse = $this->getResponse();
      if( is_null( $sResponse ) )
        $this->iPlayitemSubtitleID = URC_INTERFACE::STATUS_UNKNOWN;
      else
        $this->iPlayitemSubtitleID = (integer)$sResponse;
    }
    return $this->iPlayitemSubtitleID;
  }

  public function playitemVideoSubtitleLanguageGet( $bForceRefresh = false )
  {
    return self::STATUS_NOTIMPLEMENTED;
  }


  /*
   * METHODS: (video-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemVideoToggle()
  {
    $this->sendCommand( 'pausing_keep_force step_property', 'switch_video' );
    $this->syncBackend();
    return $this->playitemVideoGet( true );
  }

  public function playitemVideoTitleToggle()
  {
    $this->sendCommand( 'pausing_keep_force step_property', 'switch_title' );
    $this->syncBackend();
    return $this->playitemVideoTitleGet( true );
  }

  public function playitemVideoAngleToggle()
  {
    $this->sendCommand( 'pausing_keep_force step_property', 'switch_angle' );
    $this->syncBackend();
    return $this->playitemVideoAngleGet( true );
  }

  public function playitemVideoSubtitleToggle()
  {
    $this->sendCommand( 'pausing_keep_force step_property', 'sub' );
    $this->syncBackend();
    return $this->playitemVideoSubtitleGet( true );
  }

}
