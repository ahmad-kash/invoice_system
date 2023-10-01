<x-dashboard::layouts.app>
    <x-slot:title>لوحة التحكم </x-slot:title>
    <x-slot:breadcrumb>
        {{ '' }}
    </x-slot:breadcrumb>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <x-box type="info">
                    <x-slot:title>
                        اجمالي الفواتير
                    </x-slot:title>
                    <x-slot:number>
                        {{ $totalInvoicesCount }}
                    </x-slot:number>
                    <x-slot:amount>
                        {{ $totalInvoicesSum }}
                    </x-slot:amount>
                    <x-slot:link>
                        {{ route('invoices.index') }}
                    </x-slot:link>
                </x-box>
            </div>
            <div class="col-lg-3 col-6">
                <x-box type="success">
                    <x-slot:title>
                        الفواتير المدفوعة
                    </x-slot:title>
                    <x-slot:number>
                        {{ $paidInvoicesCount }}
                    </x-slot:number>
                    <x-slot:amount>
                        {{ $paidInvoicesSum }}
                    </x-slot:amount>
                    <x-slot:link>
                        {{ route('invoices.index', ['state' => 'paid']) }}
                    </x-slot:link>
                </x-box>
            </div>
            <div class="col-lg-3 col-6">
                <x-box type="danger">
                    <x-slot:title>
                        الفواتير الغير مدفوعة
                    </x-slot:title>
                    <x-slot:number>
                        {{ $unPaidInvoicesCount }}
                    </x-slot:number>
                    <x-slot:amount>
                        {{ $unPaidInvoicesSum }}
                    </x-slot:amount>
                    <x-slot:link>
                        {{ route('invoices.index', ['state'=> 'unPaid']) }}
                    </x-slot:link>
                </x-box>
            </div>
            <div class="col-lg-3 col-6">
                <x-box type="warning">
                    <x-slot:title>
                        الفواتير الدفوعة جزئيا
                    </x-slot:title>
                    <x-slot:number>
                        {{ $partiallyPaidInvoicesCount }}
                    </x-slot:number>
                    <x-slot:amount>
                        {{ $partiallyPaidInvoicesSum }}
                    </x-slot:amount>
                    <x-slot:link>
                        {{ route('invoices.index', ['state' => 'partiallyPaid']) }}
                    </x-slot:link>
                </x-box>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card ">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">
                            <i class="fas fa-chart-pie mr-1"></i>
                            عدد الفواتير
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="chart" id="sales-chart" style="position: relative; height: 300px;">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="sales-chart-canvas" height="300"
                                style="height: 300px; display: block; width: 569px;" class="chartjs-render-monitor"
                                width="569"></canvas>
                        </div>

                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-lg-6">
                <div class="card ">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">
                            <i class="fas fa-chart-bar mr-1"></i>
                            قيمة الفواتير
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="chart" id="sales-chart-bar" style="position: relative; height: 300px;">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="sales-chart-canvas-bar" height="300"
                                style="height: 300px; display: block; width: 569px;" class="chartjs-render-monitor"
                                width="569"></canvas>
                        </div>

                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    @push('bodyScripts')
        <script src="{{ URL::asset('/plugins/chart.js/Chart.min.js') }}"></script>
        <script>
            $(function() {
                let pieChart = new Chart(document.getElementById('sales-chart-canvas'), {
                    type: 'pie',
                    data: {
                        labels: [
                            'الفواتير المدفوعة',
                            'الفواتير الغير مدفوعة',
                            'الفواتير المدفوعة جزئيا',
                        ],
                        datasets: [{
                            data: [{{ $paidInvoicesCount }}, {{ $unPaidInvoicesCount }},
                                {{ $partiallyPaidInvoicesCount }}
                            ],
                            backgroundColor: ['#00a65a', '#f56954', '#f39c12'],
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });
                let barChart = new Chart(document.getElementById('sales-chart-canvas-bar'), {
                    type: 'bar',
                    data: {
                        labels: [
                            'الفواتير المدفوعة',
                            'الفواتير الغير مدفوعة',
                            'الفواتير المدفوعة جزئيا',
                        ],
                        datasets: [{
                            data: [{{ $paidInvoicesSum }}, {{ $unPaidInvoicesSum }},
                                {{ $partiallyPaidInvoicesSum }}
                            ],
                            backgroundColor: ['#00a65a', '#f56954', '#f39c12'],
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });

            })
        </script>
    @endpush
</x-dashboard::layouts.app>
