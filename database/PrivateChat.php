<?php
// This class (a Private Chat Message Model (of the `chat_message` table)) is used to store Private Chat messages in the `chat_message` database table



class PrivateChat
{
	private $chat_message_id;
	private $to_user_id;
	private $from_user_id;
	private $chat_message;
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
		$this->chat_message = $chat_message;
	}

	function getChatMessage()
	{
		return $this->chat_message;
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

	function get_all_chat_data() // Get the Private Chat History between two users from `chat_message` table
	{ // WHERE there is a chat history message between the two users i.e. the sender sends to the receiver or the receiver sends to the sender
		$query =
			"SELECT
				a.user_name as from_user_name, b.user_name as to_user_name, chat_message, timestamp, status, to_user_id, from_user_id  
			FROM chat_message
			INNER JOIN chat_user_table a
				ON chat_message.from_user_id = a.user_id
			INNER JOIN chat_user_table b
				ON chat_message.to_user_id = b.user_id
			WHERE
				(chat_message.from_user_id = :from_user_id AND chat_message.to_user_id = :to_user_id)
			OR
				(chat_message.from_user_id = :to_user_id   AND chat_message.to_user_id = :from_user_id)"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);
		$statement->bindParam(':to_user_id'	 , $this->to_user_id);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	function save_chat()
	{
		$query = "
		INSERT INTO chat_message 
			(to_user_id, from_user_id, chat_message, timestamp, status) 
			VALUES (:to_user_id, :from_user_id, :chat_message, :timestamp, :status)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':chat_message', $this->chat_message);

		$statement->bindParam(':timestamp', $this->timestamp);

		$statement->bindParam(':status', $this->status);

		$statement->execute();

		return $this->connect->lastInsertId();
	}

	function update_chat_status()
	{
		$query = "
		UPDATE chat_message 
			SET status = :status 
			WHERE chat_message_id = :chat_message_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':status', $this->status);

		$statement->bindParam(':chat_message_id', $this->chat_message_id);

		$statement->execute();
	}

	function change_chat_status() // Change the message `status` from 'Unread' to 'Read'
	{
		$query =
			"UPDATE chat_message 
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