<?php

// Temporary fix.
// Gets "environment" returns and adds them to prior "ecology" returns, 
// after deduping. 

error_reporting(E_ALL);
error_reporting (E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);

// get mysql login info, which we are presuming are in a local file
$loginfile = file_get_contents("login.json");
$j =  json_decode( $loginfile);

$user = $j -> user;
$pwd = $j->pwd;

error_log("----------------- syllabi merge --------");


 DEFINE('DB_USERNAME', $user);
 DEFINE('DB_PASSWORD', $pwd);
 DEFINE('DB_HOST', 'localhost');
 DEFINE('DB_DATABASE', 'syllabi');
 
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

// get existing results, for ecology

$feco = file_get_contents("syllabi.json");
$jeco = json_decode($feco);
//print "cj: " . count($jeco);
// build array of urls
$a = array();
foreach ($jeco as $key){
// $v = $key -> url;
// print $v;
	$a[] = $key -> url;
}

print "<p>count of priors: " . count($a) . "</p>";


// connect to the database
 $dbh = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


// on error
 if (mysqli_connect_error()) {
  die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
 }

 print 'Connected successfully.';
 
 // The Query. Alter to suit.
 $query="select url,title,date_added,google_snippet,appearances,clicked_on from syllabi where (title like  '%environment%')";
 
 // do the query
 $res = $dbh->query($query);
	

// put the returns into an array
$syllabi = array(); 
$ctr = 0;
$noctr = 0;
$acount = count($a); // how many in first json set already recorded
 while ($row=mysqli_fetch_assoc($res)) {
 	error_log(" . $ctr");
	if ($ctr < 2808){ // for debugging
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
	
	// is this a dupe
	$dupe = false; $done=false;
	$i = 0;
	//error_log("line 96");
	while (!$done){
		if ($a[$i] == $row['url']){
			$dupe = true;
			$done = true;
			print "<p>FOUND DUPE: " . $a[$i] . "</p>";
			error_log("Found Dupe: $a[$i]");
		}
		else {
			$i++;
			if ($i >= $acount){
				$done = true;
			}
		}
		
		}
	
      if ($dupe == false){
		 
	 // add this syllabus to the array of arrays if it exists
	$url = $row['url'];
	 if (url_exists($url)){
		print "<li><b>$ctr: GOT ONE: " . $url . "</b></li>";
		$syllabi[] = $syllabus;
	}
	else{
		$noctr++;
		print "<li>$ctr) " . $url . " doesn't exist.</li>";
	}
	}
	$ctr++;
  } // ctr limit for debugging
		
	 }

print "<p>number of records: " . count($syllabi);


$dbh->close();
 

// Save the file
$j = json_encode($syllabi);
 
$fp = fopen('syllabi-environment-nodupes.json', 'w');
fwrite($fp,$j);
fclose($fp);

print "<p>syllabi.json written.</p>";
print "<p>Total relevant files in database: " . $ctr . "</p>";
print "<p>Files no longer online: " . $noctr . "</p>";
print "<p><b>Total online files found: " . ($ctr - $noctr) . "</b></p>";

return;

?>




