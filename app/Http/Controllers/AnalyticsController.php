<?php

namespace App\Http\Controllers;

use App\Services\GoogleAnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    private $analyticsService;

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get dashboard analytics stats
     * 
     * @OA\Get(
     *     path="/api/analytics/dashboard-stats",
     *     summary="Get dashboard analytics statistics",
     *     description="Retrieves Google Analytics data including active users, today's visitors, page views, and average session duration. Data is cached for 60 seconds.",
     *     operationId="getDashboardStats",
     *     tags={"Analytics"},
     *     security={{"sessionAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Analytics data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="active_users", type="integer", example=42, description="Currently active users (last 30 minutes)"),
     *                 @OA\Property(property="today_visitors", type="integer", example=235, description="Total unique visitors today"),
     *                 @OA\Property(property="page_views", type="integer", example=1543, description="Total page views today"),
     *                 @OA\Property(property="avg_duration", type="string", example="2m 45s", description="Average session duration")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error - Google Analytics API failure",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Failed to fetch analytics data")
     *         )
     *     )
     * )
     */
    public function getDashboardStats()
    {
        try {
            $stats = $this->analyticsService->getDashboardStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
