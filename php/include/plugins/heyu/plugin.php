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
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** Load HEYU interface class */
require( dirname( __FILE__ ).'/interface.php' );


/** URC X-10 Automation (HEYU) control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to send X-10 commands by the mean of HEYU ('heyu').</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>house</SAMP> parameter, identifying the X-10 house identifier (A-P)</LI>
 * <LI>an OPTIONAL (<I>string</I>)<SAMP>unit</SAMP> parameter, identifying the X-10 unit identifier (1-16)</LI>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>command</SAMP> parameter, corresponding to the X-10 command, among:
 * <BR/><SAMP>switch_off</SAMP>: switch appliance (or light) off [PB]
 * <BR/><SAMP>switch_on</SAMP>: switch appliance (or light) on [PB]
 * <BR/><SAMP>switch_toggle</SAMP>: toggle appliance (or light) switch [SB]
 * <BR/><SAMP>switch_state</SAMP>: retrieve appliance (or light) switch status [TXT]
 * <BR/><SAMP>dimmer_off</SAMP>: dim light to minimum (off) [PB]
 * <BR/><SAMP>dimmer_decrease</SAMP>: dim light down by given offset ([0.0;1.0]) [PB]
 * <BR/><SAMP>dimmer_set</SAMP>: dim light to given value ([0.0;1.0]) [VC]
 * <BR/><SAMP>dimmer_increase</SAMP>: dim light up by given offset ([0.0;1.0]) [PB]
 * <BR/><SAMP>dimmer_on</SAMP>: dim light to maximum (on) [PB]
 * <BR/><SAMP>dimmer_toggle</SAMP>: toggle light dimmer (on/off) [SB]
 * <BR/><SAMP>dimmer_level</SAMP>: retrieve light dimmer level [TXT]
 * <BR/><SAMP>all_off</SAMP>: switch all appliances (and lights) off [PB]
 * <BR/><SAMP>all_on</SAMP>: switch all appliances (and lights) on [PB]
 * <BR/><SAMP>lights_off</SAMP>: switch all lights off [PB]
 * <BR/><SAMP>lights_on</SAMP>: switch all lights on [PB]</LI>
 * <LI>an OPTIONAL (<I>(array of) string</I>)<SAMP>parameters</SAMP> parameter, corresponding to the X-10 command's parameter(s)</LI>
 * <LI>an OPTIONAL (<I>mixed</I>)<SAMP>value</SAMP> parameter, overriding any UI-passed value (useful for macros)</LI>
 * </UL>
 *
 * <P><B>BACKEND:</B></P>
 * <P>The PHP user - iow. the web server's associated - should have the proper permissions to execute the 'heyu' command.
 * The easiest way to do so is to give <SAMP>sudo</SAMP> permissions to that user, in the <SAMP>/etc/sudoers</SAMP> file:</P>
 * <CODE>
 * User_Alias USER_HEYU = www-data
 * Cmnd_Alias CMND_HEYU = /usr/bin/heyu
 * USER_HEYU ALL = NOPASSWD: CMND_HEYU
 * </CODE>
 * <P>And alter the HEYU interface configuration accordingly:</P>
 * <CODE>
 * URC_INTERFACE_HEYU::useInstance()->setCommandPath( 'sudo /usr/bin/heyu' );
 * </CODE>
 *
 * <P><B>NOTE:</B> the configuration of HEYU is beyond the scope of PHP-URC documentation and support!</P>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'HEYU', 'heyu-control', 'My HEYU control', array( 'house' => 'A', 'unit' => '1', 'command'=>'switch_on' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
 * </CODE>
 * <P><B>NOTE:</B> have a look at the plug-in's sample 'urc.config.php' for more examples.</P>
 *
 * @package PHP_URC_Plugins
 * @subpackage HEYU
 */
