<?php
// This class (a Private Chat Message Model (of the `private_chat_messages` table)) is used to store Private Chat messages in the `private_chat_messages` database table



class PrivateChatMessageModel
{
	private $chat_message_id;
	private $to_user_id;
	private $from_user_id;
	private $private_chat_message;
	private $timestamp;
	private $status; // 'Yes' or 'No' which denotes a message is either 'Read' or 'Unread'
	protected $connect;



	public function __construct()
	{
		// Establish the database connection
		require_once('Database_connection.php');
		$db = new Database_connection();
		$this->connect = $db->connect();
	}


	// Setters and Getters
	function setChatMessageId($chat_message_id)
	{
		$this->chat_message_id = $chat_message_id;
	}

	function getChatMessageId()
	{
		return $this->chat_message_id;
	}

	function setToUserId($to_user_id)
	{
		$this->to_user_id = $to_user_id;
	}

	function getToUserId()
	{
		return $this->to_user_id;
	}

	function setFromUserId($from_user_id)
	{
		$this->from_user_id = $from_user_id;
	}

	function getFromUserId()
	{
		return $this->from_user_id;
	}

	function setChatMessage($chat_message)
	{
		$this->private_chat_message = $chat_message;
	}

	function getChatMessage()
	{
		return $this->private_chat_message;
	}

	function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	function getTimestamp()
	{
		return $this->timestamp;
	}

	function setStatus($status)
	{
		$this->status = $status;
	}

	function getStatus()
	{
		return $this->status;
	}

	function get_all_chat_data() // Get the Private Chat History between two users from `private_chat_messages` table
	{ // WHERE there is a chat history message between the two users i.e. the sender sends to the receiver or the receiver sends to the sender
		$query =
			"SELECT
				a.user_name as from_user_name, b.user_name as to_user_name, private_chat_message, timestamp, status, to_user_id, from_user_id  
			FROM private_chat_messages
			INNER JOIN chat_application_users a
				ON private_chat_messages.from_user_id = a.user_id
			INNER JOIN chat_application_users b
				ON private_chat_messages.to_user_id = b.user_id
			WHERE
				(private_chat_messages.from_user_id = :from_user_id AND private_chat_messages.to_user_id = :to_user_id)
			OR
				(private_chat_messages.from_user_id = :to_user_id   AND private_chat_messages.to_user_id = :from_user_id)"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);
		$statement->bindParam(':to_user_id'	 , $this->to_user_id);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	function save_chat() // Save 'One-to-One/Private' Chat messages between two users
	{
		$query =
			"INSERT INTO private_chat_messages 
				(to_user_id, from_user_id, private_chat_message, timestamp, status) 
			VALUES
				(:to_user_id, :from_user_id, :private_chat_message, :timestamp, :status)"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':to_user_id'          , $this->to_user_id);
		$statement->bindParam(':from_user_id'        , $this->from_user_id);
		$statement->bindParam(':private_chat_message', $this->private_chat_message);
		$statement->bindParam(':timestamp'   		 , $this->timestamp);
		$statement->bindParam(':status'      	     , $this->status);

		$statement->execute();

		return $this->connect->lastInsertId(); // Return the ID of the last inserted One-to-One/Private Chat message
	}

	function update_chat_status()
	{
		$query =
			"UPDATE private_chat_messages 
			SET status = :status 
			WHERE chat_message_id = :chat_message_id"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':status'         , $this->status);
		$statement->bindParam(':chat_message_id', $this->chat_message_id);

		$statement->execute();
	}

	function change_chat_status() // Change the message `status` from 'Unread' to 'Read'
	{
		$query =
			"UPDATE private_chat_messages
			SET status = 'Yes' 
			WHERE from_user_id = :from_user_id 
			AND to_user_id = :to_user_id 
			AND status = 'No'"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);
		$statement->bindParam(':to_user_id'  , $this->to_user_id);

		$statement->execute();
	}

}



?>