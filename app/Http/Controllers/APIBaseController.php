<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class APIBaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function errorMessage($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR): \Illuminate\Http\JsonResponse
    {
        $code = $code < 100 || $code > 599 ?  Response::HTTP_INTERNAL_SERVER_ERROR : $code;
        return response()->json(['message' => $message], $code);
    }
    protected function successMessage($message, $data = [], $code = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data], $code);
    }
}
