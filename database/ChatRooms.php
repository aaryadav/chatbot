<?php 
	
class ChatRooms
{
	private $chat_id;
	private $user_id;
	private $message;
	private $created_on;
	protected $connect;

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
		require_once("DB_Connection.php");

		$database_object = new DB_Connection();

		$this->connect = $database_object->connect();
	}

	function save_chat()
	{
		$query = "
		INSERT INTO chatrooms
			(user_id, msg, created_on) 
			VALUES (:user_id, :msg, :created_on)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		$statement->bindParam(':msg', $this->message);

		$statement->bindParam(':created_on', $this->created_on);

		$statement->execute();
	}

	function get_all_chat_data()
	{
		$query = "
		SELECT * FROM chatrooms 
			INNER JOIN users 
			ON users.user_id = chatrooms.user_id 
			ORDER BY chatrooms.room_id ASC
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
	
?>