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
 * @subpackage DUMMY
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/*
 * FRAMEWORK INITILIZATION
 ********************************************************************************/

// Retrieve and configure URC framework
$oURC = URC::useInstance();


/*
 * CONTROLS DEFINITION
 ********************************************************************************/

// Hyperlinks
$oURC->addHyperlink( $oURC->newHyperlink( 'HLXS', 'Hyperlink (XS)', 'URC_onMU();window.alert(\'XS\')', URC_HYPERLINK::TYPE_JAVASCRIPT ) );
$oURC->addHyperlink( $oURC->newHyperlink( 'HLS', 'Hyperlink (S)', 'URC_onMU();window.alert(\'S\')', URC_HYPERLINK::TYPE_JAVASCRIPT ) );
$oURC->addHyperlink( $oURC->newHyperlink( 'HLM', 'Hyperlink (M)', 'URC_onMU();window.alert(\'M\')', URC_HYPERLINK::TYPE_JAVASCRIPT ) );
$oURC->addHyperlink( $oURC->newHyperlink( 'HLL', 'Hyperlink (L)', 'URC_onMU();window.alert(\'L\')', URC_HYPERLINK::TYPE_JAVASCRIPT ) );
$oURC->addHyperlink( $oURC->newHyperlink( 'HLXL', 'Hyperlink (XL)', 'URC_onMU();window.alert(\'XL\');', URC_HYPERLINK::TYPE_JAVASCRIPT ) );

// Push Buttons
$oURC->addControl( $oURC->newControl( 'DUMMY', 'PBXS', 'Push Button (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'PBS', 'Push Button (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'PBM', 'Push Button (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'PBL', 'Push Button (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'PBXL', 'Push Button (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_PUSHBUTTON ) );

// Text Panels
$oURC->addControl( $oURC->newControl( 'DUMMY', 'TXXS', 'Text (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'TXS', 'Text (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'TXM', 'Text (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'TXL', 'Text (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_TEXT ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'TXXL', 'Text (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_TEXT ) );

// Switch Buttons
$oURC->addControl( $oURC->newControl( 'DUMMY', 'SBXS', 'Switch Button (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'SBS', 'Switch Button (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'SBM', 'Switch Button (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'SBL', 'Switch Button (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'SBXL', 'Switch Button (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_SWITCHBUTTON ) );

// Rotary Buttons
$oURC->addControl( $oURC->newControl( 'DUMMY', 'RBXS', 'Rotary Button (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_ROTARYBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'RBS', 'Rotary Button (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_ROTARYBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'RBM', 'Rotary Button (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_ROTARYBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'RBL', 'Rotary Button (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_ROTARYBUTTON ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'RBXL', 'Rotary Button (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_ROTARYBUTTON ) );

// Horizontal Sliders
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HSXS', 'Horizontal Slider (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HSS', 'Horizontal Slider (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HSM', 'Horizontal Slider (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HSL', 'Horizontal Slider (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HSXL', 'Horizontal Slider (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_HORIZONTALSLIDER ) );

// Horizontal Bars
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HBXS', 'Horizontal Bar (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HBS', 'Horizontal Bar (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HBM', 'Horizontal Bar (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HBL', 'Horizontal Bar (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'HBXL', 'Horizontal Bar (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_HORIZONTALBAR ) );

// Vertical Sliders
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VSXS', 'Vertical Slider (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_VERTICALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VSS', 'Vertical Slider (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_VERTICALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VSM', 'Vertical Slider (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_VERTICALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VSL', 'Vertical Slider (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_VERTICALSLIDER ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VSXL', 'Vertical Slider (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_VERTICALSLIDER ) );

// Vertical Bars
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VBXS', 'Vertical Bar (XS)', array( 'type'=>'readback', 'var'=>'XS' ), URC_CONTROL::TYPE_VERTICALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VBS', 'Vertical Bar (S)', array( 'type'=>'readback', 'var'=>'S' ), URC_CONTROL::TYPE_VERTICALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VBM', 'Vertical Bar (M)', array( 'type'=>'readback', 'var'=>'M' ), URC_CONTROL::TYPE_VERTICALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VBL', 'Vertical Bar (L)', array( 'type'=>'readback', 'var'=>'L' ), URC_CONTROL::TYPE_VERTICALBAR ) );
$oURC->addControl( $oURC->newControl( 'DUMMY', 'VBXL', 'Vertical Bar (XL)', array( 'type'=>'readback', 'var'=>'XL' ), URC_CONTROL::TYPE_VERTICALBAR ) );


/*
 * CONTROLS TWEAKING
 ********************************************************************************/

// Dependencies
$oURC->addDependentControlIDs( array( 'PBXS', 'SBXS', 'RBXS', 'HSXS', 'HBXS', 'VSXS', 'VBXS' ),
                               array( 'PBXS', 'SBXS', 'RBXS', 'HSXS', 'HBXS', 'VSXS', 'VBXS', 'TXXS' ) );
$oURC->addDependentControlIDs( array( 'PBS', 'SBS', 'RBS', 'HSS', 'HBS', 'VSS', 'VBS' ),
                               array( 'PBS', 'SBS', 'RBS', 'HSS', 'HBS', 'VSS', 'VBS', 'TXS' ) );
$oURC->addDependentControlIDs( array( 'PBM', 'SBM', 'RBM', 'HSM', 'HBM', 'VSM', 'VBM' ),
                               array( 'PBM', 'SBM', 'RBM', 'HSM', 'HBM', 'VSM', 'VBM', 'TXM' ) );
$oURC->addDependentControlIDs( array( 'PBL', 'SBL', 'RBL', 'HSL', 'HBL', 'VSL', 'VBL' ),
                               array( 'PBL', 'SBL', 'RBL', 'HSL', 'HBL', 'VSL', 'VBL', 'TXL' ) );
$oURC->addDependentControlIDs( array( 'PBXL', 'SBXL', 'RBXL', 'HSXL', 'HBXL', 'VSXL', 'VBXL' ),
                               array( 'PBXL', 'SBXL', 'RBXL', 'HSXL', 'HBXL', 'VSXL', 'VBXL', 'TXXL' ) );
