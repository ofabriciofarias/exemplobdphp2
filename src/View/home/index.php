<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/exemplobdphp2/public/css/style.css">
</head>
<body>
    <div class="container home-container">
        <?php if (isset($loggedInUser)): ?>
            <p>Bem-vindo, <?php echo htmlspecialchars($loggedInUser); ?>!</p>
            <div class="home-buttons">
                <button onclick="location.href='/exemplobdphp2/noticias'">Notícias</button>
                <button onclick="location.href='/exemplobdphp2/register'">Cadastrar Usuário</button> <!-- Moved here -->
                <button onclick="location.href='/exemplobdphp2/logout'">Sair</button>
            </div>
        <?php else: ?>
            <h1>Bem-vindo ao Nosso Site!</h1>
            <p>Escolha uma opção abaixo:</p>
            <div class="home-buttons">
                <button onclick="location.href='/exemplobdphp2/noticias'">Notícias</button>
                <button onclick="location.href='/exemplobdphp2/login'">Efetuar Login</button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>