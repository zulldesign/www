#!/usr/local/bin/perl

#----------------------------------------------------------------------
# Form-mail.pl, by Reuven M. Lerner (reuven@the-tech.mit.edu).
# This package is Copyright 1994 by The Tech.
# Packaged Modified to mail any form to you by Matt Wright (mattw@misha.net)
   
# FormMail is free software; you can redistribute it and/or modify it
# under the terms of the GNU General Public License as published by the
# Free Software Foundation; either version 2, or (at your option) any
# later version.
   
# FormMail is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# General Public License for more details.

# Write the Free Software Foundation, 675 Mass Ave, Cambridge, MA 02139, USA.
# If you would like to obtain a copy of the GNU GPL.
# ------------------------------------------------------------

####################################################
# FormMail
# Created by Matt Wright
# Created 6/9/95                Last Modified 9/23/95
# Version 1.2
# I can be reach at:		mattw@misha.net
# Scripts Archive at:		http://www.worldwidemart.com/scripts/

# Define Variables
$mailprog = '/usr/sbin/sendmail';
$date = `/usr/sbin/date`; chop($date);

# Get the input
read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
 
# Split the name-value pairs
@pairs = split(/&/, $buffer);

foreach $pair (@pairs){
   ($name, $value) = split(/=/, $pair);

   $value =~ tr/+/ /;
   $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
   $name =~ tr/+/ /;
   $name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
          
   $FORM{$name} = $value;
}

if ($FORM{'redirect'}) {
   print "Location: $FORM{'redirect'}\n\n";
}
else {
   # Print Return HTML
   print "Content-type: text/html\n\n";
   print "<html><head><title>Thanks You</title></head>\n";
   print "<body><h1>Thank You For Filling Out This Form</h1>\n";
   print "Thank you for taking the time to fill out my feedback form. ";
   print "Below is what you submitted to $FORM{'recipient'} on ";
   print "$date<hr>\n";
}

# Open The Mail
open(MAIL, "|$mailprog -t") || die "Can't open $mailprog!\n";
print MAIL "To: $FORM{'recipient'}\n";
print MAIL "From: $FORM{'realname'}  $FORM{'a_realname'}($FORM{'username'})($FORM{'email'})\n";
if ($FORM{'subject'}) {
   print MAIL "Subject: $FORM{'subject'}\n\n";
}
else {
   print MAIL "Subject: WWW Form Submission\n\n";
}
print MAIL "Below is the result of your feedback form.  It was\n";
print MAIL "submitted by $FORM{'realname'}  $FORM{'a_realname'}($FORM{'username'})($FORM{'email'}) on $date\n";
print MAIL "---------------------------------------------------------\n";

foreach $pair (@pairs) {
   ($name, $value) = split(/=/, $pair);
 
   $value =~ tr/+/ /;
   $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
   $name =~ tr/+/ /;
   $name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;

   $FORM{$name} = $value;
   unless ($name eq 'recipient' || $name eq 'subject' || $name eq 'email' || $name eq 'realname' || $name eq 'redirect') {
      # Print the MAIL for each name value pair
      if ($value ne "") {
         print MAIL "$name:  $value\n";
         print MAIL "____________________________________________\n\n";
      }

      unless ($FORM{'redirect'}) {
         if ($value ne "") {
            print "$name = $value<hr>\n";
         }
      }
   }
}
close (MAIL);

unless ($FORM{'redirect'}) {
   print "</body></html>";
}
