<?PHP
function err_text($a)
{	
	global $lang;
	$s="";
	if ($a=="clientemail") 
	{
		if ($lang=="en" || $lang=="eng") {$s="<br>Error: The email address you entered is incorrect";} 
			else {$s="<br>Ошибка: Вы указали неверный е-майл";}
	}
	elseif ($a=="email") 
	{ 
		if ($lang=="en" || $lang=="eng") {$s="<br>Error: ".$a." address to receive order is incorrect";} 
			else {$s="<br>Ошибка: Адрес для получения заказа ".$a."  неверный";} 
	}	
	return $s;
}
function send_mail($a, $comment)
{	
	global $text,$subject,$sendername,$sender,$charset;
	$r="";
	if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $a)) 
	{$r="".err_text($comment).""; }
	else 
	{
		mail($a,$subject,$text,"From: $sendername <$sender>\nContent-type: text/html; charset=".$charset."\r\n;");	
	}
	return $r;	
}



$text = $HTTP_POST_VARS["zak_pos"];


if ($text!="")
{
	$clientemail=$HTTP_POST_VARS['clientemail'];
	$email=$HTTP_POST_VARS['email'];
	$email2=$HTTP_POST_VARS['email2'];
	$email3=$HTTP_POST_VARS['email3'];
	$subject=$HTTP_POST_VARS["subject"]." - ".$clientemail;
	$payment=$HTTP_POST_VARS['payment'];
	$charset=$HTTP_POST_VARS['charset'];
	if ($charset=="") {$charset="UTF-8";}
	$lang=$HTTP_POST_VARS['lang'];
	$sendername=$email;
	$sender=$email;
	
	if ($lang=="eng" || $lang=="en") {$sback="Back";$snext="Сontinue";} else {$sback="Назад";$snext="Продолжить";}

	$errmessage=send_mail($email,"email").send_mail($email2,"email2").send_mail($email3,"email3");
	if ($HTTP_POST_VARS['send_to_client']=="2") {$errmessage.=send_mail($clientemail,"clientemail");}

	$newlocation=$payment;
	if (strchr($newlocation,"?")=="") {$newlocation=$newlocation."?";} else {$newlocation=$newlocation."&";};
	$newlocation=$newlocation."summa=".$f2itogo."&email=".$email."&clientemail=".$clientemail."&firstname=".$firstname."&lastname=".$lastname."&lang=".$lang;
	if ($errmessage!="")
	{
		print "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><center>".$errmessage."<br><br><a href='".$newlocation."'>".$snext."</a>&nbsp;<a href='javascript:history.go(-1)' class='text'>".$sback."</a></center></center>";
	}
	else
	{
		Header("Location:".$newlocation);  
	}
}
?>

