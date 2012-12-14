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

/** URC RPC Handler
 *
 * <P>This class implements the handler for URC RPC calls.</P>
 *
 * @package PHP_URC
 */
class URC_RPC
{

  /*
   * METHODS
   ********************************************************************************/

  public static function process()
  {
    // Process the RPC request
    try
    {
      // Disable notice/warning messages (causes corrupt XML response)
      ini_set( 'display_errors', false );

      // Retrieve and start the URC framework
      $oURC = URC::useInstance();
      if( $oURC->DEBUG() ) ini_set( 'display_errors', true );

      // Retrieve request parameters
      $sDo = ( isset( $_GET['do'] ) ? strtolower( trim( $_GET['do'] ) ) : null );
      $asResponseControlIDs = array();
      $sResponseError = null;

      // Handle action
      switch( $sDo )
      {
      case 'init':
        // Add all controls to response queue
        $asResponseControlIDs = $oURC->getAllControlIDs();
        break;

      case 'poll':
        // Add 'poll' controls to response queue
        $asResponseControlIDs = $oURC->getPollControlIDs();
        break;

      case 'get':
        // Get/check parameters
        $sID = ( isset( $_GET['id'] ) ? strtolower( trim( $_GET['id'] ) ) : null );
        if( is_null( $sID ) )
          throw new URC_EXCEPTION( __FILE__, 'Missing/incomplete parameters' );

        // Add control to response queue
        $asResponseControlIDs = explode( ',', $sID );
        break;

      case 'set':
        // Get/check parameters
        $sID = ( isset( $_GET['id'] ) ? strtolower( trim( $_GET['id'] ) ) : null );
        $mValue = ( isset( $_GET['value'] ) ? $_GET['value'] : null );
        if( is_null( $sID ) or is_null( $mValue ) )
          throw new URC_EXCEPTION( __FILE__, 'Missing/incomplete parameters' );
        $sID = explode( ',', $sID );
        $mValue = explode( ',', $mValue );
        if( count( $sID ) != count( $mValue ) )
          throw new URC_EXCEPTION( __FILE__, 'Inconsitent id/value pairs' );

        // Alter control(s)
        foreach( array_combine( $sID, $mValue ) as $sID => $mValue )
        {
          $roControl =& $oURC->useControl( $sID );
          $roControl->setValue( $mValue );
        }

        // Add control(s) to response queue
        $asResponseControlIDs = $oURC->getModifiedControlIDs();
        break;

      default:
        throw new URC_EXCEPTION( __FILE__, 'Unsupported action; action='.$sDo );
      }
    }
    catch( URC_EXCEPTION $e )
    {
      $e->logError();
      $asResponseControlIDs = array();
      $sResponseError = $e->getMessage();
    }

    // Return status
    header( "Content-Type: text/xml" );
    header( 'Expires: Thu, 1 Jan 1970 00:00:00 GMT' ); // Date in the past
    header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP/1.1
    header( 'Pragma: no-cache' ); // HTTP/1.0
    echo '<?xml version="1.0" encoding="utf-8" ?>'."\n";
    echo '<URC>'."\n";
    echo '<Version>'.URC::VERSION.'</Version>'."\n";
    if( empty( $sResponseError ) )
    {
      foreach( $asResponseControlIDs as $sID )
      {
        echo '<Control Id="'.$sID.'">';
        try
        {
          $roControl =& $oURC->useControl( $sID );
          $mValue = $roControl->getValue();
          $sMessage = $roControl->getMessage();
          $sError = $roControl->getError();
        }
        catch( URC_EXCEPTION $e )
        {
          $e->logError();
          echo '<Error><![CDATA['.utf8_encode($e->getMessage()).']]></Error>';
          echo '</Control>'."\n";
          break;
        }
        if( !is_null( $mValue ) ) echo '<Value><![CDATA['.utf8_encode($mValue).']]></Value>';
        if( !empty( $sMessage ) ) echo '<Message><![CDATA['.utf8_encode($sMessage).']]></Message>';
        if( !empty( $sError ) ) echo '<Error><![CDATA['.utf8_encode($sError).']]></Error>';
        echo '</Control>'."\n";
      }
    }
    else
    {
      echo '<Error><![CDATA['.utf8_encode($sResponseError).']]></Error>'."\n";
    }
    echo '</URC>'."\n";
  }

}
