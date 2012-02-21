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


/** Load VLC interface class */
require( dirname( __FILE__ ).'/interface.php' );


/** URC VLC control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to interface with the VideoLAN Player (VLC) when launched in HTTP mode (<SAMP>-I http</SAMP>).</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>command</SAMP> parameter, among:
 * <BR/><SAMP>play</SAMP>: start playback [PB]
 * <BR/><SAMP>stop</SAMP>: stop playback [PB]
 * <BR/><SAMP>pause</SAMP>: pause/resume playback [PB]
 * <BR/><SAMP>repeat</SAMP>: toggle repeat mode [SB]
 * <BR/><SAMP>random</SAMP>: toggle random mode [SB]
 * <BR/><SAMP>volume_set</SAMP>: set volume [VC]
 * <BR/><SAMP>volume_decrease</SAMP>: increase volume [PB]
 * <BR/><SAMP>volume_increase</SAMP>: decrease volume [PB]
 * <BR/><SAMP>volume_mute</SAMP>: mute volume [SB]
 * <BR/><SAMP>previous</SAMP>: go to previous playitem in playlist [PB]
 * <BR/><SAMP>goto</SAMP>: go to relative position in current playitem [PB]
 * <BR/><SAMP>rewind</SAMP>: rewind current playitem [PB]
 * <BR/><SAMP>forward</SAMP>: go forward current playitem [PB]
 * <BR/><SAMP>next</SAMP>: go to next playitem in playlist [PB]
 * <BR/><SAMP>display_playback</SAMP>: display playback state (play/stop/pause) [TXT]
 * <BR/><SAMP>display_repeat</SAMP>: display repeat playback status (on/off) [TXT]
 * <BR/><SAMP>display_random</SAMP>: display random playback status (on/off) [TXT]
 * <BR/><SAMP>display_volume</SAMP>: display current volume (scale: 0-100) [TXT]
 * <BR/><SAMP>display_file</SAMP>: display current playitem's file name [TXT]
 * <BR/><SAMP>display_artist</SAMP>: display current playitem's artist [TXT]
 * <BR/><SAMP>display_album</SAMP>: display current playitem's album [TXT]
 * <BR/><SAMP>display_title</SAMP>: display current playitem's title [TXT]
 * <BR/><SAMP>display_date</SAMP>: display current playitem's date [TXT]
 * <BR/><SAMP>display_genre</SAMP>: display current playitem's genre [TXT]
 * <BR/><SAMP>display_copyright</SAMP>: display current playitem's copyright [TXT]
 * <BR/><SAMP>display_description</SAMP>: display current playitem's description [TXT]
 * <BR/><SAMP>display_comment</SAMP>: display current playitem's comment [TXT]
 * <BR/><SAMP>display_rating</SAMP>: display current playitem's rating [TXT]
 * <BR/><SAMP>display_length</SAMP>: display current playitem's length (format: hh:mm:ss) [TXT]
 * <BR/><SAMP>display_position</SAMP>: display current playitem's position (format: hh:mm:ss) [TXT]
 * <BR/><SAMP>display_audio</SAMP>: display current audio source/stream name/ID [TXT]
 * <BR/><SAMP>display_audio_language</SAMP>: display current audio language [TXT]
 * <BR/><SAMP>display_audio_channels</SAMP>: display current audio channels quantity/name [TXT]
 * <BR/><SAMP>display_audio_samplerate</SAMP>: display current audio sampling rate [TXT]
 * <BR/><SAMP>display_audio_bitrate</SAMP>: display current audio bit rate [TXT]
 * <BR/><SAMP>display_audio_codec</SAMP>: display current audio codec [TXT]
 * <BR/><SAMP>display_video</SAMP>: display current video source/stream name/ID [TXT]
 * <BR/><SAMP>display_video_width</SAMP>: display current video width (in pixels) [TXT]
 * <BR/><SAMP>display_video_height</SAMP>: display current video height (in pixels) [TXT]
 * <BR/><SAMP>display_video_framerate</SAMP>: display current video frame rate [TXT]
 * <BR/><SAMP>display_video_bitrate</SAMP>: display current video bit rate [TXT]
 * <BR/><SAMP>display_video_codec</SAMP>: display current video codec [TXT]
 * <BR/><SAMP>display_subtitle_language</SAMP>: display current subtitle language [TXT]
 * <BR/><SAMP>display_refresh</SAMP>: dummy control to refresh all displayed values [PB]</LI>
 * <LI>an OPTIONAL (<I>mixed</I>)<SAMP>value</SAMP> parameter, overriding any UI-passed value (useful for macros)</LI>
 * </UL>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'VLC', 'vlc-control', 'My VLC control', array( 'command' => 'play' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
 * </CODE>
 * <P><B>NOTE:</B> have a look at the plug-in's sample 'urc.config.php' for more examples.</P>
 *
 * @package PHP_URC_Plugins
 * @subpackage VLC
 */
