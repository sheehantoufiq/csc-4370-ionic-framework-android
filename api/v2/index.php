<?php

header('Content-Type: application/json');

require 'Slim/Slim.php';

$app = new Slim();

// User Rest Endpoints
$app->get(		'/users', 			'getUsers');
$app->get(		'/users/:id', 	'getUser');
$app->post(		'/add_user', 		'addUser');
$app->post(		'/login', 			'loginUser');
$app->put(		'/users/:id', 	'updateUser');
$app->delete(	'/users/:id', 	'deleteUser');

// User Getter and Setter
$app->post(		'/set_user', 		'saveUserinfo');
$app->get(		'/get_user', 		'getUserinfo');

// Inventory Rest Endpoints
$app->get(		'/items/:userId', 			'getItems');
$app->get(		'/items/:userId/:id', 	'getItem');
$app->post(		'/add_item', 						'addItem');
$app->put(		'/items/:userId/:id', 	'updateItem');
$app->delete(	'/items/:userId/:id', 	'deleteItem');

$app->run();

class savedUser {
	public $userSetter = '';
	
	public function setUser($userSetter) {
		$this->userSetter = $userSetter;
	}

	public function getUser() {
		return $this->userSetter;
	}
}

$userID = new savedUser();

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="root";
	$dbname="shelfspace";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

// User Methods

function saveUserinfo() {
	global $app;
	global $userID;
  $request = $app->request();
  $params = json_decode($request->getBody());
  $email = $params->email;
  try {
	  $sql = "SELECT id FROM users WHERE email='$email'";
		$db = getConnection();
		$stmt = $db->query($sql);  
		$userSetter = $stmt->fetchAll(PDO::FETCH_OBJ);
		$userID->setUser($userSetter);
		$db = null;
		$msg = '{"message":"User Set Success."}';
		echo json_encode($msg);
	} catch(PDOException $e) {
		$msg = '{"message":"User Set Failed."}';
		echo json_encode($msg);  
	}
}

function getUserinfo() {
	global $userID;
	$user_setter = $userID->getUser;
	echo json_encode($user_setter);
}

function getUsers() {
	$sql = "SELECT * FROM users ORDER BY user_id;";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($users);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUser($id) {
	$sql = "SELECT * FROM users WHERE id=".$id." ORDER BY user_id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($users);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

// Logs in user and gets items
function loginUser(){
	global $app;
  $request = $app->request();
  $params = json_decode($request->getBody());
  $email = $params->email;
  $password = $params->password;
  $password = md5($password);
	$sql = "SELECT id as user_id, email, admin FROM users WHERE email='$email' AND password='$password'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("email", $email);
		$stmt->bindParam("password", $password);
		$stmt->execute();
		$usern=$stmt->fetchAll(PDO::FETCH_OBJ);
		if ($usern == null) {
			$array = array('msg' => "Wrong Credentials", 'error' => '');
			echo json_encode($array);
		} else {
			//$sql = "SELECT * FROM items JOIN users ON items.user_id=$user_id";
			$array = array('msg' => "Login Successful", 'error' => '');
			$array['user_info'] = $usern;
			echo json_encode($array);
		}
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
/*
function addUser() {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$pass = $user->password;
	$pass = md5($user->password);
	$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", $pass);
		$stmt->execute();
		$user->id = $db->lastInsertId();
		$user = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode('success');
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

*/

function addUser() {
	global $app;
  $request = $app->request();
  $params = json_decode($request->getBody());
  $email = $params->email;
  $password = $params->password;
  $admin = 0;
  $password = md5($password);
	$sql = "INSERT INTO users (email, password, admin) VALUES (:email, :password, :admin)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("email", $email);
		$stmt->bindParam("password", $password);
		$stmt->bindParam("admin", $admin);
		if ($stmt->execute()) {
			$array = array('msg' => "Successfully created user", 'error' => '');
			echo json_encode($array);
		} else {
			$array = array('msg' => "Failure to create user", 'error' => '');
			echo json_encode($array);
		}
		//$user->id = $db->lastInsertId();
		//$user = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
	} catch(PDOException $e) {
		$array = array('msg' => "Failure to create user");
		echo json_encode($array);
		//echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


function updateUser($id) {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "UPDATE users SET email=:email, password=:password	WHERE id=:id";
		//"image=:image, name=:name, phone=:phone, address=:address" 

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", $user->password);
		//$stmt->bindParam("image", $user->image);
		//$stmt->bindParam("name", $user->name);
		//$stmt->bindParam("phone", $user->phone);
		//$stmt->bindParam("address", $user->address);
		//$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUser($id) {
	$sql = "DELETE FROM users WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		getUsers();
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

// Inventory Methods

function getItems($userId) {
	$sql = "SELECT * FROM items JOIN users ON";
	try {
		$db = getConnection();

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getItem() {
	$sql = "";
	try {
		$db = getConnection();

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addItem() {
	$sql = "";
	try {
		$db = getConnection();

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateItem() {
	$sql = "";
	try {
		$db = getConnection();

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteItem($id) {
	$sql = "DELETE FROM items WHERE id=:id";
	try {
		$db = getConnection();

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

?>