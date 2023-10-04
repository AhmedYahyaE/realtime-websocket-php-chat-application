<?php
// This file is responsible for 'One-to-One' Chat ('Private' Chat). Check chatroom.php for Group Chat


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
		<title>Real-time One-to-One Chat Application in PHP using WebSocket Programming</title>
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
							<input type="hidden" name="is_active_chat" id="is_active_chat" value="No" />

							<div class="mt-3 mb-3 text-center">
								<div>
									<a href="chatroom.php" class="btn btn-success">Go to Group Chat</a>
								</div>
								<br>
								<img src="<?php echo $value['profile']; ?>" class="img-fluid rounded-circle img-thumbnail" width="150" />
								<h3 class="mt-2"><?php echo $value['name']; ?></h3>
								<a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
								<input type="button" class="btn btn-primary mt-2 mb-2" id="logout" name="logout" value="Logout" />
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
								// Dispaly User Online/Offline Status (based on the `user_login_status` column of the `chat_user_table` database table)
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
										$total_unread_message = '<span class="badge badge-danger badge-pill">' . $user['count_status'] . '</span>';
									}
									else // If the authenticalted/logged-in user doesn't have 'Unread' Messages received from that specific user
									{
										$total_unread_message = '';
									}


									//  Note: With the 'Group' Chat (in chatroom.php), we depended on the `user_login_status` column of `chat_user_table` table to display the Online/Offline Status of all users/clients, but with the 'One-to-One/Private' Chat (in privatechat.php), we depended on the onOpen() and onClose() methods here to send the `user_id` user id and status 'Online' or 'Offline to all users/clients on the Client Side (to be handled by JavaScript in privatechat.php inside the    conn.onmessage = function(event) {    ). And of cousre, depending on the onOpen() is the best option because it means the Online/Offline is live and instantaneous, unlike the case with depending on the `user_login_status` column

									echo "
										<a class='list-group-item list-group-item-action select_user' style='cursor:pointer' data-userid = '" . $user['user_id'] . "'>
											<img src='" . $user["user_profile"] . "' class='img-fluid rounded-circle img-thumbnail' width='50' />
											<span class='ml-1'>
												<strong>
													<span id='list_user_name_" . $user["user_id"] . "'>" . $user['user_name'] . "</span>
													<span id='userid_" . $user['user_id'] . "'>" . $total_unread_message . "</span>
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
					<h3 class="text-center">Real-time One-to-One Chat Application using Ratchet WebSocket with PHP MySQL - Online/Offline Status</h3>
					<hr />
					<br />
					<div id="chat_area"></div>
				</div>
				
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			// 'ONE-TO-ONE'/'PRIVATE' CHAT



			var receiver_userid = '';


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
				console.log('Connection Established! (One-to-One Private Chat)');
			};



			// Triggered when a message is 'received' through a WebSocket (i.e. Triggered when a message is 'received' from the backend PHP WebSocket Server) (N.B. This also includes the message SENT by the current message sender too i.e. When a user sends a message, THEY (the sender) receive this message again (his/her message) through the    conn.onmessage    function, along with all the other users who receive that message.)    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/message_event
			// Note: This    conn.onmessage    function receives all messages from our custom WebSocket handler Chat.php class
			conn.onmessage = function(event)
			{
				// Important Note: This    conn.onmessage    isn't triggered only by receiving data from the onMessage() method of the custom WebSocket handler Chat.php class, but also by receiving data from the onOpen() and onClode() method of Chat.php Class as they contain    $client->send(json_encode($data));    line of code which sends the 'user_id_status' and 'status_type' to all 'One-to-One'/Private' Chat users/clients in order to display Users and their Online/Offline Status. To check those data, type in    console.log(event);    amd    console.log(event.data);
				console.log(event);
				console.log(event.data); // Display the received data (through a WebSocket) i.e. chat message


				var data = JSON.parse(event.data);



				// Note: With the 'Group' Chat (in chatroom.php), we depended on the `user_login_status` column of `chat_user_table` table to display the Online/Offline Status of all users/clients, but with the 'One-to-One/Private' Chat (in privatechat.php), we depended on the onOpen() and onClose() methods here to send the `user_id` user id and status 'Online' or 'Offline to all users/clients on the Client Side (to be handled by JavaScript in privatechat.php inside the    conn.onmessage = function(event) {    ). And of cousre, depending on the onOpen() is the best option because it means the Online/Offline is live and instantaneous, unlike the case with depending on the `user_login_status` column
				if (data.status_type == 'Online')
				{
					$('#userstatus_'+data.user_id_status).html('<i class="fa fa-circle text-success"></i>');
				}
				else if (data.status_type == 'Offline')
				{
					$('#userstatus_' + data.user_id_status).html('<i class="fa fa-circle text-danger"></i>');
				}
				else
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


					if (receiver_userid == data.userId || data.from == 'Me')
					{
						if ($('#is_active_chat').val() == 'Yes')
						{
							var html_data = `
							<div class="`+row_class+`">
								<div class="col-sm-10">
									<div class="shadow-sm alert `+background_class+`">
										<b>`+data.from+` - </b>`+data.msg+`<br />
										<div class="text-right">
											<small><i>`+data.datetime+`</i></small>
										</div>
									</div>
								</div>
							</div>
							`;

							$('#messages_area').append(html_data);

							$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);

							$('#chat_message').val("");
						}
					}
					else
					{
						var count_chat = $('#userid'+data.userId).text();

						if (count_chat == '')
						{
							count_chat = 0;
						}

						count_chat++;

						$('#userid_'+data.userId).html('<span class="badge badge-danger badge-pill">'+count_chat+'</span>');
					}
				}
			};



			conn.onclose = function(event) // Fired when a connection with a WebSocket is closed    // https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/close_event
			{
				console.log('connection close');
			};



			function make_chat_area(user_name)
			{
				var html = `
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col col-sm-6">
									<b>Chat with <span class="text-danger" id="chat_user_name">`+user_name+`</span></b>
								</div>
								<div class="col col-sm-6 text-right">
									<a href="chatroom.php" class="btn btn-success btn-sm">Go to Group Chat</a>&nbsp;&nbsp;&nbsp;
									<button type="button" class="close" id="close_chat_area" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							</div>
						</div>
						<div class="card-body" id="messages_area">

						</div>
					</div>

					<form id="chat_form" method="POST" data-parsley-errors-container="#validation_error">
						<div class="input-group mb-3" style="height:7vh">
							<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" data-parsley-pattern="/^[a-zA-Z0-9\\s\?]+$/" required></textarea>
							<div class="input-group-append">
								<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
							</div>
						</div>
						<div id="validation_error"></div>
						<br />
					</form>
				`;

				$('#chat_area').html(html);

				$('#chat_form').parsley();
			}



			$(document).on('click', '.select_user', function(){

				receiver_userid = $(this).data('userid');

				var from_user_id = $('#login_user_id').val();

				var receiver_user_name = $('#list_user_name_'+receiver_userid).text();

				$('.select_user.active').removeClass('active');

				$(this).addClass('active');

				make_chat_area(receiver_user_name);

				$('#is_active_chat').val('Yes');

				$.ajax({
					url:"action.php",
					method:"POST",
					data:{action:'fetch_chat', to_user_id:receiver_userid, from_user_id:from_user_id},
					dataType:"JSON",
					success:function(data)
					{
						if (data.length > 0)
						{
							var html_data = '';

							for(var count = 0; count < data.length; count++)
							{
								var row_class= ''; 
								var background_class = '';
								var user_name = '';

								if (data[count].from_user_id == from_user_id)
								{
									row_class = 'row justify-content-start';

									background_class = 'alert-primary';

									user_name = 'Me';
								}
								else
								{
									row_class = 'row justify-content-end';

									background_class = 'alert-success';

									user_name = data[count].from_user_name;
								}

								html_data += `
									<div class="`+row_class+`">
										<div class="col-sm-10">
											<div class="shadow alert `+background_class+`">
												<b>`+user_name+` - </b>
												`+data[count].chat_message+`<br />
												<div class="text-right">
													<small><i>`+data[count].timestamp+`</i></small>
												</div>
											</div>
										</div>
									</div>
								`;
							}

							$('#userid_'+receiver_userid).html('');
							$('#messages_area').html(html_data);
							$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);
						}
					}
				})

			});



			$(document).on('click', '#close_chat_area', function(){
				$('#chat_area').html('');
				$('.select_user.active').removeClass('active');
				$('#is_active_chat').val('No');

				receiver_userid = '';
			});



			$(document).on('submit', '#chat_form', function(event){
				event.preventDefault();

				if ($('#chat_form').parsley().isValid())
				{
					var user_id = parseInt($('#login_user_id').val());

					var message = $('#chat_message').val();

					var data = {
						userId: user_id,
						msg: message,
						receiver_userid:receiver_userid,
						command:'private'
					};

					conn.send(JSON.stringify(data));
				}

			});



			// Logout (When the Logout button is clicked (the button is in this file)) (N.B. This changes the `user_login_status` column of the `chat_user_table` database table from 'Login' to 'Logout')
			$('#logout').click(function(){
				user_id = $('#login_user_id').val();

				$.ajax({
					url   :"action.php",
					method:"POST",
					data  : {user_id:user_id, action:'leave'},
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