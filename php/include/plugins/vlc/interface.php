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
   * @subpackage VLC
   * @author Cedric Dufour <http://www.ced-network.net/php-urc>
   * @version @version@
   */


  /** Load URC interface class */
require_once( URC_INCLUDE_PATH.'/urc.interface.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.player.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playlist.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.audio.php' );
require_once( URC_INCLUDE_PATH.'/urc.interface.playitem.video.php' );


/** URC VideoLAN Player (VLC) web (HTTP) interface
 *
 * <P><B>WARNING:</B> You MUST allow PHP's <SAMP>allow_url_fopen</SAMP> for this interface to work!</P>
 *
 * @package PHP_URC_Plugins
 * @subpackage VLC
 */
class URC_INTERFACE_VLC extends URC_INTERFACE
implements URC_INTERFACE_PLAYER, URC_INTERFACE_PLAYLIST, URC_INTERFACE_PLAYITEM, URC_INTERFACE_PLAYITEM_AUDIO, URC_INTERFACE_PLAYITEM_VIDEO
{

  /*
   * FIELDS: variables
   ********************************************************************************/

  /** VLC web (HTTP) backend URI
   * @var string */
  private $sURI = null;

  /** VLC web (HTTP) backend error
   * @var boolean */
  private $bSocketError = false;

  /** Current status (player/playitem): data
   * @var array|mixed */
  private $amStatus = null;

  /** Current playlist: entries
   * @var array|string */
  private $asPlaylist = null;

  /** Current playlist: index
   * @var array|integer */
  private $aiPlaylistIndex = null;

  /** Current playlist: files
   * @var array|string */
  private $asPlaylistFiles = null;

  /** Current playlist: position
   * @var integer */
  private $iPlaylistPosition = null;
  

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Interface singleton
   * @var URC_INTERFACE_VLC */
  private static $oINTERFACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the interface
   */
  private function __construct()
  {
    $this->resetStatus();
    $this->resetPlaylist();
  }


  /*
   * METHODS: factory - OVERRIDE
   ********************************************************************************/

  /** Returns a (singleton) interface instance (<B>as reference</B>)
   *
   * @return URC_INTERFACE_VLC
   */
  public static function &useInstance()
  {
    if( is_null( self::$oINTERFACE ) ) self::$oINTERFACE = new URC_INTERFACE_VLC();
    return self::$oINTERFACE;
  }


  /*
   * METHODS: network socket
   ********************************************************************************/

  /** Initializes the VLC backend parameters
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sURI VLC backend URI/IP
   */
  public function initBackend( $sURI )
  {
    // Sanitize/validate input
    $sURI = rtrim( trim( $sURI ), '/' );
    if( empty( $sURI ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to initialize the backend; Error: missing URI' );
    
    // Save the backend parameters
    $this->sURI = $sURI;
  }

  /** Sends a request to the VLC backend
   *
   * <P><B>RETURNS:</B> The request (XML) response.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHandler VLC handler (can be <SAMP>status</SAMP> or <SAMP>playlist</SAMP>)
   * @param array|mixed $amVariables VLC request variables (as an <SAMP>associative array</SAMP>)
   */
  public function sendRequest( $sHandler, $amVariables = null )
  {
    // Check previous error status
    if( $this->bSocketError )
      throw new URC_EXCEPTION( __METHOD__, 'Bailing out after previous error' );

    // Set error status (we'll clear it afterwards, if everything goes fine)
    $this->bSocketError = true;

    // Check the backend parameters
    if( !isset( $this->sURI ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing (unitialized) backend parameters; Did you call \'initBackend\' first?' );
    // Sanitize/validate input
    $sHandler = (string)$sHandler;
    switch( $sHandler )
    {
    case 'status': $sHandler = 'status.xml'; break;
    case 'playlist': $sHandler = 'playlist.xml'; break;
    default:
      throw new URC_EXCEPTION( __METHOD__, 'Invalid/missing handler; Handler: '.$sHandler );
    }
    if( !is_null( $amVariables ) and !is_array( $amVariables ) )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid/missing request variables' );

    // Build request
    $sURI = $this->sURI.'/requests/'.$sHandler;
    $sQuery = null;
    if( !is_null( $amVariables ) )
    {
      foreach( $amVariables as $sVariable => $sValue )
        $sQuery .= ( $sQuery ? '&' : null ).$sVariable.'='.urlencode( $sValue );
      if( $sQuery ) $sURI .= '?'.$sQuery;
    }

    // Send request
    $sResponse = file_get_contents( $sURI );
    if( $sResponse === false )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to get response from backend' );

    // Clear error status
    $this->bSocketError = false;

    // Return response
    return $sResponse;
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Resets (clears) the current status (player/playitem) data
   */
  public function resetStatus()
  {
    $this->amStatus = null;
  }

  /** Retrieves and store the current status (player/playitem) data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
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
                               'artist' => URC_INTERFACE::STATUS_UNKNOWN,
                               'album' => URC_INTERFACE::STATUS_UNKNOWN,
                               'title' => URC_INTERFACE::STATUS_UNKNOWN,
                               'date' => URC_INTERFACE::STATUS_UNKNOWN,
                               'genre' => URC_INTERFACE::STATUS_UNKNOWN,
                               'copyright' => URC_INTERFACE::STATUS_UNKNOWN,
                               'description' => URC_INTERFACE::STATUS_UNKNOWN,
                               'comment' => URC_INTERFACE::STATUS_UNKNOWN,
                               'rating' => URC_INTERFACE::STATUS_UNKNOWN,
                               'length' => URC_INTERFACE::STATUS_UNKNOWN,
                               'position' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-id' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-language' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-channels' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-samplerate' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-bitrate' => URC_INTERFACE::STATUS_UNKNOWN,
                               'audio-codec' => URC_INTERFACE::STATUS_UNKNOWN,
                               'video-id' => URC_INTERFACE::STATUS_UNKNOWN,
                               'video-width' => URC_INTERFACE::STATUS_UNKNOWN,
                               'video-height' => URC_INTERFACE::STATUS_UNKNOWN,
                               'video-framerate' => URC_INTERFACE::STATUS_UNKNOWN,
                               'video-codec' => URC_INTERFACE::STATUS_UNKNOWN,
                               'subtitle-language' => URC_INTERFACE::STATUS_UNKNOWN,
                               );

      // Retrieve status data
      $sXML = $this->sendRequest( 'status' );

      // Parse XML
      $oXML = new XMLReader();
      if( $oXML->XML( $sXML ) === false )
        throw new URC_EXCEPTION( __METHOD__, 'Failed to read XML data' );
      if( $oXML->next( 'root' ) === false )
        throw new URC_EXCEPTION( __METHOD__, 'Invalid XML data (no <root> element)' );

      // Loop through elements
      $sStreamContext = null;
      $sStreamName = URC_INTERFACE::STATUS_UNKNOWN;
      while( $oXML->read() )
      {
        if( $oXML->nodeType != XMLReader::ELEMENT ) continue;

        switch( $oXML->name )
        {
          // ... state
        case 'state':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          switch( $oXML->value )
          {
          case 'stop':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_STOP; break;
          case 'paused':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_PAUSE; break;
          case 'playing':
            $this->amStatus['state'] = URC_INTERFACE_PLAYER::PLAYBACK_PLAY; break;
          }
          break;

          // ... repeat
        case 'repeat':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          if( (integer)$oXML->value )
            $this->amStatus['repeat'] = URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE;
          elseif( (string)$this->amStatus['repeat'] == URC_INTERFACE::STATUS_UNKNOWN ) // do not overwrite loop status
            $this->amStatus['repeat'] = URC_INTERFACE_PLAYER::PLAYBACK_NORMAL;
          break;

          // ... loop
        case 'loop':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          if( (string)$this->amStatus['repeat'] != URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE ) // repeat status takes precedence
            $this->amStatus['repeat'] = ( (integer)$oXML->value ? URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
          break;

          // ... random
        case 'random':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['random'] = ( (integer)$oXML->value ? URC_INTERFACE_PLAYER::PLAYBACK_RANDOM : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
          break;
 
          // ... volume - NB: VLC volume is normalized between 0 and 1024 (200%)
        case 'volume':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $iValue = ( is_numeric( $oXML->value ) ? $iValue = (integer)round( $oXML->value / 5.12 ) : -1 );
          if( $iValue < 0 or $iValue > 100 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['volume'] = $iValue;
          break;
 
          // ... length
        case 'length':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $iValue = ( is_numeric( $oXML->value ) ? (integer)$oXML->value : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['length'] = $iValue;
          break;
 
          // ... time
        case 'time':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $iValue = ( is_numeric( $oXML->value ) ? (integer)$oXML->value : -1 );
          if( $iValue < 0 ) $iValue = URC_INTERFACE::STATUS_ERROR;
          $this->amStatus['position'] = $iValue;
          break;

          // ... stream category
        case 'category':
          $sValue = trim( $oXML->getAttribute( 'name' ) );
          if( !empty( $sValue ) )
            $sStreamName = $sValue;
          break;

          // ... stream info
        case 'info':
          $sValue = strtolower( $oXML->getAttribute( 'name' ) );
          $oXML->read();
          if( !$oXML->hasValue ) break;
          switch( $sValue )
          {
          case 'type':
            switch( strtolower( $oXML->value ) )
            {
            case 'audio':
              $sStreamContext = 'audio';
              $this->amStatus['audio-id'] = $sStreamName;
              $sStreamName = URC_INTERFACE::STATUS_UNKNOWN;
              break;
            case 'video':
              $sStreamContext = 'video';
              $this->amStatus['video-id'] = $sStreamName;
              $sStreamName = URC_INTERFACE::STATUS_UNKNOWN;
              break;
            }

          case 'codec':
            switch( $sStreamContext )
            {
            case 'audio': $this->amStatus['audio-codec'] = trim( $oXML->value ); break;
            case 'video': $this->amStatus['video-codec'] = trim( $oXML->value ); break;
            }
            break;

          case 'language':
            switch( $sStreamContext )
            {
            case 'audio': $this->amStatus['audio-language'] = trim( $oXML->value ); break;
            case 'video': $this->amStatus['subtitle-language'] = trim( $oXML->value ); break;
            }
            break;

          case 'resolution':
            switch( $sStreamContext )
            {
            case 'video': list( $this->amStatus['video-width'], $this->amStatus['video-height'] ) = explode( 'x', $oXML->value, 2 ); break;
            }
            break;

          case 'frame rate':
            switch( $sStreamContext )
            {
            case 'video': $this->amStatus['video-framerate'] = (integer)$oXML->value; break;
            }
            break;

          case 'channels':
            switch( $sStreamContext )
            {
            case 'audio': $this->amStatus['audio-channels'] = trim( $oXML->value ); break;
            }
            break;

          case 'sample rate':
            switch( $sStreamContext )
            {
            case 'audio': $this->amStatus['audio-samplerate'] = (integer)$oXML->value; break;
            }
            break;

          case 'bitrate':
            switch( $sStreamContext )
            {
            case 'audio': $this->amStatus['audio-bitrate'] = 1000 * (integer)$oXML->value; break;
            case 'video': $this->amStatus['video-bitrate'] = 1000 * (integer)$oXML->value; break;
            }
            break;
          }
          break;

          // ... meta-information: artist
        case 'artist':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['artist'] = trim( $oXML->value );
          break;

          // ... meta-information: album
        case 'album':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['album'] = trim( $oXML->value );
          break;

          // ... meta-information: title
        case 'title':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['title'] = trim( $oXML->value );
          break;

          // ... meta-information: date
        case 'date':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['date'] = trim( $oXML->value );
          break;

          // ... meta-information: genre
        case 'genre':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['genre'] = trim( $oXML->value );
          break;

          // ... meta-information: copyright
        case 'copyright':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['copyright'] = trim( $oXML->value );
          break;

          // ... meta-information: description
        case 'description':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['description'] = trim( $oXML->value );
          break;

          // ... meta-information: comment
        case 'comment':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['comment'] = trim( $oXML->value );
          break;

          // ... meta-information: rating
        case 'rating':
          $oXML->read();
          if( !$oXML->hasValue ) break;
          $this->amStatus['rating'] = trim( $oXML->value );
          break;

        }
      }
    }
  }

  /** Resets (clears) the current playlist data
   */
  public function resetPlaylist()
  {
    $this->asPlaylist = null;
    $this->aiPlaylistIndex = null;
    $this->asPlaylistFiles = null;
    $this->iPlaylistPosition = null;
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
      $sXML = $this->sendRequest( 'playlist' );

      // Parse XML
      $oXML = new XMLReader();
      if( $oXML->XML( $sXML ) === false )
        throw new URC_EXCEPTION( __METHOD__, 'Failed to read XML data' );
      if( $oXML->next( 'node' ) === false )
        throw new URC_EXCEPTION( __METHOD__, 'Invalid XML data (no <node> element)' );

      // Initialize playlist data
      $this->asPlaylist = array();
      $this->aiPlaylistIndex = array();
      $this->asPlaylistFiles = array();
      $this->iPlaylistPosition = URC_INTERFACE::STATUS_UNKNOWN;

      // Loop through elements
      $i = 0;
      while( $oXML->read() )
      {
        if( $oXML->nodeType != XMLReader::ELEMENT ) continue;
        if( $oXML->name != 'leaf' ) continue;
        if( !$oXML->getAttribute( 'uri' ) ) continue;

        // ... playlist data
        array_push( $this->asPlaylist, trim( $oXML->getAttribute( 'name' ) ) );
        array_push( $this->aiPlaylistIndex, (integer)$oXML->getAttribute( 'id' ) );
        array_push( $this->asPlaylistFiles, basename( $oXML->getAttribute( 'uri' ) ) );
        if( $oXML->getAttribute( 'current' ) )
          $this->iPlaylistPosition = $i;
        $i++;
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
    switch( $sPlayback )
    {
    case URC_INTERFACE_PLAYER::PLAYBACK_STOP:
      $this->sendRequest( 'status', array( 'command' => 'pl_stop' ) );
      $this->resetPlaylist();
      break;

    case URC_INTERFACE_PLAYER::PLAYBACK_PLAY:
      if( $sCurrent == URC_INTERFACE_PLAYER::PLAYBACK_PAUSE )
        $this->sendRequest( 'status', array( 'command' => 'pl_pause' ) );
      elseif( $sCurrent == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      {
        $this->queryPlaylist();
        if( isset( $this->aiPlaylistIndex[0] ) )
          $this->sendRequest( 'status', array( 'command' => 'pl_play', 'id' => $this->aiPlaylistIndex[0] ) );
        $this->resetPlaylist();
      }
      break;
        
    case URC_INTERFACE_PLAYER::PLAYBACK_PAUSE:
      if( $sCurrent == URC_INTERFACE_PLAYER::PLAYBACK_PLAY )
        $this->sendRequest( 'status', array( 'command' => 'pl_pause' ) );
      break;
    }
    usleep( 500000 ); // let's give the backend some time to react
    return $this->playerPlaybackGet( true );
  }

  public function playerRepeatSet( $sRepeat )
  {
    $sRepeat = trim( $sRepeat );
    $i=2;
    $sCurrent = $this->playerRepeatGet();
    while( $i-- )
    {
      if( $sRepeat == $sCurrent )
        return $sCurrent;
      switch( $sRepeat )
      {
      case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL:
        switch( $sCurrent )
        {
        case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE:
          $this->sendRequest( 'status', array( 'command' => 'pl_repeat' ) );
          break;
        case URC_INTERFACE_PLAYER::PLAYBACK_NORMAL:
          $this->sendRequest( 'status', array( 'command' => 'pl_loop' ) );
          break;
        }
        break;

      case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE:
        switch( $sCurrent )
        {
        case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL:
          $this->sendRequest( 'status', array( 'command' => 'pl_loop' ) );
          break;
        case URC_INTERFACE_PLAYER::PLAYBACK_NORMAL:
          $this->sendRequest( 'status', array( 'command' => 'pl_repeat' ) );
          break;
        }
        break;

      case URC_INTERFACE_PLAYER::PLAYBACK_NORMAL:
        switch( $sCurrent )
        {
        case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE:
          $this->sendRequest( 'status', array( 'command' => 'pl_repeat' ) );
          break;
        case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL:
          $this->sendRequest( 'status', array( 'command' => 'pl_loop' ) );
          break;
        }
        break;
      }
      $sCurrent = $this->playerRepeatGet( true );
    }
    return $sCurrent;
  }

  public function playerRandomSet( $sRandom )
  {
    $sRandom = trim( $sRandom );
    $sCurrent = $this->playerRandomGet();
    if( $sRandom == $sCurrent )
      return $sCurrent;
    $this->sendRequest( 'status', array( 'command' => 'pl_random' ) );
    return $this->playerRandomGet( true );
  }

  public function playerVolumeSet( $iVolume )
  {
    $iVolume = (integer)$iVolume; if( $iVolume < 0 ) $iVolume = 0; if( $iVolume > 100 ) $iVolume = 100;
    $this->sendRequest( 'status', array( 'command' => 'volume', 'val' => (integer)round(5.12 * $iVolume ) ) );
    return $this->playerVolumeGet( true );
  }

  public function playerMuteSet( $bMute )
  {
    if( $bMute ) $this->sendRequest( 'status', array( 'command' => 'volume', 'val' => 0 ) );
    return $this->playerMuteGet( true );
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
    $this->queryPlaylist( $bForceRefresh );
    return ( is_array( $this->asPlaylist ) ? $this->iPlaylistPosition+1 : URC_INTERFACE::STATUS_ERROR );
  }


  /*
   * METHODS: (media-)playlist "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playlistClear()
  {
    $this->resetPlaylist();
    $this->resetStatus();
    $this->sendRequest( 'status', array( 'command' => 'pl_empty' ) );
    return URC_INTERFACE::STATUS_OK;
  }

  public function playlistEntryAdd( $sEntry )
  {
    $this->resetPlaylist();
    $this->sendRequest( 'status', array( 'command' => 'pl_empty', 'input' => trim( $sEntry ) ) );
    return URC_INTERFACE::STATUS_OK;
  }

  public function playlistEntryRemove( $iPosition )
  {
    $this->queryPlaylist();
    $iPosition = (integer)$iPosition; if( $iPosition < 1 ) $iPosition = 1;
    if( isset( $this->aiPlaylistIndex[$iPosition] ) )
    {
      $this->sendRequest( 'status', array( 'command' => 'pl_delete', 'id' => $this->aiPlaylistIndex[$iPosition] ) );
      $this->resetPlaylist();
      $this->resetStatus();
    }
    usleep( 500000 ); // let's give the backend some time to react
    return $this->playlistPositionGet( true );
  }

  public function playlistPositionSet( $iPosition )
  {
    $this->queryPlaylist();
    $iPosition = (integer)$iPosition; if( $iPosition < 1 ) $iPosition = 1;
    if( isset( $this->aiPlaylistIndex[$iPosition-1] ) )
    {
      $this->sendRequest( 'status', array( 'command' => 'pl_play', 'id' => $this->aiPlaylistIndex[$iPosition-1] ) );
      $this->resetPlaylist();
      $this->resetStatus();
    }
    usleep( 500000 ); // let's give the backend some time to react
    return $this->playlistPositionGet( true );
  }


  /*
   * METHODS: (media-)playitem "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemFileGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    $this->queryPlaylist( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP or
        (string)$this->iPlaylistPosition == URC_INTERFACE::STATUS_UNKNOWN )
    if( $this->iPlaylistPosition < 0 )
      return URC_INTERFACE::STATUS_ERROR;
    return $this->asPlaylistFiles[$this->iPlaylistPosition];
  }

  public function playitemArtistGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['artist'];
  }

  public function playitemAlbumGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['album'];
  }

  public function playitemTitleGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['title'];
  }

  public function playitemDateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['date'];
  }

  public function playitemGenreGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['genre'];
  }

  public function playitemCopyrightGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['copyright'];
  }

  public function playitemDescriptionGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['description'];
  }

  public function playitemCommentGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['comment'];
  }

  public function playitemRatingGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['rating'];
  }

  public function playitemLengthGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['length'];
  }

  public function playitemPositionGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
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
    $iPosition = (integer)$iPosition; if( $iPosition < 0 ) $iPosition = 0;
    $this->sendRequest( 'status', array( 'command' => 'seek', 'val' => $iPosition ) );
    return $this->playitemPositionGet( true );
  }


  /*
   * METHODS: (audio-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-id'];
  }

  public function playitemAudioLanguageGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-language'];
  }

  public function playitemAudioChannelsGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-channels'];
  }

  public function playitemAudioSampleRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-samplerate'];
  }

  public function playitemAudioSampleBitsGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemAudioBitRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-bitrate'];
  }

  public function playitemAudioCodecGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['audio-codec'];
  }


  /*
   * METHODS: (audio-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemAudioToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }


  /*
   * METHODS: (video-)player "getters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemVideoGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-id'];
  }

  public function playitemVideoTitleGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoAngleGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoFrameWidthGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-width'];
  }

  public function playitemVideoFrameHeightGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-height'];
  }

  public function playitemVideoFrameRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-framerate'];
  }

  public function playitemVideoPixelBitsGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoBitRateGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-bitrate'];
  }

  public function playitemVideoCodecGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['video-codec'];
  }

  public function playitemVideoSubtitleGet( $bForceRefresh = false )
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoSubtitleLanguageGet( $bForceRefresh = false )
  {
    $this->queryStatus( $bForceRefresh );
    if( $this->amStatus['state'] == URC_INTERFACE_PLAYER::PLAYBACK_STOP )
      return URC_INTERFACE::STATUS_UNKNOWN;
    return $this->amStatus['subtitle-language'];
  }


  /*
   * METHODS: (video-)player "setters" - IMPLEMENTS
   ********************************************************************************/

  public function playitemVideoToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoTitleToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoAngleToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

  public function playitemVideoSubtitleToggle()
  {
    return URC_INTERFACE::STATUS_NOTIMPLEMENTED;
  }

}
