// Designed by Muhammad Naeem (naeem@email.com)

function Displaynumbers(start_value,end_value,select_value)
{
var Data;
for(var i=start_value;i<=end_value;i++)
 {
 if (i==select_value)
	         Data = "<option value=" + i + " selected >" + i +"</option>";				
			  else
				  Data = "<option value=" + i + ">" + i + "</option>";
				  document.write (Data);
				 }
				 }
				 
				 function d()
				 {
				 var textf=document.f1.txt1;
				 var textm=document.f1.txt2;
				 if (textf.value=="") {alert("Please enter first name"); textf.focus();}
				 else if (textm.value=="") {alert("Please enter middle name"); textm.focus();}
				 else {textf.value=textf.value;	textm.value=textm.value;}
				 //var num=isNaN(text.value);
				 //text.value=text.value+"";
				 //if(num==false)				 
				 }
				 function Mix()
				 {F(); d(); Fn(name);  Id41(); Id42(); Id43(); Id44(); Idc(); Dmy();}	
				 function Idc()
				 {
				 if (document.f1.txt41.value.length < 4) {alert ("complete the ID Card No."); document.f1.txt41.focus();}
				 else {if (document.f1.txt42.value.length < 2) {alert ("complete the ID Card No."); document.f1.txt42.focus();}
				 else {if (document.f1.txt43.value.length < 3) {alert ("complete the ID Card No."); document.f1.txt43.focus();}
				 else {if (document.f1.txt44.value.length < 2) {alert ("complete the ID Card No."); document.f1.txt44.focus();}}}}
				 }
				 function F()
				 {if (document.f1.txt1.value == "") document.f1.txt1.focus();}
				 
				 function Trim(name)
				 {
				 var Rx=/[^a-z]/ig;
				 name.value=name.value.replace(Rx,"");
				 name.value=name.value.toLowerCase();
				 var F =name.value.charAt(0);
				 var FF = F.toUpperCase();
				 name.value=name.value.replace(F, FF);
				 }
				 function T(name)
				 {
				 Trim(name);
				 Fn(name); 
				 
				 }
				 function Fn(name)
				 {
				 if (name.value == "") document.f1.txt1.focus();
				 else if(document.f1.txt2.value == "") document.f1.txt2.focus();
				 }
				 
				 ///function e()
				 //var num=1;
				 //for(i=0;i<=5;i++)
				 //num++;
				 
				 //{
				 
				 function Id41()
				 {
				 var Rx = /\D/g;
				 var s1 = document.f1.txt41;
				 var num=isNaN(s1.value);
				 if(num==true) {s1.value = s1.value.replace(Rx,"");
				 alert("Please enter digit in ID Card No.");}
				 if (s1.value.length < 4) s1.focus();
				 else document.f1.txt42.focus();
				 }
				 function Id42()
				 {
				 if (document.f1.txt41.value.length < 4) document.f1.txt41.focus();
				 else {var Rx = /\D/g;
				 var s2 = document.f1.txt42;
				 var num=isNaN(s2.value);
				 if(num==true) {s2.value = s2.value.replace(Rx,"");
				 alert("Please enter digit in ID Card No.");}
				 if (s2.value.length < 2) s2.focus();
				 else document.f1.txt43.focus();
				 }}
				 function Id43()
				 {
				 if (document.f1.txt41.value.length < 4) document.f1.txt41.focus();
				 else {if (document.f1.txt42.value.length < 2) document.f1.txt42.focus();
				 else {var Rx = /\D/g;
				 var s3 = document.f1.txt43;
				 var num=isNaN(s3.value);
				 if(num==true) {s3.value = s3.value.replace(Rx,"");
				 alert("Please enter digit in ID Card No.");}
				 if (s3.value.length < 3) s3.focus();
				 else document.f1.txt44.focus();
				 }}}
				 function Id44()
				 {
				 if (document.f1.txt41.value.length < 4) document.f1.txt41.focus();
				 else {if (document.f1.txt42.value.length < 2) document.f1.txt42.focus();
				 else {if (document.f1.txt43.value.length < 3) document.f1.txt43.focus();
				 else {var Rx = /\D/g;
				 var s4 = document.f1.txt44;
				 var num=isNaN(s4.value);
				 if(num==true) {s4.value = s4.value.replace(Rx,"");
				 alert("Please enter digit in ID Card No.");}
				 if (s4.value.length < 2) s4.focus();
				 }}}}
				 function Dmy()
				 {
				 var d = document.f1.date.value;
 				 var m = document.f1.month.value;
 				 var y = document.f1.year.value;
				 var p = (y%4);
 				 if (((m==4 || m==6 || m==9 || m==11) && (d>30)) ||  ((p!=0) && (m==2) && (d>28)) || ((p==0) && (m==2) && (d>29)))
 				{ alert ("Invalid date! select again"); document.f1.date.focus();}
				}
				 function D()
				 {document.f1.month.focus();}
				 function M()
				 {document.f1.year.focus();}
				 function Email()
				 {
				 var Rx=/\w+[@]\w+[.]\w{2,}/
				 var tx=document.f1.txt5;
				 var sx=tx.value.match(Rx);
				 if(sx == null) {alert("Incorrect E-mail No.! Type again"); tx.focus();}
				 else alert("OK");
				 }
