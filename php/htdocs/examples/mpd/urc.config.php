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
 * @author Cedric Dufour <http://www.ced-network.net/php-urc>
 * @version @version@
 */


/*
 * FRAMEWORK INITILIZATION
 ********************************************************************************/

// Retrieve and configure URC framework
$oURC = URC::useInstance();

// Initialize the plugin
// WARNING: Adapt these parameters to match your setup!
$oURC->loadPlugin( 'MPD' );
URC_INTERFACE_MPD::useInstance()->initBackend( '127.0.0.1', 6600, 'password' );


/*
 * CONTROLS DEFINITION
 ********************************************************************************/

// WARNING: The more controls you enable, the higher the latency of the user
//          (web) interface!

$oURC->addControl( $oURC->newControl( 'MPD', 'stop', 'Stop Playback', array( 'command'=>'stop' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'play', 'Start Playback', array( 'command'=>'play' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'pause', 'Pause Playback', array( 'command'=>'pause' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'repeat', 'Repeat (Loop)', array( 'command'=>'repeat' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'random', 'Random (Shuffle)', array( 'command'=>'random' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'volume_decrease', 'Volume Decrease', array( 'command'=>'volume_decrease' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'volume_set', 'Volume Set', array( 'command'=>'volume_set' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'volume_increase', 'Volume Increase', array( 'command'=>'volume_increase' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'volume_mute', 'Volume Mute', array( 'command'=>'volume_mute' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'previous', 'Previous Playitem', array( 'command'=>'previous' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'rewind', 'Playitem Rewind', array( 'command'=>'rewind' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'position', 'Playitem Position', array( 'command'=>'position' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'forward', 'Playitem Forward', array( 'command'=>'forward' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'next', 'Next Playitem', array( 'command'=>'next' ), URC_CONTROL::TYPE_PUSHBUTTON ) );

// Text Panels
$oURC->addControl( $oURC->newControl( 'MPD', 'display_playback', 'Playback Status', array( 'command'=>'display_playback' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_repeat', 'Repeat Status', array( 'command'=>'display_repeat' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_random', 'Random Status', array( 'command'=>'display_random' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_volume', 'Volume', array( 'command'=>'display_volume' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_file', 'Filename', array( 'command'=>'display_file' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_artist', 'Artist', array( 'command'=>'display_artist' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_album', 'Album', array( 'command'=>'display_album' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_title', 'Title', array( 'command'=>'display_title' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_date', 'Date', array( 'command'=>'display_date' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_genre', 'Genre', array( 'command'=>'display_genre' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_copyright', 'Copyright', array( 'command'=>'display_copyright' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_description', 'Description', array( 'command'=>'display_description' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_comment', 'Comment', array( 'command'=>'display_comment' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_rating', 'Rating', array( 'command'=>'display_rating' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_length', 'Length', array( 'command'=>'display_length' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_position', 'Position', array( 'command'=>'display_position' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_audio_channels', 'Channels', array( 'command'=>'display_audio_channels' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_audio_samplerate', 'Sample Rate', array( 'command'=>'display_audio_samplerate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_audio_samplebits', 'Bits per Sample', array( 'command'=>'display_audio_samplebits' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_audio_bitrate', 'Bit Rate', array( 'command'=>'display_audio_bitrate' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'MPD', 'display_refresh', 'Refresh All Fields', array( 'command'=>'display_refresh' ), URC_CONTROL::TYPE_PUSHBUTTON ) );


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
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'repeat', 'random', 'position', 'volume_set', 'volume_mute' ) ) or substr($sID,0,8) == 'display_' )
    $oURC->addPollControlIDs( $sID );
}

// Dependendent controls

// ... playback controls
$asTriggerControls = array();
$asDependentControls = array();
foreach( $oURC->getAllControlIDs() as $sID )
{
  if( in_array( $sID, array( 'stop', 'play', 'pause', 'repeat', 'random', 'previous', 'next', 'display_refresh' ) ) )
    array_push( $asTriggerControls, $sID );
  if( in_array( $sID, array( 'repeat', 'random', 'position' ) ) or substr($sID,0,8) == 'display_' )
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
