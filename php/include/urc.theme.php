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


/** URC theme
 *
 * <P>This class acts as the root definition class for URC theming resources.</P>
 *
 * @package PHP_URC
 */
abstract class URC_THEME
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Control size: undefined
   * @var string */
  const SIZE_UNDEFINED = 'nil';

  /** Control size: extra-small
   * @var string */
  const SIZE_EXTRASMALL = 'xs';

  /** Control size: small
   * @var string */
  const SIZE_SMALL = 's';


  /** Control size: medium
   * @var string */
  const SIZE_MEDIUM = 'm';

  /** Control size: large
   * @var string */
  const SIZE_LARGE = 'l';

  /** Control size: extra-large
   * @var string */
  const SIZE_EXTRALARGE = 'xl';


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the theme
   */
  public function __construct()
  {
    // Nothing to do here
  }


  /*
   * METHODS: theme
   ********************************************************************************/

  /** Returns the control's theme-associated class
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param URC_CONTROL $oControl Control's object
   * @param string $sSize Control's size (see class constants)
   * @return string
   */
  abstract public function getControlClass( URC_CONTROL $oControl, $sSize = self::SIZE_MEDIUM );

  /** Returns the control's theme-associated HTML content
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param URC_CONTROL $oControl Control's object
   * @param string $sSize Control's size (see class constants)
   * @return string
   */
  abstract public function getControlHtml( URC_CONTROL $oControl, $sSize = self::SIZE_MEDIUM );

  /** Returns the hyperlink's theme-associated class
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param URC_HYPERLINK $oHyperlink Hyperlink's object
   * @param string $sSize Hyperlink's size (see class constants)
   * @return string
   */
  abstract public function getHyperlinkClass( URC_HYPERLINK $oHyperlink, $sSize = self::SIZE_MEDIUM );

  /** Returns the hyperlink's theme-associated HTML content
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param URC_HYPERLINK $oHyperlink Hyperlink's object
   * @param string $sSize Hyperlink's size (see class constants)
   * @return string
   */
  abstract public function getHyperlinkHtml( URC_HYPERLINK $oHyperlink, $sSize = self::SIZE_MEDIUM );
}
