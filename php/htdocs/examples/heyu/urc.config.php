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
 * @subpackage HEYU
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
$oURC->loadPlugin( 'HEYU' );
URC_INTERFACE_HEYU::useInstance()->setCommandPath( 'sudo /usr/bin/heyu' );


/*
 * CONTROLS DEFINITION
 ********************************************************************************/

// Appliance
$oURC->addControl( $oURC->newControl( 'HEYU', 'appliance_toggle', 'Appliance: ON/OFF', array( 'house'=>'A', 'unit'=>'1', 'command'=>'switch_toggle' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );

// Light
$oURC->addControl( $oURC->newControl( 'HEYU', 'light_toggle', 'Light: ON/OFF', array( 'house'=>'A', 'unit'=>'2', 'command'=>'dimmer_toggle' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'HEYU', 'light_set', 'Light: adjust', array( 'house'=>'A', 'unit'=>'2', 'command'=>'dimmer_set' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'HEYU', 'light_level', 'Light: level', array( 'house'=>'A', 'unit'=>'2', 'command'=>'dimmer_level' ), URC_CONTROL::TYPE_TEXT ) );


/*
 * CONTROLS TWEAKING
 ********************************************************************************/

// Repeatable controls
foreach( array('light_set') as $sID )
  $oURC->useControl( $sID )->setRepeat( true, 0.05 );

// Polled controls
$oURC->addPollControlIDs( array( 'appliance_toggle', 'light_toggle', 'light_set', 'light_level' ) );

// Dependent controls
$oURC->addDependentControlIDs( array( 'light_toggle', 'light_set' ), array( 'light_toggle', 'light_set', 'light_level' ) );
