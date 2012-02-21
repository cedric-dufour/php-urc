/* INDENTING (emacs/vi): -*- mode:java; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab: */

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
 * @author Cedric Dufour <http://www.ced-network.net/php-urc>
 * @version @version@
 */

//document.getElementById('debugging').attributes['value'].nodeValue='DEBUGGING';


/*
 * FUNCTION:   URC_Theme_onMouseOver
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework when
 *             a 'onMouseOver' (focus) event occurs on a control.
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_Theme_onMouseOver(sCtlCls,sCtlId)
{
  switch( sCtlCls.substr(0,2) )
  {
  case 'rb':
  case 'hs':
  case 'vs':
    document.getElementById('URC-'+sCtlId+'-omo').style.visibility='visible';
    document.getElementById('URC-'+sCtlId+'-ctl-omo').style.visibility='visible';
    break;

  case 'pb':
  case 'sb':
  case 'hb':
  case 'vb':
  case 'tx':
    document.getElementById('URC-'+sCtlId+'-omo').style.visibility='visible';
    break;
  }
}

/*
 * FUNCTION:   URC_Theme_onMouseOut
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework when
 *             a 'onMouseOut' (blur) event occurs on a control.
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_Theme_onMouseOut(sCtlCls,sCtlId)
{
  switch( sCtlCls.substr(0,2) )
  {
  case 'rb':
  case 'hs':
  case 'vs':
    document.getElementById('URC-'+sCtlId+'-omo').style.visibility='hidden';
    document.getElementById('URC-'+sCtlId+'-ctl-omo').style.visibility='hidden';
    break;

  case 'pb':
  case 'sb':
  case 'hb':
  case 'vb':
  case 'tx':
    document.getElementById('URC-'+sCtlId+'-omo').style.visibility='hidden';
    break;
  }
}

/*
 * FUNCTION:   URC_Theme_onMouseDown
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework when
 *             a 'onMouseDown' event occurs on a control.
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_Theme_onMouseDown(sCtlCls,sCtlId)
{
  switch( sCtlCls.substr(0,2) )
  {
  case 'rb':
  case 'hs':
  case 'vs':
    document.getElementById('URC-'+sCtlId+'-omd').style.visibility='visible';
    document.getElementById('URC-'+sCtlId+'-ctl-omd').style.visibility='visible';
    break;

  case 'pb':
  case 'sb':
  case 'hb':
  case 'vb':
  case 'tx':
    document.getElementById('URC-'+sCtlId+'-omd').style.visibility='visible';
    break;
  }
}

/*
 * FUNCTION:   URC_Theme_onMouseUp
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework when
 *             a 'onMouseUp' event occurs on a control.
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_Theme_onMouseUp(sCtlCls,sCtlId)
{
  switch( sCtlCls.substr(0,2) )
  {
  case 'rb':
  case 'hs':
  case 'vs':
    document.getElementById('URC-'+sCtlId+'-omd').style.visibility='hidden';
    document.getElementById('URC-'+sCtlId+'-ctl-omd').style.visibility='hidden';
    break;

  case 'pb':
  case 'sb':
  case 'hb':
  case 'vb':
  case 'tx':
    document.getElementById('URC-'+sCtlId+'-omd').style.visibility='hidden';
    break;
  }
}

/*
 * FUNCTION:   URC_Theme_onFeedback
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework when
 *             feedback (RPC response) is available for a control.
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *             bCtlFb  - feedback status (on/off)
 *
 * RETURNS:    n/a
 */
function URC_Theme_onFeedback(sCtlCls,sCtlId,bCtlFb)
{
  switch( sCtlCls.substr(0,2) )
  {
  case 'rb':
  case 'hs':
  case 'vs':
    document.getElementById('URC-'+sCtlId+'-ctl-ofb').style.visibility=(bCtlFb?'visible':'hidden');
    break;

  case 'pb':
  case 'sb':
    document.getElementById('URC-'+sCtlId+'-ofb').style.visibility=(bCtlFb?'visible':'hidden');
    break;
  }
}

/*
 * FUNCTION:   URC_Theme_dragControlValue
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework to
 *             retrieve the control's new value after a drag (mouse move) event.
 *
 * PARAMETERS: sCtlCls   - control's class (without leading 'URC-' prefix)
 *             initVal  - control's initial value (when drag was started)
 *             dragFulX - full horizonal offset (relative to when drag was started)
 *             dragFulY - full vertical offset (relative to when drag was started)
 *             currVal  - control's current value (on last drag event)
 *             dragRelX - relative horizonal offset (relative to last drag event)
 *             dragRelY - relative vertical offset (relative to last drag event)
 *
 * RETURNS:    The control's new value
 */
