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


/** Load URC interface class */
require_once( URC_INCLUDE_PATH.'/urc.interface.php' );


/** URC X-10 Automation (HEYU) interface
 *
 * @package PHP_URC_Plugins
 * @subpackage HEYU
 */
class URC_INTERFACE_HEYU extends URC_INTERFACE
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** HEYU's command path
   * @var string */
  private $sCommandHeyu = null;


  /*
   * FIELDS: static
   ********************************************************************************/

  /** Interface singleton
   * @var URC_INTERFACE_HEYU */
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
   * @return URC_INTERFACE_HEYU
   */
  public static function &useInstance()
  {
    if( is_null( self::$oINTERFACE ) ) self::$oINTERFACE = new URC_INTERFACE_HEYU();
    return self::$oINTERFACE;
  }


  /*
   * METHODS: configuration
   ********************************************************************************/

  /** Sets (modifies) the HEYU command path
   *
   * @param string $sCommandHeyu HEYU's command path (if null: <SAMP>'/usr/bin/heyu'</SAMP>)
   */
  public function setCommandPath( $sCommandHeyu = null )
  {
    $this->sCommandHeyu = is_null( $sCommandHeyu ) ? '/usr/bin/heyu' : trim( $sCommandHeyu );
  }


  /*
   * METHODS: utility
   ********************************************************************************/

  /** Validate and returns the concatenated house/unit indentifier
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouse X-10 house identifier
   * @param string $sUnit X-10 unit identifier(s)
   * @return string
   */
  public static function validateHouseUnit( $sHouse, $sUnit=null )
  {
    // House identifier
    $sHouse = strtoupper( trim( $sHouse ) );
    if( strlen( $sHouse )<=0 or strlen( $sHouse )>1 or strpos( 'ABCDEFGHIJKLMNOP', $sHouse )===false )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid house identifier' );

    // Unit identifier
    if( !is_null( $sUnit ) )
    {
      foreach( explode( ',', $sUnit ) as $iUnit )
      {
        $iUnit = (integer)$iUnit;
        if( $iUnit<1 or $iUnit>16 )
          throw new URC_EXCEPTION( __METHOD__, 'Invalid unit identifier' );
      }
    }

    // Return concatenated house/unit identifier
    return $sHouse.$sUnit;
  }


  /*
   * METHODS: utility
   ********************************************************************************/

  /** Send HEYU command
   *
   * <P><B>RETURNS:</B> The command output to standard output (<SAMP>/dev/stdout</SAMP>).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sCommand HEYU command
   * @param string $sHouseUnit X-10 house/unit identifier
   * @param array|mixed $amParameters HEYU command's parameters
   * @return array|string
   */
  public function sendCommand( $sCommand, $sHouseUnit, $amParameters = null )
  {
    // Sanitize/validate input
    $sCommand = trim( $sCommand );
    $sHouseUnit = trim( $sHouseUnit );
    if( !is_null( $amParameters ) )
      $amParameters = array_map( 'trim', is_scalar( $amParameters ) ? array( $amParameters ) : $amParameters );

    // Execute command
    $asOutput = null; $iExit = null;
    $sCommand = $this->sCommandHeyu.' '.escapeshellarg( $sCommand ).' '.escapeshellarg( $sHouseUnit );
    if( is_array( $amParameters ) )
    {
      foreach( $amParameters as $mParameter )
        $sCommand .= ' '.escapeshellarg( $mParameter );
    }
		$iExit = -1; exec( $sCommand, $asOutput, $iExit );
		if( $iExit != 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command; Command: '.$sCommand.( isset( $asOutput[0] ) ? '; Output: '.$asOutput[0] : null ) );
    return $asOutput;
  }


  /*
   * METHODS: status
   ********************************************************************************/

  /** Returns the switch state for the given appliance (or light)
   *
   * <P><B>RETURNS:</B> <SAMP>1</SAMP> if switch is on, <SAMP>0</SAMP> otherwise.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   * @return integer
   */
  public function getSwitchState( $sHouseUnit )
  {
    // Execute HEYU command
    $asOutput = $this->sendCommand( 'onstate', $sHouseUnit );
		if( !isset( $asOutput[0] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command (no output); Command: '.$sCommand );
    return (integer)$asOutput[0];
  }

  /** Returns the dimmer level for the given light
   *
   * <P><B>RETURNS:</B> The dimmer level ([0;100]) if the dimmer is active, <SAMP>-1</SAMP> otherwise.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   * @return integer
   */
  public function getDimmerLevel( $sHouseUnit )
  {
    // Execute HEYU command
    $asOutput = $this->sendCommand( 'onstate', $sHouseUnit );
		if( !isset( $asOutput[0] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command (no output); Command: '.$sCommand );
    if( (integer)$asOutput[0] )
    {
      $asOutput = $this->sendCommand( 'dimlevel', $sHouseUnit );
      if( !isset( $asOutput[0] ) )
        throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command (no output); Command: '.$sCommand );
      return (integer)$asOutput[0];
    }
    else return (integer)-1;
  }


  /*
   * METHODS: commands
   ********************************************************************************/

  /** Switch off the given appliance (or light)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   */
  public function switchOff( $sHouseUnit )
  {
    // Execute HEYU command
    $this->sendCommand( 'off', $sHouseUnit );
  }

  /** Switch on the given appliance (or light)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   */
  public function switchOn( $sHouseUnit )
  {
    // Execute HEYU command
    $this->sendCommand( 'on', $sHouseUnit );
  }

  /** Dim the given light to its minimum (off)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   */
  public function dimmerOff( $sHouseUnit )
  {
    // Execute HEYU command
    $this->sendCommand( 'dim', $sHouseUnit, 22 );
  }

  /** Dim the given light up/down by the given offset ([-100;100])
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   * @param integer $iLevel Dimmer level
   */
  public function dimmerAdjust( $sHouseUnit, $iLevel )
  {
    // Convert level
    $sCommand = 'bright';
    $iLevel = (integer)$iLevel;
    if( $iLevel<0 )
    {
      $iLevel = -$iLevel;
      $sCommand = 'dim';
    }
    elseif( $iLevel>100 ) $iLevel=100;
    $iLevel = (integer)round( ($iLevel*2.1-2)/11.2+1 );
    if( $iLevel==0 ) return;

    // Execute HEYU command
    $this->sendCommand( $sCommand, $sHouseUnit, $iLevel );
  }

  /** Dim the given light to the given level ([0;100])
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   * @param integer $iLevel Dimmer level
   */
  public function dimmerSet( $sHouseUnit, $iLevel )
  {
    // Convert level
    $iLevel = (integer)$iLevel; if( $iLevel<0 ) $iLevel=0; elseif( $iLevel>100 ) $iLevel=100;

    // Increase/decrease dimmer level (in maximum three steps)
    for( $i=0; $i<3; $i++ )
    {
      $iOffset = $iLevel - $this->getDimmerLevel( $sHouseUnit );
      if( $iOffset != 0 ) $this->dimmerAdjust( $sHouseUnit, $iOffset );
      else break;
    }
  }

  /** Dim the given light to its maximum (on)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouseUnit X-10 house/unit identifier
   */
  public function dimmerOn( $sHouseUnit )
  {
    $this->sendCommand( 'bright', $sHouseUnit, 22 );
  }

  /** Switch all appliances (and lights) off
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouse X-10 house identifier
   */
  public function allOff( $sHouse )
  {
    // Execute HEYU command
    $this->sendCommand( 'alloff', $sHouse );
  }

  /** Switch all appliances (and lights) on
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouse X-10 house identifier
   */
  public function allOn( $sHouse )
  {
    // Execute HEYU command
    $this->sendCommand( 'allon', $sHouse );
  }

  /** Switch all lights off
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouse X-10 house identifier
   */
  public function lightsOff( $sHouse )
  {
    // Execute HEYU command
    $this->sendCommand( 'lightsoff', $sHouse );
  }

  /** Switch all lights on
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sHouse X-10 house identifier
   */
  public function lightsOn( $sHouse )
  {
    // Execute HEYU command
    $this->sendCommand( 'lightson', $sHouse );
  }

}
