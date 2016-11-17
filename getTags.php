<?php

// gets tags for text using ClimateTagger.net

error_log("--------- get climate tags for syllabi --------");

function removeCommonWords($input){
 // Thanks https://gist.github.com/keithmorris/4155220
 	// EEEEEEK Stop words
	$commonWords = array('0','1','2','3','4','5','6','7','8','9','syllabus','university','course','professor','prof','student','students','credit','topics','a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
 
return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}

// get the existing JSON
$f = file_get_contents("syllabi-ecology.json");
$j = json_decode($f);
$syllabi = $j->syllabi;
print count($syllabi);
print $syllabi[0]->url;

//var_dump($j);

// go through them
$ctr = 0;
$newarray = array();
foreach($syllabi as $syl){
	if (2 < 3){
	$url = $syl->url;
	print "<li>" . $url . "</li>";
	// get the syllabus text
	$html = file_get_contents($url);
	// remove the html tags
	$htmltxt = strip_tags($html);
	// remove the stop words
	$txt = removeCommonWords($htmltxt);
	// get the first 6100 characters, which is all climatetagger seems to take
	$txt = substr($txt,0,5000);
	$txt=urlencode($txt); // encode it for the web
	// get the tags from climatetagger
	$ft = file_get_contents("http://api.climatetagger.net/service/extract?token=8fabe906d5b745fbb68fd245119dc8b7&format=json&locale=en&text=" . $txt);
	
	$jt = json_decode($ft);
	//var_dump( $jt);
	//print "<p>count: " . count($jt) . "</p>";
		
	if (count($jt) > 0){
		$concepts = $jt -> concepts;
		//var_dump($concepts[0]);
		$tcount = count($concepts);
		print "<p># of tags: $tcount</p>";
		//for ($i=0; $i < $tcount; $i++){
		$newset = array();
		foreach ($concepts as $concept){
			$label = $concept->prefLabel;
			$score = $concept->score;
			$newset[] = array("label" => $label, "score" => $score);
			// $newset['label'] = $label;
// 			$newset['score'] = $score;
			print "<li> $label | $score </li>";
		}
		print "<p>json: " . $syl->url . "*" . $syl -> title . "</p>";
	
		$nurl = $syl->url;
		$syllarray = array ("url" => $nurl,
					"title" => $syl->title,
					"date_added" => $syl -> date_added,
					"snippet" => $syl -> snippet,
					"clicks" => $syl -> clicks,
					"appearances" => $syl -> appearances,
					"tags" => $newset
					);
		//$newarray[]= $syllarray;
		
		array_push($newarray, $syllarray);
		//var_dump($syllarray);
}
	
	
	$ctr++;
	} // ctr
	
	

}



// ------ Save the file
$j = json_encode($newarray);
//var_dump($j);
 
$fp = fopen("syllabi-ecology-with-tags.json", 'w');
fwrite($fp,$j);
fclose($fp);

print "<p>File written: ecotest.json";
// greenSyllabi token:
//8fabe906d5b745fbb68fd245119dc8b7

return;



?>