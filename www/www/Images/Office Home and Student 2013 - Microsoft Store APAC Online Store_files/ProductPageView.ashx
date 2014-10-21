function AT_tags(){
  try{var tags = new Array();
    var imgs = new Array();
    tags = ['https://pixel.mathtag.com/event/img?mt_id=525521&mt_adid=125559&v1=&v2=&v3=&s1=&s2=&s3='];
    for(var i=0; i<tags.length; i++)
    { imgs[i] = new Image();
      imgs[i].src = tags[i];}
    this.csk='Test';
  }catch(e){this.csk='Error';}}
var AT_csk = new AT_tags();
turn_client_track_id = "";
document.write('<sc'+'ript src="http://r.turn.com/server/beacon_call.js?b2=G0ocX8EEnzkz6H5qRkWMTJ8Gr2Dom3sSuWb30odr6fsINe0QgCCaqpv2293kn4zAPbaq0QnF91X4b2vO74kLBA" type="text/javascript"></scr'+'ipt>');
document.write('<sc'+'ript src="http://ib.adnxs.com/px?id=318679&seg=2042606&t=1" type="text/javascript"></scr'+'ipt>');
document.write('<sc'+'ript src="https://secure.adnxs.com/px?id=318679&seg=2042606&t=1" type="text/javascript"></scr'+'ipt>');