<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias</title>
    <link rel="stylesheet" href="/exemplobdphp2/public/css/style.css">
</head>
<body>
    <div class="container">
        <a href="/exemplobdphp2/" class="back-to-home">Voltar para a Home</a>
        <h1>Lista de Notícias</h1>
        <div class="noticias-list">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $noticia): ?>
                    <div class="noticia-item">
                        <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($noticia['texto'])); ?></p>
                        <small>Publicado em: <?php echo htmlspecialchars($noticia['data_insercao']); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma notícia encontrada.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>