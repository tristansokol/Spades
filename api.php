<?php

//The spades api's function is to be called via ajax requests from the main spades page, and then do all the hard work of storing the bids/scores/games 
//
//
//Two types of requests should be sent to the api
//CREATE:
//vars: Player1,player2,player3,player4
//

switch ($_POST['type']) {
	case 'create':
	//load the players from the POST variable

	$gameID = clean(base64_encode($_POST['p1'].$_POST['p2'].$_POST['p3'].$_POST['p4'].date("m-d-y")));

	$data = array('players'=>array($_POST['p1'],$_POST['p2'],$_POST['p3'],$_POST['p4']),'hands'=>array(array('bids'=>array(0,0,0,0),'takes'=>array(0,0,0,0))));
	
	if(!file_exists('games/'.$gameID.".spades")){
		file_put_contents('games/'.$gameID.".spades", json_encode($data), LOCK_EX);
	}
	echo $gameID;
	break;
	case 'update':
	$gameID = $_POST['game'];
	$data = json_decode( file_get_contents('games/'.$gameID.'.spades'));
	var_dump($_POST);
	foreach ($_POST as $key => $value) {
		if (substr($key, 0,4)=='hand'){
			if(is_numeric(substr($key, 4,2))){
				$handnumber = substr($key, 4,2);
			}else {
				$handnumber = substr($key, 4,1);
			}
			if (strpos($key, 'bid')) {
				var_dump($data->hands[$handnumber]->bids);
				$data->hands[$handnumber]->bids[substr($key, -1)-1]=$value;
			}elseif (strpos($key, 'take')) {
				$data->hands[$handnumber]->takes[substr($key, -1)-1]=$value;
			}
		}
	}

	var_dump($data);
	file_put_contents('games/'.$gameID.".spades", json_encode($data), LOCK_EX);

	break;
	default:
	exit('Error: request type not set');
	break;
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

?>