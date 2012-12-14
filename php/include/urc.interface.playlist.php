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


/** URC (media-)playlist interface
 *
 * <P>This interface provisions (media-)playlist compatible methods for backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
interface URC_INTERFACE_PLAYLIST
{

  /*
   * METHODS: (media-)playlist "getters"
   ********************************************************************************/

  /** Returns the playlist's content (entries list)
   *
   * <P><B>RETURNS:</B> The playlist's content (entries list), as an <SAMP>ARRAY</SAMP>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the playlist's content be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return array|string
   */
  public function playlistGet( $bForceRefresh = false );

  /** Returns the playlist size (quantity of entries)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the playlist size cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playlistSizeGet( $bForceRefresh = false );

  /** Returns the (currently playing) playlist position (entry index)
   *
   * <P><B>RETURNS:</B> The (currently playing) playlist position, <SAMP>STARTING FROM 1</SAMP>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the playlist position cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playlistPositionGet( $bForceRefresh = false );


  /*
   * METHODS: (media-)playlist "setters"
   ********************************************************************************/

  /** Clears (empties) the playlist
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_OK</SAMP>: if the playlist has been successfully cleared</LI>
   * <LI><SAMP>STATUS_OK</SAMP>: if the playlist was already empty</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the playlist cannot be cleared</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return integer
   */
  public function playlistClear();

  /** Adds an entry to the playlist
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_OK</SAMP>: if the entry has been successfully added</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the entry cannot be added</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return integer
   */
  public function playlistEntryAdd( $sEntry );

  /** Removes an entry from the playlist
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_OK</SAMP>: if the entry has been successfully removed</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the entry cannot be removed</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param integer $iPosition Entry's position in the playlist (index), <SAMP>STARTING FROM 1</SAMP>.</P>
   * @return integer
   */
  public function playlistEntryRemove( $iPosition );

  /** Goes to the given playlist position (entry index)
   *
   * <P><B>RETURNS:</B> The position in the playlist (index), <SAMP>STARTING FROM 1</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the position cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the position cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param integer $iPosition Position in the playlist (index), <SAMP>STARTING FROM 1</SAMP>.</P>
   * @return integer
   */
  public function playlistPositionSet( $iPosition );

}
