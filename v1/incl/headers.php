<?php
define('ENV', 'development');
//define('ENV', 'production');

if(isset($_SERVER['HTTP_SESSION_ID'])) session_id($_SERVER['HTTP_SESSION_ID']);
if(isset($_POST['session_id'])) session_id($_POST['session_id']);
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');