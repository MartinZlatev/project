<?php 

require_once("../../db/db-connection/connect_to_db.php");
require_once("../functions/check_JSON_validity.php");


// php://input is a readonly stream which allows us to read raw data from the request body - it returnes all the raw data after the HTTP headers of the request no matter the content type
// file_get_contents() reads file into a string, but in this case it parses the raw data from the stream into the string
$data = file_get_contents("php://input");
$user_data = null; // used to store the decoded JSON string $data

// check if the JSON is correct
if (strlen($data) > 0 && check_json($data)) {
    $user_data = json_decode($data, true);
    
}
else {
    http_response_code(400);
    exit(json_encode(["status" => "ERROR", "message" => "Невалиден JSON формат!"]));
}

$name = $user_data["name"]; // get input name
$email = $user_data["email"]; // get input email
$password = $user_data["password"]; // get input password
$repeated_password = $user_data["repeat_password"]; // get input repeated password
$hashed_password = password_hash($password, PASSWORD_DEFAULT); // hash the input password
$course = $user_data["course"]; // get input course
$major = $user_data["major"]; // get input major



// check if the user has repeated the password correctly
if ($password != $repeated_password) {
    http_response_code(400);
    exit(json_encode(["status" => "ERROR", "message" => "Паролите не съвпадат!"]));
}

try {
    $db = new DB();
    $connection = $db->getConnection();
    
    $search = "SELECT * 
               FROM users 
               WHERE email = :email";

    $stmt = $connection->prepare($search);
    $stmt->execute(["email" => $email]);
    
    // if a user with the inputted email already exist, then we can't create a new account
    if ($stmt->rowCount() != 0) {
        http_response_code(400);
        exit(json_encode(["status" => "ERROR", "message" => "Потребител с дадения имейл вече съществува!"]));
    }
} catch (PDOException $e) {
    http_response_code(500);
    return json_encode(["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!"]);
}

try {
    $insert = "INSERT INTO users (name, email, password, course, major)
                      VALUES (:name, :email, :password, :course, :major)";

    $stmt = $connection->prepare($insert);

    
    // if the execution passed with no failure, then create the user session
    if ($stmt->execute(["name" => $name, "email" => $email, "password" => $hashed_password, "course" => $course, "major" => $major])) {
        
        $user_id = $connection->lastInsertId(); // get the newly created user's id
        session_start();
        $user = array("id" => $user_id, "name" => $name, "email" => $email, "password" => $hashed_password, "course" => $course, "major" => $major);
        $_SESSION["user"] = $user; // create his session
        $userId = $_SESSION["user"]["id"];
        print_r($userId);
        // set cookies
        setcookie("email", $email, time() + 600);
        setcookie("password", $password, time() + 600);

        http_response_code(200);
        exit(json_encode(["status" => "SUCCESS", "message" => "Успешна регистрация!"]));
    }
    else {
        http_response_code(500);
        exit(json_encode(["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!"]));
    }
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(["status" => "ERROR", "message" => "Неочаквана грешка настъпи в сървъра!"]));
}

?>
