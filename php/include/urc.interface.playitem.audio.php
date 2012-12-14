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


/** URC (audio-)playitem interface
 *
 * <P>This interface provisions (audio-)playitem compatible methods for backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
interface URC_INTERFACE_PLAYITEM_AUDIO
{

  /*
   * METHODS: (audio-)playitem "getters"
   ********************************************************************************/

  /** Returns the audio source/stream <SAMP>ID</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the audio stream/channel/language cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string|integer
   */
  public function playitemAudioGet( $bForceRefresh = false );

  /** Returns the audio "Language" meta data
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
  public function playitemAudioLanguageGet( $bForceRefresh = false );

  /** Returns the audio "Channels" meta data, in <SAMP>CHANNEL QUANTITY</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>1.0</SAMP>: mono</LI>
   * <LI><SAMP>2.0</SAMP>: stereo</LI>
   * <LI><SAMP>2.1</SAMP>: Dolby Prologic</LI>
   * <LI><SAMP>5.1</SAMP>: Dolby Surround (DTS/AC3)</LI>
   * <LI><SAMP>7.1</SAMP>: THX Surround</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string
   */
  public function playitemAudioChannelsGet( $bForceRefresh = false );

  /** Returns the audio "Sample Rate" meta data, in <SAMP>SAMPLE PER SECOND</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>44100</SAMP>: CD Audio 44.1kHz sampling rate</LI>
   * <LI><SAMP>48000</SAMP>: DAT/AES 48kHz sampling rate</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemAudioSampleRateGet( $bForceRefresh = false );

  /** Returns the audio "Bits per Sample" meta data, in <SAMP>BITS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>16</SAMP>: CD Audio 16-bits sampling</LI>
   * <LI><SAMP>24</SAMP>: High-definition DAT/AES 24-bits sampling</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemAudioSampleBitsGet( $bForceRefresh = false );

  /** Returns the audio "Bits Rate" meta data, in <SAMP>BITS</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the meta data cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of processing error (or unexpected result)</LI>
   * </UL>
   * <P><B>NOTE:</B> Typical values are:</P>
   * <UL>
   * <LI><SAMP>128000</SAMP>: medium-quality 128kb/s MP3</LI>
   * <LI><SAMP>1411200</SAMP>: CD Audio 1411.2kb/s</LI>
   * <LI><SAMP>2304000</SAMP>: High-definition DAT/AES 2304kb/s</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playitemAudioBitRateGet( $bForceRefresh = false );

  /** Returns the audio "Codec" meta data
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
  public function playitemAudioCodecGet( $bForceRefresh = false );


  /*
   * METHODS: (audio-)playitem "setters"
   ********************************************************************************/

  /** Toggles/switches the audio source/stream
   *
   * <P><B>RETURNS:</B> The audio source/stream <SAMP>ID</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the audio source/stream cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the audio source/stream cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @return string|integer
   */
  public function playitemAudioToggle();

}
