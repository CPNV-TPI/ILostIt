<?php

namespace ILostIt\View;

$links = [
    ["Se connecter", "/auth/login", 0],
    ["S'enregistrer", "/auth/register", 0],
    ["Publications", "/objects", 1],
    ["Les objets retrouvés", "/found-objects", 1],
]
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Lost It - <?=$title?></title>
    <link rel="stylesheet" href="/src/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet"
    >
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
                    absolute z-50 hidden flex-col top-0 mt-11 w-full bg-white text-center -ml-3 shadow-2xl
                    divide-solid divide-black divide-y
                    md:w-2/4 md:right-0
                    lg:block lg:space-x-5 lg:block lg:relative lg:m-0 lg:w-auto lg:shadow-none lg:bg-transparent
                    lg:divide-y-0 lg:text-white
                "
            >
                <a class="py-4 lg:py-0" href="/">Accueil</a>

                <?php foreach ($links as $link) : ?>
                    <?php if (!isset($_SESSION['email']) && $link[2] == 0) : ?>
                        <a class="py-4 lg:py-0" href=<?=$link[1]?>><?=$link[0]?></a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['email']) && $link[2] == 1) : ?>
                        <a class="py-4 lg:py-0" href=<?=$link[1]?>><?=$link[0]?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['isMod']) && $_SESSION['isMod']) : ?>
                    <a href="/mod">Publications en attente></a>
                <?php endif; ?>

                <?php if (isset($_SESSION['email'])) : ?>
                    <button id="post_btn" class="px-2 py-4 lg:py-px">Publier une annonce</button>
                    <a href="#" class="py-4 lg:py-0">
                        <i class="fa-solid fa-user pr-2"></i>
                        <?php if (!empty($_SESSION['firstname']) && !empty($_SESSION['lastname'])) : ?>
                            <?=$_SESSION['firstname']?>
                            <?=$_SESSION['lastname']?>
                        <?php else : ?>
                            <?=$_SESSION['email']?>
                        <?php endif; ?>
                    </a>
                <?php endif;?>
            </div>
        </div>
    </div>

    <?=$content?>

    <?php if (isset($_SESSION['email'])) : ?>
        <dialog id="post_dialog">
            <div class="dialog p-5 w-[300px] md:w-[500px]">
                <div class="title text-2xl text-center mb-5">Publier</div>
                <button
                        id="post_dialog_close"
                        class="text-gray-300 hover:text-red-600 absolute right-5 top-5 text-xl"
                >
                    X
                </button>
                <form action="/objects" method="POST" class="space-y-5">
                    <div class="title">
                        <label
                                for="title"
                                class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                        >
                            Titre
                        </label>
                        <input type="text" name="title" id="title" class="w-full border-2 p-2" required>
                    </div>
                    <div class="description">
                        <label
                                for="description"
                                class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                        >
                            Description
                        </label>
                        <textarea
                                name="description"
                                id="description"
                                class="w-full resize-y border-2 p-2"
                                maxlength="500"
                                required
                        ></textarea>
                    </div>
                    <div class="attributes">
                        <p class="border-b border-black mb-2">Attributs</p>

                        <table>
                            <tr>
                                <td>
                                    <label
                                        for="classroom"
                                        class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                                    >
                                        Classe
                                    </label>
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        name="classroom"
                                        id="classroom"
                                        class="w-[150px] border-2 p-2"
                                        required
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label
                                            for="brand"
                                            class="mb-2"
                                    >
                                        Marque
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="brand" id="brand" class="w-[150px] border-2 p-2">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label
                                            for="color"
                                            class="mb-2"
                                    >
                                        Couleur
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="color" id="color" class="w-[150px] border-2 p-2">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label
                                            for="value"
                                            class="mb-2"
                                    >
                                        Valeur
                                    </label>
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        name="value"
                                        id="value"
                                        class="w-[150px] border-2 p-2"
                                        pattern="^[0-9]{1,4}\.?[0-9]{1,2}$"
                                    >
                                </td>
                            </tr>
                        </table>
                    </div>
                    <input type="submit" value="Poster" class="w-full p-2 bg-green-700 text-white cursor-pointer">
                </form>
            </div>
        </dialog>
    <?php endif; ?>

    <script src="/src/js/app.js"></script>
</body>
</html>