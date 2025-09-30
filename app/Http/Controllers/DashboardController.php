<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        try {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd = Carbon::now()->endOfMonth();

            $todayOrdersCount = Order::whereDate('created_at', $today)->count();
            $yesterdayOrdersCount = Order::whereDate('created_at', $yesterday)->count();
            
            $orderIncrease = 0;
            if ($yesterdayOrdersCount > 0) {
                $orderIncrease = (($todayOrdersCount - $yesterdayOrdersCount) / $yesterdayOrdersCount) * 100;
            } elseif ($todayOrdersCount > 0) {
                $orderIncrease = 100;
            }

            $pendingOrdersCount = Order::where('status', 'pending')->count();

            $todayRevenue = Order::whereDate('actual_delivery_date', $today)
                ->where('status', 'delivered')
                ->sum('total');

            $monthRevenue = Order::whereBetween('actual_delivery_date', [$monthStart, $monthEnd])
                ->where('status', 'delivered')
                ->sum('total');

            $totalOrdersToday = Order::whereDate('created_at', $today)->count();
            $inProgressOrdersCount = Order::where('status', 'in_progress')->count();
            $readyOrdersCount = Order::where('status', 'ready')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'orders_today' => [
                        'count' => $todayOrdersCount,
                        'increase_percentage' => round($orderIncrease, 2),
                        'yesterday_count' => $yesterdayOrdersCount
                    ],
                    'pending_orders' => [
                        'count' => $pendingOrdersCount
                    ],
                    'revenue' => [
                        'today' => [
                            'amount' => floatval($todayRevenue),
                            'formatted' => '$' . number_format($todayRevenue, 2)
                        ],
                        'month' => [
                            'amount' => floatval($monthRevenue),
                            'formatted' => '$' . number_format($monthRevenue, 2)
                        ]
                    ],
                    'order_status_summary' => [
                        'pending' => $pendingOrdersCount,
                        'in_progress' => $inProgressOrdersCount,
                        'ready' => $readyOrdersCount,
                        'total_today' => $totalOrdersToday
                    ]
                ],
                'meta' => [
                    'date' => $today->toDateString(),
                    'month' => $today->format('F Y'),
                    'timezone' => config('app.timezone')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas del dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders overview by status
     */
    public function ordersOverview(): JsonResponse
    {
        try {
            $statusCounts = Order::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            return response()->json([
                'success' => true,
                'data' => [
                    'pending' => $statusCounts['pending'] ?? 0,
                    'in_progress' => $statusCounts['in_progress'] ?? 0,
                    'ready' => $statusCounts['ready'] ?? 0,
                    'delivered' => $statusCounts['delivered'] ?? 0,
                    'cancelled' => $statusCounts['cancelled'] ?? 0,
                    'total' => $statusCounts->sum()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener resumen de pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily revenue for the last 7 days
     */
    public function weeklyRevenue(): JsonResponse
    {
        try {
            $dailyRevenue = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $revenue = Order::whereDate('actual_delivery_date', $date)
                    ->where('status', 'delivered')
                    ->sum('total');
                
                $dailyRevenue[] = [
                    'date' => $date->toDateString(),
                    'day' => $date->format('l'), // Day name
                    'revenue' => floatval($revenue),
                    'formatted' => '$' . number_format($revenue, 2)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $dailyRevenue
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ingresos semanales: ' . $e->getMessage()
            ], 500);
        }
    }
}