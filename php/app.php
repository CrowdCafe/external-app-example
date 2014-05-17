<?php 
$crowdcafe_token = "96103ac828737da6fb84ddd036127b36ca092ae6";
$crowdcafe_api_url = "http://localhost:8000/api/";
$crowdcafe_task_id = 13;


require_once '../libs/firebase/firebaseLib.php';

function setFirebaseValue($path, $value) {
	$firebase = new Firebase('https://image-tagging-app.firebaseio.com/', 'Ud2nEpZOmqhfs0eytmwf4XtCH2NZQCnpeq3YNHBw');
	return $firebase->push($path, $value);
}

$crowdcafe_call_url = $crowdcafe_api_url.'jobs/'.$crowdcafe_task_id.'/items/upload/';


if ($_POST['image-url']){

	$image_url = $_POST['image-url'];
	$data = json_decode('{"tags":" ","url":"'.$image_url.'"}');
	$new_record = setFirebaseValue('images',$data);

	$item = array(
		'id' => json_decode($new_record)->name,
		'url' => $image_url,
		);

	$options = array(
		'http' => array(
			'header'  => 
			"Authorization: Token ".$crowdcafe_token."\r\n". 
			"Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($item),
			),
		);
	$context  = stream_context_create($options);
	$result = file_get_contents($crowdcafe_call_url, false, $context);

}else{
	print 'image-url does not exist in POST';
}
?>