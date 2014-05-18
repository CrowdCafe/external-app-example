<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'settings.php';
require_once 'libs/firebase/firebaseLib.php';



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
	$headers = array('Authorization: Token ' . $crowdcafe_token);

	$curl = curl_init(); //инициализация сеанса
	curl_setopt($curl, CURLOPT_URL, $crowdcafe_call_url); //урл сайта к которому обращаемся
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); //выводим заголовки
	curl_setopt($curl, CURLOPT_POST, 1); //передача данных методом POST
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //теперь curl вернет нам ответ, а не выведет
	curl_setopt($curl, CURLOPT_POSTFIELDS, //тут переменные которые будут переданы методом POST
		$item);
	curl_setopt($curl, CURLOPT_USERAGENT, 'MSIE 8'); //эта строчка как-бы говорит: "я не скрипт, я IE5" :)
	curl_setopt ($curl, CURLOPT_REFERER, "http://ya.ru"); //а вдруг там проверяют наличие рефера
	$res = curl_exec($curl);
	var_dump($res);
	//если ошибка то печатаем номер и сообщение

	#$context  = stream_context_create($options);
	#$result = file_get_contents($crowdcafe_call_url, false, $context);

}else{
	print 'image-url does not exist in POST';
}

?>