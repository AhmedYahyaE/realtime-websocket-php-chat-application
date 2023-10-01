<?php

session_start();

// Logout script: If the Logout button has been clicked in chatroom.php file, and the HTTP Request has been sent/done via AJAX
if (isset($_POST['action']) && $_POST['action'] == 'leave')
{
	require('database/ChatUser.php');

	$user_object = new ChatUser;

	$user_object->setUserId($_POST['user_id']);
	$user_object->setUserLoginStatus('Logout'); // We change the login status (the `user_login_status` column) of the user in the `chat_user_table` database table in order to show the correct color (Green (Online) / Red (Offline)) Status of the User Login Status i.e. Login Status for the user (either Online (logged-in) or Offline (logged-out))
	$user_object->setUserToken($_SESSION['user_data'][$_POST['user_id']]['token']);

	if ($user_object->update_user_login_data())
	{
		unset($_SESSION['user_data']);
		session_destroy();
		echo json_encode([ // Send this JSON data as a response to the client (browser) (Check the AJAX request at the bottom of the chatroom.php page)
			'status' => 1
		]);
	}
}



if (isset($_POST["action"]) && $_POST["action"] == 'fetch_chat')
{
	require 'database/PrivateChat.php';

	$private_chat_object = new PrivateChat;

	$private_chat_object->setFromUserId($_POST["to_user_id"]);

	$private_chat_object->setToUserId($_POST["from_user_id"]);

	$private_chat_object->change_chat_status();

	echo json_encode($private_chat_object->get_all_chat_data());
}


?>