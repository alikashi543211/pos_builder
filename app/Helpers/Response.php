<?php

if (!function_exists('errorResponse')) {
    function errorResponse($message)
    {
        $response = ['responseCode' => 0, 'msg' => $message];
        return json_encode($response);
    }
}

if (!function_exists('successResponse')) {
    function successResponse($message)
    {
        $response = ['responseCode' => 1, 'msg' => $message];
        return json_encode($response);
    }
}

if (!function_exists('successHtmlResponse')) {
    function successHtmlResponse($html)
    {
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }
}

if (!function_exists('errorResponseWithStatus')) {
    function errorResponseWithStatus($message)
    {
        return response()->json(['status' => "error", 'message' => $message]);
    }
}

if (!function_exists('successResponseWithStatus')) {
    function successResponseWithStatus($message)
    {
        return response()->json(['status' => "success", 'message' => $message]);
    }
}

if (!function_exists('downloadFileAndDeleteAfterSend')) {
    function downloadFileAndDeleteAfterSend($filePath)
    {
        // Download File and Delete After Send and Prevent From Caching The File
        $response = response()->download($filePath)->deleteFileAfterSend(false);
        // Set headers on the response
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;

    }
}
