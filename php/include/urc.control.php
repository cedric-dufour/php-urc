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
 * @author Cedric Dufour <http://www.ced-network.net/php-urc>
 * @version @version@
 */


/** URC control
 *
 * <P>This class acts as the root definition class for URC controls.</P>
 *
 * @package PHP_URC
 */
abstract class URC_CONTROL
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Control type: undefined
   * @var string */
  const TYPE_UNDEFINED = 'nil';

  /** Control type: push button
   * @var string */
  const TYPE_PUSHBUTTON = 'pb';

  /** Control type: switch button
   * @var string */
  const TYPE_SWITCHBUTTON = 'sb';

  /** Control type: rotary button
   * @var string */
  const TYPE_ROTARYBUTTON = 'rb';

  /** Control type: horizontal slider
   * @var string */
  const TYPE_HORIZONTALSLIDER = 'hs';

  /** Control type: vertical slider
   * @var string */
  const TYPE_VERTICALSLIDER = 'vs';

  /** Control type: horizontal bar
   * @var string */
  const TYPE_HORIZONTALBAR = 'hb';

  /** Control type: vertical bar
   * @var string */
  const TYPE_VERTICALBAR = 'vb';

  /** Control type: text
   * @var string */
  const TYPE_TEXT = 'tx';


  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Control's idenficator (ID)
   * @var string */
  private $sID = null;

  /** Control's (human-friendly) name
   * @var string */
  private $sName = null;

  /** Control's parameters
   * @var array|mixed */
  protected $mParameters = null;

  /** Control's type (see class constants)
   * @var string */
  private $sType = null;

  /** Control's (human-friendly) description
   * @var string */
  private $sDescription = null;

  /** Control's read-only status
   * @var boolean */
  private $bReadOnly = false;

  /** Control's scale visibility status
   * @var boolean */
  private $bNoScale = false;

  /** Control's repeat (activation) status
   * @var boolean */
  private $bRepeat = true;

  /** Control's repeat (modification) value
   * @var float */
  private $fRepeatValue = 0.01;

  /** Control's repeat rate [milliseconds]
   * @var integer */
  private $iRepeatRate = 35;

  /** Control's repeat delay [milliseconds]
   * @var integer */
  private $iRepeatDelay = 350;

  /** Control's (HTML) overlay
   * @var string */
  private $sOverlay = null;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the control
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Control's identifier (ID)
   * @param string $sName Control's (human-friendly) name
   * @param mixed $mParameters Control's parameters
   * @param string $sType Control's type (see class constants)
   */
  public function __construct( $sID, $sName, $mParameters, $sType )
  {
    $this->sID = strtolower( trim( $sID ) );
    $this->mParameters = $mParameters;
    $this->sName = trim( $sName );
    $this->sType = strtolower( trim( $sType ) );
    $this->bReadOnly = ( $sType==self::TYPE_TEXT ? true : false );
    $this->bRepeat = ( $sType==self::TYPE_PUSHBUTTON || $sType==self::TYPE_SWITCHBUTTON ? false : true );
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Sets the control's (human-friendly) description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sDescription Control's (human-friendly) description
   */
  final public function setDescription( $sDescription )
  {
    $this->sDescription = trim( $sDescription );
  }

  /** Sets the control's status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param boolean $bReadOnly Control's read-only status
   * @param boolean $bNoScale Control's scale visibility status
   */
  final public function setStatus( $bReadOnly, $bnoScale )
  {
    $this->bReadOnly = (boolean)$bReadOnly;
    $this->bNoScale = (boolean)$bNoScale;
  }

  /** Sets the control's repeat parameters
   *
   * <P><B>NOTE:</B> It is up to the theme to honor those settings.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param boolean $bRepeat Control's repeat (activation) status
   * @param float $fRepeatValue Control's repeat (modification) value (if null: <SAMP>0.01</SAMP>)
   * @param integer $iRepeatRate Control's repeat rate [milliseconds] (if null: <SAMP>35</SAMP>)
   * @param integer $iRepeatDelay Control's repeat delay [milliseconds] (if null: <SAMP>350</SAMP>)
   */
  final public function setRepeat( $bRepeat, $fRepeatValue=null, $iRepeatRate=null, $iRepeatDelay=null )
  {
    $this->bRepeat = (boolean)$bRepeat;
    $this->fRepeatValue = is_null($fRepeatValue) ? 0.01 : (float)$fRepeatValue;
    $this->iRepeatRate = is_null($iRepeatRate) ? 35 : (integer)$iRepeatRate;
    $this->iRepeatDelay = is_null($iRepeatDelay) ? 350 : (integer)$iRepeatDelay;
  }

  /** Sets the control's (HTML) overlay
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

  /** Returns the control's identifier (ID)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getID()
  {
    return $this->sID;
  }

  /** Returns the control's (human-friendly) name
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getName()
  {
    return $this->sName;
  }

  /** Returns the control's parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getParameters()
  {
    return $this->mParameters;
  }

  /** Returns the control's type
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getType()
  {
    return $this->sType;
  }

  /** Returns the control's (human-friendly) description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getDescription()
  {
    return $this->sDescription;
  }

  /** Returns the control's read-only status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isReadOnly()
  {
    return $this->bReadOnly;
  }

  /** Returns the control's scale vibility status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasScale()
  {
    return !$this->bNoScale;
  }

  /** Returns the control's repeat (activation) status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isRepeatable()
  {
    return $this->bRepeat;
  }

  /** Returns the control's repeat (modification) value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return float
   */
  final public function getRepeatValue()
  {
    return $this->fRepeatValue;
  }

  /** Returns the control's repeat rate [milliseconds]
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getRepeatRate()
  {
    return $this->iRepeatRate;
  }

  /** Returns the control's repeat delay [milliseconds]
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getRepeatDelay()
  {
    return $this->iRepeatDelay;
  }

  /** Returns the control's (HTML) overlay
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getOverlay()
  {
    return $this->sOverlay;
  }


  /*
   * METHODS: control
   ********************************************************************************/

  /** Set the control's value
   *
   * <P><B>NOTE:</B> This method <B>SHOULD</B> call the <SAMP>URC::addModifiedControlID()</SAMP> function.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $mValue Control's value
   */
  abstract public function setValue( $mValue );

  /** Returns the control's value (<I>null</I> if non-applicable)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return mixed
   */
  public function getValue()
  {
    return null;
  }

  /** Returns the control's last informational message (<I>null</I> if non-applicable)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function getMessage()
  {
    return null;
  }

  /** Returns the control's last error message (<I>null</I> if non-applicable)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function getError()
  {
    return null;
  }

}
