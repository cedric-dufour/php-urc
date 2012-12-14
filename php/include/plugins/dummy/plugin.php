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


/** URC dummy control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to test PHP-URC and user-designed interfaces, using a dummy backend.</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>type</SAMP> parameter matching one of the following values:
 * <BR/><SAMP>readback</SAMP>: the control returns the value which was last set [PB,SB,VC,TXT]
 * <BR/><SAMP>random</SAMP>: the control returns a random value (between 0 and 1) [TXT]</LI>
 * <LI>an OPTIONAL (<I>string</I>)<SAMP>var</SAMP> parameter, which allows to save the control's
 * value in a matching $_SESSION[<SAMP>var</SAMP>] variable. PHP sessions MUST be enable for this to work.</LI>
 * </UL>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'DUMMY', 'dummy-control', 'My dummy control', array( 'type' => 'random' ), URC_CONTROL::TYPE_TEXT ) );
 * </CODE>
 *
 * @package PHP_URC_Plugins
 * @subpackage DUMMY
 */
class URC_CONTROL_DUMMY extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's value
   * @var float */
  private $fValue = 0;


  /*
   * METHODS: control - OVERRIDE
   ********************************************************************************/

  /** Set the control's value
   *
   * @param string $mValue Control's value
   */
  public function setValue( $mValue )
  {
    $mValue = (float)$mValue;
    if( isset( $this->mParameters['var'] ) and isset( $_SESSION ) )
      $_SESSION[$this->mParameters['var']] = $mValue;
    else
      $this->fValue = $mValue;
    URC::useInstance()->addModifiedControlIDs( $this->getID() );
  }

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * @return mixed
   */
  public function getValue()
  {
    if( !isset( $this->mParameters['type'] ) ) return 0;
    switch( $this->mParameters['type'] )
    {
    case 'random':
      return rand(0,1000)/1000;

    case 'readback':
      if( isset( $this->mParameters['var'] ) and isset( $_SESSION ) )
        return isset( $_SESSION[$this->mParameters['var']] ) ? $_SESSION[$this->mParameters['var']] : 0;
      return $this->fValue;
    }
    return 0;
  }

}