function URC_Theme_dragControlValue(sCtlCls,initVal,dragFulX,dragFulY,currVal,dragRelX,dragRelY)
{
  pi=3.141592653589793;
  newVal=0;
  switch( sCtlCls )
  {
  case 'rb-xs':
    newVal=currVal-(dragRelX*Math.cos(pi*(0.165+1.67*currVal))+dragRelY*Math.sin(pi*(0.165+1.67*currVal)))/(6*pi);
    break;

  case 'rb-s':
    newVal=currVal-(dragRelX*Math.cos(pi*(0.165+1.67*currVal))+dragRelY*Math.sin(pi*(0.165+1.67*currVal)))/(14*pi);
    break;

  case 'rb-m':
    newVal=currVal-(dragRelX*Math.cos(pi*(0.165+1.67*currVal))+dragRelY*Math.sin(pi*(0.165+1.67*currVal)))/(22*pi);
    break;

  case 'hs-xs':
  case 'hb-xs':
    newVal=initVal+dragFulX/40;
    break;

  case 'hs-s':
  case 'hb-s':
    newVal=initVal+dragFulX/50;
    break;

  case 'hs-m':
  case 'hb-m':
    newVal=initVal+dragFulX/100;
    break;

  case 'vs-xs':
  case 'vb-xs':
    newVal=initVal-dragFulY/40;
    break;

  case 'vs-s':
  case 'vb-s':
    newVal=initVal-dragFulY/50;
    break;

  case 'vs-m':
  case 'vb-m':
    newVal=initVal-dragFulY/100;
    break;
  }
  return newVal;
}

/*
 * FUNCTION:   URC_Theme_setControlValue
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework to
 *             set the control's value (and adjust all visual elements accordingly).
 *
 * PARAMETERS: sCtlCls - control's class (without leading 'URC-' prefix)
 *             sCtlId  - control's identifier (ID)
 *             mCtlVal - control's value
 *
 * RETURNS:    control's value (validated)
 */
function URC_Theme_setControlValue(sCtlCls,sCtlId,mCtlVal)
{
  pi=3.141592653589793;
  switch( sCtlCls )
  {
  case 'pb-xs':
  case 'pb-s':
  case 'pb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    break;

  case 'sb-xs':
  case 'sb-s':
  case 'sb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    if( mCtlVal>0 )
    {
      document.getElementById('URC-'+sCtlId+'-on').style.visibility='visible';
      document.getElementById('URC-'+sCtlId+'-off').style.visibility='hidden';
    }
    else
    {
      document.getElementById('URC-'+sCtlId+'-off').style.visibility='visible';
      document.getElementById('URC-'+sCtlId+'-on').style.visibility='hidden';
    }
    break;

  case 'rb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    ctlElmt=document.getElementById('URC-'+sCtlId+'-ctl');
    ctlElmt.style.left=parseInt(Math.round(10-2*Math.sin(pi*(0.165+1.67*mCtlVal))))+'px';
    ctlElmt.style.top=parseInt(Math.round(10+2*Math.cos(pi*(0.165+1.67*mCtlVal))))+'px';
    break;

  case 'rb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    ctlElmt=document.getElementById('URC-'+sCtlId+'-ctl');
    ctlElmt.style.left=parseInt(Math.round(15-7*Math.sin(pi*(0.165+1.67*mCtlVal))))+'px';
    ctlElmt.style.top=parseInt(Math.round(15+7*Math.cos(pi*(0.165+1.67*mCtlVal))))+'px';
    break;

  case 'rb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    ctlElmt=document.getElementById('URC-'+sCtlId+'-ctl');
    ctlElmt.style.left=parseInt(Math.round(20-12*Math.sin(pi*(0.165+1.67*mCtlVal))))+'px';
    ctlElmt.style.top=parseInt(Math.round(20+12*Math.cos(pi*(0.165+1.67*mCtlVal))))+'px';
    break;

  case 'hs-xs':
  case 'hb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-top-l').style.width=(10+mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-r'); oCtlElmt.style.left=(10+mCtlPos)+'px'; oCtlElmt.style.width=(50-mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.left=(4+mCtlPos)+'px';
    break;

  case 'hs-s':
  case 'hb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-top-l').style.width=(10+mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-r'); oCtlElmt.style.left=(10+mCtlPos)+'px'; oCtlElmt.style.width=(60-mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.left=(4+mCtlPos)+'px';
    break;

  case 'hs-m':
  case 'hb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-top-l').style.width=(10+mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-r'); oCtlElmt.style.left=(10+mCtlPos)+'px'; oCtlElmt.style.width=(110-mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.left=(4+mCtlPos)+'px';
    break;

  case 'vs-xs':
  case 'vb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-hgl'); oCtlElmt.style.top=(50-mCtlPos)+'px'; oCtlElmt.style.height=mCtlPos+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-u').style.height=(50-mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-d'); oCtlElmt.style.top=(50-mCtlPos)+'px'; oCtlElmt.style.height=(10+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.top=(44-mCtlPos)+'px';
    break;

  case 'vs-s':
  case 'vb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-hgl'); oCtlElmt.style.top=(60-mCtlPos)+'px'; oCtlElmt.style.height=mCtlPos+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-u').style.height=(60-mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-d'); oCtlElmt.style.top=(60-mCtlPos)+'px'; oCtlElmt.style.height=(10+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.top=(54-mCtlPos)+'px';
    break;

  case 'vs-m':
  case 'vb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-hgl'); oCtlElmt.style.top=(110-mCtlPos)+'px'; oCtlElmt.style.height=mCtlPos+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-u').style.height=(110-mCtlPos)+'px';
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-top-d'); oCtlElmt.style.top=(110-mCtlPos)+'px'; oCtlElmt.style.height=(10+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-ctl').style.top=(104-mCtlPos)+'px';
    break;

  case 'tx-xs':
  case 'tx-s':
  case 'tx-m':
    oCtlElmt=document.getElementById('URC-'+sCtlId+'-txt');
    if(oCtlElmt.innerText) oCtlElmt.innerText=mCtlVal;
    else oCtlElmt.firstChild.nodeValue=mCtlVal;
    break;
  }
  return mCtlVal;
}
