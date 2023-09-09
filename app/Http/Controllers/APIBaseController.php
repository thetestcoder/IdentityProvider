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
        $code = $code != 0 ? $code : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(['message' => $message], $code);
    }
    protected function successMessage($message, $code = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
