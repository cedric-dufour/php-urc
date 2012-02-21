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


/** URC interface
 *
 * <P>This class acts as the root definition class for URC backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
abstract class URC_INTERFACE
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Method not implemented
   * @var string */
  const STATUS_NOTIMPLEMENTED = '__NOTIMPLEMENTED__';

  /** Error status (e.g. processing error)
   * @var string */
  const STATUS_ERROR = '__ERROR__';

  /** Warning status (e.g. unexpected result)
   * @var string */
  const STATUS_WARNING = '__WARNING__';

  /** Unknown status
   * @var string */
  const STATUS_UNKNOWN = '__UNKNOWN__';

  /** OK status
   * @var string */
  const STATUS_OK = '__OK__';


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) interface instance (<B>as reference</B>)
   *
   * @return URC_INTERFACE
   */
  abstract public static function &useInstance();


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Returns whether a the given value matches a OK status
   *
   * @param mixed $mValue Value to be tested
   * @return boolean
   */
  public static function isStatusOK( $mValue )
  {
    if( is_string( $mValue ) )
      switch( $mValue )
      {
      case self::STATUS_NOTIMPLEMENTED:
      case self::STATUS_ERROR:
      case self::STATUS_WARNING:
      case self::STATUS_UNKNOWN:
        return false;
      }
    return true;
  }

}
