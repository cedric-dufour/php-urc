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
 * @subpackage DELAY
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** URC delay control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to introduce a delay in MACRO controls processing.</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>integer</I>) <SAMP>delay</SAMP> parameter, specifying the required delay in milliseconds</LI>
 * </UL>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'DELAY', 'delay-control', 'My delay control', array( 'delay' => 500 ) ) );
 * </CODE>
 *
 * @package PHP_URC_Plugins
 * @subpackage DELAY
 */
class URC_CONTROL_DELAY extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's delay [microseconds]
   * @var integer */
  private $iMicroDelay = 0;


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
   */
  public function __construct( $sID, $sName, $mParameters )
  {
    parent::__construct( $sID, $sName, $mParameters, URC_CONTROL::TYPE_UNDEFINED );

    // Delay
    if( !isset( $this->mParameters['delay'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing delay parameter' );
    $this->iMicroDelay = 1000*(integer)$this->mParameters['delay'];
    if( $this->iMicroDelay < 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid delay' );
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
    // Just wait the given delay
    usleep( $this->iMicroDelay );
  }

}
