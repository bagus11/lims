@if (!empty($charts['modules']))
    @php
        $totalTransaksi = array_sum(array_column($charts['modules'], 'value'));
    @endphp
    <style>
        .qc-chart-box {
            position: relative;
            height: 280px;
        }
        .qc-chart-total {
            font-size: 11px;
            color: #64748b;
        }
        .qc-chart-total strong {
            color: #0f172a;
            font-size: 13px;
        }
    </style>

    <div class="row">
        <div class="col-xl-5 mb-4">
            <div class="card qc-panel qc-mini-card h-100">
                <div class="qc-mini-card__header">
                    <div>
                        <p class="qc-mini-card__title">Transaksi per Modul</p>
                        <p class="qc-mini-card__subtitle">Komposisi jumlah transaksi pada modul yang bisa Anda akses.</p>
                    </div>
                    <span class="qc-chip">{{ $totalTransaksi }} total</span>
                </div>
                <div class="qc-chart-box">
                    <canvas id="qcModuleChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-7 mb-4">
            <div class="card qc-panel qc-mini-card h-100">
                <div class="qc-mini-card__header">
                    <div>
                        <p class="qc-mini-card__title">Tren Inspeksi 6 Bulan</p>
                        <p class="qc-mini-card__subtitle">Total transaksi per bulan dari modul yang bisa Anda akses.</p>
                    </div>
                </div>
                <div class="qc-chart-box">
                    <canvas id="qcTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            (function () {
                if (typeof Chart === 'undefined') return;

                var moduleData = @json($charts['modules']);
                var trendData = @json($charts['trend']);

                // --- Doughnut: komposisi per modul ---
                var mEl = document.getElementById('qcModuleChart');
                if (mEl) {
                    new Chart(mEl, {
                        type: 'doughnut',
                        data: {
                            labels: moduleData.map(function (m) { return m.label; }),
                            datasets: [{
                                data: moduleData.map(function (m) { return m.value; }),
                                backgroundColor: moduleData.map(function (m) { return m.color; }),
                                borderColor: '#ffffff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutoutPercentage: 62,
                            legend: { display: true, position: 'bottom', labels: { usePointStyle: true, padding: 16 } },
                            tooltips: {
                                enabled: true,
                                custom: null,
                                callbacks: {
                                    label: function (item, data) {
                                        var label = data.labels[item.index] || '';
                                        var val = data.datasets[0].data[item.index] || 0;
                                        return ' ' + label + ': ' + val;
                                    }
                                }
                            },
                            plugins: {
                                datalabels: {
                                    color: '#ffffff',
                                    font: { weight: 'bold', size: 12 },
                                    formatter: function (value) { return value > 0 ? value : ''; }
                                }
                            }
                        }
                    });
                }

                // --- Line: tren 6 bulan (deret tunggal) ---
                var tEl = document.getElementById('qcTrendChart');
                if (tEl) {
                    new Chart(tEl, {
                        type: 'line',
                        data: {
                            labels: trendData.labels,
                            datasets: [{
                                label: 'Transaksi',
                                data: trendData.data,
                                borderColor: '#179bae',
                                backgroundColor: 'rgba(23, 155, 174, 0.12)',
                                borderWidth: 2,
                                pointBackgroundColor: '#179bae',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                lineTension: 0.35
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: { display: false },
                            tooltips: {
                                enabled: true,
                                custom: null,
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function (item) { return ' ' + item.yLabel + ' transaksi'; }
                                }
                            },
                            scales: {
                                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
                            },
                            plugins: {
                                datalabels: { display: false }
                            }
                        }
                    });
                }
            })();
        </script>
    @endpush
@endif
