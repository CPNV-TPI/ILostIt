<?php

namespace ILostIt\View;

ob_start();
$title = "Annonces"
?>

<div class="posts">
    <div class="content">
        <div class="title text-4xl text-center my-10">ANNONCES</div>
        <div class="search mb-5">
            <form action="/posts" method="get" class="flex justify-center space-x-1">
                <input
                    type="text"
                    name="search"
                    id="search"
                    placeholder="Recherche..."
                    class="w-2/4 p-2 border-2"
                >
                <input type="submit" value="Rechercher" class="cursor-pointer px-4 py-2 bg-green-700 text-white">
            </form>
        </div>
        <div class="filters text-center">
            <a href="/posts?location=Yverdon" class="px-2 py-px rounded-lg bg-orange-300">Yverdon</a>
            <a href="/posts?location=Ste-Croix" class="px-2 py-px rounded-lg bg-orange-300">Ste-Croix</a>
            <a href="/posts?location=Payerne" class="px-2 py-px rounded-lg bg-orange-300">Payerne</a>
        </div>
        <div class="posts w-5/6 mx-auto flex flex-wrap justify-evenly gap-y-5 items-center mt-10">
            <?php if (count($posts) == 0) : ?>
            <div class="no-posts text-center text-xl">Aucune publications trouvées...</div>
            <?php else : ?>
                <?php foreach ($posts as $post) : ?>
            <div class="post w-96 border-2 rounded-md p-4">
                <form action="/posts/<?=$post['id']?>" method="POST">
                    <input type="hidden" name="_METHOD" value="DELETE">
                    <input type="submit" value="X">
                </form>
                <div class="title text-2xl text-center mb-5"><?=$post['title']?></div>
                <div class="desc text-justify mb-5"><pre class="whitespace-pre-wrap"><?=$post['desc']?></pre></div>
                <div class="location">
                    <a
                        href="/posts?location=<?=$post['location']?>"
                        class="px-2 py-px rounded-lg bg-orange-300"
                    >
                        <?=$post['location']?>
                    </a>
                </div>
            </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require 'gabarit.php';
?>