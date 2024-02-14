<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JWTAuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Регистрирует нового пользователя
     *
     * @OA\Post(
     *     path="/auth/register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     summary="Регистрирует нового пользователя",
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Имя пользователя",
     *          required=true,
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="Адрес электронной почты",
     *           required=true,
     *           @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *           name="password",
     *           in="query",
     *           description="Пароль",
     *           required=true,
     *           @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *           name="password_confirmation",
     *           in="query",
     *           description="Подтверждение пароля",
     *           required=true,
     *           @OA\Schema(type="string")
     *      ),
     *     @OA\Response(response="201", description="Пользователь зарегистрирован"),
     *     @OA\Response(response="422", description="Ошибки валидации"),
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:6',
        ]);

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->get('password'))]
        ));

        return response()->json([
            'message' => 'Successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Аутентифицирует пользователя и выдает JWT-токен
     *
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="login",
     *      summary="Аутентифицирует пользователя и выдает JWT-токен",
     *      tags={"Authentication"},
     *      @OA\Parameter(
     *            name="email",
     *            in="query",
     *            description="Адрес электронной почты",
     *            required=true,
     *            @OA\Schema(type="string")
     *       ),
     *      @OA\Parameter(
     *            name="password",
     *            in="query",
     *            description="Пароль",
     *            required=true,
     *            @OA\Schema(type="string")
     *       ),
     *      @OA\Response(response="200", description="Успешный вход"),
     *      @OA\Response(response="422", description="Ошибки валидации"),
     *      @OA\Response(response="401", description="Ошибка аутентификации"),
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($token = Auth::attempt($validator->validated())) {
            return response()->json([
                'access_token' => $token,
            ]);
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    /**
     * Возвращает авторизованного пользователя
     *
     * @OA\Get(
     *      path="/auth/profile",
     *      operationId="profile",
     *      summary="Возвращает авторизованного пользователя",
     *      tags={"Authentication"},
     *      @OA\Response(
     *          response="200",
     *          description="Успешный запрос",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(response="401", description="Ошибка аутентификации"),
     *      security={{"bearerAuth":{}}}
     * )
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json($user);
    }

    /**
     * Деаутентифицирует пользователя (Сбрасывает токен)
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     operationId="logout",
     *     summary="Деаутентифицирует пользователя (Сбрасывает токен)",
     *     tags={"Authentication"},
     *     @OA\Response(response="200", description="Запрос выполнен"),
     *     security={{"bearerAuth":{}}}
     * )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
