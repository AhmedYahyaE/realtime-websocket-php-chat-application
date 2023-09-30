<?php 
session_start();

if (!isset($_SESSION['user_data']))
{
	header('location:index.php');
}

require('database/ChatUser.php');

require('database/ChatRooms.php');

$chat_object = new ChatRooms;

$chat_data = $chat_object->get_all_chat_data();

$user_object = new ChatUser;

$user_data = $user_object->get_user_all_data();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Real-time Chat application in php using WebSocket programming</title>
		<!-- Bootstrap core CSS -->
		<link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

		<link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

		<link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

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
				height: 650px;
				overflow-y: auto;
				background-color:#e6e6e6;
			}

		</style>
	</head>
	<body>
		<div class="container">
			<br />
			<h3 class="text-center">Real-time One-to-One Chat App using Ratchet WebSocket with PHP MySQL - Online/Offline Status</h3>
			<br />
			<div class="row">



				<!-- Chat Area Start -->
				<div class="col-lg-8">
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col col-sm-6">
									<h3>Chat Room</h3>
								</div>
								<div class="col col-sm-6 text-right">
									<a href="privatechat.php" class="btn btn-success btn-sm">Private Chat</a>
								</div>
							</div>
						</div>
						<div class="card-body" id="messages_area"> <!-- This    id="messages_area"    is used down at the bottom by JavaScript to append chat messages -->
							<?php
								foreach ($chat_data as $chat)
								{
									if (isset($_SESSION['user_data'][$chat['userid']]))
									{
										$from = 'Me';
										$row_class = 'row justify-content-start';
										$background_class = 'text-dark alert-light';
									}
									else
									{
										$from = $chat['user_name'];
										$row_class = 'row justify-content-end';
										$background_class = 'alert-success';
									}

									echo '
										<div class="'.$row_class.'">
											<div class="col-sm-10">
												<div class="shadow-sm alert '.$background_class.'">
													<b>'.$from.' - </b>'.$chat["msg"].'
													<br />
													<div class="text-right">
														<small><i>'.$chat["created_on"].'</i></small>
													</div>
												</div>
											</div>
										</div>
									';
								}
							?>
						</div>
					</div>

					<!-- Chat HTML Form -->
					<form method="post" id="chat_form" data-parsley-errors-container="#validation_error"> <!-- This Chat HTML Form submission is handled by JavaScript down below at the bottom of this file -->
						<div class="input-group mb-3">
							<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" required></textarea>
							<div class="input-group-append">
								<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
							</div>
						</div>

						<!-- Display Parsley library Validation Errors of the Chat HTML Form -->
						<div id="validation_error"></div>
					</form>
				</div>
				<!-- Chat Area End -->



				<div class="col-lg-4">
					<?php

						$login_user_id = '';

						foreach ($_SESSION['user_data'] as $key => $value) // User's Session was planted in index.php (upon login)
						{
							// echo $key . '=>' . print_r($value) . '<br>';
							$login_user_id = $value['id'];
					?>
							<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" />
							<div class="mt-3 mb-3 text-center">
								<img src="<?php echo $value['profile']; ?>" width="150" class="img-fluid rounded-circle img-thumbnail" />
								<h3 class="mt-2"><?php echo $value['name']; ?></h3>
								<a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
								<input type="button" class="btn btn-primary mt-2 mb-2" name="logout" id="logout" value="Logout" /> <!-- Logout is done using AJAX. Check the <script> HTML tag at the bottom of this file for the AJAX call. -->
							</div>
					<?php
						}
					?>

					<div class="card mt-3">
						<div class="card-header">User List</div>
						<div class="card-body" id="user_list">
							<div class="list-group list-group-flush">
							<?php
							if (count($user_data) > 0)
							{
								foreach ($user_data as $key => $user)
								{
									$icon = '<i class="fa fa-circle text-danger"></i>';

									if ($user['user_login_status'] == 'Login')
									{
										$icon = '<i class="fa fa-circle text-success"></i>';
									}

									if ($user['user_id'] != $login_user_id)
									{
										echo '
										<a class="list-group-item list-group-item-action">
											<img src="'.$user["user_profile"].'" class="img-fluid rounded-circle img-thumbnail" width="50" />
											<span class="ml-1"><strong>'.$user["user_name"].'</strong></span>
											<span class="mt-2 float-right">'.$icon.'</span>
										</a>
										';
									}

								}
							}
							?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			// Handling the client side part (browser) of the WebSocket connection (using JavaScript)
			// This code is copied from: http://socketo.me/docs/hello-world#next_steps:~:text=Run%20the%20shell%20script%20again%2C%20open%20a%20couple%20of%20web%20browser%20windows%2C%20and%20open%20a%20Javascript%20console%20or%20a%20page%20with%20the%20following%20Javascript%3A
			// You can find the WebSocket object/class (a Browser Web API) documentation on: The WebSocket Browser Web API: https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API    and    The WebSocket object: https://developer.mozilla.org/en-US/docs/Web/API/WebSocket
			var conn = new WebSocket('ws://localhost:8080'); // Create the Browser Web API WebSocket object i.e. Start the WebSocket connection!    // Initiate/Start the WebSocket connection in the browser. Check the browser's console.
			console.log(conn);

			conn.onopen = function(e) {
				console.log("Connection established!");
			};

			conn.onmessage = function(e) { // When a chat message is sent or received
				console.log(e.data); // Display the sent or received chat message

				var data = JSON.parse(e.data);

				var row_class = '';

				var background_class = '';

				if (data.from == 'Me')
				{
					row_class = 'row justify-content-start';
					background_class = 'text-dark alert-light';
				}
				else
				{
					row_class = 'row justify-content-end';
					background_class = 'alert-success';
				}

				var html_data = "<div class='" + row_class + "'><div class='col-sm-10'><div class='shadow-sm alert " + background_class + "'><b>" + data.from + " - </b>" + data.msg + "<br /><div class='text-right'><small><i>" + data.dt + "</i></small></div></div></div></div>";

				$('#messages_area').append(html_data);

				$("#chat_message").val(""); // Empty the chat <textarea> after the chat message has been sent
			};

			$('#chat_form').parsley(); // Fire up Parsley JavaScript form validation library on the Chat HTML Form

			console.log($('#messages_area')[0]);
			console.log($('#messages_area'));

			$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight); // Scroll to the bottom of the chat area after submitting the chat message



			// Handling Chat HTML Form Submission (Sending chat messages)
			$('#chat_form').on('submit', function(event) { // When the Chat HTML Form is submitted
				event.preventDefault(); // Prevent actual HTML Form submission to avoid page refresh which can ruin user experience (i.e. Prevent form submission by HTML. JavaScript will handle form submission.)

				if ($('#chat_form').parsley().isValid()) // If the submitted data passes Parsley library validation
				{
					var user_id = $('#login_user_id').val();
					var message = $('#chat_message').val(); // The chat message written by a user in the assigned chat <textarea>
					var data    = {
						userId : user_id,
						msg    : message
					};

					conn.send(JSON.stringify(data)); // Send the chat message via WebSocket (to our custom WebSocket handler Chat.php class in the backend)    // Convert the JavaScript object to a JSON string

					$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight); // Scroll to the bottom of the chat area after submitting the chat message
				}

			});
			
			// Logout (When the Logout button is clicked (the button is in this file))
			$('#logout').click(function(){

				user_id = $('#login_user_id').val();

				$.ajax({
					url   : "action.php",
					method: "POST",
					data  : {user_id:user_id, action:'leave'},
					success:function(data) // 'data' is the response from the server. It contains the 'status' key. Check the first if condition in action.php
					{
						var response = JSON.parse(data);

						if (response.status == 1) // 'data' is the response from the server. It contains the 'status' key. Check the first if condition in action.php
						{
							conn.close();
							location = 'index.php'; // Redirect the user to the Login Page (index.php)
						}
					}
				})

			});

		});

	</script>
</html>