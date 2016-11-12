<!doctype html>
<html class="no-js" lang="fr-FR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Citadelles</title>
    <meta name="description" content="Citadelles est un jeu de société créé en 2000 par Bruno Faidutti.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <?php

            require 'vendor/autoload.php';

            spl_autoload_register(function ($class) {
                include __DIR__.'/classes/' . $class . '.php';
            });

            function trace($v) {
                echo $v.'<br/>';
            }

            $game = Game::getGame();
            $game->init(4);

        ?>
    </div>
</body>
</html>