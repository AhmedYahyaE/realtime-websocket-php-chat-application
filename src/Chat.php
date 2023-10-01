<?php
// This is our custom WebSocket handler class (that implements the 'MessageComponentInterface' of the Ratchet library)
// This class is copied from: http://socketo.me/docs/hello-world#logic
// Note: If you edit any code that is related to the WebSocket Server (i.e. Ratchet library) (example: If you edit any code in Chat.php class), you must restart the WebSocket Server in order for the changes to take effect (by opening the terminal and stopping the already running WebSocket Server by CTRL + C, then starting it again by using the    "php bin/server.php"    command).

namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require dirname(__DIR__) . "/database/ChatUser.php";
require dirname(__DIR__) . "/database/ChatRooms.php";
require dirname(__DIR__) . "/database/PrivateChat.php";



// http://socketo.me/docs/hello-world#next_steps:~:text=We%27ll%20start%20off%20by%20creating%20a%20class.%20This%20class%20will%20be%20our%20chat%20%22application%22.%20This%20basic%20application%20will%20listen%20for%204%20events
class Chat implements MessageComponentInterface { // The 'Chat' class is our custom WebSocket handler class that implements the 'MessageComponentInterface' of the Ratchet library
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo 'Server Started';
    }

    public function onOpen(ConnectionInterface $conn) {
        /* echo '<pre>', var_dump($conn), '</pre>';
        exit; */

        // Store the new connection to send messages to later
        echo 'Server Started';

        $this->clients->attach($conn);


        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if (isset($queryarray['token']))
        {

            $user_object = new \ChatUser;

            $user_object->setUserToken($queryarray['token']);

            $user_object->setUserConnectionId($conn->resourceId);

            $user_object->update_user_connection_id();

            $user_data = $user_object->get_user_id_from_token();
            
            $user_id = $user_data['user_id'];

            $data['status_type'] = 'Online';

            $data['user_id_status'] = $user_id;

            // first, you are sending to all existing users message of 'new'
            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data)); // here we are sending a status-message
            }
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) { // $msg is sent by the JavaScript conn.send() function in chatroom.php    // This method is triggered when a chat message is sent from the client side chatroom.php (via JavaScript in the <script> tag), then it takes this message and, in turn, it sends it to all other clients (receivers), including the sender themselves    // $msg    is the JSON chat message sent from the client side WebSocket ($msg is the chat message that is sent when the Chat HTML Form is submitted using JavaScript in chatroom.php. Check the <script> HTML tag in the same file (Under the "Handling Chat HTML Form Submission" Section in chatroom.php)), and it's passed on (sent again) to all other connected users' client side (i.e. Browser/JavaScript) (it's sent again to all users's client side (Browser/JavaScript) connected to the chat WebSocket)
        // echo '<pre>', var_dump($msg), '</pre>'; // Note: If you edit any code that is related to the WebSocket Server (i.e. Ratchet library) (example: If you edit any code in Chat.php class), you must restart the WebSocket Server in order for the changes to take effect (by opening the terminal and stopping the already running WebSocket Server by CTRL + C, then starting it again by using the    "php bin/server.php"    command).    // Example (JSON):     "{"userId":"11", "msg":"how are you?"}"    // This $msg (of JSON type) is sent from the client side (JavaScript) in chatroom.php
        // exit;

        // echo '<pre>', var_dump($from), '</pre>';
        // echo '<pre>', var_dump($from->resourceId), '</pre>';
        // exit;

        /* echo '<pre>', var_dump($this->clients), '</pre>';
        exit; */

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n" // This message is printed (echo-ed) inside your WebSocket Server's terminal window / Command Line window
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');


        $data = json_decode($msg, true); // Convert $msg from JSON to a PHP array    // Example (PHP array):    ['userId' => 11, 'msg' => 'how are you?']    // This $msg (of JSON type, then we converted it to a PHP array) is sent from the client side (JavaScript) in chatroom.php
        // echo '<pre>', var_dump($data), '</pre>'; // Note: If you edit any code that is related to the WebSocket Server (i.e. Ratchet library) (example: If you edit any code in Chat.php class), you must restart the WebSocket Server in order for the changes to take effect (by opening the terminal and stopping the already running WebSocket Server by CTRL+C, then starting it again by using the    "php bin/server.php"    command)
        // exit;


        // Private Chat
        // if ($data['command'] == 'private')
        if (isset($data['command']) && $data['command'] == 'private')
        {
            // Private Chat
            $private_chat_object = new \PrivateChat;
            $private_chat_object->setToUserId($data['receiver_userid']);
            $private_chat_object->setFromUserId($data['userId']);
            $private_chat_object->setChatMessage($data['msg']);
            $timestamp = date('Y-m-d h:i:s');
            $private_chat_object->setTimestamp($timestamp);
            $private_chat_object->setStatus('Yes');
            $chat_message_id = $private_chat_object->save_chat();


            $user_object = new \ChatUser;
            $user_object->setUserId($data['userId']);
            $sender_user_data = $user_object->get_user_data_by_id();
            $user_object->setUserId($data['receiver_userid']);
            $receiver_user_data = $user_object->get_user_data_by_id();
            $sender_user_name = $sender_user_data['user_name'];
            $data['datetime'] = $timestamp;
            $receiver_user_connection_id = $receiver_user_data['user_connection_id'];

            foreach ($this->clients as $client)
            {
                // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                if ($from == $client)
                {
                    $data['from'] = 'Me'; // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                }
                else
                {
                    $data['from'] = $sender_user_name; // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                }

                if ($client->resourceId == $receiver_user_connection_id || $from == $client)
                {   
                    $client->send(json_encode($data)); // The sender is not the receiver, send to each client connected    // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                }
                else
                {
                    $private_chat_object->setStatus('No');
                    $private_chat_object->setChatMessageId($chat_message_id);
                    $private_chat_object->update_chat_status();
                }
            }
        }
        else // Group Chat
        {
            // Group Chat
            $chat_object = new \ChatRooms; // To store the chat messages in the `chatrooms` database table (to display the Chat History)

            $chat_object->setUserId($data['userId']); // 'userId' which came from the client side (JavaScript) in chatroom.php using the    conn.send()    function (Under the "Handling Chat HTML Form Submission" Section in chatroom.php)
            $chat_object->setMessage($data['msg']);   // 'msg'    which came from the client side (JavaScript) in chatroom.php using the    conn.send()    function (Under the "Handling Chat HTML Form Submission" Section in chatroom.php)
            $chat_object->setCreatedOn(date("Y-m-d h:i:s"));
            $chat_object->save_chat(); // save_chat() method returns a Boolean (true or false)



            $user_object = new \ChatUser;

            $user_object->setUserId($data['userId']); // 'userId' which came from the client side (JavaScript) in chatroom.php using the    conn.send()    function (Under the "Handling Chat HTML Form Submission" Section in chatroom.php)
            $user_data = $user_object->get_user_data_by_id();

            $user_name  = $user_data['user_name']; // We get the $user_name of the message sender and the time when that message was sent (to send them to the client side as JSON in order to display them on the client side using JavaScript)
            $data['dt'] = date("d-m-Y h:i:s");     // We get the $user_name of the message sender and the time when that message was sent (to send them to the client side as JSON in order to display them on the client side using JavaScript)



            foreach ($this->clients as $client) {
                // Note: This following if condition allows sending the message to the receivers ONLY, meaning it doesn't send the message to the original sender (because they (the sender) already are the sender), but it sends it to just the receivers only. (N.B. Sending is for the client side (JavaScript in chatroom.php))
                /*if ($from !== $client) { // N.B. Don't show (send) the sent message to the original sender of this message! But show (send) it to the receivers only! (N.B. Sending is for the client side (JavaScript in chatroom.php))
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }*/

                // Note: Here we send the message to BOTH the sender and receivers (but in case of the current user is the original sender, we include    $data['from'] = 'Me'    with the message (i.e. $data variable) sent to the client side, and in case of the current user is not the sender of that message (a receiver), we include    $data['from'] = $user_name;    with the message (i.e. $data variable) sent to the client side)
                // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                if ($from == $client) // If the user is the original sender of the messsage (i.e. Send/Make    $data['from'] = 'Me'    for the original sender of the message, and Send/make it    $data['from'] = $user_name;    for the rest of the receivers of that message)
                {
                    $data['from'] = 'Me'; // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                }
                else // If the user is NOT the sender of the message (they are just a receiver) (i.e. Send/Make    $data['from'] = 'Me'    for the original sender of the message, and send/make it    $data['from'] = $user_name;    for the rest of the receivers of that message)
                {
                    $data['from'] = $user_name;  // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
                }

                // Send the $data variable (the message and other data) to the client side (Check the <script> tag in chatroom.php)
                $client->send(json_encode($data)); // The sender is not the receiver, send to each client connected    // Include the chat message sender name in the $data array to eventually convert it to JSON and send it to the client side (to be received by JavaScript)
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {

        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if (isset($queryarray['token']))
        {
            $user_object = new \ChatUser;

            $user_object->setUserToken($queryarray['token']);
            $user_data = $user_object->get_user_id_from_token();
            $user_id = $user_data['user_id'];
            $data['status_type'] = 'Offline';
            $data['user_id_status'] = $user_id;

            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data));
            }
        }

        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

?>