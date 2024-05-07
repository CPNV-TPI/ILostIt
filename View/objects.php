<?php

namespace ILostIt\View;

?>

<div class="posts">
    <div class="content">
        <div class="title text-4xl pt-5 md:pt-10 text-center">Les publications</div>
        <div class="filters text-center pt-5 md:pt-10">
            <a href="/posts?type=Perdu" class="px-2 py-px rounded-lg bg-orange-300">Perdu</a>
            <a href="/posts?type=Retrouvé" class="px-2 py-px rounded-lg bg-orange-300">Retrouvé</a>
        </div>
        <div class="
            posts w-5/6 mx-auto flex flex-wrap justify-between gap-y-5 items-center mt-10
            md:w-11/12 md:justify-evenly
        ">
            <?php if (count($objects) == 0) : ?>
                <div class="no-posts text-center text-xl">Aucune publications trouvées...</div>
            <?php else : ?>
                <?php foreach ($objects as $object) :
                        $objectId = $object['id'];
                        $image = json_decode($object['image'])[0];
                        $classroom = $object['classroom'];
                        $title = $object['title'];
                        $description = $object['description'];
                    ?>
                    <?php require('components/object.php'); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="previous-next-page text-center mt-10 space-x-2 md:space-x-5">
            <?php if (isset($nbPreviousPage)) : ?>
                <a
                    class="bg-primary py-4 px-5 md:px-10 text-white"
                    href="/posts?page=<?=$nbPreviousPage?>"
                >Page précédente</a>
            <?php endif; ?>

            <?php if (isset($nbNextPage)) : ?>
                <a
                    class="bg-primary py-4 px-5 md:px-10 text-white"
                    href="/posts?page=<?=$nbNextPage?>"
                >Page suivante</a>
            <?php endif; ?>
        </div>
    </div>
</div>