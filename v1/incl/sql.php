<?php
$link = mysqli_connect("127.0.0.1", "root", "", "notes") or die(generate_error_json("internal_error"));
//$link = mysqli_connect("mysql5", "daniel_notes", "5aCetac!", "daniel_notes") or die(generate_error_json("internal_error"));