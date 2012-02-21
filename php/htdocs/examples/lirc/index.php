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
 * @subpackage LIRC
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
  if( URC::isMobileClient() ) $oURC->setTheme('mobile');

  // Overlays
  $oURC->useControl('power_off')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/off.png">');
  $oURC->useControl('power_on')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/on.png">');
  $oURC->useControl('volume_down')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/minus.png">');
  $oURC->useControl('volume_up')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/plus.png">');
  $oURC->useControl('playlist_previous')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/previous.png">');
  $oURC->useControl('position_rewind')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/rewind.png">');
  $oURC->useControl('playlist_stop')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/stop.png">');
  $oURC->useControl('playlist_play')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/play.png">');
  $oURC->useControl('playlist_pause')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/pause.png">');
  $oURC->useControl('position_forward')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/forward.png">');
  $oURC->useControl('playlist_next')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/next.png">');
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
<TITLE>PHP Universal Remote Control (PHP-URC) - LIRC Plugin</TITLE>
<STYLE TYPE="text/css">
BODY {MARGIN:0px;PADDING:0px;BACKGROUND:#141414;}
DIV.URC DIV.sep {MARGIN:5px 0px;WIDTH:100%;BORDER-BOTTOM:solid 1px #282828;}
DIV.URC DIV.gap {MARGIN:5px 0px;}
DIV.URC TABLE.title {WIDTH:100%;}
DIV.URC TABLE.title TD {VERTICAL-ALIGN:middle;TEXT-ALIGN:left;}
DIV.URC TABLE.title TD.link {WIDTH:1px;TEXT-ALIGN:center;}
DIV.URC TABLE.title TD.name {TEXT-ALIGN:left;FONT-SIZE:14px;FONT-WEIGHT:bold;}
DIV.URC TABLE.title TD.info {TEXT-ALIGN:right;COLOR:#353535;}
DIV.URC TABLE.controls {WIDTH:100%;}
DIV.URC TABLE.controls TD {VERTICAL-ALIGN:middle;TEXT-ALIGN:center;}
DIV.URC TABLE.controls SPAN.tab {PADDING:0px 1px;COLOR:#565656;BORDER:solid 1px #565656;CURSOR:pointer;}
DIV.URC TABLE.display {WIDTH:100%;BACKGROUND:#000000;BORDER:solid 1px #282828;}
DIV.URC TABLE.display TD {VERTICAL-ALIGN:middle;TEXT-ALIGN:center;}
</STYLE>
<?php echo $oURC->htmlHeadContent();?>
</HEAD>

<BODY ONLOAD="URC_init();" ONMOUSEUP="URC_onMU();">
<DIV CLASS="viewport">
<?php echo $oURC->htmlBodyContent();?>
<?php echo $oURC->htmlFrameworkBegin();?>

<TABLE CLASS="title" CELLSPACING="0">
<TR>
<TD CLASS="name">PHP-URC</TD>
<TD CLASS="info">LIRC Plugin</TD>
</TR>
</TABLE>
<DIV CLASS="sep"></DIV>
<TABLE CLASS="controls" CELLSPACING="0">
<TR>
<TD><?php echo $oURC->htmlControl('power_off');?></TD>
<TD><?php echo $oURC->htmlControl('power_on');?></TD>
<TD><?php echo $oURC->htmlControl('volume_down');?></TD>
<TD><?php echo $oURC->htmlControl('volume_up');?></TD>
</TR>
<TR><TD COLSPAN="2">power</TD><TD COLSPAN="2">volume</TD></TR>
<TR><TD COLSPAN="4"><DIV CLASS="sep"></DIV></TD></TR>
<TR>
<TD><?php echo $oURC->htmlControl('playlist_stop');?></TD>
<TD COLSPAN="2"><?php echo $oURC->htmlControl('playlist_play');?></TD>
<TD><?php echo $oURC->htmlControl('playlist_pause');?></TD>
</TR>
<TR><TD>stop</TD><TD COLSPAN="2">play</TD><TD>pause</TD></TR>
<TR>
<TD><?php echo $oURC->htmlControl('playlist_previous');?></TD>
<TD><?php echo $oURC->htmlControl('position_rewind');?></TD>
<TD><?php echo $oURC->htmlControl('position_forward');?></TD>
<TD><?php echo $oURC->htmlControl('playlist_next');?></TD>
</TR>
<TR><TD>previous</TD><TD>rewind</TD><TD>forward</TD><TD>next</TD></TR>
</TABLE>

<?php echo $oURC->htmlFrameworkEnd();?>
</DIV>
</BODY>

</HTML>
