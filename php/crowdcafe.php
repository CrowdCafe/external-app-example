<?php

require_once 'libs/firebase/firebaseLib.php';

for ($i=0; $i<intval($_POST['length']);$i++){
	$data = json_decode($_POST['data'][$i]);
	var_dump($data);
	print $data->_id;
	print $data->_tags;
	$firebase = new Firebase('https://image-tagging-app.firebaseio.com/images/'.$data->_id, 'Ud2nEpZOmqhfs0eytmwf4XtCH2NZQCnpeq3YNHBw');
	print $firebase->set('tags', $data->_tags);
}

?>