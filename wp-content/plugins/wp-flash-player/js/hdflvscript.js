/*
  Name: WP Flash Player
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/
  Description: hdflv script file.
  Version: 1.3
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

var xmlhttp;
var myarray = [];
var myarray1;
function showUser(str, order) {
    var hdflv_token = document.getElementById('app_wp_token').value;
    var plugin_name = document.getElementById('plugin_name').value;
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    var url = "../"+content_name+"/plugins/"+plugin_name+"/process-sortable.php";
    url = url + "?" + order;
    url = url + "&playid=" + str;
    url = url + "&sid=" + Math.random()+'&hdflv_token='+hdflv_token;
    xmlhttp.onreadystatechange = stateChanged;
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}

function stateChanged() {
    if (xmlhttp.readyState == 4) {
        myarray = xmlhttp.responseText;
        myarray1 = myarray.split(",");
        var length1 = myarray1.length - 1;
        var i = 0;
        for (i = 0; i <= length1; i++) {
            document.getElementById('txtHint[' + myarray1[i] + ']').innerHTML = i;
        }

    }
}

function GetXmlHttpObject() {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }
    if (window.ActiveXObject) {
        // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}

// this function is useful to show or display the  HDFLVPlayer Options div when u click on - or + buttons
function hideContentDives(divIdIs, id) {

    var status = document.getElementById(divIdIs).style.display;
    //  alert(status);
    if (status == 'none') {
        document.getElementById(divIdIs).style.display = 'block';
        divStyleDisplaySet(divIdIs, 0);
        document.getElementById(id).className = 'ui-icon ui-icon-minusthick';
    } else {
        document.getElementById(divIdIs).style.display = 'none';
        divStyleDisplaySet(divIdIs, 1);
        document.getElementById(id).className = 'ui-icon ui-icon-plusthick';
    }
}

function divStyleDisplaySet(IdValue, setValue) {
    var plugin_name = document.getElementById('plugin_name').value;
    var hdflv_token = document.getElementById('app_wp_token').value;
    var xmlhttp;
    var url = "../"+content_name+"/plugins/"+plugin_name+"/process-sortable.php";
    if (IdValue.length == 0) {

        return;
    }
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
    //  alert(xmlhttp.responseText);
    //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
    }

    xmlhttp.open("GET", url + '?updatedisplay=1&IdValue=' + IdValue
        + '&setValue=' + setValue + '&hdflv_token=' + hdflv_token, true);
    xmlhttp.send();
}

function setVideoStatusOff(videoId, status, flag) //click on status image then it exe
{
    //if flag is set 1 then it is playlist status else video status
    var hdflv_token = document.getElementById('app_wp_token').value;
    var plugin_name = document.getElementById('plugin_name').value;
    var xmlhttp;
    var url = "../"+content_name+"/plugins/"+plugin_name+"/process-sortable.php";
    //var statusImgPath = document.getElementById('imagepath').value;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            imgName = (xmlhttp.responseText);

            //alert(imgName);
            // alert(  document.getElementById('status'+videoId).innerHTML);

            document.getElementById('status' + videoId).innerHTML = imgName;
        }
    }
    if (flag) {
        //	alert( url+'?changeplaylistStatus=1&videoId='+videoId+'&status='+status);
        xmlhttp.open("GET", url + '?changeplaylistStatus=1&videoId=' + videoId
            + '&status=' + status + '&hdflv_token=' + hdflv_token, true);//for playlist status
    } else {
        xmlhttp.open("GET", url + '?changeVideoStatus=1&videoId=' + videoId
            + '&status=' + status + '&hdflv_token=' + hdflv_token, true); //for video status
    }

    xmlhttp.send();
} //status fun end hear

/*   manage.php  script                                  */

function savePlaylist(playlistName, mediaId) {
    var name = playlistName.value;
    if(name==''){
        alert("Enter Playlist Name");
        return false;
    }else{
        var pluginUrl = document.getElementById('pluginUrl').value;

        $.ajax({
            type : "GET",
            url : pluginUrl + "/functions.php",
            data : "name=" + name + "&media=" + mediaId,
            success : function(msg) {
                var response = msg.split('##');
                //  alert(msg);
                document.getElementById('playlistchecklist').innerHTML = msg;
            }
        });
        document.getElementById('p_name').value='';
    }
}
//function to find extenstion
function extension(fname)
{
    var pos = fname.lastIndexOf(".");

    var strlen = fname.length;

    if(pos != -1 && strlen != pos+1)
    {
        var ext = fname.split(".");
        var len = ext.length;
        var extension = ext[len-1].toLowerCase();
    }
    else
    {

        extension = "No extension found";

    }

    return extension;

}

/**
 * function to validate during adding video files
 *
 */

