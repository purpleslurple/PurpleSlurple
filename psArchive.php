<?php
// Config
$show_header = true;
$show_footer = true;
// End config

// Source code disclaimer - always added
$ps_disclaimer = '<!--
PurpleSlurple Copyright 2002 by Matthew A. Schneider.
PurpleSlurple code is licensed under the Open Software License version 1.1.
This version was modified 12.12.2006 by
Hans Fredrik Nordhaug <hans@nordhaug.priv.no>:
- Made it work with register globals off (which is highly recommended).
- Added autodetecting of location of this script.
- Inserted header/disclaimer, style, base and footer without
   creating invalid HTML/breaking existing package.
- Added config section, might not be very useful.
***************************************************************
* PurpleSlurple(TM) was created by Matthew A. Schneider       *
* and was inspired by Purple, Augment, and others.            *
* It was created ostensibly for the purpose of                *
* facilitating my communication with Eric S. Raymond          *
* regarding edits to his "How to Become a Hacker" document.   *
* I\'m not kidding. You can\'t make this stuff up!              *
***************************************************************
-->';

// Automatically detect the location of this file
// if (isset($_SERVER['PATH_INFO']) && ($_SERVER['PATH_INFO'] !="") ) {
//     $file_location = $_SERVER['PATH_INFO'];
// } else if (isset($_SERVER['PHP_SELF']) && ($_SERVER['PHP_SELF'] !="") ) {
//    $file_location = $_SERVER['PHP_SELF'];
// } else {
//    $file_location = $_SERVER['SCRIPT_NAME'];
// }
// $file_location = "http://".$_SERVER['HTTP_HOST'].$file_location;
$file_location = "https://purpleslurple.com/psArchive.php";

// Register globals is bad, bad, bad - setting $theurl explicitly
$theurl = $_GET['theurl'];

// check for target URL, if none present PS form
if (!($theurl))
{
    echo '
<title>PurpleSlurple</title>
<h2>Welcome to PurpleSlurple &#153;</h2>
<h3>Granular Addressability in HTML Documents - ON THE FLY</h3>
<p><b><q>Slurp up a Web page, spit back Purple numbers</q></b></p><hr>
<p>If you are not familiar with Purple numbers you may want to read Eugene Eric Kim\'s &ldquo;
<a href="http://www.eekim.com/software/purple/purple.html">An Introduction to Purple</a>&rdquo;.
See also Eric Armstrong\'s comments on <a href="'.$file_location.
'?theurl=http://www.treelight.com/software/collaboration/whatsWrongWithEmail.html#purp587">granular addressability</a></p>
<p>Want one-click Purple numbers? Right-click on this link,
<a href="javascript:location.href=\''.$file_location.
'?theurl=\'+document.location.href;">PurpleSlurple Bookmarklet</a>,
and bookmark it, or drag and drop this bookmark onto your browser\'s personal toolbar.
Now when you are viewing a page on which you would like Purple numbers just click the bookmarklet.
(Javascript must be enabled).</p><hr>
<p>Enter the URL of the page to which you would like to apply Purple numbers.</p>
<form method="get" action="ps.php"><input type="text" name="theurl" size="30">
(e.g., http://www.somedomain.com/somepage.html)<br><input type="submit" value="Submit"></form>
<hr><p><a href="http://www.purpleslurple.net/">PurpleSlurple</a> &#153;
was created by <a href="mailto:matsch@sasites.com">Matthew A. Schneider</a></p>';
  exit;
}

// Do not slurp self
if (strpos($theurl,$file_location) !== false)
     die('PurpleSlurple won\'t slurp itself :-)'); //die, do not process

// PurpleSlurple header/disclaimer and expand / collapse link
$ps_header = '<h1>This page was generated by <a href="'.$file_location.'">PurpleSlurple</a>&#153;.
The original page can be found <a href="'.$theurl.'">here</a>.</h1><h2><a href="'.$file_location.'?collapse=no&amp;theurl='.$theurl.'">expand</a>&nbsp;|&nbsp
<a href="'.$file_location.'?collapse=yes&amp;theurl='.$theurl.'">collapse</a></h2><hr>';

// PurpleSlurple footer
$ps_footer = '<br style="clear:both"><hr><p style="height: 700px">
<a href="http://www.purpleslurple.net/">PurpleSlurple</a>&#153; was created
by <a href="mailto:matsch@sasites.com">Matthew A. Schneider</a></p>';

// set base to ensure relative links work
// Thanks to http://marc.theaimsgroup.com/?l=php-general&m=95597547227951&w=2  Duh!
$ps_base = "<base href='$theurl'>";

// collapse outline (hiding elements)
$ps_style = "<style type='text/css'>p {display:none}\nli {display:none}\n</style>\n";

// slurp the page
$fcontents = file($theurl);
$theurl = urlencode($theurl);
$ps_contents = "";
foreach ($fcontents as $line_num => $line) {
    $pattern = "/<p[^>]*>|<h[1-6][^>]*>|<li[^nk>]*>/i";
    $replacement = "\\0(<a href='$file_location?theurl=$theurl#purp$line_num' id='purp$line_num'><font color='purple'>$line_num</font></a>) ";
    $ps_contents .= preg_replace($pattern, $replacement, $line);
}

// find head and body and insert disclaimer/header/footer/style/base
list($head,$body) = explode("</head>", $ps_contents);
if (isset($_GET['collapse']) && ($_GET['collapse'] == "yes")) {
    $head = str_replace("<head>","<head>\n$ps_style", $head);;
}
if (!strpos("<base",$head)) {
    $head = str_replace("<head>","<head>\n$ps_base", $head);;
}

// insert disclaimer/header/footer
$head = str_replace("<head>","<head>\n$ps_disclaimer", $head);
if ($show_header) {
    $body = preg_replace("/<body[^>]*>/i","\\0\n$ps_header",$body);
}
if ($show_footer) {
    $body = str_replace("</body>","$ps_footer\n</body>",$body);
}

// Sending result to browser
echo $head."</head>".$body;

?>