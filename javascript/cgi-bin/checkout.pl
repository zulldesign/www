#!/usr/bin/perl
#=====================================================================||
#               NOP Design JavaScript Shopping Cart                   ||
#                     PERL CGI Checkout Module                        ||
#                                                                     ||
# For more information on SmartSystems, or how NOPDesign can help you ||
# Please visit us on the WWW at http://www.nopdesign.com              ||
#                                                                     ||
# Javascript portions of this shopping cart software are available as ||
# freeware from NOP Design.  You must keep this comment unchanged in  ||
# your code.  For more information contact FreeCart@NopDesign.com.    ||
#                                                                     ||
# JavaScript Shop Module, V.4.4.0                                     ||
#=====================================================================||
#                                                                     ||
#  Function: Writes available form elements from the NOP              ||
#            Free Cart (http://www.nopdesign.com/freecart)            ||
#            and other form elements to an email file, and            ||
#            send user confirmation                                   ||
#                                                                     ||
#=====================================================================||
require 5.001;

########################################################################
#                                                                      #
#  User defined variables:                                             #
#      $header        - string value containing the complete           #
#                       path of the HTML page header                   #
#      $footer        - string value containing the complete           #
#                       path of the HTML page footer                   #
#      $mailprogram   - string value containing the complete path to   #
#                       the sendmail binary on the system.             #
#      $youremail     - string value containing the email address to   #
#                       send catalog orders in EMAIL or BOTH modes     #
#                       **Don't forget to put a \ before the @ in your #
#                       email address. ie. spam\@nopdesign.com***      #
#      $returnpage    - URL to send user when checkout is complete     #
#      $csvfilename   - string value containing the complete           #
#                       path of the user database.                     #
#      $csvquote      - string value containing what to use for quotes #
#                       in the csv file (typically "" or \")           #
#      $mode          - string value containing 'EMAIL', 'FILE' or     #
#                       'BOTH' to determine if the script should send  #
#                       an email to you with the new order, write the  #
#                       order to a CSV file, or do both.               #
########################################################################
$header        = "header.html";
$footer        = "footer.html";
$mailprogram   = "/usr/lib/sendmail -t";
$returnpage    = "/";
$youremail     = "freecart\@nopdesign.com";
$csvfilename   = "orders.csv";
$csvquote      = "\"\"";
$mode          = "FILE";


#These are required fields.  I recommend enforcing these by javascript, 
#but let's just make sure here as well.
@required = (
'b_first',
'b_last',
'b_addr',
'b_city',
'b_state',
'b_zip',
'b_phone',
'b_email'
);

