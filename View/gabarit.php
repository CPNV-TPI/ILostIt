<?php

namespace ILostIt\View;

$links = [
    ["Home", "/"],
    ["Annonces", "/posts"],
    ["Utilisateur", "/users"]
]
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Lost It - <?=$title?></title>
    <link rel="stylesheet" href="src/css/main.css">
</head>
<body>
    <div class="nav">
        <div class="content py-2 px-10 flex justify-between items-center shadow-md">
            <div class="title text-green-600 text-2xl">I LOST IT</div>
            <div class="links space-x-5">
            <?php foreach ($links as $link) : ?>
                <a href=<?=$link[1]?>><?=$link[0]?></a>
            <?php endforeach; ?>
            <button id="post_btn" class="bg-green-700 text-white px-2 py-px">Publier une annonce</button>
            </div>
        </div>
    </div>
    <?=$pageContent?>
    <script src="src/js/app.js"></script>
</body>
</html>