<?php

// uses Dan Cohen's 2009 database of one million online syllabi to locate ones with
// particular words in their title. produces a json file.
// This is Open Source, but it is written by a total amateur so you'd
// be better off looking somewhere else.
//
//  David Weinberger
//  david@weinberger.org
//  Last updated: Nov. 12, 2016

// get mysql login info, which we are presuming is in a local file
$loginfile = file_get_contents("login.json");
$j =  json_decode( $loginfile);
$user = $j -> user;
$pwd = $j->pwd;


 DEFINE('DB_USERNAME', $user);
 DEFINE('DB_PASSWORD', $pwd);
 DEFINE('DB_HOST', 'localhost');
 DEFINE('DB_DATABASE', 'syllabi');
 $savefilename = "syllabi.json"; // alter to suit
 
// Does the url resolved to an available page?
//http://stackoverflow.com/questions/2280394/how-can-i-check-if-a-url-exists-via-php
function url_exists($url) {
	$headers = @get_headers($url);
    if(strpos($headers[0],'200')===false){
    	return false;
    }
    else {
    	return true;
    }
}

//------------ MAIN ---------------
// connect to the database
 $dbh = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// on error
 if (mysqli_connect_error()) {
  die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
 }
 print '<p>Connected successfully.</p>';
 
 // The Query. Alter to suit.
 $query="select url,title,date_added,google_snippet,appearances,clicked_on from syllabi where (title like '%ecolog%' OR title like '%environment%')";
 
 // do the query
 $res = $dbh->query($query);
	

// put the returns into an array
$syllabi = array(); 
$ctr = 0;
$noctr = 0;
 while ($row=mysqli_fetch_assoc($res)) {
	//if ($ctr < 10){ // for debugging
	$syllabus = array(
		'url' => $row['url'],
		'title' => $row['title'],
		'date_added' => $row['date_added'],
		'snippet' => $row['google_snippet'],
		'clicks' => $row['clicked_on'],
		'appearances' => $row['appearances']
	
	);
      	

	 // debugging
	// print("<li>title: " . $syllabus['title'] . "url: " . $syllabus['url'] . " appearances:" . $syllabus['appearances'] . "date: " . $syllabus['date_added'] .  "snip: " . $syllabus['snippet'] . " clicks" . $syllabus['clicks'] . "</li>");
	 
	 // add this syllabus to the array of arrays if it exists
	$url = $row['url'];
	 if (url_exists($url)){
		print "<li><b>$ctr: GOT ONE: " . $url . "</b></li>";
		$syllabi[] = $syllabus;
	}
	else{ // the page is no longer online
		$noctr++;
		print "<li>$ctr) " . $url . " doesn't exist.</li>";
	}
	$ctr++;
//}
		
	 }

print "<p>number of records: " . count($syllabi) . "</p>";


$dbh->close();
 

// ------ Save the file
$j = json_encode($syllabi);
 
$fp = fopen($savefilename, 'w');
fwrite($fp,$j);
fclose($fp);

print "<p>$savefilename written.</p>";
print "<p>Total relevant files in database: " . $ctr . "</p>";
print "<p>Files no longer online: " . $noctr . "</p>";
print "<p><b>Total online files found: " . ($ctr - $noctr) . "</b></p>";

return;

?>