##############################################################
#FUNCTION:   urlDecode                                       #
#RETURNS:    The decoded string.                             #
#PARAMETERS: An encoded string.                              #
#PURPOSE:    Decodes a URL encoded string.                   #
##############################################################
sub urlDecode {
    my ($string) = @_;
    $string =~ tr/+/ /;
    $string =~ s/%([\dA-Fa-f][\dA-Fa-f])/pack ("C", hex($1))/eg;
    $string =~ s/['"]/\'/g;
    return ($string);
}

##############################################################
#FUNCTION:   processCGI                                      #
#RETURNS:                                                    #
#PARAMETERS:                                                 #
#PURPOSE:    Retrieves form data submitted via the 'GET'     #
#            method and decodes it.  You may then access     #
#            the passed in variables via calls to $[name]    #
#            where [name] is the name of the form element.   #
##############################################################
sub processCGI {
    local ($cgiData, $key, $value, $pair, @pairs);

   if ($ENV{'REQUEST_METHOD'} eq 'GET') { $cgiData = $ENV{'QUERY_STRING'}; }
   else { $cgiData = <STDIN>; }
   @pairs = split (/&/, $cgiData);
   foreach $pair (@pairs) {
      ($key, $value) = split (/\=/, $pair);
      $key   = &urlDecode($key);
      $value = &urlDecode($value);
      if(defined ${$key}){
         ${$key} .= ", ".$value;
      }else{
         ${$key} = $value;
      }
   }
}

##############################################################
#FUNCTION:   doFormError                                     #
#RETURNS:                                                    #
#PARAMETERS: A error message string.                         #
#PURPOSE:    Generates an HTML page indicating a form        #
#            submission error occurred.                      #
##############################################################
sub doFormError {
    my ($errString) = @_;

    open (HEAD, $header);
    @LINES = <HEAD>;
    close HEAD;

    print "Content-type: text/html\n\n";

    print @LINES;

    print "<FONT SIZE=+2>The form you submitted was not complete.<BR><BR></FONT>";
    print "$errString<BR><BR>\n";
    print "<INPUT TYPE=BUTTON ONCLICK='history.back()' VALUE='  Return to the checkout page '><HR>";

    open (FOOT, $footer);
    @LINES = <FOOT>;
    close FOOT;
    print @LINES;

    exit;
}

##############################################################
#FUNCTION:   doError                                         #
#RETURNS:                                                    #
#PARAMETERS: A error message string.                         #
#PURPOSE:    Generates an HTML page indicating an error      #
#            occurred.                                       #
##############################################################
sub doError {
    my ($errString) = @_;
    print "Content-type: text/html\n\n";

    open (HEAD, $header);
    @LINES = <HEAD>;
    close HEAD;

    print @LINES;

    print "$errString<BR><BR>\n";

    open (FOOT, $footer);
    @LINES = <FOOT>;
    close FOOT;
    print @LINES;

    exit;
}

##############################################################
#FUNCTION:   invalidE                                        #
#RETURNS:    1 if invalid, 0 if valid.                       #
#PARAMETERS: An email address variable.                      #
#PURPOSE:    Checks to see if a submitted email address is   #
#            of the valid form 'x@y'.                        #
##############################################################
sub invalidE {
  my ($szEmail) = @_;
  my ($user, $host);

  $szEmail =~ tr/A-Z/a-z/;
  if ($szEmail =~ /\s/) { return 1; }
  ($user, $host) = split (/\@/, $szEmail);
  if ($host =~ /compuserve/i) { ; }
  else {
    if (! $user =~ /\D/) { return 1; }
    if (! $host =~ /\D/) { return 1; }
    if (substr ($user,0,1) !~ /[a-z]/) { return 1; }
  }
  if ($szEmail =~ /\w+\@[\w|\.]/) { return 0; }
  else { return 1; }
}


sub populateDateVar {
   @months = ();
   push(@months,"January");
   push(@months,"February");
   push(@months,"March");
   push(@months,"April");
   push(@months,"May");
   push(@months,"June");
   push(@months,"July");
   push(@months,"August");
   push(@months,"September");
   push(@months,"October");
   push(@months,"November");
   push(@months,"December");
   @days = ();
   push(@days,"Sunday");
   push(@days,"Monday");
   push(@days,"Tuesday");
   push(@days,"Wednesday");
   push(@days,"Thursday");
   push(@days,"Friday");
   push(@days,"Saturday");
   ($sec,$min,$hour,$day,$month,$year,$day2) =
   (localtime(time))[0,1,2,3,4,5,6];
   if ($sec < 10) { $sec = "0$sec"; }
   if ($min < 10) { $min = "0$min"; }
   if ($hour < 10) { $hour = "0$hour"; }
   if ($day < 10) { $day = "0$day"; }
   $year += "1900";

   #$todaysdate = "$months[$month] $day, $year $hour:$min:$sec";
}


##############################################################
##############################################################
###  MAIN                                                  ###
##############################################################
##############################################################

# process the form input.
&processCGI;
&populateDateVar;

foreach $check(@required) {
   unless ($check) {
      doFormError("It appears that you forgot to fill in the <strong>$check</strong> field.");
      exit;
   }
}

# checks for valid email address
if( &invalidE($b_email) ){
   doFormError('You submitted an invalid email address.');
}


if( $mode eq "BOTH" || $mode eq "EMAIL") {
   # Send email order to you...
   open (MAIL,"|$mailprogram");
   print MAIL "To: $youremail\n";
   print MAIL "From: $b_email\n";
   print MAIL "Subject: New Online Order\n";
   print MAIL "\n\n";
   print MAIL "A new order has been received.  A summary of this order appears below.\n";
   print MAIL "\n";
   print MAIL "Order Date: $months[$month] $day, $year $hour:$min:$sec \n"; 
   print MAIL " \n";
   print MAIL "Bill To: \n";
   print MAIL "-------- \n";
   print MAIL "   $b_first $b_last \n";
   print MAIL "   $b_addr \n";
   print MAIL "   $b_addr2 \n";
   print MAIL "   $b_city, $b_state  $b_zip \n";
   print MAIL "   $b_phone \n";
   print MAIL "   $b_fax \n";
   print MAIL "   $b_email \n";
   print MAIL " \n";
   print MAIL " \n";
   print MAIL "Ship To: \n";
   print MAIL "-------- \n";
   print MAIL "   $s_first $s_last \n";
   print MAIL "   $s_addr \n";
   print MAIL "   $s_addr2 \n";
   print MAIL "   $s_city, $s_state  $s_zip \n";
   print MAIL "   $s_phone \n";
   print MAIL " \n";
   print MAIL " \n";
   print MAIL "Qty  Price(\$)   Product ID  - Product Name\n";
   print MAIL "===================================================================== \n";
   print MAIL "$QUANTITY_1    \$$PRICE_1    $ID_1 - $NAME_1   $ADDTLINFO_1  \n";
   if( $NAME_2 ) {print MAIL "$QUANTITY_2    \$$PRICE_2    $ID_2 - $NAME_2   $ADDTLINFO_2  \n";}
   if( $NAME_3 ) {print MAIL "$QUANTITY_3    \$$PRICE_3    $ID_3 - $NAME_3   $ADDTLINFO_3  \n";}
   if( $NAME_4 ) {print MAIL "$QUANTITY_4    \$$PRICE_4    $ID_4 - $NAME_4   $ADDTLINFO_4  \n";}
   if( $NAME_5 ) {print MAIL "$QUANTITY_5    \$$PRICE_5    $ID_5 - $NAME_5   $ADDTLINFO_5  \n";}
   if( $NAME_6 ) {print MAIL "$QUANTITY_6    \$$PRICE_6    $ID_6 - $NAME_6   $ADDTLINFO_6  \n";}
   if( $NAME_7 ) {print MAIL "$QUANTITY_7    \$$PRICE_7    $ID_7 - $NAME_7   $ADDTLINFO_7  \n";}
   if( $NAME_8 ) {print MAIL "$QUANTITY_8    \$$PRICE_8    $ID_8 - $NAME_8   $ADDTLINFO_8  \n";}
   if( $NAME_9 ) {print MAIL "$QUANTITY_9    \$$PRICE_9    $ID_9 - $NAME_9   $ADDTLINFO_9  \n";}
   if( $NAME_10 ){print MAIL "$QUANTITY_10    \$$PRICE_10    $ID_10 - $NAME_10   $ADDTLINFO_10 \n";}
   if( $NAME_11 ){print MAIL "$QUANTITY_11    \$$PRICE_11    $ID_11 - $NAME_11   $ADDTLINFO_11 \n";}
   if( $NAME_12 ){print MAIL "$QUANTITY_12    \$$PRICE_12    $ID_12 - $NAME_12   $ADDTLINFO_12 \n";}
   if( $NAME_13 ){print MAIL "$QUANTITY_13    \$$PRICE_13    $ID_13 - $NAME_13   $ADDTLINFO_13 \n";}
   print MAIL "===================================================================== \n";
   print MAIL "SUBTOTAL: $SUBTOTAL \n";
   print MAIL "TOTAL: $TOTAL \n";
   print MAIL "\n";
   print MAIL "FREIGHT: $SHIPPING \n";
   print MAIL "\n\n";
   print MAIL "Comments: \n";
   print MAIL "--------- \n";
   print MAIL "$comment \n";
   print MAIL " \n";
   close MAIL;
}


if( $mode eq "BOTH" || $mode eq "FILE") {
   
   $csvcomments = $comment;
   #$csvcomments =~ s/\"/$csvquote/ig;

   open (CSVF,">>$csvfilename");
   print CSVF "\"";
   print CSVF "$months[$month] $day, $year $hour:$min:$sec";
   print CSVF "\",\"";
   print CSVF "$b_first";
   print CSVF "\",\"";
   print CSVF "$b_last";
   print CSVF "\",\"";
   print CSVF "$b_addr";
   print CSVF "\",\"";
   print CSVF "$b_addr2";
   print CSVF "\",\"";
   print CSVF "$b_city";
   print CSVF "\",\"";
   print CSVF "$b_state";
   print CSVF "\",\"";
   print CSVF "$b_zip";
   print CSVF "\",\"";
   print CSVF "$b_phone";
   print CSVF "\",\"";
   print CSVF "$b_fax";
   print CSVF "\",\"";
   print CSVF "$b_email";
   print CSVF "\",\"";
   print CSVF "$s_first";
   print CSVF "\",\"";
   print CSVF "$s_last";
   print CSVF "\",\"";
   print CSVF "$s_addr";
   print CSVF "\",\"";
   print CSVF "$s_addr2";
   print CSVF "\",\"";
   print CSVF "$s_city";
   print CSVF "\",\"";
   print CSVF "$s_state";
   print CSVF "\",\"";
   print CSVF "$s_zip";
   print CSVF "\",\"";
   print CSVF "$s_phone";
   print CSVF "\",\"";   
   print CSVF "$QUANTITY_1";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_1";
   print CSVF "\",\"";
   print CSVF "$ID_1";
   print CSVF "\",\"";
   print CSVF "$NAME_1";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_1";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_2";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_2";
   print CSVF "\",\"";
   print CSVF "$ID_2";
   print CSVF "\",\"";
   print CSVF "$NAME_2";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_2";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_3";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_3";
   print CSVF "\",\"";
   print CSVF "$ID_3";
   print CSVF "\",\"";
   print CSVF "$NAME_3";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_3";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_4";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_4";
   print CSVF "\",\"";
   print CSVF "$ID_4";
   print CSVF "\",\"";
   print CSVF "$NAME_4";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_4";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_5";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_5";
   print CSVF "\",\"";
   print CSVF "$ID_5";
   print CSVF "\",\"";
   print CSVF "$NAME_5";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_5";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_6";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_6";
   print CSVF "\",\"";
   print CSVF "$ID_6";
   print CSVF "\",\"";
   print CSVF "$NAME_6";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_6";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_7";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_7";
   print CSVF "\",\"";
   print CSVF "$ID_7";
   print CSVF "\",\"";
   print CSVF "$NAME_7";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_7";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_8";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_8";
   print CSVF "\",\"";
   print CSVF "$ID_8";
   print CSVF "\",\"";
   print CSVF "$NAME_8";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_8";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_9";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_9";
   print CSVF "\",\"";
   print CSVF "$ID_9";
   print CSVF "\",\"";
   print CSVF "$NAME_9";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_9";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_10";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_10";
   print CSVF "\",\"";
   print CSVF "$ID_10";
   print CSVF "\",\"";
   print CSVF "$NAME_10";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_10";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_11";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_11";
   print CSVF "\",\"";
   print CSVF "$ID_11";
   print CSVF "\",\"";
   print CSVF "$NAME_11";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_11";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_12";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_12";
   print CSVF "\",\"";
   print CSVF "$ID_12";
   print CSVF "\",\"";
   print CSVF "$NAME_12";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_12";
   print CSVF "\",\"";
   print CSVF "$QUANTITY_13";
   print CSVF "\",\"";
   print CSVF "\$$PRICE_13";
   print CSVF "\",\"";
   print CSVF "$ID_13";
   print CSVF "\",\"";
   print CSVF "$NAME_13";
   print CSVF "\",\"";
   print CSVF "$ADDTLINFO_13";
   print CSVF "\",\"";
   print CSVF "$SUBTOTAL";
   print CSVF "\",\"";
   print CSVF "$TOTAL";
   print CSVF "\",\"";
   print CSVF "$SHIPPING";
   print CSVF "\",\"";
   print CSVF "$comment";
   print CSVF "\"\n";
   close CSVF;
}



# Send email conformation to the customer.....
open (MAIL,"|$mailprogram");
print MAIL "To: $b_email\n";
print MAIL "From: $youremail\n";
print MAIL "Subject: Order Confirmation\n";
print MAIL "\n\n";
print MAIL "A new order has been received.  A summary of this order appears below.\n";
print MAIL "\n";
print MAIL "Order Date: $months[$month] $day, $year $hour:$min:$sec \n"; 
print MAIL " \n";
print MAIL "Bill To: \n";
print MAIL "-------- \n";
print MAIL "   $b_first $b_last \n";
print MAIL "   $b_addr \n";
print MAIL "   $b_addr2 \n";
print MAIL "   $b_city, $b_state  $b_zip \n";
print MAIL "   $b_phone \n";
print MAIL "   $b_fax \n";
print MAIL "   $b_email \n";
print MAIL " \n";
print MAIL " \n";
print MAIL "Ship To: \n";
print MAIL "-------- \n";

if ( $s_addr eq "" ) {
   print MAIL "   Use Billing Address\n";
} else {
   print MAIL "   $s_first $s_last \n";
   print MAIL "   $s_addr \n";
   print MAIL "   $s_addr2 \n";
   print MAIL "   $s_city, $s_state  $s_zip \n";
   print MAIL "   $s_phone \n";
}

print MAIL " \n";
print MAIL " \n";
print MAIL "Qty  Price(\$)   Product ID  - Product Name\n";
print MAIL "===================================================================== \n";
print MAIL "$QUANTITY_1    \$$PRICE_1    $ID_1 - $NAME_1   $ADDTLINFO_1  \n";
if( $NAME_2 ) {print MAIL "$QUANTITY_2    \$$PRICE_2    $ID_2 - $NAME_2   $ADDTLINFO_2  \n";}
if( $NAME_3 ) {print MAIL "$QUANTITY_3    \$$PRICE_3    $ID_3 - $NAME_3   $ADDTLINFO_3  \n";}
if( $NAME_4 ) {print MAIL "$QUANTITY_4    \$$PRICE_4    $ID_4 - $NAME_4   $ADDTLINFO_4  \n";}
if( $NAME_5 ) {print MAIL "$QUANTITY_5    \$$PRICE_5    $ID_5 - $NAME_5   $ADDTLINFO_5  \n";}
if( $NAME_6 ) {print MAIL "$QUANTITY_6    \$$PRICE_6    $ID_6 - $NAME_6   $ADDTLINFO_6  \n";}
if( $NAME_7 ) {print MAIL "$QUANTITY_7    \$$PRICE_7    $ID_7 - $NAME_7   $ADDTLINFO_7  \n";}
if( $NAME_8 ) {print MAIL "$QUANTITY_8    \$$PRICE_8    $ID_8 - $NAME_8   $ADDTLINFO_8  \n";}
if( $NAME_9 ) {print MAIL "$QUANTITY_9    \$$PRICE_9    $ID_9 - $NAME_9   $ADDTLINFO_9  \n";}
if( $NAME_10 ){print MAIL "$QUANTITY_10    \$$PRICE_10    $ID_10 - $NAME_10   $ADDTLINFO_10 \n";}
if( $NAME_11 ){print MAIL "$QUANTITY_11    \$$PRICE_11    $ID_11 - $NAME_11   $ADDTLINFO_11 \n";}
if( $NAME_12 ){print MAIL "$QUANTITY_12    \$$PRICE_12    $ID_12 - $NAME_12   $ADDTLINFO_12 \n";}
if( $NAME_13 ){print MAIL "$QUANTITY_13    \$$PRICE_13    $ID_13 - $NAME_13   $ADDTLINFO_13 \n";}
print MAIL "===================================================================== \n";
print MAIL "SUBTOTAL: $SUBTOTAL \n";
print MAIL "TOTAL: $TOTAL \n";
print MAIL "\n";
print MAIL "FREIGHT: $SHIPPING \n";
print MAIL "\n\n";
print MAIL "Comments: \n";
print MAIL "--------- \n";
print MAIL "$comment \n";
print MAIL " \n";
close MAIL;


print "Content-type: text/html\n\n";

open (HEAD, $header);
@LINES = <HEAD>;
close HEAD;
print @LINES;

print "<h2>Thank you</h2>";
print "Thank you for your order from our online store.  You will receive a confirmation email of your order ";
print "momentarily.  Please contact us at $youremail if you have any questions or concerns.";
print "<P>";
print "<A HREF=\"$returnpage\" target=_top>Return Home</A>";
print "<P>";

open (FOOT, $footer);
@LINES = <FOOT>;
close FOOT;

print @LINES;

exit;


