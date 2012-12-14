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
 * @package PHP_URC_Examples
 * @subpackage DEMO
 * @author Cedric Dufour <http://cedric.dufour.name>
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

  // Controls
  $asControlIDs = $oURC->getAllControlIDs();

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
  $oURC->useControl('volume_mute')->setOverlay('<IMG SRC="'.$oURC->getThemeUrlRoot().'/ovl/close.png">');
  $oURC->useControl('display_refresh')->setOverlay('<B>R</B>');
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

// NOTE: This is an elaborate example of what can be done.
//       Write your own HTML to create your own mind-blowing interface!
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>

<HEAD>
<TITLE>PHP Universal Remote Control (PHP-URC) - Web Media Player</TITLE>
<?php if( URC::isMobileClient() ) {?>
<META NAME="viewport" CONTENT="width=320,user-scalable=no">
<?php }?>
<STYLE TYPE="text/css">
@media screen {
  DIV.viewport {MARGIN:auto;PADDING:0px;WIDTH:300px;}
}
@media handheld {
  DIV.viewport {MARGIN:0px;PADDING:0px;WIDTH:300px;}
}
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
DIV.URC TABLE.display TD.sep {PADDING:0px 5px;}
DIV.URC TABLE.display TD.info {TEXT-ALIGN:left;PADDING:0px 5px 3px;}
DIV.URC TABLE.display TD.info SPAN.info {CURSOR:pointer;}
</STYLE>
<SCRIPT TYPE="text/javascript">
function divShow( id )
{
document.getElementById('meta').style.display=( id=='meta' ? 'inline' : 'none' );
document.getElementById('audio').style.display=( id=='audio' ? 'inline' : 'none' );
document.getElementById('video').style.display=( id=='video' ? 'inline' : 'none' );
}
</SCRIPT>
<?php echo $oURC->htmlHeadContent();?>
</HEAD>

<?php if( URC::isMobileClient() ) {?>
<BODY ONLOAD="URC_init();">
<?php } else {?>
<BODY ONLOAD="URC_init();" ONMOUSEUP="URC_onMU();">
<?php }?>
<DIV CLASS="viewport">
<?php echo $oURC->htmlBodyContent();?>
<?php echo $oURC->htmlFrameworkBegin();?>

