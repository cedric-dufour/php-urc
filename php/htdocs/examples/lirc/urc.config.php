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
 * @subpackage LIRC
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
$oURC->loadPlugin( 'LIRC' );
URC_INTERFACE_LIRC::useInstance()->setCommandPath( 'sudo /usr/bin/irsend' );


/*
 * CONTROLS DEFINITION
 ********************************************************************************/

// Push Buttons
$oURC->addControl( $oURC->newControl( 'LIRC', 'power_off', 'Power OFF', array( 'remote'=>'remote', 'code'=>'standby' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'power_on', 'Power ON', array( 'remote'=>'remote', 'code'=>'power' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'volume_up', 'Volume Up', array( 'remote'=>'remote', 'code'=>'volume_up' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'volume_down', 'Volume Down', array( 'remote'=>'remote', 'code'=>'volume_down' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'playlist_previous', 'Previous', array( 'remote'=>'remote', 'code'=>'previous' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'position_rewind', 'Rewind', array( 'remote'=>'remote', 'code'=>'rewind' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'playlist_stop', 'Stop', array( 'remote'=>'remote', 'code'=>'stop' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'playlist_play', 'Play', array( 'remote'=>'remote', 'code'=>'play' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'playlist_pause', 'Pause', array( 'remote'=>'remote', 'code'=>'pause' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'position_forward', 'Forward', array( 'remote'=>'remote', 'code'=>'forward' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'LIRC', 'playlist_next', 'Next', array( 'remote'=>'remote', 'code'=>'next' ), URC_CONTROL::TYPE_PUSHBUTTON ) );


/*
 * CONTROLS TWEAKING
 ********************************************************************************/

// Repeatable controls
foreach( array( 'volume_up', 'volume_down', 'position_rewind', 'position_forward' ) as $sID )
$oURC->useControl( $sID )->setRepeat( true );
