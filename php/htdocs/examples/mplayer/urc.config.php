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


/*
 * FRAMEWORK INITILIZATION
 ********************************************************************************/

// Retrieve and configure URC framework
$oURC = URC::useInstance();

// The MPLAYER backend suffers from high latency; let's adapt poll and timeout accordingly
$oURC->setUIParameters( 1000, 5000 );

// Initialize the plugin
// WARNING: Adapt these parameters to match your setup!
$oURC->loadPlugin( 'MPLAYER' );
URC_INTERFACE_MPLAYER::useInstance()->initBackend( '127.0.0.1', 5000 );


/*
 * CONTROLS DEFINITION
 ********************************************************************************/

// WARNING: The more controls you enable, the higher the latency of the user
//          (web) interface!

$oURC->addControl( $oURC->newControl( 'MPLAYER', 'stop', 'Stop Playback', array( 'command'=>'stop' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'play', 'Start Playback', array( 'command'=>'play' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'pause', 'Pause Playback', array( 'command'=>'pause' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'repeat', 'Repeat (Loop)', array( 'command'=>'repeat' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'volume_decrease', 'Volume Decrease', array( 'command'=>'volume_decrease' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'volume_set', 'Volume Set', array( 'command'=>'volume_set' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'volume_increase', 'Volume Increase', array( 'command'=>'volume_increase' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'volume_mute', 'Volume Mute', array( 'command'=>'volume_mute' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'previous', 'Previous Playitem', array( 'command'=>'previous' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'rewind', 'Playitem Rewind', array( 'command'=>'rewind' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'position', 'Playitem Position', array( 'command'=>'position' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'forward', 'Playitem Forward', array( 'command'=>'forward' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'next', 'Next Playitem', array( 'command'=>'next' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'switch_audio', 'Switch Audio Source/Stream', array( 'command'=>'switch_audio' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'switch_video', 'Switch Video Source/Stream', array( 'command'=>'switch_video' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'switch_video_title', 'Switch Video Title', array( 'command'=>'switch_video_title' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'switch_video_angle', 'Switch Video Angle', array( 'command'=>'switch_video_angle' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'switch_subtitle', 'Switch Subtitles', array( 'command'=>'switch_subtitle' ), URC_CONTROL::TYPE_PUSHBUTTON ) );

// Text Panels
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_playback', 'Playback Status', array( 'command'=>'display_playback' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_repeat', 'Repeat Status', array( 'command'=>'display_repeat' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_volume', 'Volume', array( 'command'=>'display_volume' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_file', 'Filename', array( 'command'=>'display_file' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_artist', 'Artist', array( 'command'=>'display_artist' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_album', 'Album', array( 'command'=>'display_album' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_title', 'Title', array( 'command'=>'display_title' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_date', 'Date', array( 'command'=>'display_date' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_genre', 'Genre', array( 'command'=>'display_genre' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_copyright', 'Copyright', array( 'command'=>'display_copyright' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_description', 'Description', array( 'command'=>'display_description' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_comment', 'Comment', array( 'command'=>'display_comment' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_rating', 'Rating', array( 'command'=>'display_rating' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_length', 'Length', array( 'command'=>'display_length' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_position', 'Position', array( 'command'=>'display_position' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_audio', 'Audio Source/Stream', array( 'command'=>'display_audio' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_audio_channels', 'Audio Channels', array( 'command'=>'display_audio_channels' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_audio_samplerate', 'Audio Sample Rate', array( 'command'=>'display_audio_samplerate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_audio_bitrate', 'Audio Bit Rate', array( 'command'=>'display_audio_bitrate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_audio_codec', 'Audio Codec', array( 'command'=>'display_audio_codec' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video', 'Video Source/Stream', array( 'command'=>'display_video' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_title', 'Video Title', array( 'command'=>'display_video_title' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_angle', 'Video Angle', array( 'command'=>'display_video_angle' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_width', 'Video Width', array( 'command'=>'display_video_width' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_height', 'Video Height', array( 'command'=>'display_video_height' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_framerate', 'Video Frame Rate', array( 'command'=>'display_video_framerate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_bitrate', 'Video Bit Rate', array( 'command'=>'display_video_bitrate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_video_codec', 'Video Codec', array( 'command'=>'display_video_codec' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_subtitle', 'Subtitles', array( 'command'=>'display_subtitle' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPLAYER', 'display_refresh', 'Refresh All Fields', array( 'command'=>'display_refresh' ), URC_CONTROL::TYPE_PUSHBUTTON ) );


/*
 * CONTROLS TWEAKING
 ********************************************************************************/

// NOTE: The PHP code below is a little circonvoluted but allows to easily
//       enable/disable (comment out) controls in the above section

// Repeatable controls
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'rewind', 'position', 'forward', 'volume_decrease', 'volume_set', 'volume_increase' ) ) )
    $oURC->useControl( $sID )->setRepeat( true );
}

// Polled controls
// WARNING: The MPLAYER backend has too-high a latency to poll *all* display controls;
//          We must stick to controls that are updated with 'metadata' command
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'repeat', 'position', 'volume_set', 'volume_mute', 'display_position', 'display_artist', 'display_album', 'display_title', 'display_date', 'display_genre', 'display_copyright', 'display_description', 'display_comment', 'display_rating' ) ) )
    $oURC->addPollControlIDs( $sID );
}

// Dependendent controls

// ... playback controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'stop', 'play', 'pause', 'repeat', 'previous', 'next', 'display_refresh' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'repeat', 'position' ) ) or substr($sID,0,8) == 'display_' )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );

// ... position controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'rewind', 'position', 'forward' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'position', 'display_position' ) ) )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );

// ... volume controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'volume_decrease', 'volume_set', 'volume_increase', 'volume_mute' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'volume_set', 'volume_mute', 'display_volume' ) ) )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );

// ... audio controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'switch_audio' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'display_audio', 'display_audio_channels', 'display_audio_samplerate', 'display_audio_bitrate' ) ) )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );

// ... video controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'switch_video' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'display_video', 'display_video_title', 'display_video_angle', 'display_video_width', 'display_video_height', 'display_video_framerate', 'display_video_bitrate' ) ) )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );

// ... subtitle controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'switch_subtitle' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'display_subtitle' ) ) )
    array_push( $asDependentControls, $sID );
}
$oURC->addDependentControlIDs( $asTriggerControls, $asDependentControls );
