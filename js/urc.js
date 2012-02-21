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
 * @package PHP_URC
 * @author Cedric Dufour <http://www.ced-network.net/php-urc>
 * @version @version@
 */


/*
 * AJAX PRIMITIVES
 */
if( !window.XMLHttpRequest ) XMLHttpRequest = function() {
    try{ return new ActiveXObject('MSXML3.XMLHTTP') } catch(e) {}
    try{ return new ActiveXObject('MSXML2.XMLHTTP.3.0') } catch(e) {}
    try{ return new ActiveXObject('Msxml2.XMLHTTP') } catch(e) {}
    try{ return new ActiveXObject('Microsoft.XMLHTTP') } catch(e) {}
    throw new Error('Could not find an XMLHttpRequest alternative')
  }


/*
 * GLOBAL VARIABLES
 */

// UI's backend-defined parameters
var URC_iPollRate=0;
var URC_iRpcTmout=1000;
var URC_iFbTmout=100;

// UI's polling
var URC_iPollSkip=0;

// UI's current control/status
var URC_sCtlId=null;
var URC_sCtlCls=null;
var URC_oCtlReg=null;
var URC_hHglTmout=null;

// UI's repeat (mouse click)
var URC_sClkId=null;
var URC_iClkTim=null;
var URC_hClkTmout=null;
var URC_bRptOn=false;
var URC_hRptTmout=null;

// UI's drag (mouse move)
var URC_bDragOn=false;
var URC_bDragCont=false;
var URC_mDragOrgV=0;
var URC_iDragOrgX=0;
var URC_iDragOrgY=0;
var URC_bDragLstX=0;
var URC_bDragLstY=0;
var URC_bDragMB=false;

// UI's feedback
var URC_sCtlFbId=null;
var URC_sCtlFbCls=null;
var URC_hCtlFbTmout=null;

// RPC (Ajax)
var URC_oRpcAjaxHandler=null;
var URC_hRpcAjaxTmout=null;
var URC_hRpcPollTmout=null;
var URC_sRpcAsyDo=null;
var URC_sRpcAsyId=null;
var URC_mRpcAsyVal=null;


/*
 * FUNCTION:   URC_init
 *
 * SYNOPSIS:   Initializes the URC javascript framework.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_init()
{
  URC_iPollRate = parseInt(document.getElementById('URC-PollRate').value);
  URC_iRpcTmout = parseInt(document.getElementById('URC-RpcTimeout').value);
  URC_sendRpcRequest('init');
}


/*
 * FUNCTION:   URC_onMF
 *
 * SYNOPSIS:   This function is to be called by URC controls to deal with 'onMouseOver' (focus) events.
 *
 * PARAMETERS: sCtlId - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_onMF(sCtlId)
{
  if( URC_bDragOn && sCtlId==URC_sCtlId ) URC_bDragMB=false;
  if( URC_sCtlId!=null ) return;
  URC_sCtlId=sCtlId;
  URC_sCtlCls=document.getElementById('URC-'+URC_sCtlId+'-cls').value;
  if( document.getElementById('URC-'+URC_sCtlId+'-reg') ) URC_oCtlReg=document.getElementById('URC-'+URC_sCtlId+'-reg');
  if( URC_Theme_onMouseOver ) URC_Theme_onMouseOver(URC_sCtlCls,URC_sCtlId);
}


/*
 * FUNCTION:   URC_onMB
 *
 * SYNOPSIS:   This function is to be called by URC controls to deal with 'onMouseOut' (blur) events.
 *
 * PARAMETERS: sCtlId - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_onMB(sCtlId)
{
  if( sCtlId!=URC_sCtlId ) return;
  if( URC_bDragOn )
    URC_bDragMB=true;
  else
  {
    if( !URC_bDragMB ) URC_onMU();
    if( URC_Theme_onMouseOut ) URC_Theme_onMouseOut(URC_sCtlCls,URC_sCtlId);
    URC_sCtlId=null;
    URC_sCtlCls=null;
    URC_oCtlReg=null;
  }

}


/*
 * FUNCTION:   URC_onMFB
 *
 * SYNOPSIS:   This function is to be called to simulate 'onMouseOver' (focus) and 'onMouseOut' (blur) events
 *
 * PARAMETERS: sCtlId  - control's identifier (ID)
 *
 * RETURNS:    n/a
 */
