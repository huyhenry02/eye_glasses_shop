@extends('admin.layouts.main')

@section('content')
    @php
        $formatMoney = fn($value) => number_format((int) $value, 0, ',', '.') . ' đ';
        $shortMoney = function ($value) {
            $value = (int) $value;

            if ($value >= 1000000000) {
                return number_format($value / 1000000000, 1, ',', '.') . ' tỷ';
            }

            if ($value >= 1000000) {
                return number_format($value / 1000000, 1, ',', '.') . ' tr';
            }

            if ($value >= 1000) {
                return number_format($value / 1000, 1, ',', '.') . 'k';
            }

            return number_format($value, 0, ',', '.');
        };

        $productLabels = $topProducts->pluck('product_name')->values()->all();
        $productRevenue = $topProducts->pluck('total_revenue')->map(fn($item) => (int) $item)->values()->all();
        $customerLabels = $topCustomers
            ->map(function ($item) {
                $name = $item['customer_name'] ?: 'Khách lẻ';
                $phone = $item['customer_phone'] ?: '---';
                return $name . ' - ' . $phone;
            })
            ->values()
            ->all();
        $customerRevenue = $topCustomers->pluck('total_revenue')->map(fn($item) => (int) $item)->values()->all();
    @endphp

    <style>
        .sales-dashboard .mini-report-card {
            border-right: 1px solid #e9ecef;
            height: 100%;
            padding: 8px 24px;
        }

        .sales-dashboard .mini-report-card:last-child {
            border-right: 0;
        }

        .sales-dashboard .mini-report-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sales-dashboard .mini-report-icon.revenue {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .sales-dashboard .mini-report-icon.orders {
            background: rgba(255, 159, 67, 0.15);
            color: #ff9f43;
        }

        .sales-dashboard .mini-report-icon.invoices {
            background: rgba(255, 99, 132, 0.14);
            color: #ef4444;
        }

        .sales-dashboard .mini-report-icon.customers {
            background: rgba(40, 199, 111, 0.14);
            color: #28c76f;
        }

        .sales-dashboard .metric-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .sales-dashboard .metric-value {
            font-size: 32px;
            line-height: 1.15;
            color: #212529;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .sales-dashboard .metric-sub {
            font-size: 13px;
            color: #7b8190;
            margin-bottom: 8px;
        }

        .sales-dashboard .metric-change {
            font-size: 12px;
            font-weight: 700;
        }

        .sales-dashboard .metric-change.up {
            color: #16a34a;
        }

        .sales-dashboard .metric-change.down {
            color: #dc2626;
        }

        .sales-dashboard .metric-change.same {
            color: #6c757d;
        }

        .sales-dashboard .analytics-total {
            font-size: 30px;
            font-weight: 800;
            color: #d63384;
            margin-left: 10px;
        }

        .sales-dashboard .analytics-filter-select {
            min-width: 140px;
        }

        .sales-dashboard .analytics-tab {
            border: none;
            background: transparent;
            padding: 0 0 12px;
            margin-right: 28px;
            color: #6c757d;
            font-size: 16px;
            font-weight: 700;
            border-bottom: 3px solid transparent;
            transition: 0.2s ease;
        }

        .sales-dashboard .analytics-tab.active {
            color: #d63384;
            border-bottom-color: #d63384;
        }

        .sales-dashboard .analytics-main-chart {
            min-height: 410px;
        }

        .sales-dashboard .analytics-side-chart {
            min-height: 460px;
        }

        .sales-dashboard .empty-box {
            min-height: 460px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
            color: #8c8c8c;
        }

        .sales-dashboard .empty-box-icon {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 192, 203, 0.55), rgba(255, 240, 245, 0.95));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d63384;
            font-size: 38px;
        }

        @media (max-width: 1399.98px) {
            .sales-dashboard .mini-report-card {
                border-right: 0;
                border-bottom: 1px solid #e9ecef;
                padding-left: 0;
                padding-right: 0;
            }

            .sales-dashboard .mini-report-row > div:last-child .mini-report-card {
                border-bottom: 0;
            }
        }

        @media (max-width: 767.98px) {
            .sales-dashboard .metric-value {
                font-size: 26px;
            }

            .sales-dashboard .analytics-total {
                display: block;
                margin-left: 0;
                margin-top: 6px;
            }

            .sales-dashboard .analytics-main-chart,
            .sales-dashboard .analytics-side-chart,
            .sales-dashboard .empty-box {
                min-height: 340px;
            }
        }
    </style>

    <form id="dashboardFilterForm" method="GET" action="{{ route('admin.dashboard.showIndex') }}" class="sales-dashboard">
        <input type="hidden" name="revenue_view" id="revenueViewInput" value="{{ $revenueView }}">

        <div class="main-content">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="hstack justify-content-between align-items-start mb-4 flex-wrap gap-3">
                                <div>
                                    <h5 class="mb-1">Kết quả bán hàng hôm nay</h5>
                                    <span class="fs-12 text-muted">Dữ liệu tổng hợp từ đơn hàng và hóa đơn</span>
                                </div>
                            </div>

                            <div class="row mini-report-row g-0">
                                @foreach ($summaryCards as $card)
                                    <div class="col-xxl-3 col-lg-6">
                                        <div class="mini-report-card d-flex align-items-start gap-3">
                                            <div class="mini-report-icon {{ $card['key'] }}">
                                                @if ($card['key'] === 'revenue')
                                                    <i class="feather-dollar-sign"></i>
                                                @elseif ($card['key'] === 'orders')
                                                    <i class="feather-shopping-bag"></i>
                                                @elseif ($card['key'] === 'invoices')
                                                    <i class="feather-file-text"></i>
                                                @else
                                                    <i class="feather-users"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="metric-label">{{ $card['label'] }}</div>
                                                <div class="metric-value">
                                                    {{ $card['key'] === 'revenue' ? $formatMoney($card['value']) : number_format((int) $card['value'], 0, ',', '.') }}
                                                </div>
                                                @if (!is_null($card['sub_value']))
                                                    <div class="metric-sub">{{ number_format((int) $card['sub_value'], 0, ',', '.') }} {{ $card['sub_label'] }}</div>
                                                @endif
                                                <div class="metric-change {{ $card['change_type'] }}">{{ $card['change_text'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                                <div>
                                    <h5 class="mb-2">
                                        Doanh thu thuần
                                        <span class="analytics-total">{{ $formatMoney($revenueData['total_revenue']) }}</span>
                                    </h5>
                                    <div class="fs-12 text-muted">Khoảng thời gian: {{ $revenueData['label'] }}</div>
                                </div>

                                <div>
                                    <select class="form-select analytics-filter-select" name="revenue_range" onchange="document.getElementById('dashboardFilterForm').submit()">
                                        <option value="today" {{ $revenueRange === 'today' ? 'selected' : '' }}>Hôm nay</option>
                                        <option value="yesterday" {{ $revenueRange === 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
                                        <option value="7_days" {{ $revenueRange === '7_days' ? 'selected' : '' }}>7 ngày qua</option>
                                        <option value="this_month" {{ $revenueRange === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                                        <option value="last_month" {{ $revenueRange === 'last_month' ? 'selected' : '' }}>Tháng trước</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button type="button" class="analytics-tab {{ $revenueView === 'day' ? 'active' : '' }}" data-view="day">Theo ngày</button>
                                <button type="button" class="analytics-tab {{ $revenueView === 'hour' ? 'active' : '' }}" data-view="hour">Theo giờ</button>
                                <button type="button" class="analytics-tab {{ $revenueView === 'weekday' ? 'active' : '' }}" data-view="weekday">Theo thứ</button>
                            </div>

                            <div id="netRevenueChart" class="analytics-main-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Top 10 hàng bán chạy</h5>
                            <div class="card-header-action d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-light text-dark border">Theo doanh thu thuần</span>
                                <select class="form-select analytics-filter-select" name="product_range" onchange="document.getElementById('dashboardFilterForm').submit()">
                                    <option value="today" {{ $productRange === 'today' ? 'selected' : '' }}>Hôm nay</option>
                                    <option value="this_week" {{ $productRange === 'this_week' ? 'selected' : '' }}>Tuần này</option>
                                    <option value="this_month" {{ $productRange === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                                    <option value="this_year" {{ $productRange === 'this_year' ? 'selected' : '' }}>Năm nay</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($topProducts->isEmpty())
                                <div class="empty-box">
                                    <div class="empty-box-icon">
                                        <i class="feather-inbox"></i>
                                    </div>
                                    <div class="fs-15 fw-semibold">Chưa có dữ liệu</div>
                                </div>
                            @else
                                <div id="topProductsChart" class="analytics-side-chart"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Top 10 khách mua nhiều nhất</h5>
                            <div class="card-header-action d-flex align-items-center gap-2 flex-wrap">
                                <select class="form-select analytics-filter-select" name="customer_range" onchange="document.getElementById('dashboardFilterForm').submit()">
                                    <option value="today" {{ $customerRange === 'today' ? 'selected' : '' }}>Hôm nay</option>
                                    <option value="this_week" {{ $customerRange === 'this_week' ? 'selected' : '' }}>Tuần này</option>
                                    <option value="this_month" {{ $customerRange === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($topCustomers->isEmpty())
                                <div class="empty-box">
                                    <div class="empty-box-icon">
                                        <i class="feather-shopping-bag"></i>
                                    </div>
                                    <div class="fs-15 fw-semibold">Chưa có dữ liệu</div>
                                </div>
                            @else
                                <div id="topCustomersChart" class="analytics-side-chart"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const dashboardForm = document.getElementById('dashboardFilterForm');
        const revenueViewInput = document.getElementById('revenueViewInput');

        document.querySelectorAll('.analytics-tab').forEach(function (button) {
            button.addEventListener('click', function () {
                revenueViewInput.value = this.dataset.view;
                dashboardForm.submit();
            });
        });

        function formatMoney(value) {
            return new Intl.NumberFormat('vi-VN').format(Number(value || 0)) + ' đ';
        }

        function formatCompactMoney(value) {
            const number = Number(value || 0);

            if (number >= 1000000000) {
                return (number / 1000000000).toLocaleString('vi-VN', {maximumFractionDigits: 1}) + ' tỷ';
            }

            if (number >= 1000000) {
                return (number / 1000000).toLocaleString('vi-VN', {maximumFractionDigits: 1}) + ' tr';
            }

            if (number >= 1000) {
                return (number / 1000).toLocaleString('vi-VN', {maximumFractionDigits: 1}) + 'k';
            }

            return new Intl.NumberFormat('vi-VN').format(number);
        }

        new ApexCharts(document.querySelector('#netRevenueChart'), {
            chart: {
                type: 'bar',
                height: 410,
                toolbar: {show: false},
                fontFamily: 'inherit'
            },
            series: [{
                name: 'Doanh thu',
                data: @json($revenueData['series'])
            }],
            colors: ['#1677ff'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '34%'
                }
            },
            dataLabels: {enabled: false},
            stroke: {show: false},
            xaxis: {
                categories: @json($revenueData['categories']),
                axisBorder: {show: false},
                axisTicks: {show: false},
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return formatCompactMoney(value);
                    },
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#edf2f7',
                strokeDashArray: 3
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return formatMoney(value);
                    }
                }
            },
            legend: {show: false}
        }).render();

        @if (!$topProducts->isEmpty())
        new ApexCharts(document.querySelector('#topProductsChart'), {
            chart: {
                type: 'bar',
                height: {{ max(460, $topProducts->count() * 44) }},
                toolbar: {show: false},
                fontFamily: 'inherit'
            },
            series: [{
                name: 'Doanh thu',
                data: @json($productRevenue)
            }],
            colors: ['#1677ff'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '56%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (value) {
                    return formatCompactMoney(value);
                },
                offsetX: 12,
                style: {
                    fontSize: '12px',
                    fontWeight: 700,
                    colors: ['#6c757d']
                },
                background: {enabled: false}
            },
            xaxis: {
                categories: @json($productLabels),
                labels: {
                    formatter: function (value) {
                        return formatCompactMoney(value);
                    },
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#495057',
                        fontSize: '13px',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                borderColor: '#edf2f7',
                strokeDashArray: 3
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return formatMoney(value);
                    }
                }
            },
            legend: {show: false}
        }).render();
        @endif

        @if (!$topCustomers->isEmpty())
        new ApexCharts(document.querySelector('#topCustomersChart'), {
            chart: {
                type: 'bar',
                height: {{ max(460, $topCustomers->count() * 44) }},
                toolbar: {show: false},
                fontFamily: 'inherit'
            },
            series: [{
                name: 'Doanh thu',
                data: @json($customerRevenue)
            }],
            colors: ['#1677ff'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '56%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (value) {
                    return formatCompactMoney(value);
                },
                offsetX: 12,
                style: {
                    fontSize: '12px',
                    fontWeight: 700,
                    colors: ['#6c757d']
                },
                background: {enabled: false}
            },
            xaxis: {
                categories: @json($customerLabels),
                labels: {
                    formatter: function (value) {
                        return formatCompactMoney(value);
                    },
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#495057',
                        fontSize: '13px',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                borderColor: '#edf2f7',
                strokeDashArray: 3
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return formatMoney(value);
                    }
                }
            },
            legend: {show: false}
        }).render();
        @endif
    </script>
@endsection

