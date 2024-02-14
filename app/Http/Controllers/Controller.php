<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="YAMAGUCHI Demo API",
 *     version="1.0.0",
 *     description="Документация к API для тестового задания YAMAGUCHI",
 *     @OA\Contact(
 *          email="nickgaidai@gmail.com"
 *     ),
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 *
 * @OA\Server(
 *       url=L5_SWAGGER_CONST_HOST,
 *       description="YAMAGUCHI API Server"
 * )
 *
 * @OA\Tag(
 *      name="Authentication",
 *      description="Эндпоинты для аутентификации"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
