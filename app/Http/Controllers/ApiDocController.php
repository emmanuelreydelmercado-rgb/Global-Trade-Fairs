<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Global Trade Fairs API",
 *         version="1.0.0",
 *         description="Complete API documentation for Global Trade Fairs platform",
 *         @OA\Contact(
 *             email="emmanuelreydelmercado@gmail.com",
 *             name="Global Trade Fairs Support"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local Development"
 *     ),
 *     @OA\Server(
 *         url="https://global-trade-fairs.onrender.com",
 *         description="Production Server"
 *     )
 * )
 */
class ApiDocController extends Controller
{
    //
}
