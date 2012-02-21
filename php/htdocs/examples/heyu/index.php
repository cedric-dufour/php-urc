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
 * @subpackage HEYU
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

  // Adjust UI parameters to better handle plug-in latency
  $oURC->setUIParameters( 10000, 10000 );
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
<TITLE>PHP Universal Remote Control (PHP-URC) - HEYU Plugin</TITLE>
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
<TD CLASS="info">HEYU Plugin</TD>
</TR>
</TABLE>
<DIV CLASS="sep"></DIV>
<TABLE CLASS="title" CELLSPACING="0">
<TR>
<TD CLASS="name">Appliance</TD>
<TD CLASS="info">AM* X-10 modules</TD>
</TR>
</TABLE>
<TABLE CLASS="controls" CELLSPACING="0">
<TR>
<TD><?php echo $oURC->htmlControl('appliance_toggle');?></TD>
</TR>
</TABLE>
<TABLE CLASS="title" CELLSPACING="0">
<TR>
<TD CLASS="name">Light</TD>
<TD CLASS="info">LM* X-10 modules</TD>
</TR>
</TABLE>
<TABLE CLASS="controls" CELLSPACING="0">
<TR>
<TD><?php echo $oURC->htmlControl('light_toggle');?></TD>
<TD><?php echo $oURC->htmlControl('light_set');?></TD>
<TD STYLE="WIDTH:30px;TEXT-ALIGN:right;"><?php echo $oURC->htmlControl('light_level');?></TD>
</TR>
</TABLE>

<?php echo $oURC->htmlFrameworkEnd();?>
</DIV>
</BODY>

</HTML>
