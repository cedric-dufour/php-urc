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


/** URC exception
 *
 * @package PHP_URC
 */
class URC_EXCEPTION
extends Exception
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Triggering context
   * @var string */
  private $sContext = null;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new exception
   *
   * @param string $sContext Triggering context
   * @param string $sMessage Error message
   * @param int $iCode Error code
   */
  public function __construct( $sContext, $sMessage, $iCode = 0 ) {
    parent::__construct( $sMessage, $iCode );
    $this->sContext = $sContext;
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this object
   *
   * @param boolean $bIncludeFileInfo Include file information (filepath and line)
   * @param boolean $bIncludeStackTrace Include calling stack trace
   */
  public function toString( $bIncludeFileInfo = true, $bIncludeStackTrace = true )
  {
    return ( $bIncludeFileInfo ? $this->getFile().'('.$this->getLine()."): \r\n" : null ).'['.$this->sContext.'('.$this->getCode().')] '.$this->getMessage().( $bIncludeStackTrace ? " \r\n".$this->getTraceAsString() : null );
  }

  /** Returns a <I>string</I> representation of this object
   */
  public function __toString() { return $this->toString(); }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Returns the triggering context
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getContext()
  {
    return $this->sContext;
  }


  /*
   * METHODS: logging
   ********************************************************************************/

  /** Log the exception to the error log
   *
   * @param boolean $bIncludeStackTrace Include calling stack trace
   * @see PHP_MANUAL#error_log() error_log()
   */
  public function logError( $bIncludeStackTrace = true )
  {
    error_log( 'PHP Exception: '.$this->getFile().'('.$this->getLine().'): ['.$this->sContext.'('.$this->getCode().')] '.$this->getMessage().( $bIncludeStackTrace ? " \r\n".$this->getTraceAsString() : null ) );
  }

}
