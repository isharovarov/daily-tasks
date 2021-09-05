<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiController extends Controller
{
    public $user;

    public function __construct(Request $request)
    {
        try
        {
            $this->user = JWTAuth::parseToken()->authenticate();
        }
        catch(JWTException $e)
        {
            Log::error($e->getMessage(). ". File " .$e->getFile(). ": line " .$e->getLine());

            header('Content-Type: application/json');
            http_response_code(401);
            die(json_encode([ 'error'=>'Permission denied' ], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Return simple success response
     *
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(int $code = 200)
    {
        return response()->json(['status' => true], $code);
    }

    /**
     * Return simple fail response
     *
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function fail(int $code = 400)
    {
        return response()->json(['status' => false], $code);
    }
}
