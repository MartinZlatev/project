<?php
session_start();
if (isset($_GET['path']) && isset($_SESSION['user'])){
    require '../../db/dbhandler.php';

    $path = $_GET['path'];
    $user_id=$_SESSION['user']['id'];

    # Check if any of the fields is empty
    if (empty($path)){
        header("Location: ../../../client/home-page/home.html?error=emptyfields");
        exit();
    }
    else {
        #   Get images with given major and course
        $sql = "UPDATE images
                set picked=false
                where id IN ( SELECT image_instances.image_id 
                                FROM image_instances JOIN users on image_instances.user_id=users.id
                                WHERE users.id=? )";
        $sql2 = "UPDATE images
                SET images.picked=true
                where images.id IN ( select image_instances.image_id
                           from image_instances
                           JOIN users on image_instances.user_id = users.id
                           join images on image_instances.image_id = images.id
                           where users.id=? AND images.path=?)";
        $statement = mysqli_stmt_init($conn);
        $statement2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql) || !mysqli_stmt_prepare($statement2,$sql2)) {
            header("Location: ../../../client/home-page/home.html?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "i", $user_id);
            mysqli_stmt_execute($statement);

            mysqli_stmt_bind_param($statement2,"is",$user_id, $path);
            mysqli_stmt_execute($statement2);

            exit(json_encode(["status" => "SUCCESS"]));       
        }
    }

}
else {
    # Login the right way using the form
    header("Location: ../../../client/home-page/home.html");
    exit();
}