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


/** Load URC interface class */
require_once( URC_INCLUDE_PATH.'/urc.interface.php' );


/** URC Linux Infra-Red Control (LIRC) interface
 *
 * @package PHP_URC_Plugins
 * @subpackage LIRC
 */
class URC_INTERFACE_LIRC extends URC_INTERFACE
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** LIRC's IR send command path
   * @var string */
  private $sCommandIrSend = null;


  /*
   * FIELDS: static
   ********************************************************************************/

  /** Interface singleton
   * @var URC_INTERFACE_LIRC */
  private static $oINTERFACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the interface
   */
  private function __construct()
  {
    $this->setCommandPath();
  }


  /*
   * METHODS: factory - OVERRIDE
   ********************************************************************************/

  /** Returns a (singleton) interface instance (<B>as reference</B>)
   *
   * @return URC_INTERFACE_LIRC
   */
  public static function &useInstance()
  {
    if( is_null( self::$oINTERFACE ) ) self::$oINTERFACE = new URC_INTERFACE_LIRC();
    return self::$oINTERFACE;
  }


  /*
   * METHODS: configuration
   ********************************************************************************/

  /** Sets (modifies) the LIRC command path
   *
   * @param string $sCommandIrSend LIRC's IR send command path (if null: <SAMP>'/usr/bin/irsend'</SAMP>)
   */
  public function setCommandPath( $sCommandIrSend = null )
  {
    $this->sCommandIrSend = is_null( $sCommandIrSend ) ? '/usr/bin/irsend' : trim( $sCommandIrSend );
  }


  /*
   * METHODS: commands
   ********************************************************************************/

  /** Send the LIRC remote code
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sRemote LIRC's remote
   * @param string $sCode LIRC's code
   */
  public function send( $sRemote, $sCode )
  {
    // Execute LIRC command
    $sCommand = $this->sCommandIrSend.' SEND_ONCE '.escapeshellarg( $sRemote ).' '.escapeshellarg( $sCode );
    $iExit = -1; exec( $sCommand, $asOutput, $iExit );
		if( $iExit != 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command; Command: '.$sCommand.( isset( $asOutput[0] ) ? '; Output: '.$asOutput[0] : null ) );
  }

}
