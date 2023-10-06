<?php 
// User Profile Page


session_start();

if (!isset($_SESSION['user_data'])) // If the user is unauthenticated/logged-out, redirect them to the login page
{
    header('location:index.php'); // Redirect the user to the login page
}


require('database/ChatUserModel.php');


$user_object = new ChatUserModel;
$user_id = '';

// Get the user ID from the Session
foreach ($_SESSION['user_data'] as $key => $value)
{
    $user_id = $value['id'];
}

$user_object->setUserId($user_id);
$user_data = $user_object->get_user_data_by_id();


$message = '';

if (isset($_POST['edit'])) // If the Edit Profile HTML Form has been submitted (here in the same page)
{
    $user_profile = $_POST['hidden_user_profile'];

    if ($_FILES['user_profile']['name'] != '')
    {
        $user_profile = $user_object->upload_image($_FILES['user_profile']);
        $_SESSION['user_data'][$user_id]['profile'] = $user_profile;
    }

    $user_object->setUserName($_POST['user_name']);
    $user_object->setUserEmail($_POST['user_email']);
    $user_object->setUserPassword($_POST['user_password']);
    $user_object->setUserProfile($user_profile);
    $user_object->setUserId($user_id);

    if ($user_object->update_data()) // If the submitted data to update profile are successfully updated in the database table, show a success message
    {
        $message = '<div class="alert alert-success">Profile Details Updated</div>';
    }
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Real-time Chat Application in PHP using WebSocket Ratchet Library</title>
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
</head>
<body>
	<div class="container">
		<br />
        <br />
        <h1 class="text-center">Real-time One-to-One & Group Chat Application using WebSocket</h1>
        <br />
        <br />
        <?php echo $message; ?> <!-- echo the Profile Update success message -->
		<div class="card">
			<div class="card-header">
                <div class="row">
                    <div class="col-md-6">Profile</div>
                    <div class="col-md-6 text-right"><a href="group_chat.php" class="btn btn-warning btn-sm">Go to <b>Group Chat</b></a></div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" id="profile_form" enctype="multipart/form-data"> <!-- This HTML Form is without an 'action' HTML attribute, meaning it's submitted to the same page -->
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required value="<?php echo $user_data['user_name']; ?>" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required readonly value="<?php echo $user_data['user_email']; ?>" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-pattern="^[a-zA-Z0-9]+$" required value="<?php echo $user_data['user_password']; ?>" />
                    </div>
                    <div class="form-group">
                        <label>Profile</label><br />
                        <input type="file" name="user_profile" id="user_profile" />
                        <br />
                        <img src="<?php echo $user_data['user_profile']; ?>" class="img-fluid img-thumbnail mt-3" width="100" />
                        <input type="hidden" name="hidden_user_profile" value="<?php echo $user_data['user_profile']; ?>" />
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="edit" class="btn btn-success" value="Edit" />
                    </div>
                </form>
            </div>
		</div>
	</div>
</body>
</html>

<script>

$(document).ready(function(){

    $('#profile_form').parsley(); // Fire up the Parsley JavaScript Validaton Library on the Profile HTML Form

    $('#user_profile').change(function(){
        var extension = $('#user_profile').val().split('.').pop().toLowerCase();
        if (extension != '')
        {
            if (jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Invalid Image File");
                $('#user_profile').val('');
                return false;
            }
        }
    });

});

</script>