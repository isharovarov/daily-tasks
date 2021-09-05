<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="User registration",
     * description="User registration",
     * operationId="userRegistration",
     * tags={"auth"},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *        @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9rbm93bGVkZ2UuYmFzZVwvYXBpXC9sb2dpbiIsImlhdCI6MTU5ODYxNjM5MiwiZXhwIjoxNTk4NjIzNTkyLCJuYmYiOjE1OTg2MTYzOTIsImp0aSI6IkViWkJGUmxhZFdNUVk5N0IiLCJzdWIiOjExLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.cCnRV13xz6sIPrGv5utbVOyhJSVmWEPZ62YMv8IddYw")
     *    )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=false),
     *    )
     * )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'email'    => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Login success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9rbm93bGVkZ2UuYmFzZVwvYXBpXC9sb2dpbiIsImlhdCI6MTU5ODYxNjM5MiwiZXhwIjoxNTk4NjIzNTkyLCJuYmYiOjE1OTg2MTYzOTIsImp0aSI6IkViWkJGUmxhZFdNUVk5N0IiLCJzdWIiOjExLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.cCnRV13xz6sIPrGv5utbVOyhJSVmWEPZ62YMv8IddYw")
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Login failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Wrong email or password")
     *    )
     * )
     * )
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials))
        {
            return response()->json([
                'success' => false,
                'message' => 'Wrong email or password',
            ], 401);
        }

        return response()->json([
            'success'   =>  true,
            'token'     =>  $token,
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/me",
     * summary="Get auth user info",
     * description="Get auth user info",
     * operationId="getAuthUserInfo",
     * tags={"auth"},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(ref="#/components/schemas/User")
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=false),
     *    )
     * )
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout",
     * description="Logout",
     * operationId="logout",
     * tags={"auth"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     *    in="query",
     *    name="token",
     *    required=false,
     *    example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9rbm93bGVkZ2UuYmFzZVwvYXBpXC9sb2dpbiIsImlhdCI6MTU5ODYxNjM5MiwiZXhwIjoxNTk4NjIzNTkyLCJuYmYiOjE1OTg2MTYzOTIsImp0aSI6IkViWkJGUmxhZFdNUVk5N0IiLCJzdWIiOjExLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.cCnRV13xz6sIPrGv5utbVOyhJSVmWEPZ62YMv8IddYw"
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *        @OA\Property(property="message", type="boolean", example="Successfully logged out"),
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Expired token"),
     *    )
     * )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
