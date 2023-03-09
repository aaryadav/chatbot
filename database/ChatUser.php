<?php

class ChatUser
{
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_profile;
    private $user_status;
    private $user_created_on;
    private $user_login_status;
    public $connect;

    function __construct()
    {
        require_once 'DB_Connection.php';
        $db = new DB_Connection();
        $this->connect = $db->connect();
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    function getUserName()
    {
        return $this->user_name;
    }

    function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    function getUserEmail()
    {
        return $this->user_email;
    }

    function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    function getUserPassword()
    {
        return $this->user_password;
    }

    function setUserProfile($user_profile)
    {
        $this->user_profile = $user_profile;
    }

    function getUserProfile()
    {
        return $this->user_profile;
    }

    function setUserStatus($user_status)
    {
        $this->user_status = $user_status;
    }

    function getUserStatus()
    {
        return $this->user_status;
    }

    function setUserCreatedOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }

    function getUserCreatedOn()
    {
        return $this->user_created_on;
    }

    function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }

    function getUserLoginStatus()
    {
        return $this->user_login_status;
    }

    function get_user_data_by_email($user_email)
    {
        $query = "
        SELECT * FROM users WHERE user_email = :user_email
        ";
        $statement = $this->connect->prepare($query);
        $statement->execute(
            array(
                ':user_email' => $user_email
            )
        );
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function save_data()
    {
        $query = "
		INSERT INTO users (user_name, user_email, user_password, user_status, user_created_on) 
		VALUES (:user_name, :user_email, :user_password, :user_status, :user_created_on)
		";
        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_name', $this->user_name);

        $statement->bindParam(':user_email', $this->user_email);

        $statement->bindParam(':user_password', $this->user_password);

        $statement->bindParam(':user_status', $this->user_status);

        $statement->bindParam(':user_created_on', $this->user_created_on);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update_user_login_data()
    {
        $query = "
		UPDATE users 
		SET user_login_status = :user_login_status 
		WHERE user_id = :user_id
		";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_login_status', $this->user_login_status);

        $statement->bindParam(':user_id', $this->user_id);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_user_data_by_id()
    {
        $query = "
		SELECT * FROM users 
		WHERE user_id = :user_id";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_id', $this->user_id);

        try {
            if ($statement->execute()) {
                $user_data = $statement->fetch(PDO::FETCH_ASSOC);
            } else {
                $user_data = array();
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        return $user_data;
    }

    function get_user_all_data()
    {
        $query = "
		SELECT * FROM users 
		";

        $statement = $this->connect->prepare($query);

        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

}


?>