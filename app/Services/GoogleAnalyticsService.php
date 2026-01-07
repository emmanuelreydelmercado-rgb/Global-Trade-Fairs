<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    private $client;
    private $propertyId;

    public function __construct()
    {
        $credentialsPath = base_path(config('services.google_analytics.credentials_path'));
        $this->propertyId = config('services.google_analytics.property_id');

        // PRODUCTION FIX: Generate credentials file from ENV if missing
        if (!file_exists($credentialsPath) && env('GOOGLE_ANALYTICS_CREDENTIALS_JSON')) {
            $jsonContent = env('GOOGLE_ANALYTICS_CREDENTIALS_JSON');
            if (is_array($jsonContent)) {
                $jsonContent = json_encode($jsonContent);
            }
            file_put_contents($credentialsPath, $jsonContent);
        }

        if (!file_exists($credentialsPath)) {
            Log::error('Google Analytics credentials file not found: ' . $credentialsPath);
            return;
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
        
        try {
            $this->client = new BetaAnalyticsDataClient();
        } catch (\Exception $e) {
            Log::error('Failed to initialize Google Analytics client: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard statistics
     * Returns cached data to avoid hitting API limits
     */
    public function getDashboardStats()
    {
        return Cache::remember('ga_dashboard_stats', 60, function () {
            try {
                return [
                    'active_users' => $this->getActiveUsers(),
                    'today_visitors' => $this->getTodayVisitors(),
                    'page_views' => $this->getTodayPageViews(),
                    'avg_duration' => $this->getAvgSessionDuration(),
                ];
            } catch (\Exception $e) {
                Log::error('Error fetching GA dashboard stats: ' . $e->getMessage());
                return [
                    'active_users' => 0,
                    'today_visitors' => 0,
                    'page_views' => 0,
                    'avg_duration' => '0m 0s',
                    'error' => $e->getMessage()
                ];
            }
        });
    }

    /**
     * Get active users (last 30 minutes)
     */
    private function getActiveUsers()
    {
        if (!$this->client) return 0;

        try {
            $request = (new RunRealtimeReportRequest())
                ->setProperty('properties/' . $this->propertyId)
                ->setMetrics([
                    new Metric(['name' => 'activeUsers'])
                ]);

            $response = $this->client->runRealtimeReport($request);
            
            if ($response->getRowCount() > 0) {
                return (int) $response->getRows()[0]->getMetricValues()[0]->getValue();
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Error fetching active users: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get today's total visitors
     */
    private function getTodayVisitors()
    {
        if (!$this->client) return 0;

        try {
            $request = (new RunReportRequest())
                ->setProperty('properties/' . $this->propertyId)
                ->setDateRanges([
                    new DateRange([
                        'start_date' => 'today',
                        'end_date' => 'today',
                    ])
                ])
                ->setMetrics([
                    new Metric(['name' => 'totalUsers'])
                ]);

            $response = $this->client->runReport($request);
            
            if ($response->getRowCount() > 0) {
                return (int) $response->getRows()[0]->getMetricValues()[0]->getValue();
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Error fetching today visitors: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get today's page views
     */
    private function getTodayPageViews()
    {
        if (!$this->client) return 0;

        try {
            $request = (new RunReportRequest())
                ->setProperty('properties/' . $this->propertyId)
                ->setDateRanges([
                    new DateRange([
                        'start_date' => 'today',
                        'end_date' => 'today',
                    ])
                ])
                ->setMetrics([
                    new Metric(['name' => 'screenPageViews'])
                ]);

            $response = $this->client->runReport($request);
            
            if ($response->getRowCount() > 0) {
                return (int) $response->getRows()[0]->getMetricValues()[0]->getValue();
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Error fetching page views: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get average session duration (today)
     */
    private function getAvgSessionDuration()
    {
        if (!$this->client) return '0m 0s';

        try {
            $request = (new RunReportRequest())
                ->setProperty('properties/' . $this->propertyId)
                ->setDateRanges([
                    new DateRange([
                        'start_date' => 'today',
                        'end_date' => 'today',
                    ])
                ])
                ->setMetrics([
                    new Metric(['name' => 'averageSessionDuration'])
                ]);

            $response = $this->client->runReport($request);
            
            if ($response->getRowCount() > 0) {
                $seconds = (int) $response->getRows()[0]->getMetricValues()[0]->getValue();
                return $this->formatDuration($seconds);
            }
            
            return '0m 0s';
        } catch (\Exception $e) {
            Log::error('Error fetching avg duration: ' . $e->getMessage());
            return '0m 0s';
        }
    }

    /**
     * Format seconds to human-readable duration
     */
    private function formatDuration($seconds)
    {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%dm %ds', $minutes, $secs);
    }
}
