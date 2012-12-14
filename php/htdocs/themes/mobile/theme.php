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
 * @package PHP_URC_Themes
 * @author Cedric Dufour <http://cedric.dufour.name>
 * @version @version@
 */


/** URC mobile theme (no drag support)
 *
 * @package PHP_URC_Themes
 * @subpackage mobile
 */
class URC_THEME_MOBILE extends URC_THEME
{

  /*
   * METHODS: theme - OVERRIDE
   ********************************************************************************/

  /** Returns the control's theme-associated class
   */
  public function getControlClass( URC_CONTROL $oControl, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    // Control parameters
    $sType = $oControl->getType();
    $sSize = strtolower( trim( $sSize ) );
    
    // Adjust size for smaller-screen devices
    switch( $sSize )
    {
    case URC_THEME::SIZE_SMALL: $sSize=URC_THEME::SIZE_EXTRASMALL; break;
    case URC_THEME::SIZE_MEDIUM: $sSize=URC_THEME::SIZE_SMALL; break;
    case URC_THEME::SIZE_LARGE:
    case URC_THEME::SIZE_EXTRALARGE: $sSize=URC_THEME::SIZE_MEDIUM; break;
    }

    // Corresponding theme class
    return $sType.'-'.$sSize;
  }

  /** Returns the control's theme-associated HTML content
   */
  public function getControlHtml( URC_CONTROL $oControl, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    // Control parameters
    $sID = $oControl->getID();
    $sName = $oControl->getName();
    $sType = $oControl->getType();
    $sClass = $this->getControlClass( $oControl, $sSize );
    $bReadOnly = $oControl->isReadOnly();
    $bRepeat = $oControl->isRepeatable();
    $fRepeatValue = $oControl->getRepeatValue();
    $iRepeatRate = $oControl->getRepeatRate();
    $iRepeatDelay = 2*$oControl->getRepeatDelay(); // we double repeat-delay because of mobiles' crappy UI

    // HTML output
    $sOutput = null;
    switch( $sType )
    {
    case URC_CONTROL::TYPE_PUSHBUTTON:
      $sOverlay = $oControl->getOverlay();
      $sOutput .= '<DIV ID="URC-'.$sID.'-gui" CLASS="URC-'.$sClass.'">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-bcg" CLASS="bcg"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omo" CLASS="omo"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omd" CLASS="omd"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ofb" CLASS="ofb"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ovl" CLASS="ovl">'.( $sOverlay ? '<TABLE CELLSPACING="0"><TR><TD>'.$sOverlay.'</TD></TR></TABLE>' : null ).'</DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-top" CLASS="top" TITLE="[*] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,null,'.$iRepeatRate.','.$iRepeatDelay : '0' ).')"' ).'></DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      break;

