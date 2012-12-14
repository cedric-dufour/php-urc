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
 * @subpackage MACRO
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** URC macro control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to aggregate multiple controls in a single macro control.</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>an array of other URC controls</LI>
 * </UL>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oControl_1 = $oURC->newControl( 'LIRC', 'control-1', 'My first control', array( 'remote_1' => 'on' ) ) );
 * $oControl_2 = $oURC->newControl( 'DELAY', 'control-2', 'My second control', array( 'delay' => 500 ) ) );
 * $oControl_3 = $oURC->newControl( 'LIRC', 'control-3', 'My third control', array( 'remote_2' => 'on' ) ) );
 * $oURC->addControl( $oURC->newControl( 'MACRO', 'macro-control', 'My macro control', array( $oControl_1, $oControl_2, $oControl_3 ), URC_CONTROL::TYPE_PUSHBUTTON ) );
 * </CODE>
 *
 * @package PHP_URC_Plugins
 * @subpackage MACRO
 */
class URC_CONTROL_MACRO extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Controls list
   * @var array|URC_CONTROL */
  private $aoControls = array();


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the control
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Control's identifier (ID)
   * @param string $sName Control's (human-friendly) name
   * @param array|URC_CONTROL $mParameters Array of URC controls
   * @param string $sType Control's type (see class constants)
   */
  public function __construct( $sID, $sName, $mParameters, $sType )
  {
    parent::__construct( $sID, $sName, $mParameters, $sType );

    // Add controls
    if( !is_null( $mParameters ) )
    {
      if( is_scalar( $mParameters ) ) $mParameters = array( $mParameters );
      foreach( $mParameters as &$roControl )
      {
        if( !( $oControl instanceof URC_CONTROL ) )
          throw new URC_EXCEPTION( __METHOD__, 'Invalid control' );
        array_push( $this->aoControls, $roControl );
      }
    }
  }

  /** Adds control to the macro
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param URC_CONTROL URC control to add
   */
  public function addControl( URC_CONTROL &$roControl )
  {
    array_push( $this->aoControls, $roControl );
  }


  /*
   * METHODS: control - OVERRIDE
   ********************************************************************************/

  /** Set the control's value
   *
   * @param string $mValue Control's value
   */
  public function setValue( $mValue )
  {
    // Set each control's value
    foreach( $this->aoControls as &$roControl )
      $roControl->setValue( $mValue );

    // Tell the URC framework that something happened here
    URC::useInstance()->addModifiedControlIDs( $this->getID() );
  }

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * @return mixed
   */
  public function getValue()
  {
    // Default return value (macro executed)
    return 1;
  }

}
