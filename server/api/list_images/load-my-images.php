<?php
    require "../../db/dbhandler.php";
    session_start();
    if (isset($_SESSION['user'])){
        $query = "SELECT * FROM images WHERE id IN (SELECT image_id FROM image_instances WHERE user_id=?)";
        $statement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($statement, $query)) {
            http_response_code(404);
            header("Location: index.php?error=sqlerror");
            exit();
        } 
        else {
            mysqli_stmt_bind_param($statement, "i", $_SESSION['user']['id']);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $array=null;
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $array[$i]=$row;
                $i+=1;
            }
            http_response_code(200);
            exit(json_encode(["status" => "SUCESS", "data" => $array]));
            
            
        }
    }else{
        http_response_code(402);
    }
?>