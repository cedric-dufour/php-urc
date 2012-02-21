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
 * @package PHP_URC_Plugins
 * @subpackage MPD
 * @author Cedric Dufour <http://www.ced-network.net/php-urc>
 * @version @version@
 */


/*
 * FRAMEWORK INITILIZATION
 ********************************************************************************/

// Load URC framework and configuration
require_once( 'urc.php' );
try
{
  require_once( 'urc.config.php' );
  $oURC = URC::useInstance();
  $oURC->setUIParameters( 1000, 5000 );

  // Overlays
  $oURC->useControl('stop')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/stop.png">');
  $oURC->useControl('play')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/play.png">');
  $oURC->useControl('pause')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/pause.png">');
  $oURC->useControl('previous')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/previous.png">');
  $oURC->useControl('rewind')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/rewind.png">');
  $oURC->useControl('forward')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/forward.png">');
  $oURC->useControl('next')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/next.png">');
  $oURC->useControl('volume_decrease')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/minus.png">');
  $oURC->useControl('volume_increase')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/plus.png">');

  // Adjust UI parameters to better handle plug-in latency
  //$oURC->setUIParameters( 1000, 5000 );
}
catch( URC_EXCEPTION $e )
{
  echo '<br/><b>Exception</b>: '.$e->getMessage();
  $e->logError();
  exit;
}


/*
 * WEB INTERFACE
 ********************************************************************************/

// NOTE: This is a crude example of what can be done.
//       Write your own HTML to create your own mind-blowing interface!
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>

<HEAD>
<TITLE>PHP Universal Remote Control (PHP-URC) - MPD Plugin</TITLE>
<STYLE TYPE="text/css">
BODY {MARGIN:0px;PADDING:0px;BACKGROUND:#141414;}
DIV.URC TABLE.controls {WIDTH:100%;}
DIV.URC TABLE.controls TD {VERTICAL-ALIGN:middle;TEXT-ALIGN:center;}
</STYLE>
<?php echo $oURC->htmlHeadContent();?>
</HEAD>

<BODY ONLOAD="URC_init();" ONMOUSEUP="URC_onMU();">
<DIV CLASS="viewport">
<?php echo $oURC->htmlBodyContent();?>
<?php echo $oURC->htmlFrameworkBegin();?>

<H1 TITLE="PHP Universal Remote Control (PHP-URC) - MPD Plug-in" STYLE="TEXT-ALIGN:center;">PHP-URC - MPD</H1>
<TABLE CLASS="controls" CELLSPACING="0">
<?php
foreach( $oURC->getAllControlIDs() as $sID )
{
  $roControl =& $oURC->useControl( $sID );
  echo '<TR><TD>'.$sID.':</TD><TD>'.$oURC->htmlControl( $sID, URC_THEME::SIZE_SMALL ).'</TD><TD>'.htmlentities( $roControl->getName() )."</TD></TR>\n";
}
?>
</TABLE>

<?php echo $oURC->htmlFrameworkEnd();?>
</DIV>
</BODY>

</HTML>
