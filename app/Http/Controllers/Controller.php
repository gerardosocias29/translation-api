<?php

namespace App\Http\Controllers;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use token from /api/login as 'Bearer {token}'",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="Sanctum Token",
 *     securityScheme="bearerAuth"
 * )
 */
abstract class Controller
{
    //
}
