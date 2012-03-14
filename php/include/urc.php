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


// Define URC root (include) path
if( !defined( 'URC_INCLUDE_PATH' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_URC_INCLUDE_PATH', $_SERVER ) ) define( 'URC_INCLUDE_PATH', rtrim( $_SERVER[ 'PHP_URC_INCLUDE_PATH' ], '/' ) );
  /** @ignore */
  elseif( array_key_exists( 'PHP_URC_INCLUDE_PATH', $_ENV ) ) define( 'URC_INCLUDE_PATH', rtrim( $_ENV[ 'PHP_URC_INCLUDE_PATH' ], '/' ) );
  /** Define URC root (include) path */
  else define( 'URC_INCLUDE_PATH', dirname( __FILE__ ) );
}

// Define URC root (htdocs) URL
if( !defined( 'URC_HTDOCS_URL' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_URC_HTDOCS_URL', $_SERVER ) ) define( 'URC_HTDOCS_URL', rtrim( $_SERVER[ 'PHP_URC_HTDOCS_URL' ], '/' ) );
  /** @ignore */
  elseif( array_key_exists( 'PHP_URC_HTDOCS_URL', $_ENV ) ) define( 'URC_HTDOCS_URL', rtrim( $_ENV[ 'PHP_URC_HTDOCS_URL' ], '/' ) );
  /** Define URC root (htdocs) URL */
  else define( 'URC_HTDOCS_URL', '/php-urc' );
}

/** Load URC exception class */
require_once( URC_INCLUDE_PATH.'/urc.exception.php' );

/** Load URC RPC class */
require_once( URC_INCLUDE_PATH.'/urc.rpc.php' );

/** Load URC theme class */
require_once( URC_INCLUDE_PATH.'/urc.theme.php' );

/** Load URC control class */
require_once( URC_INCLUDE_PATH.'/urc.control.php' );

/** Load URC hyperlink class */
require_once( URC_INCLUDE_PATH.'/urc.hyperlink.php' );


/** URC framework
 *
 * <P><B>SYNOPSIS:</B></P>
 * <P>The <B>PHP Universal Remote Control (PHP-URC)</B> framework is a library
 * which allows low-skilled PHP developers to easily design web-based remote
 * control interfaces.</P>
 * <P>As such, it comes as an ideal development resource for those setting-up a
 * Home Theater PC (HTPC) or a Home Control PC (HCPC), and willing to control
 * both their PC and external appliances - amplifier, beamer, coffee machine,
 * etc. - from a single interface, accessible with any web-able device.</P>
 * <P>From the <B>front-end</B> (user) interface point of view, PHP-URC is entirely
 * Ajax-based and provides a "look-and-feel" close to a genuine remote control.</P>
 * <P>From the <B>back-end</B> (services/appliances) interface point of view,
 * PHP-URC relies on "control" plug-ins, which can easily be extended to drive any
 * imagineable backend. The current packaging includes plug-ins for the following backends:</P>
 * <UL>
 * <LI><B>LIRC</B>: Linux Infra-Red Control (http://lirc.org)</LI>
 * <LI><B>HEYU</B>: X-10 Automation (http://lirc.org)</LI>
 * <LI><B>MPD</B>: Music Player Daemon (http://www.musicpd.org)</LI>
 * <LI><B>MPLAYER</B>: Multimedia Player (http://www.mplayerhq.hu)</LI>
 * <LI><B>VLC: VideoLAN Media Player (http://www.videolan.org)</LI>
 * <LI><B>SHELL: Command Line (allowing to send arbitrary system commands)</LI>
 * <LI><B>MACRO: Macro Commands (allowing to gather several control/command as one)</LI>
 * </UL>
 *
 * <BR/><P>PHP-URC features:</P>
 * <UL>
 * <LI>Standard <B>API</B> for interfacing with media players</LI>
 * <LI><B>Ajax</B>-based user (web) interface</LI>
 * <LI><B>Ready-to-use "controls"</B> (push buttons, switch buttons, rotary buttons, horizontal and vertical sliders, text display)</LI>
 * <LI>Visible <B>feedbacks</B> for user actions and backend responses</LI>
 * </UL>
 *
 * <BR/><P><B>USAGE:</B></P>
 * <P>Designing a new interface based of PHP-URC is achieved in three steps:</P>
 * <UL>
 * <LI>Configure the backends and define controls ('urc.config.php')</LI>
 * <LI>Create the Ajax/RPC handler ('urc.rpc.php')</LI>
 * <LI>Include the controls in your web page ('index.php')</LI>
 * </UL>
 *
 * <BR/><P><B>EXAMPLE: 'urc.config.php'</B></P>
 * <CODE>
 * // Retrieve and configure URC framework
 * $oURC = URC::useInstance();
 *
 * // Add controls
 * // $oURC->addControl( $oURC->newControl( $sPluginId, $sId, $sName, $mParameter, $sType ) );
 * $oURC->addControl( $oURC->newControl( 'DUMMY', 'dummy-control', 'My dummy control', array( 'type' => 'random' ), URC_CONTROL::TYPE_TEXT ) );
 * </CODE>
 *
 * <BR/><P><B>EXAMPLE: urc.rpc.php</B></P>
 * <CODE>
 * // Retrieve and configure URC framework
 * require_once('urc.php');
 * require_once('urc.config.php');
 *
 * // Handle the Remote Procedure Call (RPC)
 * URC_RPC::process();
 * </CODE>
 *
 * <BR/><P><B>EXAMPLE: index.php</B></P>
 * <CODE>
 * // Retrieve and configure URC framework
 * require_once('urc.php');
 * require_once('urc.config.php');
 * $oURC = URC::useInstance();
 *
 * // Insert controls
 * // $oURC->htmlControl( $sId, $sSize );
 * echo $oURC->htmlControl( 'dummy-control', URC_THEME::SIZE_MEDIUM );
 * </CODE>
 * 
 * @package PHP_URC
 */
class URC
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Version
   * @var string */
  const VERSION = '@version@';


  /*
   * FIELDS: variable
   ********************************************************************************/

  /** Debugging status
   * @var boolean */
  private $bDEBUG = false;

  /** Plugins path
   * @var string */
  private $sPathPlugins = null;

  /** Themes path
   * @var string */
  private $sPathThemes = null;

  /** PHP (RPC) URL
   * @var string */
  private $sUrlPhp = null;

  /** Javascript URL
   * @var string */
  private $sUrlJavascript = null;

  /** Themes URL root
   * @var string */
  private $sUrlThemesRoot = null;

  /** Associated theme (style)
   * @var string */
  private $sTheme = null;

  /** Controls
   * @var array|URC_CONTROL */
  private $aoControls = array();

  /** Poll control IDs
   * @var array|string */
  private $asPollControlIDs = array();

  /** Dependent control IDs
   * @var array|array|string */
  private $aasDependentControlIDs = array();

  /** Modified control IDs
   * @var array|string */
  private $asModifiedControlIDs = array();

  /** User-interface poll (refresh) rate [milliseconds]
   * @var integer */
  private $iPollRate = null;

  /** User-interface RPC request timeout [milliseconds]
   * @var integer */
  private $iRpcTimeout = null;

  /** Framework boundaries enforcement status
   * @var boolean */
  private $bFrameworkBoundary = null;

  /** Hyperlinks
   * @var array|URC_HYPERLINK */
  private $aoHyperlinks = array();


  /*
   * FIELDS: static
   ********************************************************************************/

  /** Framework singleton
   * @var URC */
  private static $oURC;

  /** Theme singleton
   * @var URC_THEME */
  private static $oTHEME;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the framework
   */
  private function __construct()
  {
    $this->setPaths();
    $this->setUrls();
    $this->setTheme( 'default' );
    $this->setUIParameters();
  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) framework instance (<B>as reference</B>)
   *
   * @return URC
   */
  public static function &useInstance()
  {
    if( is_null( self::$oURC ) ) self::$oURC = new URC();
    return self::$oURC;
  }

  /** Loads the given plugin resource and returns the corresponding class name
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sPlugin Control's plugin name
   * @return string
   */
  public function loadPlugin( $sPlugin )
  {
    // Check/load plugin class
    $sPlugin = strtolower( trim( $sPlugin ) );
    $sPluginClass = strtoupper( 'URC_CONTROL_'.$sPlugin );
    if( !class_exists( $sPluginClass, false ) )
    {
      require_once( $this->sPathPlugins.'/'.$sPlugin.'/plugin.php' );
      if( !class_exists( $sPluginClass, false ) )
        throw new URC_EXCEPTION( __METHOD__, 'Failed to load URC plugin class; Plugin: '.$sPlugin );
    }
    return $sPluginClass;
  }


  /** Loads the URC theme resource and returns the corresponding class name
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @return string
   */
  public function loadTheme()
  {
    // Check/load plugin class
    $sTheme = $this->sTheme;
    $sThemeClass = strtoupper( 'URC_THEME_'.$sTheme );
    if( !class_exists( $sThemeClass, false ) )
    {
      require_once( $this->sPathThemes.'/'.$this->sTheme.'/theme.php' );
      if( !class_exists( $sThemeClass, false ) )
        throw new URC_EXCEPTION( __METHOD__, 'Failed to load URC theme class; Theme: '.$sTheme );
    }
    return $sThemeClass;
  }


  /*
   * METHODS: configuration
   ********************************************************************************/

  /** Sets the URC framework's debugging status
   *
   * @param boolean $bDEBUG Debugging status
   */
  public function setDebug( $bDEBUG )
  {
    $this->bDEBUG = (boolean)$bDEBUG;
  }

  /** Sets the URC framework's (PHP) paths
   *
   * @param string $sPathPlugins Local path to URC plugins (if null: <SAMP>URC_INCLUDE_PATH.'/plugins'</SAMP>)
   * @param string $sPathThemes Local path to URC themes (if null: <SAMP>URC_INCLUDE_PATH.'/themes'</SAMP>)
   */
  public function setPaths( $sPathPlugins = null, $sPathThemes = null )
  {
    $this->sPathPlugins = is_null( $sPathPlugins ) ? URC_INCLUDE_PATH.'/plugins' : (string)$sPathPlugins;
    $this->sPathThemes = is_null( $sPathThemes ) ? URC_INCLUDE_PATH.'/themes' : (string)$sPathThemes;
  }

  /** Sets the URC framework's (HTTP) URLs
   *
   * @param string $sUrlPhp URL to URC (RPC) PHP handler (if null: <SAMP>'urc.rpc.php'</SAMP>)
   * @param string $sUrlJavascript URL root to URC javascript handler (if null: <SAMP>URC_HTDOCS_URL.'/js/urc.js'</SAMP>)
   * @param string $sUrlThemesRoot URL root to URC themes (if null: <SAMP>URC_HTDOCS_URL.'/themes'</SAMP>)
   */
  public function setUrls( $sUrlPhp = null, $sUrlJavascript = null, $sUrlThemesRoot = null )
  {
    $this->sUrlPhp = is_null( $sUrlPhp ) ? 'urc.rpc.php' : (string)$sUrlPhp;
    $this->sUrlJavascript = is_null( $sUrlJavascript ) ? URC_HTDOCS_URL.'/js/urc.js' : (string)$sUrlJavascript;
    $this->sUrlThemesRoot = is_null( $sUrlThemesRoot ) ? URC_HTDOCS_URL.'/themes' : (string)$sUrlThemesRoot;
  }

  /** Sets the URC framework's associated theme (style)
   *
   * @param string $sTheme Theme (style) name
   */
  public function setTheme( $sTheme )
  {
    $this->sTheme = trim( $sTheme );
  }

  /** Sets the user interface parameters
   *
   * @param integer $iPollRate Poll rate [milliseconds] (if null: <SAMP>1000</SAMP>)
   * @param integer $iRpcTimeout RPC request timeout [milliseconds] (if null: <SAMP>2000</SAMP>)
   * @param boolean $bFrameworkBoundary Framework boundary enforcement status (if null: <SAMP>false</SAMP>)
   */
  public function setUIParameters( $iPollRate = null, $iRpcTimeout = null, $bFrameworkBoundary = null )
  {
    $this->iPollRate = is_null( $iPollRate ) ? 1000 : (integer)$iPollRate;
    $this->iRpcTimeout = is_null( $iRpcTimeout ) ? 2000 : (integer)$iRpcTimeout;
    $this->bFrameworkBoundary = is_null( $bFrameworkBoundary ) ? false : (boolean)$bFrameworkBoundary;
  }

  /** Returns the URC framework's debugging status
   *
   * @return boolean
   */
  public function DEBUG()
  {
    return $this->bDEBUG;
  }

  /** Returns the URL root to the URC framework's associated theme
   *
   * @return string
   */
  public function getThemeUrlRoot()
  {
    return $this->sUrlThemesRoot.'/'.$this->sTheme;
  }


  /*
   * METHODS: controls
   ********************************************************************************/

  /** Returns a new URC control, matching the given plugin and parameters (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sPlugin Control's plugin name
   * @param string $sID Control's identifier (ID)
   * @param string $sName Control's (human-friendly) name
   * @param mixed $mParameters Control's parameters
   * @param string $sType Control's type (see class constants)
   * @return URC_CONTROL
   */
  public function &newControl( $sPlugin, $sID, $sName, $mParameters, $sType )
  {
    // Return new control
    $sPluginClass = URC::loadPlugin( $sPlugin );
    $oControl = new $sPluginClass( $sID, $sName, $mParameters, $sType );
    if( !($oControl instanceof URC_CONTROL) )
      throw new URC_EXCEPTION( __METHOD__, 'Mismatched URC plugin class; Plugin: '.$sPlugin );
    return $oControl;
  }

  /** Adds a new control to the URC framework
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param URC_CONTROL
   */
  public function addControl( URC_CONTROL $oControl )
  {
    $sID = $oControl->getID();
    if( array_key_exists( $sID, $this->aoControls ) )
      throw new URC_EXCEPTION( __METHOD__, 'Already existing control identifier; ID: '.$sID );
    $this->aoControls[$sID] = $oControl;
  }

  /** Adds the given control's identifier to the list of regularly polled controls
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   * <P><B>NOTE:</B> <SAMP>Polled</SAMP> controls are control which's value must be
   * regularly queried and updated by the user (web) interface. Typically, those are
   * controls which's value may change without any interaction via the user (web)
   * interface.</P>
   *
   * @param array|string $asIDs Controls identifiers (IDs)
   */
  public function addPollControlIDs( $asIDs )
  {
    if( is_scalar( $asIDs ) ) $asIDs = array( $asIDs );
    foreach( $asIDs as $sID )
    {
      $sID = strtolower( trim( $sID ) );
      if( !array_key_exists( $sID, $this->aoControls ) )
        throw new URC_EXCEPTION( __METHOD__, 'Invalid control identifier; ID: '.$sID );
      if( !in_array( $sID, $this->asPollControlIDs ) )
        array_push( $this->asPollControlIDs, $sID );
    }
  }

  /** Adds the given controls' identifiers as dependencies
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   * <P><B>NOTE:</B> <SAMP>Dependent</SAMP> controls are control which's value is affected
   * by another (<SAMP>trigger</SAMP>) control and which must thus be queried and updated
   * by the user (web) interface whenever a trigger control is modified. Typically, those
   * are control which's value is modified when <B>another</B> (trigger) control is used
   * in the user (web) interface.</P>
   *
   * @param array|string $asTriggerIDs Triggering control's identifiers (IDs)
   * @param array|string $asDependentIDs Dependent controls' identifiers (IDs)
   */
  public function addDependentControlIDs( $asTriggerIDs, $asDependentIDs )
  {
    if( is_scalar( $asTriggerIDs ) ) $asTriggerIDs = array( $asTriggerIDs );
    if( is_scalar( $asDependentIDs ) ) $asDependentIDs = array( $asDependentIDs );
    foreach( $asTriggerIDs as $sTriggerID )
    {
      $sTriggerID = strtolower( trim( $sTriggerID ) );
      if( !array_key_exists( $sTriggerID, $this->aoControls ) )
        throw new URC_EXCEPTION( __METHOD__, 'Invalid control identifier; ID: '.$sTriggerID );
      if( !array_key_exists( $sTriggerID, $this->aasDependentControlIDs ) ) $this->aasDependentControlIDs[$sTriggerID] = array();
      foreach( $asDependentIDs as $sDependentID )
      {
        $sDependentID = strtolower( trim( $sDependentID ) );
        if( $sDependentID == $sTriggerID ) continue;
        if( !array_key_exists( $sDependentID, $this->aoControls ) )
          throw new URC_EXCEPTION( __METHOD__, 'Invalid control identifier; ID: '.$sDependentID );
        if( !in_array( $sDependentID, $this->aasDependentControlIDs[$sTriggerID] ) ) array_push( $this->aasDependentControlIDs[$sTriggerID], $sDependentID );
      }
    }
  }

  /** Adds the given control's identifier to the list of (currently) modified controls
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   * <P><B>NOTE:</B> <SAMP>Modified</SAMP> controls are control which's value has been
   * modified by an action. This method should only be invoked by URC plug-ins when
   * appropriate.</P>
   *
   * @param array|string $asIDs Controls identifiers (IDs)
   */
  public function addModifiedControlIDs( $asIDs )
  {
    if( is_scalar( $asIDs ) ) $asIDs = array( $asIDs );
    foreach( $asIDs as $sID )
    {
      $sID = strtolower( trim( $sID ) );
      if( !array_key_exists( $sID, $this->aoControls ) )
        throw new URC_EXCEPTION( __METHOD__, 'Invalid control identifier; ID: '.$sID );
      $asSubIDs = array( $sID );
      if( array_key_exists( $sID, $this->aasDependentControlIDs ) ) $asSubIDs = array_unique( array_merge( $asSubIDs, $this->aasDependentControlIDs[$sID] ) );
      foreach( $asSubIDs as $sSubID )
      {
        if( !in_array( $sSubID, $this->asModifiedControlIDs ) )
          array_push( $this->asModifiedControlIDs, $sSubID );
      }
    }
  }

  /** Returns the given control (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Control's identifier (ID)
   * @return URC_CONTROL
   */
  public function &useControl( $sID )
  {
    $sID = strtolower( trim( $sID ) );
    if( !array_key_exists( $sID, $this->aoControls ) )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid control identifier; ID: '.$sID );
    return $this->aoControls[$sID];
  }

  /** Returns the list of control identifiers (IDs)
   *
   * @return array|string
   */
  public function getAllControlIDs()
  {
    return array_keys( $this->aoControls );
  }

  /** Returns the list of pollable control identifiers (IDs)
   *
   * @return array|string
   */
  public function getPollControlIDs()
  {
    return $this->asPollControlIDs;
  }

  /** Returns the list of (currently) modified control identifiers (IDs)
   *
   * @return array|string
   */
  public function getModifiedControlIDs()
  {
    return $this->asModifiedControlIDs;
  }


  /*
   * METHODS: hyperlinks
   ********************************************************************************/

  /** Returns a new URC hyperlink, matching the given plugin and parameters (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Hyperlink's identifier (ID)
   * @param string $sName Hyperlink's (human-friendly) name
   * @param string $sContent Hyperlink's content
   * @param string $sType Hyperlink's type (see class constants)
   * @return URC_HYPERLINK
   */
  public function &newHyperlink( $sID, $sName, $sContent, $sType )
  {
    // Return new hyperlink
    $oHyperlink = new URC_HYPERLINK( $sID, $sName, $sContent, $sType );
    return $oHyperlink;
  }

  /** Adds a new hyperlink to the URC framework
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param URC_HYPERLINK
   */
  public function addHyperlink( URC_HYPERLINK $oHyperlink )
  {
    $sID = $oHyperlink->getID();
    if( array_key_exists( $sID, $this->aoHyperlinks ) )
      throw new URC_EXCEPTION( __METHOD__, 'Already existing hyperlink identifier; ID: '.$sID );
    $this->aoHyperlinks[$sID] = $oHyperlink;
  }

  /** Returns the given hyperlink (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>URC_EXCEPTION</SAMP>.</P>
   *
   * @param string $sID Hyperlink's identifier (ID)
   * @return URC_HYPERLINK
   */
  public function &useHyperlink( $sID )
  {
    $sID = strtolower( trim( $sID ) );
    if( !array_key_exists( $sID, $this->aoHyperlinks ) )
      throw new URC_EXCEPTION( __METHOD__, 'Invalid hyperlink identifier; ID: '.$sID );
    return $this->aoHyperlinks[$sID];
  }


  /*
   * METHODS: HTML
   ********************************************************************************/

  /** Returns the HTML's HEAD content required by the URC framework
   *
   * @return string
   */
  public function htmlHeadContent()
  {
    // HTML output
    $sOutput = null;
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$this->sUrlJavascript.'"></SCRIPT>'."\n";
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$this->getThemeUrlRoot().'/theme.js"></SCRIPT>'."\n";
    $sOutput .= '<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->getThemeUrlRoot().'/theme.css">'."\n";
    return $sOutput;
  }

  /** Returns the HTML's BODY content required by the URC framework
   *
   * @return string
   */
  public function htmlBodyContent()
  {
    // HTML output
    $sOutput = null;
    $sOutput .= '<INPUT ID="URC-PollRate" TYPE="hidden" VALUE="'.( count( $this->asPollControlIDs )>0 ? $this->iPollRate : 0 ).'" DISABLED>'."\n";
    $sOutput .= '<INPUT ID="URC-RpcTimeout" TYPE="hidden" VALUE="'.$this->iRpcTimeout.'" DISABLED>'."\n";
    $sOutput .= '<INPUT ID="URC-RpcUrl" TYPE="hidden" VALUE="'.$this->sUrlPhp.'" DISABLED>'."\n";
    return $sOutput;
  }

  /** Returns the (opening) HTML content required by the URC framework
   *
   * @return string
   */
  public function htmlFrameworkBegin()
  {
    // HTML output
    $sOutput = null;
    $sOutput .= "\n\n".'<!-- BEGIN: PHP-URC Framework -->'."\n";
    $sOutput .= '<DIV CLASS="URC">'."\n";
    $sOutput .= '<TABLE CLASS="frm" CELLSPACING="0">'."\n";
    if( $this->bFrameworkBoundary )
    {
      $sOutput .= '<TR CLASS="t"><TD CLASS="tl" ONMOUSEOVER="URC_onMU()">&nbsp;</TD><TD CLASS="tc" ONMOUSEOVER="URC_onMU()">&nbsp;</TD><TD CLASS="tr" ONMOUSEOVER="URC_onMU()">&nbsp;</TD></TR>'."\n";
      $sOutput .= '<TR CLASS="m"><TD CLASS="ml" ONMOUSEOVER="URC_onMU()">&nbsp;</TD><TD CLASS="mc" ONMOUSEUP="URC_onMU()">'."\n";
    }
    else
    {
      $sOutput .= '<TR CLASS="t"><TD CLASS="tl">&nbsp;</TD><TD CLASS="tc">&nbsp;</TD><TD CLASS="tr">&nbsp;</TD></TR>'."\n";
      $sOutput .= '<TR CLASS="m"><TD CLASS="ml">&nbsp;</TD><TD CLASS="mc">'."\n";
    }
    return $sOutput;
  }

  /** Returns the (closing) HTML content required by the URC framework
   *
   * @return string
   */
  public function htmlFrameworkEnd()
  {
    // HTML output
    $sOutput = null;
    if( $this->bFrameworkBoundary )
    {
      $sOutput .= '</TD><TD CLASS="mr" ONMOUSEOVER="URC_onMU()">&nbsp;</TD></TR>'."\n";
      $sOutput .= '<TR CLASS="b"><TD CLASS="bl" ONMOUSEOVER="URC_onMU()">&nbsp;</TD><TD CLASS="bc" ONMOUSEOVER="URC_onMU()">&nbsp;</TD><TD CLASS="br" ONMOUSEOVER="URC_onMU()">&nbsp;</TD></TR>'."\n";
    }
    else
    {
      $sOutput .= '</TD><TD CLASS="mr">&nbsp;</TD></TR>'."\n";
      $sOutput .= '<TR CLASS="b"><TD CLASS="bl">&nbsp;</TD><TD CLASS="bc">&nbsp;</TD><TD CLASS="br">&nbsp;</TD></TR>'."\n";
    }
    $sOutput .= '</TABLE>'."\n";
    $sOutput .= '</DIV>'."\n";
    $sOutput .= '<!-- END: PHP-URC Framework -->'."\n\n";
    return $sOutput;
  }

  /** Returns the HTML's content for a given control
   *
   * @param string $sID Control's identifier (ID)
   * @param string $sSize Control's size (see class constants)
   * @return string
   */
  public function htmlControl( $sID, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    try{

      // Retrieve control
      $sID = strtolower( trim( $sID ) );
      $roControl =& $this->useControl( $sID );

      // Are theme's PHP resources loaded ?
      if( is_null( self::$oTHEME ) )
      {
        $sThemeClass = URC::loadTheme();
        $roTHEME = new $sThemeClass();
        if( !($roTHEME instanceof URC_THEME) )
          throw new URC_EXCEPTION( __METHOD__, 'Mismatched URC theme class; Theme: '.$this->sTheme );
        self::$oTHEME =& $roTHEME;
      }

      // HTML output
      $sOutput = null;
      $sOutput .= "\n\n".'<!-- BEGIN: PHP-URC Control ['.$sID.'] -->'."\n";
      $sOutput .= '<INPUT ID="URC-'.$sID.'-cls" TYPE="hidden" VALUE="'.self::$oTHEME->getControlClass( $roControl, $sSize ).'" DISABLED>'."\n";
      $sOutput .= '<INPUT ID="URC-'.$sID.'-reg" TYPE="hidden" VALUE="0" DISABLED>'."\n";
      $sOutput .= self::$oTHEME->getControlHtml( $roControl, $sSize );
      $sOutput .= '<!-- END: PHP-URC Control ['.$sID.'] -->'."\n\n";
      return $sOutput;
    }
    catch( URC_EXCEPTION $e )
    {
      $e->logError(); 
      return "\n\n".'<!-- ERROR: PHP-URC Control ['.$sID.'] -->'."\n\n";
    }
  }

  /** Returns the HTML's content for a given hyperlink
   *
   * @param string $sID Hyperlink's identifier (ID)
   * @param string $sSize Hyperlink's size (see class constants)
   * @return string
   */
  public function htmlHyperlink( $sID, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    try{

      // Retrieve hyperlink
      $sID = strtolower( trim( $sID ) );
      $roHyperlink =& $this->useHyperlink( $sID );

      // Are theme's PHP resources loaded ?
      if( is_null( self::$oTHEME ) )
      {
        $sThemeClass = URC::loadTheme();
        $roTHEME = new $sThemeClass();
        if( !($roTHEME instanceof URC_THEME) )
          throw new URC_EXCEPTION( __METHOD__, 'Mismatched URC theme class; Theme: '.$this->sTheme );
        self::$oTHEME =& $roTHEME;
      }

      // HTML output
      $sOutput = null;
      $sOutput .= "\n\n".'<!-- BEGIN: PHP-URC Hyperlink ['.$sID.'] -->'."\n";
      $sOutput .= '<INPUT ID="URC-'.$sID.'-cls" TYPE="hidden" VALUE="'.self::$oTHEME->getHyperlinkClass( $roHyperlink, $sSize ).'" DISABLED>'."\n";
      $sOutput .= self::$oTHEME->getHyperlinkHtml( $roHyperlink, $sSize );
      $sOutput .= '<!-- END: PHP-URC Hyperlink ['.$sID.'] -->'."\n\n";
      return $sOutput;
    }
    catch( URC_EXCEPTION $e )
    {
      $e->logError(); 
      return "\n\n".'<!-- ERROR: PHP-URC Hyperlink ['.$sID.'] -->'."\n\n";
    }
  }



  /*
   * METHODS: utilities
   ********************************************************************************/
  
  /** Returns whether the client device may be categorized as "mobile"
   *
   * @return boolean
   */
  public static function isMobileClient()
  {
    // NOTE: get_browser() sucks... it fails to recognize rather well-spread user agents... (e.g. Opera Mobile)

    static $bMobileClient;
    if( is_null( $bMobileClient ) )
    {
      $asMobileSignatures = array( 'nokia', 'sonyericsson', 'motorola', 'samsung', 'lg-', 'lge-', 'sie-', 'up.b', 'blackberry', 'iphone', 'mot-', 'windows ce', 'ppc', 'palmsource', 'palm' );
      $bMobileClient = preg_match( '/'.implode('|',array_map('preg_quote',$asMobileSignatures)).'/i', $_SERVER['HTTP_USER_AGENT'] );
    }
    return $bMobileClient;
  }

}
