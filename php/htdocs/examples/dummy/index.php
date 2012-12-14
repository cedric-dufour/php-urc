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
 * @subpackage DUMMY
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/*
 * FRAMEWORK INITILIZATION
 ********************************************************************************/

// Start PHP session (required by this plugin)
session_start();

// Load URC framework and configuration
require_once( 'urc.php' );
try
{
  require_once( 'urc.config.php' );
  $oURC = URC::useInstance();

  // Overlays
  $oURC->useHyperlink('HLXS')->setOverlay('XS');
  $oURC->useHyperlink('HLS')->setOverlay('S');
  $oURC->useHyperlink('HLM')->setOverlay('M');
  $oURC->useHyperlink('HLL')->setOverlay('L');
  $oURC->useHyperlink('HLXL')->setOverlay('XL');
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
<TITLE>PHP Universal Remote Control (PHP-URC)</TITLE>
<?php echo $oURC->htmlHeadContent();?>
<STYLE TYPE="text/css">
BODY {MARGIN:0px;PADDING:0px;BACKGROUND:#141414;}
DIV.URC TABLE.controls {WIDTH:100%;}
DIV.URC TABLE.controls TD {VERTICAL-ALIGN:middle;TEXT-ALIGN:center;}
</STYLE>
</HEAD>

<BODY ONLOAD="URC_init();" ONMOUSEUP="URC_onMU();">
<DIV CLASS="viewport">
<?php echo $oURC->htmlBodyContent();?>
<?php echo $oURC->htmlFrameworkBegin();?>

<H1 TITLE="PHP Universal Remote Control (PHP-URC) - Dummy Plug-in Demonstration" STYLE="TEXT-ALIGN:center;">PHP-URC - Dummy</H1>
<TABLE CLASS="controls" CELLSPACING="0">
<TR><TD>&nbsp;</TD><TD>XS</TD><TD>S</TD><TD>M</TD><TD>L</TD><TD>XL</TD></TR>
<TR><TD>HL</TD><TD><?php echo $oURC->htmlHyperlink('HLXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlHyperlink('HLS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlHyperlink('HLM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlHyperlink('HLL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlHyperlink('HLXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>PB</TD><TD><?php echo $oURC->htmlControl('PBXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('PBS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('PBM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('PBL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('PBXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>TX</TD><TD><?php echo $oURC->htmlControl('TXXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('TXS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('TXM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('TXL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('TXXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>SB</TD><TD><?php echo $oURC->htmlControl('SBXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('SBS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('SBM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('SBL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('SBXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>RB</TD><TD><?php echo $oURC->htmlControl('RBXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('RBS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('RBM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('RBL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('RBXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>HS</TD><TD><?php echo $oURC->htmlControl('HSXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('HSS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('HSM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('HSL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('HSXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>HB</TD><TD><?php echo $oURC->htmlControl('HBXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('HBS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('HBM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('HBL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('HBXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR>
<TR><TD>VS/VB</TD><TD><TABLE CELLSPACING="0" STYLE="MARGIN:auto;"><TR><TD><?php echo $oURC->htmlControl('VSXS',URC_THEME::SIZE_EXTRASMALL);?></TD><TD><?php echo $oURC->htmlControl('VBXS',URC_THEME::SIZE_EXTRASMALL);?></TD></TR></TABLE></TD><TD><TABLE CELLSPACING="0" STYLE="MARGIN:auto;"><TR><TD><?php echo $oURC->htmlControl('VSS',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('VBS',URC_THEME::SIZE_SMALL);?></TD></TR></TABLE></TD><TD><TABLE CELLSPACING="0" STYLE="MARGIN:auto;"><TR><TD><?php echo $oURC->htmlControl('VSM',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('VBM',URC_THEME::SIZE_MEDIUM);?></TD></TR></TABLE></TD><TD><TABLE CELLSPACING="0" STYLE="MARGIN:auto;"><TR><TD><?php echo $oURC->htmlControl('VSL',URC_THEME::SIZE_LARGE);?></TD><TD><?php echo $oURC->htmlControl('VBL',URC_THEME::SIZE_LARGE);?></TD></TR></TABLE></TD><TD><TABLE CELLSPACING="0" STYLE="MARGIN:auto;"><TR><TD><?php echo $oURC->htmlControl('VSXL',URC_THEME::SIZE_EXTRALARGE);?></TD><TD><?php echo $oURC->htmlControl('VBXL',URC_THEME::SIZE_EXTRALARGE);?></TD></TR></TABLE></TD></TR>
</TABLE>

<?php echo $oURC->htmlFrameworkEnd();?>
</DIV>
</BODY>

</HTML>
<?php
// Stop PHP session
session_write_close();