<TABLE CLASS="title" CELLSPACING="0">
<TR>
<TD CLASS="name">PHP-URC</TD>
<TD CLASS="info">Web Media Player</TD>
</TR>
</TABLE>
<DIV CLASS="sep"></DIV>
<TABLE CLASS="controls" CELLSPACING="0">
<TR><TD>play</TD><TD>pause</TD><TD>stop</TD></TR>
<TR><TD><?php echo $oURC->htmlControl('play');?></TD><TD><?php echo $oURC->htmlControl('pause');?></TD><TD><?php echo $oURC->htmlControl('stop');?></TD></TR>
</TABLE>
<TABLE CLASS="display" CELLSPACING="0">
<TR><TD COLSPAN="3" STYLE="PADDING-TOP:5px;"><B><?php echo $oURC->htmlControl('display_title');?></B></TD></TR>
<TR><TD COLSPAN="3"><?php echo $oURC->htmlControl('display_album');?></TD></TR>
<TR><TD COLSPAN="3"><?php echo $oURC->htmlControl('display_artist');?></TD></TR>
<TR><TD STYLE="WIDTH:45%;TEXT-ALIGN:right;"><?php echo $oURC->htmlControl('display_position');?></TD><TD>&nbsp;/&nbsp;</TD><TD STYLE="WIDTH:45%;TEXT-ALIGN:left;"><?php echo $oURC->htmlControl('display_length');?></TD></TR>
<TR><TD COLSPAN="3"><?php echo $oURC->htmlControl('position',URC_THEME::SIZE_LARGE);?></TD></TR>
<TR><TD COLSPAN="3" CLASS="sep"><DIV CLASS="sep"></DIV></TD></TR>
<TR><TD COLSPAN="3" CLASS="info">Info: <SPAN CLASS="info" ONCLICK="javascript:divShow('meta');">meta</SPAN> &middot; <SPAN CLASS="info" ONCLICK="javascript:divShow('audio');">audio</SPAN> &middot; <SPAN CLASS="info" ONCLICK="javascript:divShow('video');">video</SPAN> &middot; <SPAN CLASS="info" ONCLICK="javascript:divShow(null);">(hide)</SPAN>
<DIV ID="meta" STYLE="display:none;">
<BR/>&nbsp;File: <?php echo $oURC->htmlControl('display_file',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Date: <?php echo $oURC->htmlControl('display_date',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Genre: <?php echo $oURC->htmlControl('display_genre',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Copyright: <?php echo $oURC->htmlControl('display_copyright',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Description: <?php echo $oURC->htmlControl('display_description',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Comment: <?php echo $oURC->htmlControl('display_comment',URC_THEME::SIZE_SMALL);?>
<BR/>&nbsp;Rating: <?php echo $oURC->htmlControl('display_rating',URC_THEME::SIZE_SMALL);?>
</DIV>
<DIV ID="audio" STYLE="display:none;">
<?php if( array_intersect(array('display_audio_channels','display_audio_samplerate','display_audio_bitrate','display_audio_codec'),$asControlIDs) ) { ?>
<?php if( in_array('display_audio_channels',$asControlIDs) ) {?><BR/>&nbsp;Channels: <?php echo $oURC->htmlControl('display_audio_channels',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_audio_samplerate',$asControlIDs) ) {?><BR/>&nbsp;Samplerate: <?php echo $oURC->htmlControl('display_audio_samplerate',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_audio_bitrate',$asControlIDs) ) {?><BR/>&nbsp;Bitrate: <?php echo $oURC->htmlControl('display_audio_bitrate',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_audio_codec',$asControlIDs) ) {?><BR/>&nbsp;Codec: <?php echo $oURC->htmlControl('display_audio_codec',URC_THEME::SIZE_SMALL); }?>
<?php } else { ?>
<BR/>&nbsp;<I>not available for this backend</I>
<?php } ?>
</DIV>
<DIV ID="video" STYLE="display:none;">
<?php if( array_intersect(array('display_video_width','display_video_height','display_video_framerate','display_video_codec'),$asControlIDs) ) { ?>
<?php if( in_array('display_video_width',$asControlIDs) ) {?><BR/>&nbsp;Width: <?php echo $oURC->htmlControl('display_video_width',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_video_height',$asControlIDs) ) {?><BR/>&nbsp;Height: <?php echo $oURC->htmlControl('display_video_height',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_video_framerate',$asControlIDs) ) {?><BR/>&nbsp;Framerate: <?php echo $oURC->htmlControl('display_video_framerate',URC_THEME::SIZE_SMALL); }?>
<?php if( in_array('display_video_codec',$asControlIDs) ) {?><BR/>&nbsp;Codec: <?php echo $oURC->htmlControl('display_video_codec',URC_THEME::SIZE_SMALL); }?>
<?php } else { ?>
<BR/>&nbsp;<I>not available for this backend</I>
<?php } ?>
</DIV>
</TD></TR>
</TABLE>
<TABLE CLASS="controls" CELLSPACING="0">
<TR><TD><?php echo $oURC->htmlControl('previous',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('rewind',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('forward',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('next',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('repeat',URC_THEME::SIZE_SMALL);?></TD></TD><TD><?php echo $oURC->htmlControl('display_refresh',URC_THEME::SIZE_SMALL);?></TD></TR>
<TR><TD>previous</TD><TD>rewind</TD><TD>forward</TD><TD>next</TD><TD>repeat</TD><TD>refresh</TD></TR>
<TR><TD COLSPAN="6"><DIV CLASS="sep"></DIV></TD></TR>
<TR><TD><?php echo $oURC->htmlControl('volume_decrease',URC_THEME::SIZE_SMALL);?></TD><TD COLSPAN="3"><?php echo $oURC->htmlControl('volume_set',URC_THEME::SIZE_MEDIUM);?></TD><TD><?php echo $oURC->htmlControl('volume_increase',URC_THEME::SIZE_SMALL);?></TD><TD><?php echo $oURC->htmlControl('volume_mute',URC_THEME::SIZE_SMALL);?></TD></TR>
<TR><TD COLSPAN="5">volume</TD><TD>mute</TD></TR>
</TABLE>

<?php echo $oURC->htmlFrameworkEnd();?>
</DIV>
</BODY>

</HTML>
