<?php 
// Config 
// $show_header = true; 
// $show_footer = true; 
// End config 

// Source code disclaimer - always added 
// $ps_disclaimer = '<!-- 
// PurpleSlurple Copyright 2002 by Matthew A. Schneider. 
// PurpleSlurple code is licensed under the Open Software License version 1.1. 
// This version was modified 12.12.2006 by 
// Hans Fredrik Nordhaug <hans@nordhaug.priv.no>: 
// - Made it work with register globals off (which is highly recommended). 
// - Added autodetecting of location of this script. 
// - Inserted header/disclaimer, style, base and footer without 
//    creating invalid HTML/breaking existing package. 
// - Added config section, might not be very useful. 
// *************************************************************** 
// * PurpleSlurple(TM) was created by Matthew A. Schneider       * 
// * and was inspired by Purple, Augment, and others.            * 
// * It was created ostensibly for the purpose of                * 
// * facilitating my communication with Eric S. Raymond          * 
// * regarding edits to his "How to Become a Hacker" document.   * 
// * I\'m not kidding. You can\'t make this stuff up!              * 
// *************************************************************** 
// -->'; 

// Automatically detect the location of this file 
if (isset($_SERVER['REQUEST_URI']) && ($_SERVER['REQUEST_URI'] !="") ) { 
    $file_location = $_SERVER['REQUEST_URI']; 
} else if (isset($_SERVER['PHP_SELF']) && ($_SERVER['PHP_SELF'] !="") ) { 
    $file_location = $_SERVER['PHP_SELF']; 
} else { 
    $file_location = $_SERVER['SCRIPT_NAME']; 
} 
$file_location = "https://".$_SERVER['SERVER_NAME'].$file_location;

// Register globals is bad, bad, bad - setting $theurl explicitly 
$theurl = $_GET['theurl'];
echo $theurl."<br>";
// check for target URL, if none present PS form 
if (!($theurl)) 
{ 
    echo ' 
<title>PurpleSlurple</title> 
<h2>Welcome to PurpleSlurple &#153;</h2> 
<h3>Granular Addressability in HTML Documents - ON THE FLY</h3> 
<p><b><q>Slurp up a Web page, spit back Purple numbers</q></b></p><hr> 
<p>If you are not familiar with Purple numbers you might want to read Eugene Eric Kim\'s &ldquo; 
<a href="https://web.archive.org/web/20021102203338/http://www.eekim.com/software/purple/purple.html">An Introduction to Purple</a>&rdquo;. 
See also Eric Armstrong\'s comments on <a href="">granular addressability</a></p> 
<p>Want one-click Purple numbers? Right-click on this link, 
<a href="javascript:location.href=\''.$file_location. 
'?theurl=\'+document.location.href;">PurpleSlurple Bookmarklet</a>, 
and bookmark it, or drag and drop this bookmark onto your browser\'s personal toolbar. 
Now when you are viewing a page on which you would like Purple numbers just click the bookmarklet. 
(Javascript must be enabled).</p><hr> 
<p>Enter the URL of the page to which you would like to apply Purple numbers.</p> 
<form method="get" action="ps.php"><input type="text" name="theurl" size="30"> 
(e.g., https://www.somedomain.com/somepage.html)<br><input type="submit" value="Submit"></form> 
<hr><p><a href="https://purpleslurple.com/ps.php">PurpleSlurple</a> &#153; 
was created by <a href="mailto:matsch@sasites.com">Matthew A. Schneider</a></p>'; 
  exit; 
} 

// Do not slurp self 
// if (strpos($theurl,$file_location) !== false) 
//      die('PurpleSlurple won\'t slurp itself :-)'); //die, do not process 

// PurpleSlurple header/disclaimer and expand / collapse link 
// $ps_header = '<p>This page was generated by <a href="'.$file_location.'">PurpleSlurple</a>&#153;. 
// The original page can be found <a href="'.$theurl.'">here</a>.</p><br><a href="'.$file_location.'?collapse=no&amp;theurl='.$theurl.'">expand</a>&nbsp;|&nbsp 
// <a href="'.$file_location.'?collapse=yes&amp;theurl='.$theurl.'">collapse</a><p><hr>'; 
// // The following isn't displayed since the collapse function really doesn't 
// // work as intended - as far as I can tell. (Hans Nordhaug, 12.05.2007.) 
// $ps_header_unused = '<br><a href="'.$file_location.'?collapse=no&amp;theurl='.$theurl.'">expand</a>&nbsp;|&nbsp 
// <a href="'.$file_location.'?collapse=yes&amp;theurl='.$theurl.'">collapse</a><p><hr>'; 

// PurpleSlurple footer 
// $ps_footer = '<br style="clear:both"><hr><p style="height: 700px"> 
// <a href="https://purpleslurple.com/ps.php">PurpleSlurple</a>&#153; was created 
// by <a href="mailto:matsch@sasites.com">Matthew A. Schneider</a></p>'; 

// set base to ensure relative links work 
// Thanks to http://marc.theaimsgroup.com/?l=php-general&m=95597547227951&w=2  Duh! 
// $ps_base = "<base href='$theurl'>"; 

// $theurl = urlencode($theurl); 
$html_content = file_get_contents($theurl);
echo "html_content - line 90<br>".$html_content;

if ($html_content !== false) {
    // create a new DOMDocument instance
    $dom = new DOMDocument();
}
    // load the HTML content into the DOMDocument
    $dom->loadHTML($html_content);
    echo "<hr>dom:<br>";
    echo $dom;
    die;
    // find all the relevant HTML tags (p, h1-h6, li)
    $tags = $dom->getElementsByTagName('p');
    $tags = array_merge($tags, $dom->getElementsByTagName('h1'));
    $tags = array_merge($tags, $dom->getElementsByTagName('h2'));
    $tags = array_merge($tags, $dom->getElementsByTagName('h3'));
    $tags = array_merge($tags, $dom->getElementsByTagName('h4'));
    $tags = array_merge($tags, $dom->getElementsByTagName('h5'));
    $tags = array_merge($tags, $dom->getElementsByTagName('h6'));
    $tags = array_merge($tags, $dom->getElementsByTagName('li'));
    
    // loop through each tag and modify its content
    foreach ($tags as $tag) {
        $line_num = $tag->getLineNo();
        $tag_content = $tag->ownerDocument->saveXML($tag);
        $pattern = "/<p[^>]*>|<h[1-6][^>]*>|<li[^nk>]*>/i";
        $replacement = "$tag_content(<a href='$file_location?theurl=$theurl#purp$line_num' name='purp$line_num'><font color='purple'>$line_num</font></a>) ";
        $new_tag_content = preg_replace($pattern, $replacement, $tag_content);
        $new_tag = $dom->createDocumentFragment();
        $new_tag->appendXML($new_tag_content);
        $tag->parentNode->replaceChild($new_tag, $tag);
    }
    
    // get the modified HTML content from the DOMDocument
    $modified_html_content = $dom->saveHTML();
    echo $modified_html_content;
?>