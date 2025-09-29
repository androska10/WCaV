<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binance Klines</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0f0f13;
            color: #e0e0ff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00f7ff, #b967ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 10px rgba(185, 103, 255, 0.3);
            letter-spacing: 1px;
        }

        h1::before {
            content: "₿ ";
        }

        .card {
            background: #16161d;
            border: 1px solid #2a2a3a;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 247, 255, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }

        .chart-container {
            position: relative;
            height: 500px;
            width: 100%;
        }

        canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #a0a0c0;
            font-size: 0.9rem;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            font-size: 1.4rem;
            color: #ff00c8;
            text-shadow: 0 0 8px rgba(255, 0, 200, 0.4);
        }
    </style>
</head>
<body>
    <h1>Binance Klines (BTC/USDT)</h1>

    <div class="card">
        <?php if (empty($data)): ?>
            <div class="no-data">Нет данных для отображения</div>
        <?php else: ?>
            <div class="chart-container">
                <canvas id="chart"></canvas>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        Данные обновляются каждые 5 минут • <?= date('Y-m-d H:i') ?>
    </div>

    <script>
        <?php if (!empty($data)): ?>
            const klines = <?= json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) ?>;

            const ctx = document.getElementById('chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: klines.map(k => {
                        const d = new Date(k.open_time);
                        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }),
                    datasets: [{
                        label: 'Цена закрытия (USDT)',
                        data: klines.map(k => k.close_price),
                        borderColor: '#00f7ff', // Неон-циан
                        backgroundColor: 'rgba(0, 247, 255, 0.05)',
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e0e0ff',
                                font: { size: 14 }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(22, 22, 29, 0.9)',
                            titleColor: '#00f7ff',
                            bodyColor: '#e0e0ff',
                            borderColor: '#2a2a3a',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(100, 100, 120, 0.2)'
                            },
                            ticks: {
                                color: '#a0a0c0'
                            }
                        },
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(100, 100, 120, 0.2)'
                            },
                            ticks: {
                                color: '#a0a0c0',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>