class URC_CONTROL_HEYU extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's associated house/unit identifier
   * @var string */
  private $sHouseUnit = null;

  /** Control's associated command
   * @var string */
  private $sCommand = null;

  /** Control's command's parameters
   * @var array|mixed */
  private $amParameters = array();

  /** Control's overriding value
   * @var mixed */
  private $mValue = null;


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

    // House/Unit identifiers
    if( !isset( $this->mParameters['house'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing "house" parameter' );
    $sHouse = $this->mParameters['house'];
    $sUnit = isset( $this->mParameters['unit'] ) ? $this->mParameters['unit'] : null;
    $this->sHouseUnit = URC_INTERFACE_HEYU::validateHouseUnit( $sHouse, $sUnit );

    // Command
    if( !isset( $this->mParameters['command'] ) )
      throw new URC_EXCEPTION( __METHOD__, 'Missing "command" parameter' );
    $this->sCommand = trim( $this->mParameters['command'] );
    if( strlen( $this->sCommand )<=0 )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid command' );
    
    // Parameters
    if( isset( $this->mParameters['parameters'] ) )
    {
      $this->amParameters = array_map( 'trim', is_scalar( $this->mParameters['parameters'] ) ? array( $this->mParameters['parameters'] ) : $this->mParameters['parameters'] );
    }
    
    // Overriding value
    if( isset( $this->mParameters['value'] ) )
      $this->mValue = $this->mParameters['value'];
  }


  /*
   * METHODS: interface
   ********************************************************************************/

  /** Returns the HEYU interface (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return URC_INTERFACE_HEYU
   */
  public static function &useInterface()
  {
    return URC_INTERFACE_HEYU::useInstance();
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
    // Retrieve HEYU interface
    $roHEYU =& URC_INTERFACE_HEYU::useInstance();

    // Override value
    if( !is_null( $this->mValue ) ) $mValue=$this->mValue;

    // Make sure the input value is normalized
    $mValue = (float)$mValue; if($mValue<0) $mValue=0; elseif($mValue>1) $mValue=1;

    // Handle control
    switch( $this->sCommand )
    {
    case 'switch_off':
      $roHEYU->switchOff( $this->sHouseUnit );
      break;

    case 'switch_on':
      $roHEYU->switchOn( $this->sHouseUnit );
      break;

    case 'switch_toggle':
      if( $mValue>0 ) $roHEYU->switchOn( $this->sHouseUnit );
      else $roHEYU->switchOff( $this->sHouseUnit );
      break;

    case 'dimmer_off':
      $roHEYU->dimmerOff( $this->sHouseUnit );
      break;

    case 'dimmer_decrease':
      $roHEYU->dimmerAdjust( $this->sHouseUnit, -100*$mValue );
      break;

    case 'dimmer_set':
      $roHEYU->dimmerSet( $this->sHouseUnit, 100*$mValue );
      break;

    case 'dimmer_increase':
      $roHEYU->dimmerAdjust( $this->sHouseUnit, 100*$mValue );
      break;

    case 'dimmer_on':
      $roHEYU->dimmerOn( $this->sHouseUnit );
      break;

    case 'dimmer_toggle':
      if( $mValue>0 ) $roHEYU->dimmerOn( $this->sHouseUnit );
      else $roHEYU->dimmerOff( $this->sHouseUnit );
      break;

    case 'all_off':
      $roHEYU->allOff( $this->sHouseUnit );
      break;

    case 'all_on':
      $roHEYU->allOn( $this->sHouseUnit );
      break;

    case 'lights_off':
      $roHEYU->lightsOff( $this->sHouseUnit );
      break;

    case 'lights_on':
      $roHEYU->lightsOn( $this->sHouseUnit );
      break;

    case 'switch_state':
    case 'dimmer_level':
      // Not a action (status retrieval only)
      break;

    default:
      throw new URC_EXCEPTION( __METHOD__, 'Invalid command; Command: '.$this->sCommand );
    }

    // Tell the URC framework that something happened here
    URC::useInstance()->addModifiedControlIDs( $this->getID() );
  }

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return mixed
   */
  public function getValue()
  {
    // Retrieve HEYU interface
    $roHEYU =& URC_INTERFACE_HEYU::useInstance();

    // Handle control
    switch( $this->sCommand )
    {
    case 'switch_toggle': return $roHEYU->getSwitchState( $this->sHouseUnit );
    case 'switch_state': return ( $roHEYU->getSwitchState( $this->sHouseUnit ) ? 'on' : 'off' );
    case 'dimmer_set':
    case 'dimmer_toggle': $iLevel = $roHEYU->getDimmerLevel( $this->sHouseUnit ); return ( $iLevel>=5 ? (float)$iLevel/100 : 0 );
    case 'dimmer_level': $iLevel = $roHEYU->getDimmerLevel( $this->sHouseUnit ); return $iLevel;
    }

    // Undefined return value
    return null;
  }

}
