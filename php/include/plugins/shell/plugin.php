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
 * @subpackage SHELL
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** URC shell control
 *
 * <P><B>USAGE:</B></P>
 * <P>This plug-in allows to execute arbitrary shell command.</P>
 * <P>Controls accept the following parameters:</P>
 * <UL>
 * <LI>a REQUIRED (<I>string</I>)<SAMP>command</SAMP> parameter</LI>
 * <LI>an OPTIONAL (<I>(array of) string</I>)<SAMP>parameters</SAMP> parameter(s).
 * Note that special string <I>%VALUE%</I> will get replaced with the actual UI-passed value.</LI>
 * </UL>
 * <P>After its execution (<SAMP>setValue</SAMP>), the <B>first</B> line of the command's
 * standard output is retrievable (<SAMP>getValue</SAMP>).</P>
 *
 * <BR/><P><B>EXAMPLE:</B></P>
 * <CODE>
 * $oURC->addControl( $oURC->newControl( 'SHELL', 'shell-control', 'My shell control', array( 'command'=>'shutdown -h now' ), URC_CONTROL::TYPE_PUSHBUTTON ) );
 * </CODE>
 *
 * @package PHP_URC_Plugins
 * @subpackage SHELL
 */
class URC_CONTROL_SHELL extends URC_CONTROL
{

  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's command
   * @var string */
  private $sCommand = null;

  /** Control's command's parameters
   * @var array|mixed */
  private $amParameters = array();

  /** Control's output
   * @var string */
  private $sOuptut = null;


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
    // Make sure the input value is normalized
    $mValue = (float)$mValue; if($mValue<0) $mValue=0; elseif($mValue>1) $mValue=1;

    // Execute shell command
    $sCommand = escapeshellarg( $this->sCommand );
    foreach( $this->amParameters as $mParameter )
    {
      if( $mParameter == '%VALUE%' ) $mParameter=$mValue;
      $sCommand .= ' '.escapeshellarg( $mParameter );
    }
    $iExit = -1; exec( $sCommand, $asOutput, $iExit );
		if( $iExit != 0 )
      throw new URC_EXCEPTION( __METHOD__, 'Failed to execute command; Command: '.$sCommand.( isset( $asOutput[0] ) ? '; Output: '.$asOutput[0] : null ) );
    $this->sOutput = isset( $asOutput[0] ) ? $asOutput[0] : null;
  }

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * @return mixed
   */
  public function getValue()
  {
    // Returns the last executed commands output
    return $this->sOutput;
  }

}
