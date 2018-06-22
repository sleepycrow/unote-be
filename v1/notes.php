<?php
require './incl/headers.php';

require './incl/common.php';
require './incl/sql.php';

if(!isset($_SESSION['user'])){

    //if the user is not logged in, fuck him lol
    http_response_code(401);
    die(generate_error_json("unauthorized"));

}elseif($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])){
    
    //get note
    $id = intval($_GET['id']);

    $query = mysqli_query($link, "SELECT * FROM `notes` WHERE `id`='$id' LIMIT 1")
        or die(generate_error_json("internal_error"));
    $note = mysqli_fetch_assoc($query);

    if($note['author'] == $_SESSION['user']['id']){
        die(json_encode($note));
    }else{
        http_response_code(403);
        die(generate_error_json("forbidden"));
    }

}elseif($_SERVER['REQUEST_METHOD'] == "GET" && !isset($_GET['id'])){

    //get all notes
    $query = mysqli_query($link, "SELECT * FROM `notes` WHERE `author`='" . $_SESSION['user']['id'] . "' ORDER BY `id` DESC")
        or die(generate_error_json("internal_error"));

    $notes = [];
    while($note = mysqli_fetch_assoc($query)){
        array_push($notes, $note);
    }
    die(json_encode($notes));

}elseif($_SERVER['REQUEST_METHOD'] == "POST"){

    //create/edit notes
    if(isset($_GET['id'])) $id = intval($_GET['id']);
    if(isset($_POST['title'])) $title = mysqli_escape_string($link, htmlspecialchars($_POST['title']));
    if(isset($_POST['content'])) $content = mysqli_escape_string($link, $_POST['content']);
    $author = $_SESSION['user']['id'];

    if(isset($id)){

        //edit note
        $query_elements = [];
        if(isset($title)) array_push($query_elements, "`title`='$title'");
        if(isset($content)) array_push($query_elements, "`content`='$content'");
        $query_elements_joined = implode(",", $query_elements);

        $query = mysqli_query($link, "UPDATE `notes` SET $query_elements_joined WHERE `id`='$id' AND `author`='$author'")
            or die(generate_error_json("internal_error"));

        die($generic_success_json);

    }elseif(isset($title) && isset($content) && strlen($title) > 0 && strlen($content) > 0){
    
        //add note
        $query = mysqli_query($link, "INSERT INTO `notes` (`title`, `author`, `content`) VALUES ('$title', '$author', '$content')")
            or die(generate_error_json("internal_error"));

        die($generic_success_json);

    }else{
        die(generate_error_json("insufficient_arguments"));
    }

}elseif($_SERVER['REQUEST_METHOD'] == "DELETE" && isset($_GET['id'])){

    //delete note
    $id = intval($_GET['id']);
    $author = $_SESSION['user']['id'];

    $query = mysqli_query($link, "DELETE FROM `notes` WHERE `id`='$id' AND `author`='$author'");

    if(mysqli_affected_rows($link) > 0){
        die($generic_success_json);
    }else{
        http_response_code(403);
        die(generate_error_json("forbidden"));
    }

}