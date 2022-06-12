<?php 

require_once("../../db/db-connection/connect_to_db.php");
require_once("../functions/login-function.php");

$data = file_get_contents("php://input");

session_start(); // we need to use $_SESSION variable
// if there is no session set but there are cookies set
if (!isset($_SESSION["user"]) && isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $user = ["email" => $_COOKIE["email"], "password" => $_COOKIE["password"]]; // create an associative array which contains the user email and password, saved on the cookies (we shall use this array to create a session)
    $response = login($user); // create the session for the user

    if ($response["status"] == "ERROR") { // if there was an error creating the session
        http_response_code($response["code"]);
        exit(json_encode(["status" => $response["status"], "message" => $response["message"]]));
    }
}
// if there is no session and at least one cookie is missing return an error
else if (!isset($_SESSION["user"]) && (!isset($_COOKIE["email"]) || !isset($_COOKIE["password"]))) {
    http_response_code(401);
    exit(json_encode(["status" => "ERROR", "message" => "Потребителят не е автентикиран!"]));
}

if(isset($_SESSION)){
    $user_id = $_SESSION["user"]["id"];
}
else {
    http_response_code(400);
    exit(json_encode(["status" => "ERROR", "message" => "user not set"]));
}

$address = $data;


upload($address,$user_id);


function upload($address,$user_id) {
    try {
        $db = new DB();
        $connection = $db->getConnection();
        
        $insert = "INSERT INTO images (image, user_id)
                      VALUES (:image, :user_id)";
    
        $stmt = $connection->prepare($insert);
        $stmt->execute(["image" => $address , "user_id" => $user_id]);
    
        exit(json_encode(["status" => "SUCCESS", "message" => "Успешно качване!"]));
    } 
    catch (PDOException $e) {
        return ["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!", "code" => 500];
    }
}

?>