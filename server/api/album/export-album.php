<?php
if (isset($_GET['course']) && isset($_GET['major'])){
    require '../../db/dbhandler.php';

    $major = $_GET['major'];
    $course = $_GET['course'];


    # Check if any of the fields is empty
    if (empty($course) || empty($major)){
        header("Location: ../../../client/home-page/home.html?error=emptyfields");
        exit();
    }
    else {
        #   Get images with given major and course
        $sql = "SELECT DISTINCT images.path,images.id,users.name FROM images 
        join image_instances on images.id = image_instances.image_id
        join users on users.id = image_instances.user_id
        where images.picked = true AND image_instances.id IN (
        select image_instances.id 
        from image_instances
        join users on image_instances.user_id = users.id 
        where users.course = ? AND users.major=?)";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../../../client/home-page/home.html?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "is", $course, $major);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $array=null;
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $array[$i]=$row;
                $i+=1;
            }
            http_response_code(200);
            exit(json_encode(["data" => $array]));       
        }
    }

}
else {
    # Login the right way using the form
    header("Location: ../../../client/home-page/home.html");
    exit();
}