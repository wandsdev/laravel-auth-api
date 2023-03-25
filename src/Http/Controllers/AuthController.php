<?php

namespace WandsDev\AuthApi\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = $this->registerValidation($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 422);
        }

        $user = $this->createUser($request);
        $token = $this->createToken($user);

        return $this->responseToken($token, $user, 'Successfully registered');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = $this->loginValidation($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $this->createToken($user);

        return $this->responseToken($token, $user, 'Successfully logged in');
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json([ 'status' => true, 'message' => 'Successfully logout']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function registerValidation(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|max:18|confirmed',
        ]);
    }

    private function loginValidation(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|max:18',
        ]);
    }

    private function createUser(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    private function createToken(User $user): string
    {
        return $user->createToken('api_token')->plainTextToken;
    }

    /**
     * @param $token
     * @param $user
     * @param $msg
     * @return JsonResponse
     */
    private function responseToken($token, $user, $msg): JsonResponse
    {
        $data = array(
            'token' => $token,
            'type' => 'Bearer',
            'message' => $msg,
            'userData' => [
                'name' => $user->name
            ]
        );
        return response()->json($data, 200);
    }
}
