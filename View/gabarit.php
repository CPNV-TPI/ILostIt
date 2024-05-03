<?php

namespace ILostIt\View;

$links = [
    ["Accueil", "/", -1],
    ["Se connecter", "/login", 0],
    ["S'enregistrer", "/login", 0],
    ["Publications", "/posts", 1],
    ["Les objets retrouvés", "/found-objects", 1],
]
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Lost It - <?=$title?></title>
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d8302aa554.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
</head>
<body class="font-istok">
    <div class="nav">
        <div class="content py-2 px-3 flex justify-between items-center shadow-md bg-primary">
            <div class="title text-white text-md md:text-lg lg:text-xl">I LOST IT</div>
            <div class="hamburger block lg:hidden text-lg">
                <i class="fa-solid fa-bars text-white"></i>
            </div>

            <div
                class="
                    links
                    absolute z-50 hidden flex-col top-0 mt-11 w-full bg-white text-center -ml-3 shadow-2xl divide-solid divide-black divide-y
                    md:w-2/4 md:right-0
                    lg:block lg:space-x-5 lg:block lg:relative lg:m-0 lg:w-auto lg:shadow-none lg:bg-transparent lg:divide-y-0 lg:text-white
                "
            >
                <?php foreach ($links as $link) : ?>
                    <?php if ($link[2] == -1) : ?>
                        <a class="py-4 lg:py-0" href=<?=$link[1]?>><?=$link[0]?></a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION) && $link[2] == 0) : ?>
                        <a class="py-4 lg:py-0" href=<?=$link[1]?>><?=$link[0]?></a>
                    <?php endif; ?>

                    <?php if (!isset($_SESSION) && $link[2] == 1) : ?>
                        <a class="py-4 lg:py-0" href=<?=$link[1]?>><?=$link[0]?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (isset($_SESSION) && $_SESSION['isMod'] == true) : ?>
                    <a href="/mod">Publications en attente></a>
                <?php endif; ?>

                <?php if (!isset($_SESSION)) : ?>
                    <button id="post_btn" class="px-2 py-4 lg:py-px">Publier une annonce</button>
                <?php endif;?>
            </div>
        </div>
    </div>

    <?=$content?>

    <dialog id="post_dialog">
        <div class="dialog p-5 w-[500px]">
            <div class="title text-2xl text-center mb-5">Publier</div>
            <button
                    id="post_dialog_close"
                    class="text-gray-300 hover:text-red-600 absolute right-5 top-5 text-xl"
            >
                X
            </button>
            <form action="/posts" method="POST" class="space-y-5">
                <div class="title">
                    <label
                            for="title"
                            class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                    >
                        Titre
                    </label>
                    <input type="text" name="title" id="title" class="w-full border-2 p-2" required>
                </div>
                <div class="desc">
                    <label
                            for="desc"
                            class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                    >
                        Description
                    </label>
                    <textarea name="desc" id="desc" class="w-full resize-y border-2 p-2" required></textarea>
                </div>
                <div class="location">
                    <label
                            for="location"
                            class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                    >
                        Location
                    </label>
                    <select name="location" id="location" class="border-2" required>
                        <option value="Yverdon">Yverdon</option>
                        <option value="Ste-Croix">Ste-Croix</option>
                        <option value="Payerne">Payerne</option>
                    </select>
                </div>
                <input type="submit" value="Poster" class="w-full p-2 bg-green-700 text-white cursor-pointer">
            </form>
        </div>
    </dialog>

    <script src="src/js/app.js"></script>
</body>
</html>