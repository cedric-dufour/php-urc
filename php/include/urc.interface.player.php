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


/** URC (media-)player interface
 *
 * <P>This interface provisions (media-)player compatible method for backend-interfacing resources.</P>
 *
 * @package PHP_URC
 */
interface URC_INTERFACE_PLAYER
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Playback status: STOP
   * @var string */
  const PLAYBACK_STOP = '__STOP__';

  /** Playback status: PAUSE
   * @var string */
  const PLAYBACK_PAUSE = '__PAUSE__';

  /** Playback status: PLAY
   * @var string */
  const PLAYBACK_PLAY = '__PLAY__';

  /** Playback mode: NORMAL
   * @var string */
  const PLAYBACK_NORMAL = '__NORMAL__';

  /** Playback mode: REPEAT
   * @var string */
  const PLAYBACK_REPEAT_ALL = '__REPEAT_ALL__';

  /** Playback mode: REPEAT ALBUM
   * @var string */
  const PLAYBACK_REPEAT_ALBUM = '__REPEAT_ALBUM__';

  /** Playback mode: REPEAT TITLE
   * @var string */
  const PLAYBACK_REPEAT_TITLE = '__REPEAT_TITLE__';

  /** Playback mode: RANDOM
   * @var string */
  const PLAYBACK_RANDOM = '__RANDOM__';


  /*
   * METHODS: (media-)player "getters"
   ********************************************************************************/

  /** Returns the playback state (stop/start/pause)
   *
   * <P><B>RETURNS:</B> The playback status code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_STOP</SAMP>: when playback is stopped</LI>
   * <LI><SAMP>PLAYBACK_PAUSE</SAMP>: when playback is paused</LI>
   * <LI><SAMP>PLAYBACK_PLAY</SAMP>: when playback is started</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string
   */
  public function playerPlaybackGet( $bForceRefresh = false );

  /** Returns the playback repeat mode
   *
   * <P><B>RETURNS:</B> The playback repeat code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_NORMAL</SAMP>: when playback mode is none of those mentioned below</LI>
   * <LI><SAMP>PLAYBACK_REPEAT_ALL</SAMP>: when playback is set to repeat the entire playlist</LI>
   * <LI><SAMP>PLAYBACK_REPEAT_ALBUM</SAMP>: when playback is set to repeat the current album</LI>
   * <LI><SAMP>PLAYBACK_REPEAT_TITLE</SAMP>: when playback is set to repeat the current title</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string
   */
  public function playerRepeatGet( $bForceRefresh = false );

  /** Returns the playback random mode
   *
   * <P><B>RETURNS:</B> The playback random code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_NORMAL</SAMP>: when playback mode is none of those mentioned below</LI>
   * <LI><SAMP>PLAYBACK_RANDOM</SAMP>: when playback is set to random items</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return string
   */
  public function playerRandomGet( $bForceRefresh = false );

  /** Returns the volume
   *
   * <P><B>RETURNS:</B> The playback volume, normalized between <SAMP>0</SAMP> and <SAMP>100</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback volume cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return integer
   */
  public function playerVolumeGet( $bForceRefresh = false );

  /** Returns the playback mute state
   *
   * <P><B>RETURNS:</B> The playback mute state, as a <SAMP>BOOLEAN</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback mute state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bForceRefresh Discard cache and force data refresh
   * @return boolean
   */
  public function playerMuteGet( $bForceRefresh = false );


  /*
   * METHODS: (media-)player "setters"
   ********************************************************************************/

  /** Sets the playback state (stop/start/pause)
   *
   * <P><B>RETURNS:</B> The playback status code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_*</SAMP>: when the playback state has been successfully stopped/started/paused</LI>
   * <LI><SAMP>PLAYBACK_*</SAMP>: when the playback state was already stopped/started/paused</LI>
   * <LI><SAMP>PLAYBACK_*</SAMP>: the actual playback status, if it could not be changed (for normal reasons)</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param string $sPlayback Playback state
   * @return string
   */
  public function playerPlaybackSet( $sPlayback );

  /** Sets the playback repeat mode
   *
   * <P><B>RETURNS:</B> The playback repeat code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_*</SAMP>: when the repeat mode has been successfully changed</LI>
   * <LI><SAMP>PLAYBACK_*</SAMP>: the actual repeat mode, if it could not be changed (for normal reasons)</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param string $sRepeat Repeat mode
   * @return string
   */
  public function playerRepeatSet( $sRepeat );

  /** Sets the playback random mode
   *
   * <P><B>RETURNS:</B> The playback random code (see note below).</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>PLAYBACK_*</SAMP>: when the random mode has been successfully changed</LI>
   * <LI><SAMP>PLAYBACK_*</SAMP>: the actual random mode, if it could not be changed (for normal reasons)</LI>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback state cannot be determined</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param string $sRandom Random mode
   * @return string
   */
  public function playerRandomSet( $sRandom );

  /** Sets the volume
   *
   * <P><B>RETURNS:</B> The volume, normalized between <SAMP>0</SAMP> and <SAMP>100</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if the volume cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the volume cannot be set</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param integer $iVolume Playback volume, normalized between <SAMP>0</SAMP> and <SAMP>100</SAMP>
   * @return integer
   */
  public function playerVolumeSet( $iVolume );

  /** Sets the playback mute state
   *
   * <P><B>RETURNS:</B> The playback mute state, as a <SAMP>BOOLEAN</SAMP>.</P>
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP> (critical error).</P>
   * <P><B>NOTE:</B> This method MUST return the following status codes:</P>
   * <UL>
   * <LI><SAMP>STATUS_UNKNOWN</SAMP>: if playback mute state cannot be determined</LI>
   * <LI><SAMP>STATUS_WARNING</SAMP>: if the playback cannot be muted</LI>
   * <LI><SAMP>STATUS_ERROR</SAMP>: in case of error (processing error)</LI>
   * </UL>
   *
   * @param boolean $bMute Playback mute state, as a <SAMP>BOOLEAN</SAMP>
   * @return boolean
   */
  public function playerMuteSet( $bMute );

}
