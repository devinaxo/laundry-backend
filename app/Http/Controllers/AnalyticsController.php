<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('es');
    }

    /**
     * Get orders and revenue statistics for a specific month
     * 
     * @param Request $request (year, month)
     * @return JsonResponse
     */
    public function monthlyStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|digits:4|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $validated['year'] ?? date('Y');
        $month = $validated['month'] ?? date('m');
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $stats = Order::whereBetween('reception_date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total) as total_revenue,
                AVG(total) as average_order_value,
                COUNT(DISTINCT client_id) as unique_clients
            ')
            ->first();

        $statusBreakdown = Order::whereBetween('reception_date', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'period' => [
                'year' => $year,
                'month' => $month,
                'month_name' => $startDate->translatedFormat('F'),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'stats' => [
                'total_orders' => $stats->total_orders ?? 0,
                'total_revenue' => number_format((float)$stats->total_revenue, 2, '.', ''),
                'average_order_value' => number_format((float)$stats->average_order_value, 2, '.', ''),
                'unique_clients' => $stats->unique_clients ?? 0,
            ],
            'status_breakdown' => $statusBreakdown,
        ]);
    }

    /**
     * Get orders per month for a specific year (for line/bar charts)
     * 
     * @param Request $request (year)
     * @return JsonResponse
     */
    public function ordersPerMonth(Request $request): JsonResponse
    {
        $year = $request->input('year', date('Y'));
        // Validate year: must be a 4-digit integer between 1900 and 2100
        if (!preg_match('/^\d{4}$/', (string)$year) || (int)$year < 1900 || (int)$year > 2100) {
            return response()->json([
                'error' => 'Invalid year. Year must be a 4-digit number between 1900 and 2100.'
            ], 422);
        }

        $monthlyData = Order::whereYear('reception_date', $year)
            ->selectRaw('
                MONTH(reception_date) as month,
                COUNT(*) as total_orders,
                SUM(total) as total_revenue,
                AVG(total) as average_order_value
            ')
            ->groupBy(DB::raw('MONTH(reception_date)'))
            ->orderBy('month')
            ->get();

        // Fill in missing months with zeros
        $result = collect(range(1, 12))->map(function ($month) use ($monthlyData) {
            $data = $monthlyData->firstWhere('month', $month);
            
            return [
                'month' => $month,
                'month_name' => Carbon::create(null, $month, 1)->translatedFormat('F'),
                'month_short' => Carbon::create(null, $month, 1)->translatedFormat('M'),
                'total_orders' => $data ? $data->total_orders : 0,
                'total_revenue' => $data ? number_format((float)$data->total_revenue, 2, '.', '') : '0.00',
                'average_order_value' => $data ? number_format((float)$data->average_order_value, 2, '.', '') : '0.00',
            ];
        });

        return response()->json([
            'year' => $year,
            'data' => $result,
        ]);
    }

    /**
     * Get revenue comparison between multiple years
     * 
     * @param Request $request (years array)
     * @return JsonResponse
     */
    public function yearlyComparison(Request $request): JsonResponse
    {
        $years = $request->input('years', [date('Y') - 1, date('Y')]);
        
        $comparison = [];
        
        foreach ($years as $year) {
            $yearlyStats = Order::whereYear('reception_date', $year)
                ->selectRaw('
                    COUNT(*) as total_orders,
                    SUM(total) as total_revenue,
                    AVG(total) as average_order_value
                ')
                ->first();

            $comparison[] = [
                'year' => $year,
                'total_orders' => $yearlyStats->total_orders ?? 0,
                'total_revenue' => number_format((float)$yearlyStats->total_revenue, 2, '.', ''),
                'average_order_value' => number_format((float)$yearlyStats->average_order_value, 2, '.', ''),
            ];
        }

        $growth = null;
        if (count($comparison) >= 2) {
            $firstYear = $comparison[0];
            $secondYear = $comparison[1];
            
            if ($firstYear['total_orders'] > 0) {
                $ordersGrowth = (($secondYear['total_orders'] - $firstYear['total_orders']) / $firstYear['total_orders']) * 100;
            } elseif ($secondYear['total_orders'] > 0) {
                $ordersGrowth = 100;
            } else {
                $ordersGrowth = 0;
            }
            
            $firstRevenue = (float)str_replace(',', '', $firstYear['total_revenue']);
            $secondRevenue = (float)str_replace(',', '', $secondYear['total_revenue']);
            
            if ($firstRevenue > 0) {
                $revenueGrowth = (($secondRevenue - $firstRevenue) / $firstRevenue) * 100;
            } elseif ($secondRevenue > 0) {
                $revenueGrowth = 100;
            } else {
                $revenueGrowth = 0;
            }
            
            $firstAvgValue = (float)str_replace(',', '', $firstYear['average_order_value']);
            $secondAvgValue = (float)str_replace(',', '', $secondYear['average_order_value']);
            
            if ($firstAvgValue > 0) {
                $avgValueGrowth = (($secondAvgValue - $firstAvgValue) / $firstAvgValue) * 100;
            } elseif ($secondAvgValue > 0) {
                $avgValueGrowth = 100;
            } else {
                $avgValueGrowth = 0;
            }
            
            $growth = [
                'orders_growth_percentage' => round($ordersGrowth, 2),
                'revenue_growth_percentage' => round($revenueGrowth, 2),
                'average_value_growth_percentage' => round($avgValueGrowth, 2),
                'from_year' => $firstYear['year'],
                'to_year' => $secondYear['year'],
            ];
        }

        return response()->json([
            'years' => $years,
            'comparison' => $comparison,
            'growth' => $growth,
        ]);
    }

    /**
     * Get top clients ranking by total revenue and order count
     * 
     * @param Request $request (limit, start_date, end_date)
     * @return JsonResponse
     */
    public function topClients(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'integer|min:1|max:100',
        ]);
        $limit = $validated['limit'] ?? 10;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Client::select(
                'clients.id',
                'clients.forename',
                'clients.surname',
                'clients.phone',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total) as total_spent'),
                DB::raw('AVG(orders.total) as average_order_value'),
                DB::raw('MAX(orders.reception_date) as last_order_date')
            )
            ->join('orders', 'clients.id', '=', 'orders.client_id')
            ->groupBy('clients.id', 'clients.forename', 'clients.surname', 'clients.phone');

        if ($startDate) {
            $query->where('orders.reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('orders.reception_date', '<=', $endDate);
        }

        $topClients = $query
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($client, $index) {
                return [
                    'rank' => $index + 1,
                    'client_id' => $client->id,
                    'client_name' => $client->forename . ' ' . $client->surname,
                    'phone' => $client->phone,
                    'total_orders' => $client->total_orders,
                    'total_spent' => number_format((float)$client->total_spent, 2, '.', ''),
                    'average_order_value' => number_format((float)$client->average_order_value, 2, '.', ''),
                    'last_order_date' => $client->last_order_date,
                ];
            });

        return response()->json([
            'limit' => $limit,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'top_clients' => $topClients,
        ]);
    }

    /**
     * Get most frequent clients by order count
     * 
     * @param Request $request (limit, start_date, end_date)
     * @return JsonResponse
     */
    public function mostFrequentClients(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $limit = max(1, min($limit, 100)); // Enforce limit between 1 and 100
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Client::select(
                'clients.id',
                'clients.forename',
                'clients.surname',
                'clients.phone',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total) as total_spent'),
                DB::raw('MAX(orders.reception_date) as last_order_date'),
                DB::raw('MIN(orders.reception_date) as first_order_date')
            )
            ->join('orders', 'clients.id', '=', 'orders.client_id')
            ->groupBy('clients.id', 'clients.forename', 'clients.surname', 'clients.phone');

        if ($startDate) {
            $query->where('orders.reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('orders.reception_date', '<=', $endDate);
        }

        $frequentClients = $query
            ->orderBy('total_orders', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($client, $index) {
                return [
                    'rank' => $index + 1,
                    'client_id' => $client->id,
                    'client_name' => $client->forename . ' ' . $client->surname,
                    'phone' => $client->phone,
                    'total_orders' => $client->total_orders,
                    'total_spent' => number_format((float)$client->total_spent, 2, '.', ''),
                    'first_order_date' => $client->first_order_date,
                    'last_order_date' => $client->last_order_date,
                    'customer_since_days' => Carbon::parse($client->first_order_date)->diffInDays(Carbon::now()),
                ];
            });

        return response()->json([
            'limit' => $limit,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'frequent_clients' => $frequentClients,
        ]);
    }

    /**
     * Get most popular services/subcategories
     * 
     * @param Request $request (limit, start_date, end_date)
     * @return JsonResponse
     */
    public function popularServices(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);
        // Validate and clamp the limit to a reasonable maximum (e.g., 100)
        $limit = (is_numeric($limit) && (int)$limit > 0) ? min((int)$limit, 100) : 10;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = OrderItem::select(
                'subcategories.id',
                'subcategories.name as service_name',
                'categories.name as category_name',
                DB::raw('COUNT(order_items.id) as times_ordered'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->join('subcategories', 'order_items.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id');

        if ($startDate) {
            $query->where('orders.reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('orders.reception_date', '<=', $endDate);
        }

        $popularServices = $query
            ->groupBy('subcategories.id', 'subcategories.name', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($service, $index) {
                return [
                    'rank' => $index + 1,
                    'service_id' => $service->id,
                    'service_name' => $service->service_name,
                    'category_name' => $service->category_name,
                    'times_ordered' => $service->times_ordered,
                    'total_quantity' => $service->total_quantity,
                    'total_revenue' => number_format((float)$service->total_revenue, 2, '.', ''),
                ];
            });

        return response()->json([
            'limit' => $limit,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'popular_services' => $popularServices,
        ]);
    }

    /**
     * Get daily orders and revenue for a specific date range (for detailed graphs)
     * 
     * @param Request $request (start_date, end_date)
     * @return JsonResponse
     */
    public function dailyStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $validated['end_date'] ?? Carbon::now()->format('Y-m-d');
        $dailyData = Order::whereBetween('reception_date', [$startDate, $endDate])
            ->selectRaw('
                DATE(reception_date) as date,
                COUNT(*) as total_orders,
                SUM(total) as total_revenue,
                AVG(total) as average_order_value
            ')
            ->groupBy(DB::raw('DATE(reception_date)'))
            ->orderBy('date')
            ->get()
            ->map(function ($day) {
                return [
                    'date' => $day->date,
                    'day_name' => Carbon::parse($day->date)->translatedFormat('l'),
                    'total_orders' => $day->total_orders,
                    'total_revenue' => number_format((float)$day->total_revenue, 2, '.', ''),
                    'average_order_value' => number_format((float)$day->average_order_value, 2, '.', ''),
                ];
            });

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1,
            ],
            'daily_data' => $dailyData,
        ]);
    }

    /**
     * Get order status distribution for pie charts
     * 
     * @param Request $request (start_date, end_date)
     * @return JsonResponse
     */
    public function statusDistribution(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'));

        if ($startDate) {
            $query->where('reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('reception_date', '<=', $endDate);
        }

        $distribution = $query
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'count' => $item->count,
                    'revenue' => number_format((float)$item->revenue, 2, '.', ''),
                ];
            });

        $total = $distribution->sum('count');

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'total_orders' => $total,
            'distribution' => $distribution->map(function ($item) use ($total) {
                return [
                    'status' => $item['status'],
                    'count' => $item['count'],
                    'revenue' => $item['revenue'],
                    'percentage' => $total > 0 ? round(($item['count'] / $total) * 100, 2) : 0,
                ];
            }),
        ]);
    }

    /**
     * Get comprehensive overview with key metrics
     * 
     * @param Request $request (start_date, end_date)
     * @return JsonResponse
     */
    public function overview(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::query();

        if ($startDate) {
            $query->where('reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('reception_date', '<=', $endDate);
        }

        $stats = $query->selectRaw('
            COUNT(*) as total_orders,
            SUM(total) as total_revenue,
            AVG(total) as average_order_value,
            COUNT(DISTINCT client_id) as unique_clients,
            MAX(total) as highest_order,
            MIN(total) as lowest_order
        ')->first();

        // Get growth metrics (compare with previous period)
        if ($startDate && $endDate) {
            $periodLength = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
            $previousStartDate = Carbon::parse($startDate)->subDays($periodLength + 1)->format('Y-m-d');
            $previousEndDate = Carbon::parse($startDate)->subDay()->format('Y-m-d');

            $previousStats = Order::whereBetween('reception_date', [$previousStartDate, $previousEndDate])
                ->selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue')
                ->first();

            if ($previousStats->total_orders > 0) {
                $orderGrowth = (($stats->total_orders - $previousStats->total_orders) / $previousStats->total_orders) * 100;
            } elseif ($previousStats->total_orders == 0 && $stats->total_orders > 0) {
                $orderGrowth = 100;
            } else {
                $orderGrowth = 0;
            }

            if ($previousStats->total_revenue > 0) {
                $revenueGrowth = (($stats->total_revenue - $previousStats->total_revenue) / $previousStats->total_revenue) * 100;
            } elseif ($previousStats->total_revenue == 0 && $stats->total_revenue > 0) {
                $revenueGrowth = 100;
            } else {
                $revenueGrowth = 0;
            }
        } else {
            $orderGrowth = null;
            $revenueGrowth = null;
        }

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'metrics' => [
                'total_orders' => $stats->total_orders ?? 0,
                'total_revenue' => number_format((float)$stats->total_revenue, 2, '.', ''),
                'average_order_value' => number_format((float)$stats->average_order_value, 2, '.', ''),
                'unique_clients' => $stats->unique_clients ?? 0,
                'highest_order' => number_format((float)$stats->highest_order, 2, '.', ''),
                'lowest_order' => number_format((float)$stats->lowest_order, 2, '.', ''),
            ],
            'growth' => [
                'orders_growth_percentage' => $orderGrowth !== null ? round($orderGrowth, 2) : null,
                'revenue_growth_percentage' => $revenueGrowth !== null ? round($revenueGrowth, 2) : null,
            ],
        ]);
    }

    /**
     * Get revenue trends by category
     * 
     * @param Request $request (start_date, end_date)
     * @return JsonResponse
     */
    public function categoryRevenue(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = OrderItem::select(
                'categories.id',
                'categories.name as category_name',
                DB::raw('COUNT(order_items.id) as total_items'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('AVG(order_items.subtotal) as average_item_value')
            )
            ->join('subcategories', 'order_items.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id');

        if ($startDate) {
            $query->where('orders.reception_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('orders.reception_date', '<=', $endDate);
        }

        $categoryData = $query
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'category_id' => $category->id,
                    'category_name' => $category->category_name,
                    'total_items' => $category->total_items,
                    'total_revenue' => number_format((float)$category->total_revenue, 2, '.', ''),
                    'average_item_value' => number_format((float)$category->average_item_value, 2, '.', ''),
                ];
            });

        $totalRevenue = $categoryData->sum(function ($item) {
            return (float)$item['total_revenue'];
        });

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'total_revenue' => number_format($totalRevenue, 2, '.', ''),
            'categories' => $categoryData->map(function ($category) use ($totalRevenue) {
                return array_merge($category, [
                    'percentage' => $totalRevenue > 0 ? round(((float)$category['total_revenue'] / $totalRevenue) * 100, 2) : 0,
                ]);
            }),
        ]);
    }
}
