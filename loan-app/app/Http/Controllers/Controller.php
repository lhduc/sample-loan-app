<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Application API",
 *      version="1.0.0",
 *      @OA\Contact(
 *          email="lehongduc87@gmail.com"
 *      ),
 * )
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param mixed $data
     * @return JsonResponse
     */
    public function success(mixed $data): JsonResponse
    {
        return response()->json(['data' => $data]);
    }
}
