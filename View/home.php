<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binance Klines</title>
</head>
<body>
    <h1>📊 Binance Klines (BTC/USDT)</h1>
    <canvas id="chart" height="400"></canvas>

    <script>
        // Передаём данные из PHP в JS
        const klines = <?= json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) ?>;

        // Проверка
        if (!klines || klines.length === 0) {
            document.body.innerHTML = '<h2>Нет данных</h2>';
        } else {
            const ctx = document.getElementById('chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: klines.map(k => new Date(k.open_time).toLocaleTimeString()),
                    datasets: [{
                        label: 'Цена закрытия',
                        data: klines.map(k => k.close_price),
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: false }
                    }
                }
            });
        }
    </script>
</body>
</html>