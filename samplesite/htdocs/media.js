//=====================================================================||
//               NOP Design Media Cycling Script                       ||
//                                                                     ||
// For more information on SmartSystems, or how NOPDesign can help you ||
// Please visit us on the WWW at http://www.nopdesign.com              ||
//                                                                     ||
// The media cycler is available as freeware from NOP Design, under the||
// GNU Public License.  You must keep this comment unchanged in your   ||
// code.  For more information contact Scott@NopDesign.com             ||
//                                                                     ||
// MediaCyle Script Module, V.1.0.0                                    ||
//=====================================================================||
//---------------------------------------------------------------------||
// INTERNAL GLOBAL VARIABLES                                           ||
// PURPOSE:     Internal use only, do not modify.                      ||
//---------------------------------------------------------------------||
var xMediaContent  = new Array();
var xMediaImage    = new Image;
var iCurrentImage  = 0;
var iInternalCount = 5000;
var xTimerHandle   = null;
var bTimerRunning  = false;


//---------------------------------------------------------------------||
// USER DEFINED VARIABLES                                              ||
// PURPOSE:     Set these variables to customize your script           ||
//              TimeInSecondsBetweenCycles - Seconds to wait before    ||
//                                           cycling to the next image ||
//              LoadInNewWindow            - When true, the link will  ||
//                                           be opened in a new window ||
//                                           otherwise, it will load in||
//                                           the current window.       ||
//              WrapAtEnd                  - Wraps to start at end.    ||
//                                                                     ||
//              MEDIAIMAGE                - Needs defined in your HTML ||
//                                           page as an image name.    ||
//---------------------------------------------------------------------||
var TimeInSecondsBetweenCycles = 2.5;
var LoadInNewWindow = false;
var WrapAtEnd       = true;


//---------------------------------------------------------------------||
// MEDIA FILES TO LOAD                                                 ||
// PURPOSE:     This is the image that you wish displayed, followed by ||
//              the URL to link to.  All images should be on even      ||
//              numbered lines, URLS on odd.  Pay careful attention to ||
//              increment the counter inside of the [] symbols when you||
//              add more images.  There is no maximum to the amount of ||
//              images you can load.                                   ||
//---------------------------------------------------------------------||
xMediaContent[0] = "./images/feature1.jpg";
xMediaContent[1] = "vegetable.html";

xMediaContent[2] = "./images/feature2.jpg";
xMediaContent[3] = "fruit.html";

xMediaContent[4] = "./images/feature3.jpg";
xMediaContent[5] = "fruit.html";

xMediaContent[6] = "./images/feature4.jpg";
xMediaContent[7] = "vegetable.html";

//---------------------------------------------------------------------||
// FUNCTION:    MediaStop                                              ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Moves the media                                        ||
//---------------------------------------------------------------------||
function MediaStop()
{
    if( bTimerRunning )
        clearTimeout( xTimerHandle );

    bTimerRunning = false;
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaGoBack                                            ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Moves the media                                        ||
//---------------------------------------------------------------------||
function MediaGoBack()
{
    MediaStop();

    if (WrapAtEnd)
      (iCurrentImage == 0) ? iCurrentImage = (xMediaContent.length - 2) : iCurrentImage-=2;
    else
      (iCurrentImage == 0) ? iCurrentImage = 0 : iCurrentImage-=2;

    document.MEDIAIMAGE.src = xMediaContent[iCurrentImage];
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaGoForward                                         ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Moves the media                                        ||
//---------------------------------------------------------------------||
function MediaGoForward()
{
    MediaStop();

    if (WrapAtEnd)
      (iCurrentImage == (xMediaContent.length - 2)) ? iCurrentImage = 0 : iCurrentImage+=2;
    else
      (iCurrentImage == (xMediaContent.length - 2)) ? iCurrentImage = iCurrentImage : iCurrentImage+=2;

    document.MEDIAIMAGE.src = xMediaContent[iCurrentImage];
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaInternalCycle                                     ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Internal media cycle routine                           ||
//---------------------------------------------------------------------||
function MediaInternalCycle()
{
    (iCurrentImage == (xMediaContent.length - 2)) ? iCurrentImage = 0 : iCurrentImage+=2;
    if( document.MEDIAIMAGE ) document.MEDIAIMAGE.src = xMediaContent[iCurrentImage];

    xTimerHandle   = setTimeout("MediaInternalCycle()", iInternalCount);
    bTimerRunning  = true;
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaStart                                             ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Starts the media cycling. Call with 'OnLoad' from body ||
//              tag to have an image start cycling on page load.       ||
//---------------------------------------------------------------------||
function MediaStart()
{
    iInternalCount = TimeInSecondsBetweenCycles * 1000;
    MediaStop();
    MediaInternalCycle();
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaClick                                             ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Clicks straight through to your media URL              ||
//---------------------------------------------------------------------||
function MediaClick()
{
    if( LoadInNewWindow ) {
        URL = xMediaContent[iCurrentImage+1];
        win=window.open(URL,"NewWindow","");
        if (!win.opener)win.opener=self;
    } else
        document.location.href = xMediaContent[iCurrentImage+1];
}


//---------------------------------------------------------------------||
// FUNCTION:    MediaClickWithInfo                                     ||
// PARAMETERS:                                                         ||
// RETURNS:                                                            ||
// PURPOSE:     Clicks to the URL you have listing in your media, plus ||
//              the value passed in as 'ADDITIONAL INFO'.  This is     ||
//              useful if you have defined your media URL's as         ||
//              directories, and have multiple files in those directori||
//---------------------------------------------------------------------||
function MediaClickWithInfo( AdditionalInfo )
{
    if( LoadInNewWindow ) {
        URL = xMediaContent[iCurrentImage+1] + AdditionalInfo;
        win=window.open(URL,"NewWindow","");
        if (!win.opener)win.opener=self;
    } else
        document.location.href = (xMediaContent[iCurrentImage+1] + AdditionalInfo);
}


