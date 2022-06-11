<?php 

require_once("../functions/check_JSON_validity.php");
require_once("../../db/db-connection/connect_to_db.php");

// php://input is a readonly stream which allows us to read raw data from the request body - it returnes all the raw data after the HTTP headers of the request no matter the content type
// file_get_contents() reads file into a string, but in this case it parses the raw data from the stream into the string
$data = file_get_contents("php://input");
$user_data; // this will be used for the decoded data (JSON decoded)

// check if the input JSON is correct
if (strlen($data) > 0 && check_json($data)) {
    $user_data = json_decode($data, true);
}
else {
    http_response_code(400);
    exit(json_encode(["status" => "ERROR", "message" => "Невалиден JSON формат!"]));
}

$email = $user_data["email"]; // get input email
$password = $user_data["password"]; // get input password
$user = ["email" => $email, "password" => $password];

// login user
$response = login($user);

http_response_code($response["code"]);
exit(json_encode(["status" => $response["status"], "message" => $response["message"]]));

function login($user) {
    try {
        $db = new DB();
        $connection = $db->getConnection();
        
        $select = "SELECT id, password 
                    FROM users 
                    WHERE email = :email";
    
        $stmt = $connection->prepare($select);
        $stmt->execute(["email" => $user["email"]]);
    
        // no such user exists
        if ($stmt->rowCount() == 0) {
            return ["status" => "ERROR", "message" => "Не съществува потребител с посочения имейл!", "code" => 400];
        }
    
        $db_user = $stmt->fetch(PDO::FETCH_ASSOC); // we expect only one row since we are filtering our query by email which is unique
    
        // check if passwords match
        if (!password_verify($user["password"], $db_user["password"])) {
            return ["status" => "ERROR", "message" => "Невалидна парола!", "code" => 400];
        }
    } 
    catch (PDOException $e) {
        return ["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!", "code" => 500];
    }

    // if the user input correct data, start the user's session
    session_start();
    $_SESSION["user"] = $db_user;
    
    // set cookies
    setcookie("email", $user["email"], time() + 600, "/");
    setcookie("password", $user["password"], time() + 600, "/");

    return ["status" => "SUCCESS", "message" => "Успешно влизане в системата!", "code" => 200];
}

?>