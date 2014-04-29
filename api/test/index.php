<?php

//require '../v1/index.php';
require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';

public function getAllUsers() {
            //global $user_id;
            $response = array();
            $db = new DbHandler();

            $result = $db->getUsers();

            $response["error"] = false;
            $response ["users"] = array();

            while ($user = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["id"] = $user["id"];
                $tmp["email"] = $user["email"];
                $tmp["name"] = $user["name"];
                $tmp["status"] = $user["status"];
                $tmp["created_at"] = $user["created_at"];
                array_push($response["users"], $tmp);
            }

            echoRespnse(200, $response);
        }

$db = new DbHandler();
$db->getUsers();

?>