    case URC_CONTROL::TYPE_SWITCHBUTTON:
      $sOutput .= '<DIV ID="URC-'.$sID.'-gui" CLASS="URC-'.$sClass.'">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-bcg" CLASS="bcg"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omo" CLASS="omo"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omd" CLASS="omd"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ofb" CLASS="ofb"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-off" CLASS="off"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-on" CLASS="on"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-top" CLASS="top" TITLE="[0/1] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\',1)"' ).'></DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      break;

    case URC_CONTROL::TYPE_ROTARYBUTTON:
    case URC_CONTROL::TYPE_HORIZONTALSLIDER:
    case URC_CONTROL::TYPE_VERTICALSLIDER:
      $sOutput .= '<DIV ID="URC-'.$sID.'-gui" CLASS="URC-'.$sClass.'">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-bcg" CLASS="bcg"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omo" CLASS="omo"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omd" CLASS="omd"></DIV>'."\n";
      if( $oControl->hasScale() ) $sOutput .= '<DIV ID="URC-'.$sID.'-scl" CLASS="scl"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-hgl" CLASS="hgl"></DIV>'."\n";
      if( $sType==URC_CONTROL::TYPE_VERTICALSLIDER )
      {
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-u" CLASS="top-u" TITLE="[+] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-d" CLASS="top-d" TITLE="[-] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,-'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
      }
      else
      {
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-l" CLASS="top-l" TITLE="[-] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,-'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-r" CLASS="top-r" TITLE="[+] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
      }
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl" CLASS="ctl">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-bcg" CLASS="ctl-bcg"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-omo" CLASS="ctl-omo"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-omd" CLASS="ctl-omd"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-ofb" CLASS="ctl-ofb"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-top" CLASS="ctl-top" TITLE="'.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\')"' ).'></DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      break;

    case URC_CONTROL::TYPE_HORIZONTALBAR:
    case URC_CONTROL::TYPE_VERTICALBAR:
      $sOutput .= '<DIV ID="URC-'.$sID.'-gui" CLASS="URC-'.$sClass.'">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-bcg" CLASS="bcg"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omo" CLASS="omo"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-omd" CLASS="omd"></DIV>'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-hgl" CLASS="hgl"></DIV>'."\n";
      if( $sType==URC_CONTROL::TYPE_VERTICALBAR )
      {
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-u" CLASS="top-u" TITLE="[+] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-d" CLASS="top-d" TITLE="[-] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,-'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
      }
      else
      {
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-l" CLASS="top-l" TITLE="[-] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,-'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
        $sOutput .= '<DIV ID="URC-'.$sID.'-top-r" CLASS="top-r" TITLE="[+] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\','.( $bRepeat ? '4,'.$fRepeatValue.','.$iRepeatRate.','.$iRepeatDelay : '1' ).')"' ).'></DIV>'."\n";
      }
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl" CLASS="ctl">'."\n";
      $sOutput .= '<DIV ID="URC-'.$sID.'-ctl-top" CLASS="ctl-top" TITLE="[<->] '.$sName.'"'.( $bReadOnly ? null : ' ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\')"' ).'></DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      $sOutput .= '</DIV>'."\n";
      break;

    case URC_CONTROL::TYPE_TEXT:
      $sOutput .= '<SPAN ID="URC-'.$sID.'-txt" CLASS="URC-'.$sClass.'">&nbsp;</SPAN>'."\n";
      break;
    }
    return $sOutput;
  }

  /** Returns the hyperlink's theme-associated class
   */
  public function getHyperlinkClass( URC_HYPERLINK $oHyperlink, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    // Hyperlink parameters
    $sSize = strtolower( trim( $sSize ) );
    
    // Adjust size for smaller-screen devices
    switch( $sSize )
    {
    case URC_THEME::SIZE_SMALL: $sSize=URC_THEME::SIZE_EXTRASMALL; break;
    case URC_THEME::SIZE_MEDIUM: $sSize=URC_THEME::SIZE_SMALL; break;
    case URC_THEME::SIZE_LARGE:
    case URC_THEME::SIZE_EXTRALARGE: $sSize=URC_THEME::SIZE_MEDIUM; break;
    }

    // Corresponding theme class
    return 'pb-'.$sSize;
  }

  /** Returns the hyperlink's theme-associated HTML content
   */
  public function getHyperlinkHtml( URC_HYPERLINK $oHyperlink, $sSize = URC_THEME::SIZE_MEDIUM )
  {
    // Hyperlink parameters
    $sID = $oHyperlink->getID();
    $sName = $oHyperlink->getName();
    $sClass = $this->getHyperlinkClass( $oHyperlink, $sSize );
    $sOverlay = $oHyperlink->getOverlay();

    // HTML output
    $sOutput = null;
    $sOutput .= '<DIV ID="URC-'.$sID.'-gui" CLASS="URC-'.$sClass.'">'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-bcg" CLASS="bcg"></DIV>'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-omo" CLASS="omo"></DIV>'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-omd" CLASS="omd"></DIV>'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-ofb" CLASS="ofb"></DIV>'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-ovl" CLASS="ovl">'.( $sOverlay ? '<TABLE CELLSPACING="0"><TR><TD>'.$sOverlay.'</TD></TR></TABLE>' : null ).'</DIV>'."\n";
    $sOutput .= '<DIV ID="URC-'.$sID.'-top" CLASS="top" TITLE="[*] '.$sName.'" ONMOUSEOVER="URC_onMFB(\''.$sID.'\')" ONCLICK="URC_onMFDUB(\''.$sID.'\');'.$oHyperlink->getContentJavascript().'"></DIV>'."\n";
    $sOutput .= '</DIV>'."\n";
    return $sOutput;
  }

}
