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
  return currVal;
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
      document.getElementById('URC-'+sCtlId+'-on').style.display='block';
      document.getElementById('URC-'+sCtlId+'-off').style.display='none';
    }
    else
    {
      document.getElementById('URC-'+sCtlId+'-off').style.display='block';
      document.getElementById('URC-'+sCtlId+'-on').style.display='none';
    }
    break;

  case 'rb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    iMrgLft=parseInt(Math.round(2-2*Math.sin(pi*(0.165+1.67*mCtlVal))));
    iMrgTop=parseInt(Math.round(13+2*Math.cos(pi*(0.165+1.67*mCtlVal))));
    document.getElementById('URC-'+sCtlId+'-ctl').style.margin=iMrgTop+'px '+(4-iMrgLft)+'px 0px '+iMrgLft+'px';
    break;

  case 'rb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    iMrgLft=parseInt(Math.round(7-7*Math.sin(pi*(0.165+1.67*mCtlVal))));
    iMrgTop=parseInt(Math.round(18+7*Math.cos(pi*(0.165+1.67*mCtlVal))));
    document.getElementById('URC-'+sCtlId+'-ctl').style.margin=iMrgTop+'px '+(14-iMrgLft)+'px 0px '+iMrgLft+'px';
    break;

  case 'rb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    iMrgLft=parseInt(Math.round(12-12*Math.sin(pi*(0.165+1.67*mCtlVal))));
    iMrgTop=parseInt(Math.round(23+12*Math.cos(pi*(0.165+1.67*mCtlVal))));
    document.getElementById('URC-'+sCtlId+'-ctl').style.margin=iMrgTop+'px '+(24-iMrgLft)+'px 0px '+iMrgLft+'px';
    break;

  case 'hs-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.width=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.width=(44-mCtlPos)+'px';
    break;

  case 'hb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.width=(50-mCtlPos)+'px';
    break;

  case 'hs-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.width=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.width=(54-mCtlPos)+'px';
    break;

  case 'hb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.width=(60-mCtlPos)+'px';
    break;

  case 'hs-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.width=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.width=(104-mCtlPos)+'px';
    break;

  case 'hb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.width=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.width=(110-mCtlPos)+'px';
    break;

  case 'vs-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.height=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.height=(44-mCtlPos)+'px';
    break;

  case 'vb-xs':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(40*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.height=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.height=(50-mCtlPos)+'px';
    break;

  case 'vs-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.height=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.height=(54-mCtlPos)+'px';
    break;

  case 'vb-s':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(50*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.height=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.height=(60-mCtlPos)+'px';
    break;

  case 'vs-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-pre-ctl').style.height=(4+mCtlPos)+'px';
    document.getElementById('URC-'+sCtlId+'-post-ctl').style.height=(104-mCtlPos)+'px';
    break;

  case 'vb-m':
    mCtlVal=parseFloat(mCtlVal); if( mCtlVal<0 ) mCtlVal=0; else if( mCtlVal>1 ) mCtlVal=1;
    mCtlPos=parseInt(100*mCtlVal);
    document.getElementById('URC-'+sCtlId+'-hgl').style.height=mCtlPos+'px';
    document.getElementById('URC-'+sCtlId+'-post-hgl').style.height=(110-mCtlPos)+'px';
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