function URC_onMFB(sCtlId)
{
  if( URC_hHglTmout ) { window.clearTimeout( URC_hHglTmout ); URC_hHglTmout=null; URC_onMB(URC_sCtlId); }
  URC_onMF(sCtlId);
  URC_hHglTmout = window.setTimeout('URC_hHglTmout=null;URC_onMB(\''+sCtlId+'\');',250);
}


/*
 * FUNCTION:   URC_onMD
 *
 * SYNOPSIS:   This function is to be called by URC controls to deal with 'onMouseDown' events.
 *
 * PARAMETERS: sCtlId  - control's identifier (ID)
 *             iClkTyp - control's click interaction type (null=none, 0=set, 1=toggle, 2=invert, 3=adjust, 4=repeat, 5=drag)
 *             fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *             iRptDly - control's repeat delay [milliseconds]
 *
 * RETURNS:    n/a
 */
function URC_onMD(sCtlId,iClkTyp,fModVal,iRptRte,iRptDly)
{
  if( sCtlId!=URC_sCtlId ) return;
  switch( iClkTyp )
  {
  case 0:
    mNewVal=parseFloat(URC_oCtlReg.value);
    URC_setControlValue(mNewVal,URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
    break;

  case 1:
    mNewVal=( parseFloat(URC_oCtlReg.value)>0 ? 0 : 1 );
    URC_setControlValue(mNewVal,URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
    break;

  case 2:
    mNewVal=1-parseFloat(URC_oCtlReg.value);
    URC_setControlValue(mNewVal,URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
    break;

  case 3:
    mNewVal=parseFloat(URC_oCtlReg.value)+( fModVal!=null ? fModVal : 0 );
    URC_setControlValue(mNewVal,URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
    break;

  case 4:
    URC_repeatStart(fModVal,iRptRte,iRptDly);
    break;

  case 5:
    URC_dragStart();
    break;
  }
  if( URC_Theme_onMouseDown ) URC_Theme_onMouseDown(URC_sCtlCls,URC_sCtlId);
}


/*
 * FUNCTION:   URC_onMU
 *
 * SYNOPSIS:   This function is to be called for any 'onMouseUp' event.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_onMU()
{
  URC_repeatStop();
  URC_dragStop();
  if( URC_sCtlId==null ) return;
  if( URC_Theme_onMouseUp ) URC_Theme_onMouseUp(URC_sCtlCls,URC_sCtlId);
}


/*
 * FUNCTION:   URC_onMDU
 *
 * SYNOPSIS:   This function is to be called to simulate delayed 'onMouseDown' and 'onMouseUp' events
 *
 * PARAMETERS: sCtlId  - control's identifier (ID)
 *             iClkTyp - control's click interaction type (null=none, 0=set, 1=toggle, 2=invert, 3=adjust, 4=repeat, 5=drag)
 *             fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *             iClkDly - control's double-click delay [milliseconds]
 *
 * RETURNS:    n/a
 */
function URC_onMDU(sCtlId,iClkTyp,fModVal,iRptRte,iClkDly)
{
  URC_onMC(sCtlId,iClkTyp,fModVal,iRptRte,iClkDly,false);
}


/*
 * FUNCTION:   URC_onMFDUB
 *
 * SYNOPSIS:   This function is to be called to simulate delayed 'onMouseOver' (focus),
 *             'onMouseDown', 'onMouseUp' and 'onMouseOut' (blur) events
 *
 * PARAMETERS: sCtlId  - control's identifier (ID)
 *             iClkTyp - control's click interaction type (null=none, 0=set, 1=toggle, 2=invert, 3=adjust, 4=repeat, 5=drag)
 *             fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *             iClkDly - control's double-click delay [milliseconds]
 *
 * RETURNS:    n/a
 */
function URC_onMFDUB(sCtlId,iClkTyp,fModVal,iRptRte,iClkDly)
{
  URC_onMC(sCtlId,iClkTyp,fModVal,iRptRte,iClkDly,true);
}


/*
 * FUNCTION:   URC_onMFDUB
 *
 * SYNOPSIS:   This function is to be called to simulate generic 'onClick' events, namely
 *             'onMouseOver' (focus, optionally), 'onMouseDown', 'onMouseUp' and 'onMouseOut' (blur, optionally)
 *
 * PARAMETERS: sCtlId  - control's identifier (ID)
 *             iClkTyp - control's click interaction type (null=none, 0=set, 1=toggle, 2=invert, 3=adjust, 4=repeat, 5=drag)
 *             fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *             iClkDly - control's double-click delay [milliseconds]
 *             bOnMDB  - simulate 'onMouseOver' (focus) and 'onMouseOut' (blur) events
 *
 * RETURNS:    n/a
 */
function URC_onMC(sCtlId,iClkTyp,fModVal,iRptRte,iClkDly,bOnMFB)
{
  if( bOnMFB )
  {
    if( URC_hHglTmout ) { window.clearTimeout( URC_hHglTmout ); URC_hHglTmout=null; }
    URC_onMF(sCtlId);
  }
  if( URC_sCtlId==null ) return;
  bDblClk=(URC_hClkTmout!=null);
  if( URC_hClkTmout ) { window.clearTimeout( URC_hClkTmout ); URC_hClkTmout=null; }
  if( URC_sCtlId!=sCtlId )
  {
    URC_onMU();
    if( bOnMFB ) URC_onMB(URC_sCtlId);
  }
  else if( iClkTyp==4 )
  {
    if( bDblClk )
      URC_repeatStart(fModVal,iRptRte,iRptRte);
    else if( URC_bRptOn )
    {
      URC_onMU();
      if( bOnMFB ) URC_onMB(sCtlId);
    }
    else
    {
      URC_onMD(sCtlId,3,fModVal);
      URC_hClkTmout = window.setTimeout('URC_hClkTmout=null;URC_onMU();'+(bOnMFB?'URC_onMB(\''+sCtlId+'\');':null),iClkDly);
    }
  }
  else
  {
    if( iClkTyp>3 ) iClkTyp=3;
    URC_onMD(sCtlId,iClkTyp,fModVal);
    URC_hClkTmout = window.setTimeout('URC_hClkTmout=null;URC_onMU();'+(bOnMFB?'URC_onMB(\''+sCtlId+'\');':null),150);
  }
}


/*
 * FUNCTION:   URC_repeatStart
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework
 *             when a repeat (mouse down) event starts.
 *
 * PARAMETERS: fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *             iRptDly - control's repeat delay [milliseconds]
 *
 * RETURNS:    n/a
 */
function URC_repeatStart(fModVal,iRptRte,iRptDly)
{
  if( URC_bRptOn || URC_sCtlId==null ) return;
  URC_bRptOn=true;
  fOldVal=parseFloat(URC_oCtlReg.value);
  fNewVal=URC_setControlValue(fOldVal+( fModVal!=null ? fModVal : 0 ),URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
  if( fModVal==null || fNewVal!=fOldVal ) URC_hRptTmout = window.setTimeout( 'URC_hRptTmout=null;URC_repeatEvent('+( fModVal!=null ? fModVal : 'null' )+','+iRptRte+');', iRptDly );
  else URC_repeatStop();
}


/*
 * FUNCTION:   URC_repeatStop
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework
 *             when a repeat (mouse move) event stops.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_repeatStop()
{
  if( URC_sCtlId==null ) return;
  if( URC_hRptTmout ) { window.clearTimeout( URC_hRptTmout ); URC_hRptTmout=null; }
  URC_bRptOn=false;
}


/*
 * FUNCTION:   URC_repeatEvent
 *
 * SYNOPSIS:   This function is called whenever a repeat timeout occurs.
 *
 * PARAMETERS: fModVal - control's modification value
 *             iRptRte - control's repeat rate [milliseconds]
 *
 * RETURNS:    n/a
 */
function URC_repeatEvent(fModVal,iRptRte)
{
  if( URC_sCtlId==null ) return;
  if( URC_oRpcAjaxHandler != null ) // don't repeat faster than RPC backend can handle!
    URC_hRptTmout = window.setTimeout( 'URC_hRptTmout=null;URC_repeatEvent('+fModVal+','+iRptRte+');', iRptRte );
  else
  {
    fOldVal=parseFloat(URC_oCtlReg.value);
    fNewVal=URC_setControlValue(fOldVal+( fModVal!=null ? fModVal : 0 ),URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
    if( fModVal==null || fNewVal!=fOldVal ) URC_hRptTmout = window.setTimeout( 'URC_hRptTmout=null;URC_repeatEvent('+( fModVal!=null ? fModVal : 'null' )+','+iRptRte+');', iRptRte );
    else URC_repeatStop();
  }
}


/*
 * FUNCTION:   URC_dragStart
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework
 *             when a drag (mouse move) event starts.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_dragStart()
{
  if( URC_bDragOn || URC_sCtlId==null ) return;
  URC_bDragOn=true;
  URC_bDragCont=false;
  URC_bDragMB=false;
  document.onmousemove=URC_dragEvent;
}


/*
 * FUNCTION:   URC_dragStop
 *
 * SYNOPSIS:   This function is called by the URC generic javascript framework
 *             when a drag (mouse move) event stops.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_dragStop()
{
  if( URC_sCtlId==null ) return;
  document.onmousemove=null;
  URC_bDragOn=false;
  URC_mDragOrgV=0;
  URC_iDragOrgX=0;
  URC_iDragOrgY=0;
  URC_bDragLstX=0;
  URC_bDragLstY=0;
  URC_bDragCont=false;
  if( URC_bDragMB )
  {
    URC_bDragMB=false;
    URC_onMB(URC_sCtlId);
  }
}


/*
 * FUNCTION:   URC_dragEvent
 *
 * SYNOPSIS:   This function is called whenever a drag (mouse move) event happens.
 *
 * PARAMETERS: event - browser supplied event object
 *
 * RETURNS:    n/a
 */
function URC_dragEvent(event)
{
  if( !event ) event=window.event;
  if( !URC_bDragCont )
  {
    URC_mDragOrgV=parseFloat(URC_oCtlReg.value);
    URC_iDragOrgX=URC_bDragLstX=event.clientX;
    URC_iDragOrgY=URC_bDragLstY=event.clientY;
    URC_bDragCont=true;
    return;
  }
  mCtlVal=URC_Theme_dragControlValue(URC_sCtlCls,URC_mDragOrgV,event.clientX-URC_iDragOrgX,event.clientY-URC_iDragOrgY,parseFloat(URC_oCtlReg.value),event.clientX-URC_bDragLstX,event.clientY-URC_bDragLstY);
  mCtlVal=URC_setControlValue(mCtlVal,URC_sCtlId,URC_sCtlCls,URC_oCtlReg,true);
  URC_bDragLstX=event.clientX;
  URC_bDragLstY=event.clientY;
}


/*
 * FUNCTION:   URC_setControlFeedback
 *
 * SYNOPSIS:   This function is called whenever a RPC response has been received for a given control.
 *
 * PARAMETERS: bCtlFb  - control's feedback status (on/off)
 *             sCtlId  - control's identifier (ID)
 *             sCtlCls - control's class (without leading 'URC-' prefix) [OPTIONAL]
 *
 * RETURNS:    n/a
 */
function URC_setControlFeedback(bCtlFb,sCtlId,sCtlCls)
{
  if( !URC_Theme_onFeedback ) return;
  if( sCtlCls==null ) sCtlCls=document.getElementById('URC-'+sCtlId+'-cls').value;
  if( URC_hCtlFbTmout ) { window.clearTimeout( URC_hCtlFbTmout ); URC_hCtlFbTmout=null; URC_Theme_onFeedback(URC_sCtlFbCls,URC_sCtlFbId,false); }
  URC_Theme_onFeedback(sCtlCls,sCtlId,bCtlFb);
  if( bCtlFb )
  {
    URC_sCtlFbCls = sCtlCls;
    URC_sCtlFbId = sCtlId;
    URC_URC_hCtlFbTmout = window.setTimeout( "URC_hCtlFbTmout=null;URC_Theme_onFeedback('"+sCtlCls+"','"+sCtlId+"',false);", URC_iFbTmout );
  }
}


/*
 * FUNCTION:   URC_setControlValue
 *
 * SYNOPSIS:   This function is called whenever the value of a control must be set/modified.
 *
 * PARAMETERS: mCtlVal - control's value (when drag was started)
 *             sCtlId  - control's identifier (ID)
 *             sCtlCls - control's class (without leading 'URC-' prefix) [OPTIONAL]
 *             oCtlReg - control's input element (containing the control's value) [OPTIONAL]
 *             bRpcSnd - send corresponding RPC request
 *
 * RETURNS:    n/a
 */
function URC_setControlValue(mCtlVal,sCtlId,sCtlCls,oCtlReg,bRpcSnd)
{
  if( sCtlCls==null ) sCtlCls=document.getElementById('URC-'+sCtlId+'-cls').value;
  if( oCtlReg==null ) oCtlReg=document.getElementById('URC-'+sCtlId+'-reg');
  mCtlVal=URC_Theme_setControlValue(sCtlCls,sCtlId,mCtlVal);
  if( bRpcSnd ) URC_sendRpcRequest('set',sCtlId,mCtlVal);
  oCtlReg.value=mCtlVal.toString();
  return mCtlVal;
}


/*
 * FUNCTION:   URC_poll
 *
 * SYNOPSIS:   Starts/stops the regular controls polling mechanism.
 *
 * PARAMETERS: bStart - Start polling status (true=start; false=stop)
 *
 * RETURNS:    n/a
 */
function URC_poll(bStart)
{
  if( URC_hRpcPollTmout ) { window.clearTimeout( URC_hRpcPollTmout ); URC_hRpcPollTmout=null; }
  if( bStart && URC_iPollRate>0 ) { URC_hRpcPollTmout = window.setTimeout( "URC_hRpcPollTmout=null;URC_sendRpcRequest('poll');", URC_iPollRate ); URC_iPollSkip=0; }
}


/*
 * FUNCTION:   URC_pollSkip
 *
 * SYNOPSIS:   Skip the next polling events.
 *
 * PARAMETERS: iCount - Quantity of polling events to skip
 *
 * RETURNS:    n/a
 */
function URC_pollSkip(iCount)
{
  if( iCount==null ) iCount=1;
  if( iCount<0 ) iCount=0;
  URC_iPollSkip=iCount;
}


/*
 * FUNCTION:   URC_sendRpcRequest
 *
 * SYNOPSIS:   Sends an asynchronous RPC (Ajax) request.
 *
 * PARAMETERS: sRpcDo  - RPC method
 *             sRpcId  - Control identifiers (IDs), comma-separated
 *             mRpcVal - Control values, comma-separated
 *
 * RETURNS:    n/a
 */
function URC_sendRpcRequest(sRpcDo,sRpcId,mRpcVal)
{
  // Check ongoing request (do not allow concurrent RPC calls)
  if( URC_oRpcAjaxHandler!=null )
  {
    // Save asynchronous RPC call
    URC_sRpcAsyDo=sRpcDo;
    URC_sRpcAsyId=sRpcId;
    URC_mRpcAsyVal=mRpcVal;
    return;
  }

  // Clear asynchronous RPC call
  URC_sRpcAsyDo=null;
  URC_sRpcAsyId=null;
  URC_mRpcAsyVal=null;

  // Cancel controls polling
  URC_poll(false);

  // Build and send RPC request
  sRpcUrl=document.getElementById('URC-RpcUrl').value;
  URC_oRpcAjaxHandler = new XMLHttpRequest();
  URC_oRpcAjaxHandler.onreadystatechange = URC_processRpcResponse;
  URC_oRpcAjaxHandler.open( 'GET', sRpcUrl+'?do='+sRpcDo+(sRpcId!=null?'&id='+sRpcId:'')+(mRpcVal!=null?'&value='+mRpcVal:''), true );
  URC_oRpcAjaxHandler.send( null );

  // Request/response timeout (reset RPC handler)
  URC_hRpcAjaxTmout = window.setTimeout( "URC_oRpcAjaxHandler=null;URC_hRpcAjaxTmout=null;", URC_iRpcTmout );
}

/*
 * FUNCTION:   URC_processRpcResponse
 *
 * SYNOPSIS:   Receives and process an asynchronous RPC (Ajax) response.
 *
 * PARAMETERS: n/a
 *
 * RETURNS:    n/a
 */
function URC_processRpcResponse()
{
  try
  {
    // Check response state/status
    if( URC_oRpcAjaxHandler == null ) return;
    if( URC_oRpcAjaxHandler.readyState != 4 ) return;
    if( URC_oRpcAjaxHandler.status != 200 ) return;
    if( URC_hRpcAjaxTmout ) { window.clearTimeout( URC_hRpcAjaxTmout ); URC_hRpcAjaxTmout=null; }

    // Retrieve XML response content
    oXmlResponse = URC_oRpcAjaxHandler.responseXML;
    if( !oXmlResponse )
      throw new Error("No XML response");

    // Parse XML response
    if( oXmlResponse.documentElement.nodeName!='URC' || !oXmlResponse.documentElement.hasChildNodes() )
      throw new Error("Invalid XML response");
  }
  catch(e)
  {
    URC_oRpcAjaxHandler = null;
    if( URC_hRpcAjaxTmout ) { window.clearTimeout( URC_hRpcAjaxTmout ); URC_hRpcAjaxTmout=null; }
    return;
  }

  // Loop through level-1 nodes
  iL1Max=oXmlResponse.documentElement.childNodes.length;
  for( iL1=0; iL1<iL1Max; iL1++ )
  {
    oNodeL1=oXmlResponse.documentElement.childNodes[iL1];
    switch( oNodeL1.nodeName )
    {
      // Handle 'Control' elements
    case 'Control':
      // Check control
      sCtlId=oNodeL1.getAttribute('Id');
      if( document.getElementById('URC-'+sCtlId+'-reg')==null ) continue;

      // Adjust control
      mCtlVal=null;
      sCtlMsg=null;
      sCtlErr=null;
      iMax=oXmlResponse.documentElement.childNodes.length;

      // Loop through level-2 nodes
      if( oNodeL1.hasChildNodes() )
      {
        iL2Max=oNodeL1.childNodes.length;
        for( iL2=0; iL2<iL2Max; iL2++ )
        {
          oNodeL2=oNodeL1.childNodes[iL2];
          if( oNodeL2.hasChildNodes )
          {
            switch( oNodeL2.nodeName )
            {
            case 'Value': mCtlVal=oNodeL2.firstChild.nodeValue; break;
            case 'Message': sCtlMsg=oNodeL2.firstChild.nodeValue; break;
            case 'Error': sCtlErr=oNodeL2.firstChild.nodeValue; break;
            }
          }
        }
        if( mCtlVal!=null )
        {
          // Set control value (for all controls, except the current control when an asynchronous RPC call is queued)
          if( sCtlId!=URC_sCtlId || URC_sRpcAsyDo==null ) URC_setControlValue(mCtlVal,sCtlId);
        }
      }

      // Provide feedback
      if( sCtlId==URC_sCtlId ) URC_setControlFeedback(true,sCtlId);
      break;

      // Handle 'Error' elements
    case 'Error':
      window.alert(oNodeL1.firstChild.nodeValue);
      break;
    }
  }

  // Clear request
  URC_oRpcAjaxHandler = null;

  // Check existing asynchronous RPC call
  if( URC_sRpcAsyDo!=null ) URC_sendRpcRequest(URC_sRpcAsyDo,URC_sRpcAsyId,URC_mRpcAsyVal);

  // Re-schedule controls polling
  else
  {
    if( URC_iPollSkip>0 ) --URC_iPollSkip;
    else URC_poll(true);
  }
}
