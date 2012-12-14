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
 * @package PHP_URC
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** URC hyperlink
 *
 * @package PHP_URC
 */
class URC_HYPERLINK
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Hyperlink type: URL
   * @var string */
  const TYPE_URL = 'url';

  /** Hyperlink type: script
   * @var string */
  const TYPE_JAVASCRIPT = 'js';


  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Hyperlink's idenficator (ID)
   * @var string */
  private $sID = null;

  /** Hyperlink's (human-friendly) name
   * @var string */
  private $sName = null;

  /** Hyperlink's content (see class constants)
   * @var string */
  private $sContent = null;

  /** Hyperlink's type (see class constants)
   * @var string */
  private $sType = null;

  /** Hyperlink's (human-friendly) description
   * @var string */
  private $sDescription = null;

  /** Hyperlink's (HTML) overlay
   * @var string */
  private $sOverlay = null;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the hyperlink
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Hyperlink's identifier (ID)
   * @param string $sName Hyperlink's (human-friendly) name
   * @param string $sContent Hyperlink's content
   * @param string $sType Hyperlink's type (see class constants)
   */
  public function __construct( $sID, $sName, $sContent, $sType )
  {
    $this->sID = strtolower( trim( $sID ) );
    $this->sName = trim( $sName );
    $this->sContent = trim( $sContent );
    $this->sType = strtolower( trim( $sType ) );
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Sets the hyperlink's (human-friendly) description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sDescription Hyperlink's (human-friendly) description
   */
  final public function setDescription( $sDescription )
  {
    $this->sDescription = trim( $sDescription );
  }

  /** Sets the hyperlink's (HTML) overlay
   *
   * <P><B>NOTE:</B> It is up to the theme to honor this setting.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sOverlay Overlay HTML code
   */
  final public function setOverlay( $sOverlay )
  {
    $this->sOverlay = trim( $sOverlay );
  }

  /** Returns the hyperlink's identifier (ID)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getID()
  {
    return $this->sID;
  }

  /** Returns the hyperlink's (human-friendly) name
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getName()
  {
    return $this->sName;
  }

  /** Returns the hyperlink's content
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getContent()
  {
    return $this->sContent;
  }

  /** Returns the hyperlink's Javascript-compatible content
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getContentJavascript()
  {
    // Handle content
    switch( $this->sType )
    {
    case URC_HYPERLINK::TYPE_URL:
      return 'javascript:document.location.href=\''.addcslashes($this->sContent,"'").'\'';

    case URC_HYPERLINK::TYPE_JAVASCRIPT:
      return ( substr($this->sContent,0,11)=='javascript:' ? $this->sContent : 'javascript:'.$this->sContent );
    }
    
    // We should not get there
    return null;
  }

  /** Returns the hyperlink's type
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getType()
  {
    return $this->sType;
  }

  /** Returns the hyperlink's (HTML) overlay
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getOverlay()
  {
    return $this->sOverlay;
  }

}