class URC_CONTROL_VLC extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's associated command
   * @var string */
  private $sCommand = null;

  /** Control's overriding value
   * @var mixed */
  private $mValue = null;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the control
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Control's identifier (ID)
   * @param string $sName Control's (human-friendly) name
   * @param mixed $mParameters Control's parameters
   * @param string $sType Control's type (see class constants)
   */
  public function __construct( $sID, $sName, $mParameters, $sType )
  {
    parent::__construct( $sID, $sName, $mParameters, $sType );

    // Command
    if( !isset( $this->mParameters['command'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing "command" parameter' );
    $this->sCommand = strtolower( trim( $this->mParameters['command'] ) );
    
    // Overriding value
    if( isset( $this->mParameters['value'] ) )
      $this->mValue = $this->mParameters['value'];
  }


  /*
   * METHODS: interface
   ********************************************************************************/

  /** Returns the VLC interface (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return URC_INTERFACE_VLC
   */
  public static function &useInterface()
  {
    return URC_INTERFACE_VLC::useInstance();
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Returns whether a value is disaplayble
   *
   * @param mixed $mValue Value to be tested
   * @return boolean
   */
  public static function displayableValue( $mValue )
  {
    return( !empty( $mValue ) && URC_INTERFACE::isStatusOK( $mValue ) );
  }

  /** Returns the time nicely formatted as '(hh:)mm:ss'
   *
   * @param integer $iSeconds Time [seconds]
   * @return string
   */
  public static function displayTime( $iSeconds )
  {
    $iSeconds = (integer)$iSeconds;
    if( $iSeconds<0 ) return '--:--';
    $iMinutes = floor($iSeconds/60); $iSeconds = $iSeconds%60;
    $iHours = floor($iMinutes/60); $iMinutes = $iMinutes%60;
    return $iHours>0 ? sprintf( '%d:%02d:%02d', $iHours, $iMinutes, $iSeconds ) : sprintf( '%02d:%02d', $iMinutes, $iSeconds );
  }


  /*
   * METHODS: control - OVERRIDE
   ********************************************************************************/

  /** Set the control's value
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $mValue Control's value
   */
  public function setValue( $mValue )
  {
    // Retrieve VLC interface
    $roVLC =& URC_INTERFACE_VLC::useInstance();

    // Override value
    if( !is_null( $this->mValue ) ) $mValue=$this->mValue;

    // Make sure the input value is normalized
    $mValue = (float)$mValue; if($mValue<0) $mValue=0; elseif($mValue>1) $mValue=1;

    // Handle control
    switch( $this->sCommand )
    {
    case 'stop':
      $roVLC->playerPlaybackSet( URC_INTERFACE_PLAYER::PLAYBACK_STOP );
      break;

    case 'pause':
      $roVLC->playerPlaybackSet( URC_INTERFACE_PLAYER::PLAYBACK_PAUSE );
      break;

    case 'play':
      $roVLC->playerPlaybackSet( URC_INTERFACE_PLAYER::PLAYBACK_PLAY );
      break;

    case 'repeat':
      $roVLC->playerRepeatSet( $mValue ? URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
      break;

    case 'random':
      $roVLC->playerRandomSet( $mValue ? URC_INTERFACE_PLAYER::PLAYBACK_RANDOM : URC_INTERFACE_PLAYER::PLAYBACK_NORMAL );
      break;

    case 'volume_set':
      $roVLC->playerVolumeSet( 100*$mValue );
      break;

    case 'volume_decrease':
      $roVLC->playerVolumeSet( $roVLC->playerVolumeGet()-100*$this->getRepeatValue() );
      break;

    case 'volume_increase':
      $roVLC->playerVolumeSet( $roVLC->playerVolumeGet()+100*$this->getRepeatValue()+1 ); // NOTE: The +1 is a hack to circumvent VLC bug
      break;

    case 'volume_mute':
      $roVLC->playerMuteSet( $mValue );
      break;

    case 'previous':
      $roVLC->playlistPositionSet( $roVLC->playlistPositionGet()-1 );
      break;

    case 'next':
      $roVLC->playlistPositionSet( $roVLC->playlistPositionGet()+1 );
      break;

    case 'position':
      $roVLC->playitemPositionSet( $mValue*$roVLC->playitemLengthGet() );
      break;

    case 'rewind':
      $roVLC->playitemPositionSet( $roVLC->playitemPositionGet()-$this->getRepeatValue()*$roVLC->playitemLengthGet() );
      break;

    case 'forward':
      $roVLC->playitemPositionSet( $roVLC->playitemPositionGet()+$this->getRepeatValue()*$roVLC->playitemLengthGet() );
      break;

    case 'display_playback':
    case 'display_repeat':
    case 'display_random':
    case 'display_volume':
    case 'display_file':
    case 'display_artist':
    case 'display_album':
    case 'display_title':
    case 'display_date':
    case 'display_genre':
    case 'display_copyright':
    case 'display_description':
    case 'display_comment':
    case 'display_rating':
    case 'display_length':
    case 'display_position':
    case 'display_audio':
    case 'display_audio_language':
    case 'display_audio_channels':
    case 'display_audio_samplerate':
    case 'display_audio_bitrate':
    case 'display_audio_codec':
    case 'display_video':
    case 'display_video_width':
    case 'display_video_height':
    case 'display_video_framerate':
    case 'display_video_bitrate':
    case 'display_video_codec':
    case 'display_subtitle_language':
    case 'display_refresh':
      // Not a action (status display only)
      break;

    default:
      throw new URC_EXCEPTION( __METHOD__, 'Invalid command; Command: '.$this->sCommand );
    }

    // Tell the URC framework that something happened here
    URC::useInstance()->addModifiedControlIDs( $this->getID() );
  }

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * @return mixed
   */
  public function getValue()
  {
    // Retrieve VLC backend
    $roVLC =& URC_INTERFACE_VLC::useInstance();

    // Handle control
    switch( $this->sCommand )
    {
    case 'repeat':
      return ( $roVLC->playerRepeatGet() == URC_INTERFACE_PLAYER::PLAYBACK_NORMAL ? 0 : 1 );

    case 'random':
      return ( $roVLC->playerRandomGet() == URC_INTERFACE_PLAYER::PLAYBACK_NORMAL ? 0 : 1 );

    case 'position':
    case 'rewind':
    case 'forward':
      $iPosition = $roVLC->playitemPositionGet();
      $iLength = $roVLC->playitemLengthGet();
      return ( $iPosition>=0 && $iLength>0 ? (float)($iPosition/$iLength): 0 );

    case 'volume_set':
    case 'volume_decrease':
    case 'volume_increase':
      return (float)$roVLC->playerVolumeGet()/100;

    case 'volume_mute':
      return ( $roVLC->playerMuteGet() ? 1 : 0 );

    case 'display_playback':
      $mValue = $roVLC->playerPlaybackGet();
      switch( $mValue )
      {
      case URC_INTERFACE_PLAYER::PLAYBACK_STOP: return 'stop';
      case URC_INTERFACE_PLAYER::PLAYBACK_PAUSE: return 'pause';
      case URC_INTERFACE_PLAYER::PLAYBACK_PLAY: return 'play';
      }
      return '--';

    case 'display_repeat':
      $mValue = $roVLC->playerRepeatGet();
      switch( $mValue )
      {
      case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALL:
      case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_ALBUM:
      case URC_INTERFACE_PLAYER::PLAYBACK_REPEAT_TITLE: return 'on';
      case URC_INTERFACE_PLAYER::PLAYBACK_NORMAL: return 'off';
      }
      return '--';

    case 'display_random':
      $mValue = $roVLC->playerRandomGet();
      switch( $mValue )
      {
      case URC_INTERFACE_PLAYER::PLAYBACK_RANDOM: return 'on';
      case URC_INTERFACE_PLAYER::PLAYBACK_NORMAL: return 'off';
      }
      return '--';

    case 'display_volume':
      $mValue = $roVLC->playerVolumeGet();
      return ( self::displayableValue( $mValue ) && $mValue>=0 ? $mValue.'%' : '--' );

    case 'display_file':
      $mValue = $roVLC->playitemFileGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_artist':
      $mValue = $roVLC->playitemArtistGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_album':
      $mValue = $roVLC->playitemAlbumGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_title':
      $mValue = $roVLC->playitemTitleGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_date':
      $mValue = $roVLC->playitemDateGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_genre':
      $mValue = $roVLC->playitemGenreGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_copyright':
      $mValue = $roVLC->playitemCopyrightGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_description':
      $mValue = $roVLC->playitemDescriptionGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_comment':
      $mValue = $roVLC->playitemCommentGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_rating':
      $mValue = $roVLC->playitemRatingGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_length':
      $mValue = $roVLC->playitemLengthGet();
      return ( self::displayableValue( $mValue ) && $mValue>=0 ? self::displayTime( $mValue ) : '--:--' );

    case 'display_position':
      $mValue = $roVLC->playitemPositionGet();
      return ( self::displayableValue( $mValue ) && $mValue>=0 ? self::displayTime( $mValue ) : '--:--' );

    case 'display_audio':
      $mValue = $roVLC->playitemAudioGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_audio_language':
      $mValue = $roVLC->playitemAudioLanguageGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_audio_channels':
      $mValue = $roVLC->playitemAudioChannelsGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_audio_samplerate':
      $mValue = $roVLC->playitemAudioSampleRateGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'Hz' : '--' );

    case 'display_audio_bitrate':
      $mValue = $roVLC->playitemAudioBitRateGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'bps' : '--' );

    case 'display_audio_codec':
      $mValue = $roVLC->playitemAudioCodecGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_video':
      $mValue = $roVLC->playitemVideoGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_video_width':
      $mValue = $roVLC->playitemVideoFrameWidthGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'px' : '--' );

    case 'display_video_height':
      $mValue = $roVLC->playitemVideoFrameHeightGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'px' : '--' );

    case 'display_video_framerate':
      $mValue = $roVLC->playitemVideoFrameRateGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'fps' : '--' );

    case 'display_video_bitrate':
      $mValue = $roVLC->playitemVideoBitRateGet();
      return ( self::displayableValue( $mValue ) ? $mValue.'bps' : '--' );

    case 'display_video_codec':
      $mValue = $roVLC->playitemVideoCodecGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );

    case 'display_subtitle_language':
      $mValue = $roVLC->playitemVideoSubtitleLanguageGet();
      return ( self::displayableValue( $mValue ) ? $mValue : '--' );
    }

    // Undefined return value
    return null;
  }

}
