<?php

$limit 		= "limit=10";
$before_wid	= "";
$first_wid	= "";
$ch 		= curl_init();
$args		= "$limit&before_wid=$before_wid";
$url 		= "https://hackproxy.whisper.sh/whispers/latest?$args";


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	TRUE);	//Return the result
curl_setopt($ch, CURLOPT_FAILONERROR,		TRUE);
curl_setopt($ch, CURLOPT_HTTPGET, 			TRUE);	//Set mode to GET	
curl_setopt($ch, CURLOPT_URL, 				$url);	//set URL	
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 	false);	// Disable SSL verification
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Version: ios_3.0.0"));

$jsonDat = curl_exec($ch);
curl_close($ch);
$dat = json_decode($jsonDat);
$dat = $dat->latest;
$locations = array();
$first_wid = $dat[0]->wid;
foreach ($dat as $item){
	$before_wid = $item->wid;
	$curr = $item->places;
	if(sizeof($curr) ==2)
	{
		$locality = $curr[0]->name;
		$region	= $curr[1]->name;
		$locations[] = "$locality $region";
	}
}
echo json_encode($locations);
var_dump($locations);


?>