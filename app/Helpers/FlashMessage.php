<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('errorFlashMessage')) {
    function ErrorFlashMessage($message){
        Session::flash("flash_message_error", $message);
    }
}

if (!function_exists('successFlashMessage')) {
    function SuccessFlashMessage($message){
        Session::flash("flash_message_success", $message);
    }
}

