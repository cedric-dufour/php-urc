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


/** URC (media-)playitem interface
 *
 * <P>This interface provisions (media-)playitem compatible methods for backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
interface URC_INTERFACE_PLAYITEM
{

  /*
   * METHODS: (media-)playitem "getters"
   ********************************************************************************/

  /** Returns the "File" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemFileGet( $bForceRefresh = false );

  /** Returns the "Artist" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemArtistGet( $bForceRefresh = false );

  /** Returns the "Album" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemAlbumGet( $bForceRefresh = false );

  /** Returns the "Title" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemTitleGet( $bForceRefresh = false );

  /** Returns the "Date" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemDateGet( $bForceRefresh = false );

  /** Returns the "Genre" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string
   */
  public function playitemGenreGet( $bForceRefresh = false );

  /** Returns the "Copyright" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemCopyrightGet( $bForceRefresh = false );

  /** Returns the "Description" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemDescriptionGet( $bForceRefresh = false );

  /** Returns the "Comment" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemCommentGet( $bForceRefresh = false );

  /** Returns the "Rating" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemRatingGet( $bForceRefresh = false );

  /** Returns the length (duration), in <SAMP>SECONDS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the length cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemLengthGet( $bForceRefresh = false );

  /** Returns the current position, in <SAMP>SECONDS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the position cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemPositionGet( $bForceRefresh = false );


  /*
   * METHODS: (media-)playitem "setters"
   ********************************************************************************/

  /** Seeks to the given position
   *
   * <P><B>RETURNS:</B> The position in the playitem, in <SAMP>SECONDS</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the position cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the position cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param integer $iPosition Position, in <SAMP>SECONDS</SAMP>
   * @return integer
   */
  public function playitemPositionSet( $iPosition );

}
