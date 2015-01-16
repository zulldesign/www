/**
 * @name          : Joomla HD Video Share
 * @version	  : 3.5.1
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2014 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @abstract      : Contus HD Video Share Component googlead.js file
 * @Creation Date : March 2010
 * @Modified Date : March 2014
 * */

var pagearray=new Array();
var timerout1 ;
var timerout;
var timerout2;
var timerout3;
pageno =0 ;

//postadd
setTimeout('onplayerloaded()',100);
pagearray[0]=folder_path;

function getFlashMovie(movieName)
{
    var isIE = navigator.appName.indexOf("Microsoft") != -1;
    return (isIE) ? window[movieName] : document[movieName];
}

function googleclose()
{
  
    if(document.all)
    {
        document.all.IFrameName.src="";
    }
    else
    {
        document.getElementById("IFrameName").src="";
    }
    document.getElementById('lightm').style.display="none";
    clearTimeout();
}

function onplayerloaded()
{
    pageno=1;
   timerout1 =window.setTimeout('bindpage(0)', 1000);
}

function findPosX(obj)
{
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
            curleft += obj.offsetLeft;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
}

function findPosY(obj)
{
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
            curtop += obj.offsetTop;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
}

function closediv()
{

 document.getElementById('lightm').style.display="none";
 clearTimeout();
 if( ropen!=''){setTimeout('bindpage(0)', ropen); }
}

function bindpage(pageno)
{
    if(document.all)
    {
        document.all.IFrameName.src=pagearray[0];
    }
    else
    {
        document.getElementById("IFrameName").src=pagearray[pageno];
    }

    document.getElementById('closeimgm').style.display="block";
    document.getElementById('lightm').style.display="block";
    if(closeadd !='') setTimeout('closediv()', closeadd);
}