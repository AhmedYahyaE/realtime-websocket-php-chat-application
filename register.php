<?php

// Import the namespaces of PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



// Include the Autoloading class of Composer
require 'vendor/autoload.php';



$error = '';
$success_message = '';

if (isset($_POST["register"])) // If the registration HTML Form has been submitted
{
    session_start();

    // If the user is already authenticated/logged-in, redirect them to group_chat.php
    if (isset($_SESSION['user_data']))
    {
        header('location:group_chat.php');
    }



    require_once('database/ChatUserModel.php');



    $user_object = new ChatUserModel;

    // Fill in the ChatUserModel model with the registration HTML Form submitted data (using the Setter methods (Setters))
    $user_object->setUserName($_POST['user_name']);
    $user_object->setUserEmail($_POST['user_email']);
    $user_object->setUserPassword($_POST['user_password']);
    $user_object->setUserProfile($user_object->make_avatar(strtoupper($_POST['user_name'][0]))); //    $_POST['user_name'][0]    is the first letter in the submitted user_name
    $user_object->setUserStatus('Disabled');
    $user_object->setUserCreatedOn(date('Y-m-d H:i:s'));
    $user_object->setUserVerificationCode(md5(uniqid())); // Generate a random Verification Code to be sent to the newly registered user by email to verify their email address

    $user_data = $user_object->get_user_data_by_email(); // Check if the newly registered user's email already exists in our database table

    // If the user registers with an already existing email, show an error message, else INSERT them into the database table and send the confirmation mail to the user using PHPMailer package
    if (is_array($user_data) && count($user_data) > 0)
    {
        $error = 'This email already exists!';
    }
    else
    {
        if ($user_object->save_data()) // The save_data() method returns a Boolean (true or false)
        {
            /*
                // Send the confirmation mail to the user using PHPMailer package    // PHPMailer: https://github.com/PHPMailer/PHPMailer
                $mail = new PHPMailer(true); // Create an instance; passing `true` enables exceptions

                // Server settings
                $mail->isSMTP(); // Send using SMTP
                $mail->Host       = 'smtpout.secureserver.net'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true; // Enable SMTP authentication
                $mail->Username   = 'emailaddress@test.com'; // SMTP username
                $mail->Password   = 'password'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable implicit TLS encryption
                $mail->Port       = 80; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                // Recipients
                $mail->setFrom('tutorial@webslesson.info', 'Webslesson');
                $mail->addAddress($user_object->getUserEmail()); // Add a recipient    // Name is optional

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Registration Verification for Chat Application Demo';
                $mail->Body = '
                    <p>Thank you for registering for Chat Application Demo.</p>
                    <p>This is a verification email, please click the link to verify your email address.</p>
                    <p><a href="http://localhost:81/tutorial/chat_application/verify.php?code='.$user_object->getUserVerificationCode().'">Click to Verify</a></p>
                    <p>Thank you...</p>
                ';

                $mail->send(); // Send email
            */


            // $success_message = 'Verification Email sent to ' . $user_object->getUserEmail() . ', so before login first verify your email';
            $success_message = 'Registration Successful!';
        }
        else
        {
            $error = 'Something went wrong with registration. Please try again!';
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Register | Real-time One-to-One & Group Chat Application using WebSocket</title>

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

        <div class="containter">
            <br />
            <br />
            <h1 class="text-center">Chat Application in PHP & MySQL using WebSocket - Email Verification</h1>
            
            <div class="row justify-content-md-center">
                <div class="col col-md-4 mt-5">
                    <?php
                        // Display registration error
                        if ($error != '')
                        {
                            echo '
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    ' . $error . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            ';
                        }

                        // Display registration success message
                        if ($success_message != '')
                        {
                            echo '
                                <div class="alert alert-success">
                                    ' . $success_message . '
                                </div>
                            ';
                        }
                    ?>

                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">

                            <form method="post" id="register_form">
                                <div class="form-group">
                                    <label>Enter Your Name</label>
                                    <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                                </div>

                                <div class="form-group">
                                    <label>Enter Your Email</label>
                                    <input type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required />
                                </div>

                                <div class="form-group">
                                    <label>Enter Your Password</label>
                                    <input type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-pattern="^[a-zA-Z0-9]+$" required />
                                </div>

                                <div class="form-group text-center">
                                    <input type="submit" name="register" class="btn btn-success" value="Register" />
                                </div>
                            </form>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </body>

</html>



<script>

    $(document).ready(function(){

        $('#register_form').parsley();

    });

</script>