function validateInput() {
    document.getElementById('message').style.display = '';
    document.getElementById('message').innerHTML = '';
    var YouTubeUrl = document.getElementById('filepath1').value;
    var CustomUrl = document.getElementById('filepath2').value;
    var HdUrl = document.getElementById('filepath3').value;
    var ThumbUrl = document.getElementById('filepath4').value;
    var ThumbPreviewUrl = document.getElementById('filepath5').value;
    var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
    if (!tomatch.test(CustomUrl) && (CustomUrl != '')) {
        if (document.getElementById('btn3').checked == true){
            document.getElementById('video_url_message').style.display = 'block';
            document.getElementById('video_url_message').innerHTML = 'Please enter valid URL';
            return false;
        }
    } else if(document.getElementById('btn3').checked == true && (CustomUrl != '') ){
        var  extn = extension(CustomUrl);
        if(extn != 'flv' && extn != 'FLV' && extn != 'mp4' && extn != 'MP4' && extn != 'm4v' && extn != 'M4V' && extn != 'mp4v' && extn != 'Mp4v' && extn != 'm4a' && extn != 'M4A' && extn != 'mov' && extn != 'MOV' && extn != 'f4v' && extn != 'F4V')
        {
            document.getElementById('video_url_message').style.display = 'block';
            document.getElementById('video_url_message').innerHTML = "Please enter valid Video Url";
            return false;
        }else{
            document.getElementById('video_url_message').innerHTML = '';
        }
    }

    if (!tomatch.test(HdUrl) && (HdUrl != '')) {
        document.getElementById('videohd_url_message').style.display = 'block';
        document.getElementById('videohd_url_message').innerHTML = 'Please enter valid Hd Url';
        return false;
    }else if((HdUrl != '') ){
        var  extn1 = extension(HdUrl);
        if(extn1 != 'flv' && extn1 != 'FLV' && extn1 != 'mp4' && extn1 != 'MP4' && extn1 != 'm4v' && extn1 != 'M4V' && extn1 != 'mp4v' && extn1 != 'Mp4v' && extn1 != 'm4a' && extn1 != 'M4A' && extn1 != 'mov' && extn1 != 'MOV' && extn1 != 'f4v' && extn1 != 'F4V')
        {
            document.getElementById('videohd_url_message').style.display = 'block';
            document.getElementById('videohd_url_message').innerHTML = "Please enter valid Hd Url";
            return false;
        }else{
            document.getElementById('videohd_url_message').innerHTML = ''  ;
        }
    }

    if (!tomatch.test(ThumbUrl) && (ThumbUrl != '')) {
        document.getElementById('thumb_url_message').style.display = 'block';
        document.getElementById('thumb_url_message').innerHTML = 'Please enter valid Thumb Image Url';
        return false;
    } else if(ThumbUrl != '') {
        var  extn2 = extension(ThumbUrl);
        if(extn2 != 'jpg' && extn2 != 'png' && extn2 != 'jpeg' )
        {
            document.getElementById('thumb_url_message').style.display = 'block';
            document.getElementById('thumb_url_message').innerHTML = 'Please enter valid Thumb Image Url';
            return false;
        }else{
            document.getElementById('thumb_url_message').innerHTML = '';
        }
    }

    if (!tomatch.test(ThumbPreviewUrl) && (ThumbPreviewUrl != '')) {
        document.getElementById('preview_url_message').style.display = 'block';
        document.getElementById('preview_url_message').innerHTML = 'Please enter Preview Image  Url';
        return false;
    }else if(ThumbPreviewUrl != '') {
        var  extn3 = extension(ThumbPreviewUrl);
        if(extn3 != 'jpg' && extn3 != 'png'  && extn3 != 'jpeg')
        {
            document.getElementById('preview_url_message').style.display = 'block';
            document.getElementById('preview_url_message').innerHTML = 'Please enter Preview Image  Url';
            return false;
        }
        else{
            document.getElementById('preview_url_message').innerHTML = ''  ;
        }
    }

    if (!tomatch.test(YouTubeUrl) && (YouTubeUrl != '')) {
        document.getElementById('youtube_url_message').style.display= 'block';
        document.getElementById('youtube_url_message').innerHTML = 'Please enter valid You Tube Url';
        return false;
    }


    if(document.getElementById('btn4').checked == true)
    {
        var streamer_name = document.getElementById('streamname').value;
        document.getElementById('streamerpath-value').value=streamer_name;
        var islivevalue2=(document.getElementById('islive2').checked);
        var tomatch= /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/
        if(streamer_name == ''){
            document.getElementById('streamermessage').style.display = 'block';
            document.getElementById('streamermessage').innerHTML = 'You must provide a streamer path!';
            return false;
        } else if (!tomatch.test(streamer_name)){
            document.getElementById('streamermessage').style.display = 'block';
            document.getElementById('streamermessage').innerHTML = 'Please enter a valid streamer path';
            document.getElementById('streamname').focus();
            return false;
        } else if(islivevalue2==true) {
            document.getElementById('islive-value').value=1;
        } else {
            document.getElementById('islive-value').value=0;
        }
    }
    if ((document.getElementById('btn3').checked == true  || document.getElementById('btn4').checked == true)
        && document.getElementById('filepath2').value == '') {
        document.getElementById('video_url_message').style.display = 'block';
        return false;
    }
    if ((document.getElementById('btn3').checked == true || document.getElementById('btn4').checked == true)
        && document.getElementById('filepath4').value == '') {
        document.getElementById('thumb_url_message').style.display = 'block';
        return false;
    }
    if (document.getElementById('btn2').checked == true
        && document.getElementById('filepath1').value == '') {
        document.getElementById('youtube_url_message').style.display = 'block';
        return false;
    }
    if (document.getElementById('btn1').checked == true
        && document.getElementById('f1-upload-form').style.display != 'none') {
        document.getElementById('upload_video_message').style.display = 'block';
        document.getElementById('upload_video_message').innerHTML = 'Upload Video';
        return false;
    }
    if (document.getElementById('btn1').checked == true
        && document.getElementById('thumbimageform-value').value == '') {
        document.getElementById('upload_thumb_message').style.display = 'block';
        document.getElementById('upload_thumb_message').innerHTML = 'Upload Thumb Image';
        return false;
    }


    var titlename = document.getElementById('name').value;
    titlename = titlename.trim();
    if (titlename == '') {
        document.getElementById('Errormsgname').innerHTML = 'Please enter the title for video ';
        document.getElementById('name').focus();
        return false;
    }
    var check_box = document.getElementsByTagName('input');
    for (var i = 0; i < check_box.length; i++)
    {
        if (check_box[i].type == 'checkbox')
        {
            if (check_box[i].checked) {
                return true
            }
        }
    }
    document.getElementById('jaxcat').innerHTML = 'Select any playlist for your video';
    return false;
}

