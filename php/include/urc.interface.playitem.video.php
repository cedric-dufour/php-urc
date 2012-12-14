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


/** URC (video-)playitem interface
 *
 * <P>This interface provisions (video-)playitem compatible methods for backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
interface URC_INTERFACE_PLAYITEM_VIDEO
{

  /*
   * METHODS: (video-)playitem "getters"
   ********************************************************************************/

  /** Returns the video source/stream <SAMP>ID</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video source/stream cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemVideoGet( $bForceRefresh = false );

  /** Returns the video title <SAMP>ID</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video title cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemVideoTitleGet( $bForceRefresh = false );

  /** Returns the video angle <SAMP>ID</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video angle cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemVideoAngleGet( $bForceRefresh = false );

  /** Returns the video "Frame Width" meta data, in <SAMP>PIXELS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>720</SAMP>: NTSC/PAL stream</LI>
   * <LI><SAMP>1280</SAMP>: 720p HD stream</LI>
   * <LI><SAMP>1920</SAMP>: 1080i/p HD stream</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemVideoFrameWidthGet( $bForceRefresh = false );

  /** Returns the video "Frame Height" meta data, in <SAMP>PIXELS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>480</SAMP>: NTSC stream</LI>
   * <LI><SAMP>520</SAMP>: PAL stream</LI>
   * <LI><SAMP>720</SAMP>: 720p HD stream</LI>
   * <LI><SAMP>1080</SAMP>: 1080i/p HD stream</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemVideoFrameHeightGet( $bForceRefresh = false );

  /** Returns the video "Frame Rate" meta data, in <SAMP>FRAMES PER SECOND</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>23.97</SAMP>: NSTC stream</LI>
   * <LI><SAMP>25</SAMP>: PAL stream</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return float|integer
   */
  public function playitemVideoFrameRateGet( $bForceRefresh = false );

  /** Returns the video "Bits per Pixel" meta data, in <SAMP>BITS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>24</SAMP>: TV stream</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemVideoPixelBitsGet( $bForceRefresh = false );

  /** Returns the video "Bits Rate" meta data, in <SAMP>BITS PER SECOND</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemVideoBitRateGet( $bForceRefresh = false );

  /** Returns the video "Codec" meta data
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
  public function playitemVideoCodecGet( $bForceRefresh = false );

  /** Returns the video subtitles <SAMP>ID</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video subtitles cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemVideoSubtitleGet( $bForceRefresh = false );

  /** Returns the video subtitles "Language" meta data
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are (per ISO-639 standard codes):</P>
   * <UL>
   * <LI><SAMP>en</SAMP>: English</LI>
   * <LI><SAMP>fr</SAMP>: French</LI>
   * <LI><SAMP>es</SAMP>: Spanish</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemVideoSubtitleLanguageGet( $bForceRefresh = false );


  /*
   * METHODS: (video-)playitem "setters"
   ********************************************************************************/

  /** Toggles/switches the video source/stream
   *
   * <P><B>RETURNS:</B> The video source/stream <SAMP>ID</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video source/stream cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the video source/stream cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return string|integer
   */
  public function playitemVideoToggle();

  /** Toggles/switches the video title
   *
   * <P><B>RETURNS:</B> The video title <SAMP>ID</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video title cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the video title cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return string|integer
   */
  public function playitemVideoTitleToggle();

  /** Toggles/switches the video angle
   *
   * <P><B>RETURNS:</B> The video angle <SAMP>ID</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video angle cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the video angle cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return string|integer
   */
  public function playitemVideoAngleToggle();

  /** Toggles/switches the video subtitles
   *
   * <P><B>RETURNS:</B> The subtitle <SAMP>ID</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the video subtitles cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the video subtitles cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return string|integer
   */
  public function playitemVideoSubtitleToggle();

}
