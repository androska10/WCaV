<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WCaV</title>
</head>
<body>
    <h1>Hi, i am your site?</h1>
    <br>
    <h2>Данные из API</h2>
    <?php if (!empty($data)): ?>
        <pre>
            <?php print_r($data); ?>
        </pre>
    <?php else: ?>
        <p>Нет данных</p>
    <?php endif; ?>
</body>
</html>