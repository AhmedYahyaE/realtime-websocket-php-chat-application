<?php 
// This class (a Public Chat Room Message Model) is used to store the chat messages in the `group_chat_messages` database table (to display the Chat History)



class GroupChatMessageModel
{
	// `group_chat_messages` database table column names
	private $chat_id; // The ID of the group chat message
	private $user_id; // The ID of the user who sent the group chat message
	private $group_chat_message;
	private $created_on;
	protected $connect;



	public function __construct()
	{
		// Establish the database connection
		require_once("Database_connection.php");
		$database_object = new Database_connection;
		$this->connect = $database_object->connect();
	}


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
		$this->group_chat_message = $message;
	}

	function getMessage()
	{
		return $this->group_chat_message;
	}

	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}

	function save_chat()
	{
		$query =
			"INSERT INTO group_chat_messages 
			(userid, group_chat_message, created_on) VALUES (:userid, :group_chat_message, :created_on)"
		;

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':userid'	           , $this->user_id);
		$statement->bindParam(':group_chat_message', $this->group_chat_message);
		$statement->bindParam(':created_on'		   , $this->created_on);

		$statement->execute(); // This returns a Boolean (true or false)
	}

	function get_all_chat_data()
	{
		$query =
			"SELECT * FROM group_chat_messages 
			INNER JOIN chat_application_users 
			ON chat_application_users.user_id = group_chat_messages.userid 
			ORDER BY group_chat_messages.id ASC"
		;

		$statement = $this->connect->prepare($query);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
	
?>