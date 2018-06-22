<?php
$generic_success_json = '{"result": "success"}';

function generate_error_json($error_id){
    return '{"result": "error", "error": "' . $error_id . '"}';
}