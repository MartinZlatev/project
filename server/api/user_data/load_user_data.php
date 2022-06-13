<?php 

require_once("../../db/db-connection/connect_to_db.php");
require_once("../functions/login-function.php");

/* returns the firstname, lastname, email and the user type so that this info can be displayed on the user's main page */

session_start();
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
    exit(json_encode(["status" => "UNAUTHENTICATED", "message" => "Потребителят не е автентикиран!"]));
}

$user_id = $_SESSION["user"]["id"];

try {
    $db = new DB();
    $connection = $db->getConnection();

    $sql = "SELECT name,email,major,course, images.path
            FROM users join image_instances on users.id = image_instances.user_id join images on image_instances.image_id = images.id
            WHERE users.id = :id AND images.picked=true LIMIT 1";

    $stmt = $connection->prepare($sql);
    $stmt->execute(["id" => $user_id]);

    $user_data = $stmt->fetch(PDO::FETCH_ASSOC); // only one row will be returned from the database since we filter our search by id which is unique

    if(! $user_data ){ //user has not picked his picture
        $sql2 = "SELECT name,email,major,course
                FROM users
                Where users.id = :id";

        $stmt = $connection->prepare($sql2);
        $stmt->execute(["id" => $user_id]); 

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
    }
    http_response_code(200);
    exit(json_encode(["status" => "SUCCESS", "data" => $user_data]));
}
catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!"]));
}

?>