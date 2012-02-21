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


/** Load LIRC interface class */
require( dirname( __FILE__ ).'/interface.php' );


/** URC Linux Infra-Red Control (LIRC) control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to send LIRC commands ('irsend').</P>
 * <P>Controls [PB] accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>remote</SAMP> parameter, identifying the LIRC remote as
 * defined in the <SAMP>lircd.conf</SAMP> file</LI>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>code</SAMP> parameter, identifying the LIRC code as
 * defined in the <SAMP>lircd.conf</SAMP> file</LI>
 * </UL>
 *
 * <P><B>BACKEND:</B></P>
 * <P>The PHP user - iow. the web server's associated user - should have the proper permissions to execute the 'irsend' command.
 * The easiest way to do so is to give <SAMP>sudo</SAMP> permissions to that user, in the <SAMP>/etc/sudoers</SAMP> file:</P>
 * <CODE>
 * User_Alias USER_LIRC = www-data
 * Cmnd_Alias CMND_LIRC = /usr/bin/irsend
 * USER_LIRC ALL = NOPASSWD: CMND_LIRC
 * </CODE>
 * <P>And alter the LIRC interface configuration accordingly:</P>
 * <CODE>
 * URC_INTERFACE_LIRC::useInstance()->setCommandPath( 'sudo /usr/bin/irsend' );
 * </CODE>
 *
 * <P><B>NOTE:</B> the configuration of LIRC is beyond the scope of PHP-URC documentation and support!</P>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'LIRC', 'lirc-control', 'My LIRC control', array( 'remote' => 'MyRemote', 'code' => 'MyCode' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
 * </CODE>
 * <P><B>NOTE:</B> have a look at the plug-in's sample 'urc.config.php' for more examples.</P>
 *
 * @package PHP_URC_Plugins
 * @subpackage LIRC
 */
class URC_CONTROL_LIRC extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's associated remote
   * @var string */
  private $sRemote = null;

  /** Control's associated code
   * @var string */
  private $sCode = null;


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

    // Remote identifier
    if( !isset( $this->mParameters['remote'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing "remote" parameter' );
    $this->sRemote = trim( $this->mParameters['remote'] );
    if( strlen( $this->sRemote )<=0 )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid remote identifier' );

    // Code identifier
    if( !isset( $this->mParameters['code'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing "code" parameter' );
    $this->sCode = trim( $this->mParameters['code'] );
    if( strlen( $this->sCode )<=0 )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid code identifier' );
  }


  /*
   * METHODS: interface
   ********************************************************************************/

  /** Returns the LIRC interface (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return URC_INTERFACE_LIRC
   */
  public static function &useInterface()
  {
    return URC_INTERFACE_LIRC::useInstance();
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
    // Send LIRC sequence
    $roLIRC =& URC_INTERFACE_LIRC::useInstance();
    $roLIRC->send( $this->sRemote, $this->sCode );

    // Tell the URC framework that something happened here
    URC::useInstance()->addModifiedControlIDs( $this->getID() );
  }

}
