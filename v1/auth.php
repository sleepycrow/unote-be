<?php
require './incl/headers.php';

require './incl/common.php';
require './incl/sql.php';

$password_salt = "faPRuhebus2AbucreGAFaJ";
$safe_selections = "`id`, `username`, `api_key`";

if(isset($_GET['register']) && isset($_POST['username']) && isset($_POST['password'])){

    //register
    $username = preg_replace("/[^A-Za-z0-9._-]/", "", $_POST['username']);
    $password = crypt($_POST['password'], '$2y$07$' . $password_salt . '$');

    $query = mysqli_query($link, "SELECT `id` FROM `users` WHERE `username`='$username'")
        or die(generate_error_json("internal_error"));
    
    if(mysqli_num_rows($query) == 0){
        $api_key = md5($username . time() . rand(1000, 10000));

        $query = mysqli_query($link, "INSERT INTO `users` (`username`, `password`, `api_key`) VALUES ('$username', '$password', '$api_key')")
            or die(generate_error_json("internal_error"));

        die($generic_success_json);
    }else{
        die(generate_error_json("username_already_in_use"));
    }
    
}elseif(isset($_POST['username']) && isset($_POST['password'])){

    //login
    $username = preg_replace("/[^A-Za-z0-9._-]/", "", $_POST['username']);
    $password = crypt($_POST['password'], '$2y$07$' . $password_salt . '$');

    $query = mysqli_query($link, "SELECT $safe_selections FROM `users` WHERE `username`='$username' AND `password`='$password'")
        or die(generate_error_json("internal_error"));

    if(mysqli_num_rows($query) > 0){
        $user_info = mysqli_fetch_assoc($query);
        $user_info["session_id"] = session_id();

        $_SESSION['user'] = $user_info;
        die(json_encode($user_info));
    }else{
        die(generate_error_json("incorrect_credentials"));
    }

}elseif(isset($_POST['api_key'])){

    //login via api key
    $api_key = mysqli_escape_string($link, $_POST['api_key']);

    $query = mysqli_query($link, "SELECT $safe_selections FROM `users` WHERE `api_key`='$api_key'")
        or die(generate_error_json("internal_error"));

    if(mysqli_num_rows($query) > 0){
        $user_info = mysqli_fetch_assoc($query);
        $user_info["session_id"] = session_id();

        $_SESSION['user'] = $user_info;
        die(json_encode($user_info));
    }else{
        die(generate_error_json("incorrect_credentials"));
    }

}elseif(isset($_GET['logout'])){

    //logout
    session_destroy();
    die($generic_success_json);
    
}