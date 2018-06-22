<?php
if(isset($_SERVER['HTTP_SESSION_ID'])) session_id($_SERVER['HTTP_SESSION_ID']);
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');