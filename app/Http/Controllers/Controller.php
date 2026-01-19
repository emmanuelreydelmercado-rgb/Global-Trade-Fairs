<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Global Trade Fairs API Documentation",
 *     version="1.0.0",
 *     description="Complete API documentation for Global Trade Fairs platform. This API provides endpoints for managing trade fairs, user authentication, payments, analytics, chatbot interactions, and wishlist management.",
 *     @OA\Contact(
 *         email="emmanuelreydelmercado@gmail.com",
 *         name="Global Trade Fairs Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\Server(
 *     url="https://global-trade-fairs.onrender.com",
 *     description="Production Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sessionAuth",
 *     type="apiKey",
 *     in="cookie",
 *     name="laravel_session",
 *     description="Laravel session-based authentication"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication and registration endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Analytics",
 *     description="Google Analytics data and dashboard statistics"
 * )
 *
 * @OA\Tag(
 *     name="Chatbot",
 *     description="AI-powered chatbot interactions (Groq/Llama)"
 * )
 *
 * @OA\Tag(
 *     name="Wishlist",
 *     description="User wishlist management for trade fairs"
 * )
 *
 * @OA\Tag(
 *     name="Payments",
 *     description="Razorpay payment processing and orders"
 * )
 *
 * @OA\Tag(
 *     name="Events",
 *     description="Trade fair events and listings"
 * )
 */
abstract class Controller
{
    //
}
