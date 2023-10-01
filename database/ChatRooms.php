<?php 
// This class (a Chat Room Message Model) is used to store the chat messages in the `chatrooms` database table (to display the Chat History)



class ChatRooms
{
	// `chatrooms` database table column names
	private $chat_id; // The ID of the chat message
	private $user_id; // The ID of the user who sent the message
	private $message;
	private $created_on;
	protected $connect;

	// Setters and Getters
	public function setChatId($chat_id)
	{
		$this->chat_id = $chat_id;
	}

	function getChatId()
	{
		return $this->chat_id;
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setMessage($message)
	{
		$this->message = $message;
	}

	function getMessage()
	{
		return $this->message;
	}

	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}

	public function __construct()
	{
		// Establish the database connection
		require_once("Database_connection.php");
		$database_object = new Database_connection;
		$this->connect = $database_object->connect();
	}

	function save_chat()
	{
		$query =
			"INSERT INTO chatrooms 
			(userid, msg, created_on) VALUES (:userid, :msg, :created_on)"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':userid'	   , $this->user_id);
		$statement->bindParam(':msg'	   , $this->message);
		$statement->bindParam(':created_on', $this->created_on);

		$statement->execute(); // This returns a Boolean (true or false)
	}

	function get_all_chat_data()
	{
		$query =
			"SELECT * FROM chatrooms 
			INNER JOIN chat_user_table 
			ON chat_user_table.user_id = chatrooms.userid 
			ORDER BY chatrooms.id ASC"
		;

		$statement = $this->connect->prepare($query);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
	
?>