/**
 * function to validate during editing video files
 *
 */

function edtValidate() {

    var edtVideoTitle = document.getElementById('act_name').value;
    var videoUrl = document.getElementById('act_filepath').value;
    var hdUrl = document.getElementById('act_hdpath').value;
    var thumbimgUrl = document.getElementById('act_image').value;
    var previewimgUrl = document.getElementById('act_opimg').value;
    var linkUrl = document.getElementById('act_link').value;
    var edit_thumb = document.getElementById('edit_thumb').value;

    var regexp = /^(((ht|f){1}((tp|tps):[/][/]){1}))[-a-zA-Z0-9@:%_\+.~#!?&//=]+$/;

    var streamer_name = document.getElementById('streamname').value;
    document.getElementById('streamerpath-value').value=streamer_name;
    var tomatch= /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/
    if(streamer_name == ''){
        document.getElementById('message').innerHTML = 'You must provide a streamer path!';
        return false;
    } else if (!tomatch.test(streamer_name)) {
        document.getElementById('message').innerHTML = 'Please enter a valid streamer path';
        document.getElementById('streamname').focus();
        return false;
    } else if (edtVideoTitle.trim() == '') {
        document.getElementById('alert_title').innerHTML = 'Please Enter Video Title';
        return false;
    }else if (videoUrl.trim() == '') {
        if(streamer_name == ''){
            document.getElementById('alert_VUrl').innerHTML = 'Please Enter Video URL';
            return false;
        }
    }else if(videoUrl != '' && regexp.test(videoUrl)== false){
        document.getElementById('alert_VUrl').innerHTML = 'Please Enter Valid Video URL';
        return false;
    }else if(hdUrl != '' && regexp.test(hdUrl)== false){
        document.getElementById('alert_HDURL').innerHTML = 'Please Enter Valid HD Url';
        return false;
    }else if (thumbimgUrl == '') {
        if(edit_thumb == ''){
            document.getElementById('errmsg_thumbimg').style.display = '';
            document.getElementById('errmsg_thumbimg').innerHTML = 'Please Enter Thumb Image/Url';
            return false;
        }
    }else if(linkUrl != '' && regexp.test(linkUrl)== false) {
        document.getElementById('alert_linkURL').innerHTML = 'Please Enter Valid Link Url';
        return false;
    }
}

/**
 * function to validate during edit upload video files
 *
 */

function validateFileExt() {
    var previewImg = document.getElementById('edit_preview').value;
    var thumbImg = document.getElementById('edit_thumb').value;

    if(thumbImg != ''){
        var filext = thumbImg.substring(thumbImg.lastIndexOf(".")+1);
    }

    if(previewImg != ''){
        var filext = previewImg.substring(previewImg.lastIndexOf(".")+1);
    }

    filext = filext.toLowerCase();

    if (filext == 'jpg' || filext == 'png' || filext == 'jpeg' || filext == 'gif'){
        return true;
    }else{
        alert("Invalid File Format Selected");
        document.getElementById('edit_preview').value = "";
        document.getElementById('edit_thumb').value = "";
        return false;
    }
}
