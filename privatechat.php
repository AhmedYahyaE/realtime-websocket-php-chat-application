<?php
// This file is responsible for 'One-to-One' Chat ('Private' Chat). Check chatroom.php for Group Chat
// Note: Fetching Chat History: With 'Group' Chat, we readily displayed the Chat History in chatroom.php from the `chatrooms` database table, but with 'One-to-One/Private' Chat, we displayed (fetched) the Chat History with the authenticated/logged-in user with every user through an AJAX Request to action.php.


session_start();

if (!isset($_SESSION['user_data'])) // If the user is unauthenticated / logged out, redirect them to the login page
{
	header('location:index.php');
}


require('database/ChatUser.php');
require('database/ChatRooms.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Real-time One-to-One Private Chat Application in PHP using WebSocket Programming</title>
		<!-- Bootstrap core CSS -->
		<link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>
		<link rel="icon" type="image/x-icon" href="vendor-front/bubble-chat.png"> <!-- HTML Favicon -->

		<!-- Bootstrap core JavaScript -->
		<script src="vendor-front/jquery/jquery.min.js"></script>
		<script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Core plugin JavaScript-->
		<script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>
		<script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
		<style type="text/css">
			html,
			body {
			height: 100%;
			width: 100%;
			margin: 0;
			}
			#wrapper
			{
				display: flex;
				flex-flow: column;
				height: 100%;
			}
			#remaining
			{
				flex-grow : 1;
			}
			#messages {
				height: 200px;
				background: whitesmoke;
				overflow: auto;
			}
			#chat-room-frm {
				margin-top: 10px;
			}
			#user_list
			{
				height:450px;
				overflow-y: auto;
			}

			#messages_area
			{
				height: 75vh;
				overflow-y: auto;
				/*background-color:#e6e6e6;*/
				/*background-color: #EDE6DE;*/
			}

		</style>
	</head>
	<body>
		<div class="container-fluid">
			
			<div class="row">

				<div class="col-lg-3 col-md-4 col-sm-5" style="background-color: #f1f1f1; height: 100vh; border-right:1px solid #ccc;">
					<?php
						$login_user_id = '';
						$token = '';

						foreach ($_SESSION['user_data'] as $key => $value)
						{
							$login_user_id = $value['id'];
							$token = $value['token'];
					?>
							<!-- Display authenticated/logged-in user profile data (on the left side of the page) -->
							<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" />
							<input type="hidden" name="is_active_chat" id="is_active_chat" value="No" /> <!-- This is used to control opening/closing of the private chat area (using JavaScript) when a user from the Users List (on the left side of the page) is clicked on to chat with -->

							<div class="mt-3 mb-3 text-center">
								<div>
									<a href="chatroom.php" class="btn btn-success">Go to Group Chat</a>
								</div>
								<br>
								<img src="<?php echo $value['profile']; ?>" class="img-fluid rounded-circle img-thumbnail" width="150" />
								<h3 class="mt-2"><?php echo $value['name']; ?></h3>
								<a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
								<input type="button" class="btn btn-primary mt-2 mb-2" id="logout" name="logout" value="Logout" /> <!-- Logout is done using AJAX. Check the <script> HTML tag at the bottom of this file for the AJAX call to action.php -->
							</div>
					<?php
						}
					?>



					<?php
						$user_object = new ChatUser;
						$user_object->setUserId($login_user_id);
						$user_data = $user_object->get_user_all_data_with_status_count(); // Get ALL user's data and the number/count of the 'Unread' Messages they (he/she) sent to the authenticated/logged-in user    // This method returns ALL members/users' data (in `chat_user_table`) in addition to the count/number of the 'Unread' Messages that they (he/she) sent to the authenticated/logged-in user (based on the `status` column (which denotes 'Read'/'Unread') of the `chat_message` database table)    // N.B. We'll use the if condition (check below!)    if ($user['user_id'] != $login_user_id)    to show all users/members EXCEPT the authenticated/logged-in user    // In other words, this method returns all user's data and how many 'Unread' Messages they (for every single user) have sent to the authenticated/logged-in user    // Another way to say it: This method returns the all (every) users's data and how many 'Unread' Messages the authenticated/logged-in user received from those users (every user)    // Get the number/count of the 'Unread' Messages that the authenticated/logged-in user received from all members/users
						/* echo '<pre>', var_dump($user_data), '</pre>';
						exit; */
					?>

					<!-- Display all Chat Users/Members (on the left side of the page), and their Online/Offline Status (based on the `user_login_status` column of the `chat_user_table` database table) -->
					<div class="list-group" style=" max-height: 100vh; margin-bottom: 10px; overflow-y:scroll; -webkit-overflow-scrolling: touch;">
						<?php
							foreach ($user_data as $key => $user)
							{
								// Dispaly User Online/Offline Status (here with the 'One-to-One/Private Chat case) (based on the onOpen() and onClose of the custom WebSocket handler Chat.php Class)
								// Note: For displaying User Online/Offline Status, with 'One-to-One/Private' Chat, we depended on the onOpen() and onClose() methods of the custom WebSocket handler Chat.php class (which is the best way because it's Real-time and Instantaneous), but with the 'Group' Chat, we depended on the `user_login_status` column of the `chat_user_table` database table (which is a bad idea, because a user can just close the browser and don't click on Logout, and if they don't click on Logout, the `user_login_status` column value won't be changed, then their Online/Offline Status will be always 'Online').
								$icon = '<i class="fa fa-circle text-danger"></i>'; // Show a 'red' circle to denote the User 'Offline' Status

								// If the user is authenticated/logged in (based on the `user_login_status` column of the `chat_user_table` database table, not the browser's Session), show the 'green' circle to denote the User 'Online' Status
								if ($user['user_login_status'] == 'Login')
								{
									$icon = '<i class="fa fa-circle text-success"></i>'; // Show a 'green' circle to denote the User 'Online' Status
								}


								// To display all Chat Users/Members EXCEPT the authenticated/logged-in user (We don't want to display the currently authenticated user to themselves. We want to exclude them.)
								if ($user['user_id'] != $login_user_id) // Display/Show ONLY the Unauthenticated users i.e. Show all users EXCEPT the authenticated user
								{
									if ($user['count_status'] > 0) // If the authenticated/logged-in user has 'Unread' Messages received from that specific user    // N.B. 'count_status' is the SQL Aliasing used with the Nested Query/Subquery inside the get_user_all_data_with_status_count() method of the ChatUser.php class
									{
										$total_unread_message = '<span class="badge badge-danger badge-pill">' . $user['count_status'] . '</span>'; // Show the 'Unread' messages count red-colored Push Notification
									}
									else // If the authenticalted/logged-in user doesn't have 'Unread' Messages received from that specific user
									{
										$total_unread_message = '';
									}


									//  Note: With the 'Group' Chat (in chatroom.php), we depended on the `user_login_status` column of `chat_user_table` table to display the Online/Offline Status of all users/clients, but with the 'One-to-One/Private' Chat (in privatechat.php), we depended on the onOpen() and onClose() methods here to send the `user_id` user id and status 'Online' or 'Offline to all users/clients on the Client Side (to be handled by JavaScript in privatechat.php inside the    conn.onmessage = function(event) {    ). And of cousre, depending on the onOpen() is the best option because it means the Online/Offline is live and instantaneous, unlike the case with depending on the `user_login_status` column

									// The 'select_user' CSS class is used by JavaScript down below
									// The data-* Custom HTML Data Attribute 'data-userid' is used by JavaScript down below
									echo "
										<a class='list-group-item list-group-item-action select_user' style='cursor:pointer' data-userid = '" . $user['user_id'] . "'>
											<img src='" . $user["user_profile"] . "' class='img-fluid rounded-circle img-thumbnail' width='50' />
											<span class='ml-1'>
												<strong>
													<span id='list_user_name_" . $user["user_id"] . "'>" . $user['user_name'] . "</span>
													<span id='userid_" . $user['user_id'] . "'>" . $total_unread_message . "</span> <!-- Show the 'Unread' messages count red-colored Push Notification -->
												</strong>
											</span>
											<span class='mt-2 float-right' id='userstatus_" . $user['user_id'] . "'>" . $icon . "</span>
										</a>
									";
								}
							}
						?>
					</div>
				</div>
				
				<div class="col-lg-9 col-md-8 col-sm-7">
					<br />
					<h3 class="text-center">Real-time One-to-One Private Chat Application using Ratchet WebSocket with PHP & MySQL - Online/Offline Status</h3>
					<hr />
					<br />
					<div id="chat_area"></div> <!-- We'll use JavaScript down below to load 'One-to-One/Private' Chat Area with every single chat user (when a targeted user is clicked on the left side of the page) -->
				</div>
				
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			// 'ONE-TO-ONE'/'PRIVATE' CHAT



			var receiver_userid = ''; // The user that the authenticated/logged-in user clicked on (from the Users List) to send them (he/she) a 'private' chat message


			// Handling the client side part (browser) of the WebSocket connection (using JavaScript)
			// This code is copied from: http://socketo.me/docs/hello-world#next_steps:~:text=Run%20the%20shell%20script%20again%2C%20open%20a%20couple%20of%20web%20browser%20windows%2C%20and%20open%20a%20Javascript%20console%20or%20a%20page%20with%20the%20following%20Javascript%3A
			// You can find the WebSocket object/class (a Browser Web API) documentation on: The WebSocket Browser Web API: https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API    and    The WebSocket object: https://developer.mozilla.org/en-US/docs/Web/API/WebSocket
			// Start the WebSocket connection from the client side
			// Note: With every client (user) connected to a WebSocket Connection (Ratchet library), WebSocket Connection generates and assigns every client (user) a unique connection ID (N.B. You can check that unique WebSocket identifier using     $conn->resourceId     ). And the idea of the 'One-to-One' or 'Private' Chat is based on it plus the `user_token` that we generate by ourselves with every user Login process in index.php. We can store those unique Connection IDs plust the `user_token` for every client in the database, and later we can depend on them to implement One-to-One or Private Chat. To implement the 'One-to-One' or 'Private' Chat, We use TWO table columns of the `chat_user_table`, firstly, the `user_token` column, where with every user login process we generate a unique random string using     md5(uniqid())     and then we pass this unique string as a Query String Parameter in the URL used in the client side JavaScript WebSocket constructor to start the WebSocket connection (i.e.     var conn = new WebSocket('ws://localhost:8080?token=<php echo $token; >');     ). N.B.  You can check this unique string (token) by connecting to a WebSocket Connection, then use 'Inspect' in your browser console, click on the 'Network' tab, then under 'Name' you'll find something like 'ws://localhost:8080/?token=014c3972efce8dc679b25d45a2ce2bd6', you can click on it and check the 'Headers', 'Payload', 'Initiators', ... tabs), secondly, the `user_connection_id` column which is randomly generated by WebSocket for every client connected to the WebSocket Connection (AKA WebSocket Session ID). N.B. You can access this unique identifier using     $conn->resourceId     . Check     https://www.youtube.com/watch?v=PuXfgSzDDcg&list=PLxl69kCRkiI0U4rM9RA1VBah5tfU26-Fp&index=15
			// So, We append the $token i.e. `user_token` column of `chat_user_table` to the next link (ws://localhost:8080) as a Query String Parameter (URL Parameter)
			var conn = new WebSocket('ws://localhost:8080?token=<?php echo $token; ?>'); // Create the Browser Web API WebSocket object i.e. Start the WebSocket connection!    // Initiate/Start the WebSocket connection in the browser. Check the browser's console.    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/WebSocket



			// Triggered when a WebSocket Connection is opnend    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/open_event
			conn.onopen = function(event)
			{
				// console.log(event);
				console.log('Connection Established! (One-to-One/Private Chat)');
			};



			// Triggered when a message is 'received' through a WebSocket (i.e. Triggered when a message is 'received' from the backend PHP WebSocket Server) (N.B. This also includes the message SENT by the current message sender too i.e. When a user sends a message, THEY (the sender) receive this message again (his/her message) through the    conn.onmessage    function, along with all the other users who receive that message.)    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/message_event
			// Important Note: This    conn.onmessage    isn't triggered only by receiving data from the onMessage() method of the custom WebSocket handler Chat.php class, but also by receiving data from the onOpen() and onClode() methods of Chat.php Class (to display users and their Online/Offline Status) as they contain    $client->send(json_encode($data));    line of code which sends the 'user_id_status' and 'status_type' to all 'One-to-One'/Private' Chat users/clients in order to display Users and their User Online/Offline Status. To check those data, type in    console.log(event);    and    console.log(event.data);
			// Note: This    conn.onmessage    function receives all messages from our custom WebSocket handler Chat.php class
			// Note: Sending data from any methods of the custom WebSocket Handler Chat.php Class (using    $client->send()    ) triggers the    conn.onmessage    event in JavaScript on the client side (here in this project, in privatechat.php or chatroom.php) (i.e. It doesn't trigger the    conn.onopen    or    conn.onclose    JavaScript events!)
			conn.onmessage = function(event)
			{
				// console.log(event);
				// console.log(event.data); // Log the received data through WebSocket in the browser's console (from the onMessage() method of the custom WebSocket handler Chat.php Class)


				var data = JSON.parse(event.data); // Convert the received data through WebSocket (from the onMessage() method of the custom WebSocket handler Chat.php Class) from JSON string to a JavaScript Object
				console.log(data);



				// Note: With the 'Group' Chat (in chatroom.php), we depended on the `user_login_status` column of `chat_user_table` table to display the Online/Offline Status of all users/clients, but with the 'One-to-One/Private' Chat (in privatechat.php), we depended on the onOpen() and onClose() methods here to send the `user_id` user id and status 'Online' or 'Offline to all users/clients on the Client Side (to be handled by JavaScript in privatechat.php inside the    conn.onmessage = function(event) {    ). And of cousre, depending on the onOpen() is the best option because it means the Online/Offline is live and instantaneous, unlike the case with depending on the `user_login_status` column
				// Note: For displaying User Online/Offline Status, with 'One-to-One/Private' Chat, we depended on the onOpen() and onClose() methods of the custom WebSocket handler Chat.php class (which is the best way because it's Real-time and Instantaneous), but with the 'Group' Chat, we depended on the `user_login_status` column of the `chat_user_table` database table (which is a bad idea, because a user can just close the browser and don't click on Logout, and if they don't click on Logout, the `user_login_status` column value won't be changed, then their Online/Offline Status will be always 'Online').
				if (data.status_type == 'Online') // Depending on the $data variable sent from the onOpen() method of Chat.php Class (and it's sent from onClose() method too when a WebSocket Connection is closed)
				{
					$('#userstatus_' + data.user_id_status).html('<i class="fa fa-circle text-success"></i>'); // Show a 'green' circle to denote the User 'Online' Status
				}
				else if (data.status_type == 'Offline') // Depending on the $data variable sent from the onClose() method of Chat.php Class (and it's sent from onOpen() method too when a WebSocket Connection has opened)
				{
					$('#userstatus_' + data.user_id_status).html('<i class="fa fa-circle text-danger"></i>'); // Show a 'red' circle to denote the User 'Offline' Status
				}
				else // If there's a 'normal' message received from the onMessage() method of Chat.php class, but not from either onOpen() or onClose() methods (because    data.status_type    comes ONLY from those two methods)
				{
					var row_class 		 = '';
					var background_class = '';

					// If the user is the original sender of the message i.e. If the chat message is 'sent' i.e. If the chat message is sent by the current user (i.e. the user that is currently using the browser window i.e. 'Me'), make the chat message on the left side, and also play that specific notification audio
					if (data.from == 'Me')
					{
						row_class 		 = 'row justify-content-start';
						background_class = 'alert-primary';

						// Play this specific notification sound when a chat message is 'sent'    // https://dev.to/shantanu_jana/how-to-play-sound-on-button-click-in-javascript-3m48
						var myNotificationAudioPath = 'vendor-front/sounds/mixkit-clear-announce-tones-2861.wav';
					}
					else // If the user is not the sender of the message (they're just a receiver) i.e. If the chat message is 'received' i.e. If the chat message is sent by another user (i.e. the chat message is received from another user i.e. the chat message is sent by a user other than the current user who is currently using the browser window), make the received chat message on the right side and give it a green color (using the Bootstrap 'alert-success' CSS class), and also play that OTHER specific notification audio
					{
						row_class		 = 'row justify-content-end';
						background_class = 'alert-success';

						// Play this specific notification sound when a chat message is 'received'    // https://dev.to/shantanu_jana/how-to-play-sound-on-button-click-in-javascript-3m48
						var myNotificationAudioPath = 'vendor-front/sounds/mixkit-arabian-mystery-harp-notification-2489.wav';
					}

					// Play the specific notification audio based on whether the chat message is 'sent' or 'received'
					let myAudio = new Audio(myNotificationAudioPath); // https://developer.mozilla.org/en-US/docs/Web/API/HTMLAudioElement/Audio
					myAudio.play(); // myNotificationAudioPath    was defined inside the last if-else statement block


					// Display the received data through WebSocket (from any methods of Chat.php Class (here, from onMessage() method))
					if (receiver_userid == data.userId || data.from == 'Me') // If there's a received message for the authenticated/logged-in user throug WebSocket (mostly sent from the onMessage() method of Chat.php Class) (because we're already inside    conn.onmessage    event) and while so, if the autheticated/logged-in user is already having a user's private chat area clicked/opended and that specific user has sent that authenticated/logged-in user a message (which all means the authenticated/logged-in is ONLINE/CONNECTED), OR, the authenticated/logged-in user themselves (he/she) has sent a message to some user (which also means that the authenticated/logged-in user is ONLINE/CONNECTED)
					{
						if ($('#is_active_chat').val() == 'Yes') // If the Private Chat Area (with any specific user) is opened
						{
							var html_data = `
								<div class="${row_class}">
									<div class="col-sm-10">
										<div class="shadow-sm alert ${background_class}">
											<b>${data.from} - </b>${data.msg}<br />
											<div class="text-right">
												<small>
													<i>${data.datetime}</i>
												</small>
											</div>
										</div>
									</div>
								</div>
							`;

							$('#messages_area').append(html_data); // Display the newly received private chat message (N.B. This message could either be originally sent by the currently authenticated/logged-in user themselves (he/she) or received from another user))
							$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight); // Scroll to the bottom of the 'One-to-One/Private' Chat area to show latest messages (after the message has been received through WebSocket (N.B. This message could either be originally sent by the currently authenticated/logged-in user themselves (he/she) or received from another user))
							$('#chat_message').val(""); // Empty the chat <textarea> after the chat message has been sent (in case the authenticated/logged-in user sent that message or they (he/she) received a message from another user)
						}
					}
					else // If there's a received message for the authenticated/logged-in user throug WebSocket (mostly sent from the onMessage() method of Chat.php Class) (because we're already inside    conn.onmessage    event) but the authenticated/logged-in user either didn't click on/open the sender user's private chat area (didn't open the message) or not logged in (unauthenticated), show the 'Unread' messages count/number
					{
						// console.log('Some friend has sent you a message while you are not opening their private chat area!');

						var count_chat = $('#userid' + data.userId).text();

						if (count_chat == '')
						{
							count_chat = 0;
						}

						// Problem: The counter doesn't work after the second received message! It shows only 1 (Because Javascript is a client side programming language, and number will always be 0 every time the page refreshes/loads)
						// The solutions to this problem are using localStorage on the client side (using JavaScript only) or else resorting to server side and database (e.g. store the 'Unread' messages count a database table column) techniques
						// https://www.google.com/search?q=counter+doesn%27t+work+using+websocket+in+javascript&sca_esv=570874343&sxsrf=AM9HkKlhFn_UAVqi-hUl_13b3N93HzI53Q%3A1696477195336&ei=CzAeZeSCFKfqkdUPr9KPoAE&oq=counter+doesn%27t+work+using+websocket+in+ja&gs_lp=Egxnd3Mtd2l6LXNlcnAiKmNvdW50ZXIgZG9lc24ndCB3b3JrIHVzaW5nIHdlYnNvY2tldCBpbiBqYSoCCAAyBxAhGKABGAoyBBAhGBVIv15QngdYmFVwA3gBkAEAmAGsAaAB4jCqAQQwLjQyuAEDyAEA-AEBwgIKEAAYRxjWBBiwA8ICBxAjGIoFGCfCAgQQIxgnwgIIEAAYigUYkQLCAgcQABiKBRhDwgIHEC4YigUYQ8ICChAAGIoFGLEDGEPCAgsQLhiABBixAxiDAcICERAuGIAEGLEDGIMBGMcBGNEDwgILEAAYgAQYsQMYgwHCAggQABiKBRixA8ICDRAuGIoFGLEDGIMBGEPCAhMQLhiKBRixAxiDARjHARjRAxhDwgIIEAAYgAQYsQPCAgUQABiABMICCxAAGIoFGLEDGIMBwgIEEAAYA8ICChAAGIAEGBQYhwLCAg4QLhgWGB4YxwEY0QMYCsICBhAAGBYYHsICCBAAGBYYHhgPwgIKEAAYFhgeGA8YCsICDRAAGBYYHhgPGPEEGArCAggQABgWGB4YCsICHRAuGBYYHhjHARjRAxgKGJcFGNwEGN4EGOAE2AEBwgIIEAAYigUYhgPCAggQABgFGB4YDcICCBAAGAgYHhgNwgIFECEYoAHCAggQIRgWGB4YHeIDBBgAIEGIBgGQBgi6BgYIARABGBQ&sclient=gws-wiz-serp
						// https://www.google.com/search?q=how+to+make+a+counter+retain+value+in+javascript&oq=how+to+make+a+counter+retain+value+in+javascript&gs_lcrp=EgZjaHJvbWUyCwgAEEUYChg5GKAB0gEJMTIxODJqMGo3qAIAsAIA&sourceid=chrome&ie=UTF-8
						count_chat++; // With every message received from another user but not opening it, increase the counter by +1
						// console.log(count_chat);
						// console.log('Some friend has sent you a message while you are not opening their private chat area!');


						$('#userid_' + data.userId).html('<span class="badge badge-danger badge-pill">' + count_chat + '</span>'); // Show the 'Unread' messages count/number red-colored Push Notification
					}
				}
			};



			conn.onclose = function(event) // Fired when a connection with a WebSocket is closed    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/close_event
			{
				console.log('Connection Closed!');
			};



			function make_chat_area(user_name) // When a user is clicked on to chat with (on the left side in the Private Chat)    // user_name    function parameter is the user name of the clicked user (receiver)
			{
				var html = `
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col col-sm-6">
									<b>Private Chat with: <span class="text-danger" id="chat_user_name">${user_name}</span></b>
								</div>
								<div class="col col-sm-6 text-right">
									<a href="chatroom.php" class="btn btn-success btn-sm">Go to Group Chat</a>&nbsp;&nbsp;&nbsp;
									<button type="button" class="close" id="close_chat_area" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							</div>
						</div>
						<div class="card-body" id="messages_area"> <!-- This is the Chat History area with the selected user -->

						</div>
					</div>


					<!-- Start of Private Chat HTML Form -->
					<form id="chat_form" method="POST" data-parsley-errors-container="#validation_error">
						<div class="input-group mb-3" style="height:7vh">
							<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" data-parsley-pattern="/^[a-zA-Z0-9\\s\?]+$/" required></textarea>
							<div class="input-group-append">
								<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
							</div>
						</div>

						<!-- Display Parsley Validation Errors -->
						<div id="validation_error"></div>
						<br />
					</form>
					<!-- Start of Private Chat HTML Form -->
				`;


				$('#chat_area').html(html); // Set the HTML contents of every matched element
				$('#chat_form').parsley(); // Validate Private Chat HTML Form with Parsley JavaScript library
			}

			// When the authenticated/logged-in user clicks on a user from the left side Users List to send them (he/she) a chat message, show/display the private chat area, fetch the Chat History with that user through an AJAX request, and remove the 'Unread' messages count/number red-colored Push Notification
			$(document).on('click', '.select_user', function(){
				receiver_userid  = $(this).data('userid');    // Get the selected user's               `user_id` from `chat_user_table` table (the message receiver)
				var from_user_id = $('#login_user_id').val(); // Get the autheticated/logged-in user's `user_id` from `chat_user_table` table (the message sender  )
				var receiver_user_name = $('#list_user_name_' + receiver_userid).text(); // Get the selected user's name

				$('.select_user.active').removeClass('active'); // Remove the .active CSS class from any other previously selected user (to add it on the newly selected user)
				$(this).addClass('active'); // Add the .active CSS on the newly selected user

				make_chat_area(receiver_user_name); // Show the chat area with the selected user
				$('#is_active_chat').val('Yes'); // Change the private chat area with a user from 'No' to 'Yes' to be used for different purposes


				// Fetch the private Chat History of the authenticated/logged-in user with the selected user from the `chat_message` database table using AJAX
				$.ajax({
					url     :"action.php",
					method  :"POST",
					data    :{
						action      :'fetch_chat',
						to_user_id  : receiver_userid, // The selected user (by the authenticated/logged-user) i.e. the receiver
						from_user_id: from_user_id     // The authenticated/logged-in user i.e. the sender
					},
					dataType:"JSON",
					success:function(data) // 'data' is the response (Chat History between the two users) from the server/server side/backend (action.php)
					{
						// console.log(data); // JavaScript Array
						// console.log(JSON.stringify(data)); // JavaScript Object


						if (data.length > 0) // If there's a chat history between the authenticated/logged-in user and the selected user
						{
							var html_data = '';

							for (var count = 0; count < data.length; count++)
							{
								var row_class        = ''; 
								var background_class = '';
								var user_name        = '';

								if (data[count].from_user_id == from_user_id) // If the Chat History message is sent by the authenticated/logged-in user to the selected user, show it on the left side of the chat area
								{
									row_class        = 'row justify-content-start';
									background_class = 'alert-primary';
									user_name        = 'Me';
								}
								else // If the Chat History essage is sent by the selected user to the authenticated/logged-in user, show it on the right side of the chat area
								{
									row_class = 'row justify-content-end';
									background_class = 'alert-success';
									user_name = data[count].from_user_name;
								}

								// Display/Show the Chat History message's sender name, message itself, its timestamp, and some CSS classes
								html_data += `
									<div class="${row_class}">
										<div class="col-sm-10">
											<div class="shadow alert ${background_class}">
												<b>${user_name} - </b>
												${data[count].chat_message}<br />
												<div class="text-right">
													<small><i>${data[count].timestamp}</i></small>
												</div>
											</div>
										</div>
									</div>
								`;
							}

							$('#userid_' + receiver_userid).html(''); // Remove the 'Unread' messages count/number red-colored Push Notification (because the message has been opened (seen) then)
							$('#messages_area').html(html_data); // Display/Show the Chat History messages between the authenticated/logged-in user and the selected user
							$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight); // Scroll to the bottom of the private chat area to show latest messages (after displaying/showing the History Chat message/s)
						}
					}
				})

			});



			// Close the private chat area with a user when clicking on the x button (on the far right side of the private chat area)
			$(document).on('click', '#close_chat_area', function(){
				$('#chat_area').html(''); // Remove the contents of the Private Chat area
				$('.select_user.active').removeClass('active'); // Remove the .active CSS class
				$('#is_active_chat').val('No'); // Convert the value from 'Yes' to 'No'

				receiver_userid = ''; // Empty the receiver user's variable
			});



			// Handling 'One-to-One/Private' Chat HTML Form Submission (sending messages to a one particular/specific user, NOT all users as with 'Group' Chat) (Handling sending chat messages to the onMessage() method of the custom WebSocket handler Chat.php class)
			$(document).on('submit', '#chat_form', function(event){
				event.preventDefault(); // Prevent actual HTML Form submission to avoid page refresh which can ruin user experience (i.e. Prevent form submission by HTML. JavaScript will handle form submission.)

				if ($('#chat_form').parsley().isValid()) // If the One-to-One/Private Chat HTML Form submitted data passes Parsley library validation
				{
					var user_id = parseInt($('#login_user_id').val()); // The authenticated/logged-in user's `user_id` in `chat_user_table` table
					var message = $('#chat_message').val(); // The submitted Private Chat message

					var data = { // This 'data' object will be sent to the onMessage() method of the custom WebSocket handler Chat.php Class)
						userId         : user_id,
						msg            : message,
						receiver_userid:receiver_userid,
						command        :'private' // We send this    command: 'private'    key-value pair to signal that this is a ONE-TO-ONE/PRIVATE Chat message, not a Group Chat message, to the onMessage() method in Chat.php Class
					};

					conn.send(JSON.stringify(data)); // Send the One-to-One/Private Chat message via WebSocket to the onMessage() method of our custom WebSocket handler Chat.php class in the backend    // Convert the JavaScript Object to a JSON string (to send it to the server (our custom WebSocket handler Chat.php class))
				}

			});



			// Logout (When the Logout button is clicked (the button is in this file)) (N.B. This updates the `user_login_status` column of the `chat_user_table` database table from 'Login' to 'Logout')
			$('#logout').click(function(){
				user_id = $('#login_user_id').val();

				$.ajax({
					url   :"action.php",
					method:"POST",
					data  : {
						user_id: user_id,
						action : 'leave'
					},
					success:function(data) // 'data' is the response from the server (server-side/backend). It contains the 'status' key. Check the first if condition in action.php
					{
						var response = JSON.parse(data);

						if (response.status == 1) // 'data' is the response from the server (server-side/backend). It contains the 'status' key. Check the first if condition in action.php
						{
							conn.close(); // Closes the WebSocket connection    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/close
							location = 'index.php'; // Redirect the user to the Login Page (index.php) after logging out
						}
					}
				})
			});
		})
	</script>
</html>