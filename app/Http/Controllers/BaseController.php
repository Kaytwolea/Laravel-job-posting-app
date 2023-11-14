<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message = '', $data = null, $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function sendPaginatedResponse($message = '', $data = null, $code = 200, $path, $current_page, $next_page_url, $prev_page_url, $total)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'path' => $path,
            'current_page' => $current_page,
            'next_page_url' => $next_page_url,
            'prev_page_url' => $prev_page_url,
            'total' => $total,

        ], $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = null, $code = 404)
    {
        $response = [
            'message' => $error,
            'data' => $errorMessages
        ];



        return response()->json($response, $code);
    }
}
