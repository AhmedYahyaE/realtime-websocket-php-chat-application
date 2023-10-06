<?php

session_start();

// Logout script: (AJAX Request from group_chat.php and private_chat.php): If the Logout button has been clicked in group_chat.php and private_chat.php, and the HTTP Request has been sent/done via AJAX
if (isset($_POST['action']) && $_POST['action'] == 'leave')
{
	require('database/ChatUserModel.php');

	$user_object = new ChatUserModel;

	$user_object->setUserId($_POST['user_id']);
	$user_object->setUserLoginStatus('Logout'); // We change the login status (the `user_login_status` column) of the user in the `chat_application_users` database table in order to display User Online/Offline Status to show the correct color (Green (Online) / Red (Offline)) Status of the User Login Status i.e. Login Status for the user (either Online (logged-in) or Offline (logged-out))    // Note: For displaying User Online/Offline Status, with 'One-to-One/Private' Chat, we depended on the onOpen() and onClose() methods of the custom WebSocket handler Chat.php class (which is the best way because it's Real-time and Instantaneous), but with the 'Group' Chat, we depended on the `user_login_status` column of the `chat_application_users` database table (which is a bad idea, because a user can just close the browser and don't click on Logout, and if they don't click on Logout, the `user_login_status` column value won't be changed, then their Online/Offline Status will be always 'Online').
	$user_object->setUserToken($_SESSION['user_data'][$_POST['user_id']]['token']);

	if ($user_object->update_user_login_data())
	{
		unset($_SESSION['user_data']);
		session_destroy();
		echo json_encode([ // Send this JSON data as a response to the client (browser) (Check the AJAX request at the bottom of the group_chat.php page)
			'status' => 1
		]);
	}
}



// AJAX Request from private_chat.php: Fetch the Private Chat History from the `private_chat_messages` table between the authenticated/logged-in user and the user they (the authenticated/logged-in user) selected/clicked on to chat with (in private_chat.php)
if (isset($_POST["action"]) && $_POST["action"] == 'fetch_chat')
{
	require 'database/PrivateChatMessageModel.php';

	$private_chat_object = new PrivateChatMessageModel;

	$private_chat_object->setFromUserId($_POST["to_user_id"]); // The selected user's ID `to_user_id` in `private_chat_messages` table (by the authenticated/logged-user) i.e. the receiver
	$private_chat_object->setToUserId($_POST["from_user_id"]); // The authenticated/logged-in user's ID `from_user_id` in `private_chat_messages` table i.e the sender
	$private_chat_object->change_chat_status(); // Convert the chat message `status` column in `private_chat_messages` table from 'Unread' to 'Read'

	echo json_encode($private_chat_object->get_all_chat_data());
}


?>