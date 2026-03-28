<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function showIndex(Request $request)
    {
        $revenueRange = $this->sanitizeFilter((string) $request->get('revenue_range', '7_days'), [
            'today',
            'yesterday',
            '7_days',
            'this_month',
            'last_month',
        ], '7_days');

        $revenueView = $this->sanitizeFilter((string) $request->get('revenue_view', 'day'), [
            'day',
            'hour',
            'weekday',
        ], 'day');

        $productRange = $this->sanitizeFilter((string) $request->get('product_range', 'this_month'), [
            'today',
            'this_week',
            'this_month',
            'this_year',
        ], 'this_month');

        $customerRange = $this->sanitizeFilter((string) $request->get('customer_range', 'today'), [
            'today',
            'this_week',
            'this_month',
        ], 'today');

        $today = $this->resolveRange('today');
        $yesterday = $this->resolveRange('yesterday');

        $todayRevenue = $this->getTotalRevenue($today['start'], $today['end']);
        $yesterdayRevenue = $this->getTotalRevenue($yesterday['start'], $yesterday['end']);

        $todayOrders = $this->getOrderCount($today['start'], $today['end']);
        $yesterdayOrders = $this->getOrderCount($yesterday['start'], $yesterday['end']);

        $todayInvoices = $this->getInvoiceCount($today['start'], $today['end']);
        $yesterdayInvoices = $this->getInvoiceCount($yesterday['start'], $yesterday['end']);

        $todayCustomers = $this->getUniqueCustomerCount($today['start'], $today['end']);
        $yesterdayCustomers = $this->getUniqueCustomerCount($yesterday['start'], $yesterday['end']);

        $summaryCards = [
            [
                'key' => 'revenue',
                'label' => 'Doanh thu',
                'value' => $todayRevenue,
                'sub_value' => $todayOrders + $todayInvoices,
                'sub_label' => 'Giao dịch hôm nay',
                'change_text' => $this->buildDailyChangeText($todayRevenue, $yesterdayRevenue, true),
                'change_type' => $this->resolveChangeType($todayRevenue, $yesterdayRevenue),
            ],
            [
                'key' => 'orders',
                'label' => 'Đơn hàng',
                'value' => $todayOrders,
                'sub_value' => null,
                'sub_label' => null,
                'change_text' => $this->buildDailyChangeText($todayOrders, $yesterdayOrders, false),
                'change_type' => $this->resolveChangeType($todayOrders, $yesterdayOrders),
            ],
            [
                'key' => 'invoices',
                'label' => 'Hóa đơn',
                'value' => $todayInvoices,
                'sub_value' => null,
                'sub_label' => null,
                'change_text' => $this->buildDailyChangeText($todayInvoices, $yesterdayInvoices, false),
                'change_type' => $this->resolveChangeType($todayInvoices, $yesterdayInvoices),
            ],
            [
                'key' => 'customers',
                'label' => 'Khách hàng',
                'value' => $todayCustomers,
                'sub_value' => null,
                'sub_label' => null,
                'change_text' => $this->buildDailyChangeText($todayCustomers, $yesterdayCustomers, false),
                'change_type' => $this->resolveChangeType($todayCustomers, $yesterdayCustomers),
            ],
        ];

        $revenueData = $this->getRevenueChartData($revenueRange, $revenueView);
        $topProducts = $this->getTopProducts($productRange);
        $topCustomers = $this->getTopCustomers($customerRange);

        return view('admin.pages.dashboard.index', compact(
            'summaryCards',
            'revenueData',
            'revenueRange',
            'revenueView',
            'productRange',
            'customerRange',
            'topProducts',
            'topCustomers'
        ));
    }

    protected function sanitizeFilter(string $value, array $allowed, string $default): string
    {
        return in_array($value, $allowed, true) ? $value : $default;
    }

    protected function resolveRange(string $rangeKey): array
    {
        $now = now();

        return match ($rangeKey) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'label' => 'Hôm nay',
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->startOfDay(),
                'end' => $now->copy()->subDay()->endOfDay(),
                'label' => 'Hôm qua',
            ],
            '7_days' => [
                'start' => $now->copy()->subDays(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'label' => '7 ngày qua',
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay(),
                'end' => $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay(),
                'label' => 'Tuần này',
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth()->startOfDay(),
                'end' => $now->copy()->endOfMonth()->endOfDay(),
                'label' => 'Tháng này',
            ],
            'last_month' => [
                'start' => $now->copy()->subMonthNoOverflow()->startOfMonth()->startOfDay(),
                'end' => $now->copy()->subMonthNoOverflow()->endOfMonth()->endOfDay(),
                'label' => 'Tháng trước',
            ],
            'this_year' => [
                'start' => $now->copy()->startOfYear()->startOfDay(),
                'end' => $now->copy()->endOfYear()->endOfDay(),
                'label' => 'Năm nay',
            ],
            default => [
                'start' => $now->copy()->subDays(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'label' => '7 ngày qua',
            ],
        };
    }

    protected function orderBaseQuery(?Carbon $start = null, ?Carbon $end = null)
    {
        return DB::table('orders')
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('orders.created_at', [$start, $end]);
            });
    }

    protected function invoiceBaseQuery(?Carbon $start = null, ?Carbon $end = null)
    {
        return DB::table('invoices')
            ->where('invoices.status', Invoice::STATUS_COMPLETED)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('invoices.created_at', [$start, $end]);
            });
    }

    protected function getTotalRevenue(Carbon $start, Carbon $end): int
    {
        $orderRevenue = (int) $this->orderBaseQuery($start, $end)->sum('orders.total_amount');
        $invoiceRevenue = (int) $this->invoiceBaseQuery($start, $end)->sum('invoices.total_amount');

        return $orderRevenue + $invoiceRevenue;
    }

    protected function getOrderCount(Carbon $start, Carbon $end): int
    {
        return (int) $this->orderBaseQuery($start, $end)->count('orders.id');
    }

    protected function getInvoiceCount(Carbon $start, Carbon $end): int
    {
        return (int) $this->invoiceBaseQuery($start, $end)->count('invoices.id');
    }

    protected function getUniqueCustomerCount(Carbon $start, Carbon $end): int
    {
        $orderPhones = $this->orderBaseQuery($start, $end)
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'customers.user_id', '=', 'users.id')
            ->selectRaw("COALESCE(NULLIF(users.phone, ''), NULLIF(orders.shipping_phone, '')) as phone")
            ->pluck('phone');

        $invoicePhones = $this->invoiceBaseQuery($start, $end)
            ->selectRaw("NULLIF(invoices.customer_phone, '') as phone")
            ->pluck('phone');

        return collect($orderPhones)
            ->merge($invoicePhones)
            ->filter(fn($phone) => filled($phone))
            ->unique()
            ->count();
    }

    protected function buildDailyChangeText(int $current, int $previous, bool $isMoney): string
    {
        if ($current === $previous) {
            return 'Không đổi so với hôm qua';
        }

        $difference = abs($current - $previous);
        $percent = $previous > 0 ? round(($difference / $previous) * 100, 2) : 100;
        $prefix = $current > $previous ? 'Tăng' : 'Giảm';
        $value = $isMoney
            ? number_format($difference, 0, ',', '.') . ' đ'
            : number_format($difference, 0, ',', '.');

        return $prefix . ' ' . $value . ' (' . $percent . '%) so với hôm qua';
    }

    protected function resolveChangeType(int $current, int $previous): string
    {
        if ($current > $previous) {
            return 'up';
        }

        if ($current < $previous) {
            return 'down';
        }

        return 'same';
    }

    protected function getRevenueChartData(string $rangeKey, string $view): array
    {
        $range = $this->resolveRange($rangeKey);
        $start = $range['start'];
        $end = $range['end'];

        $categories = [];
        $series = [];

        if ($view === 'hour') {
            $categories = collect(range(0, 23))
                ->map(fn($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT))
                ->values()
                ->all();

            $orderRows = $this->orderBaseQuery($start, $end)
                ->selectRaw('HOUR(orders.created_at) as bucket, SUM(orders.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $invoiceRows = $this->invoiceBaseQuery($start, $end)
                ->selectRaw('HOUR(invoices.created_at) as bucket, SUM(invoices.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            foreach (range(0, 23) as $hour) {
                $series[] = (int) ($orderRows[$hour] ?? 0) + (int) ($invoiceRows[$hour] ?? 0);
            }
        } elseif ($view === 'weekday') {
            $categories = ['Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7', 'CN'];

            $orderRows = $this->orderBaseQuery($start, $end)
                ->selectRaw('WEEKDAY(orders.created_at) as bucket, SUM(orders.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $invoiceRows = $this->invoiceBaseQuery($start, $end)
                ->selectRaw('WEEKDAY(invoices.created_at) as bucket, SUM(invoices.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            foreach (range(0, 6) as $weekday) {
                $series[] = (int) ($orderRows[$weekday] ?? 0) + (int) ($invoiceRows[$weekday] ?? 0);
            }
        } else {
            foreach (CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay()) as $date) {
                $categories[] = $date->format('d');
            }

            $orderRows = $this->orderBaseQuery($start, $end)
                ->selectRaw('DATE(orders.created_at) as bucket, SUM(orders.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $invoiceRows = $this->invoiceBaseQuery($start, $end)
                ->selectRaw('DATE(invoices.created_at) as bucket, SUM(invoices.total_amount) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            foreach (CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay()) as $date) {
                $dateKey = $date->format('Y-m-d');
                $series[] = (int) ($orderRows[$dateKey] ?? 0) + (int) ($invoiceRows[$dateKey] ?? 0);
            }
        }

        return [
            'label' => $range['label'],
            'view' => $view,
            'total_revenue' => $this->getTotalRevenue($start, $end),
            'categories' => $categories,
            'series' => $series,
        ];
    }

    protected function getTopProducts(string $rangeKey): Collection
    {
        $range = $this->resolveRange($rangeKey);
        $start = $range['start'];
        $end = $range['end'];

        $orderProducts = $this->orderBaseQuery($start, $end)
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->selectRaw('order_details.product_id as product_id, MAX(products.name) as product_name, SUM(order_details.quantity) as total_quantity, SUM(order_details.total_price) as total_revenue')
            ->groupBy('order_details.product_id')
            ->get();

        $invoiceProducts = $this->invoiceBaseQuery($start, $end)
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('products', 'products.id', '=', 'invoice_details.product_id')
            ->selectRaw('invoice_details.product_id as product_id, MAX(products.name) as product_name, SUM(invoice_details.quantity) as total_quantity, SUM(invoice_details.total_price) as total_revenue')
            ->groupBy('invoice_details.product_id')
            ->get();

        $merged = [];

        foreach ($orderProducts as $item) {
            $productId = (int) $item->product_id;
            $merged[$productId] = [
                'product_id' => $productId,
                'product_name' => (string) $item->product_name,
                'total_quantity' => (int) $item->total_quantity,
                'total_revenue' => (int) $item->total_revenue,
            ];
        }

        foreach ($invoiceProducts as $item) {
            $productId = (int) $item->product_id;

            if (!isset($merged[$productId])) {
                $merged[$productId] = [
                    'product_id' => $productId,
                    'product_name' => (string) $item->product_name,
                    'total_quantity' => 0,
                    'total_revenue' => 0,
                ];
            }

            $merged[$productId]['total_quantity'] += (int) $item->total_quantity;
            $merged[$productId]['total_revenue'] += (int) $item->total_revenue;
        }

        return collect($merged)
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();
    }

    protected function getTopCustomers(string $rangeKey): Collection
    {
        $range = $this->resolveRange($rangeKey);
        $start = $range['start'];
        $end = $range['end'];

        $orderCustomers = $this->orderBaseQuery($start, $end)
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'customers.user_id', '=', 'users.id')
            ->selectRaw("COALESCE(NULLIF(users.phone, ''), CONCAT('order-', orders.customer_id), NULLIF(orders.shipping_phone, ''), CONCAT('order-temp-', orders.id)) as customer_key")
            ->selectRaw("MAX(COALESCE(NULLIF(customers.full_name, ''), NULLIF(orders.shipping_name, ''), 'Khách lẻ')) as customer_name")
            ->selectRaw("MAX(COALESCE(NULLIF(users.phone, ''), NULLIF(orders.shipping_phone, ''), '')) as customer_phone")
            ->selectRaw('SUM(orders.total_amount) as total_revenue')
            ->groupBy('customer_key')
            ->get();

        $invoiceCustomers = $this->invoiceBaseQuery($start, $end)
            ->selectRaw("COALESCE(NULLIF(invoices.customer_phone, ''), CONCAT('invoice-', invoices.id)) as customer_key")
            ->selectRaw("MAX(COALESCE(NULLIF(invoices.customer_name, ''), 'Khách lẻ')) as customer_name")
            ->selectRaw("MAX(COALESCE(NULLIF(invoices.customer_phone, ''), '')) as customer_phone")
            ->selectRaw('SUM(invoices.total_amount) as total_revenue')
            ->groupBy('customer_key')
            ->get();

        $merged = [];

        foreach ($orderCustomers as $item) {
            $key = (string) $item->customer_key;
            $merged[$key] = [
                'customer_name' => (string) $item->customer_name,
                'customer_phone' => (string) $item->customer_phone,
                'total_revenue' => (int) $item->total_revenue,
            ];
        }

        foreach ($invoiceCustomers as $item) {
            $key = (string) $item->customer_key;

            if (!isset($merged[$key])) {
                $merged[$key] = [
                    'customer_name' => (string) $item->customer_name,
                    'customer_phone' => (string) $item->customer_phone,
                    'total_revenue' => 0,
                ];
            }

            if ($merged[$key]['customer_name'] === '' && (string) $item->customer_name !== '') {
                $merged[$key]['customer_name'] = (string) $item->customer_name;
            }

            if ($merged[$key]['customer_phone'] === '' && (string) $item->customer_phone !== '') {
                $merged[$key]['customer_phone'] = (string) $item->customer_phone;
            }

            $merged[$key]['total_revenue'] += (int) $item->total_revenue;
        }

        return collect($merged)
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();
    }
}
