<?php

namespace ILostIt\View;

?>

<div class="posts py-5 md:py-10">
    <div class="content">
        <div class="title text-4xl text-center">Mes objets</div>
        <div class="filters text-center pt-5 md:pt-10">
            <a
                href="/account/my-objects?type=Perdu<?=isset($byStatus) ? "&status=" . $byStatus : ""?>"
               class="px-2 py-px rounded-lg bg-orange-300"
            >Perdu</a>
            <a href="/account/my-objects?type=Retrouvé<?=isset($byStatus) ? "&status=" . $byStatus : ""?>"
               class="px-2 py-px rounded-lg bg-orange-300"
            >Retrouvé</a>
            <a
                href="/account/my-objects<?=isset($byStatus) ? "?status=" . $byStatus : ""?>"
                class="px-2 py-px rounded-lg text-red-500"
            >X</a>
        </div>
        <div class="filters text-center pt-5 md:pt-10">
            <a
                href="/account/my-objects?status=0<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >En attente</a>
            <a
                href="/account/my-objects?status=1<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >Validé</a>
            <a
                href="/account/my-objects?status=2<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >Confirmation de résolution</a>
            <a
                href="/account/my-objects?status=3<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >Résolu</a>
            <a
                href="/account/my-objects?status=4<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >Annulé</a>
            <a
                href="/account/my-objects?status=5<?=isset($byType) ? "&type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg bg-orange-300"
            >Refusé</a>
            <a
                href="/account/my-objects<?=isset($byType) ? "?type=" . $byType : ""?>"
                class="px-2 py-px rounded-lg text-red-500"
            >X</a>
        </div>
        <div class="
            posts w-5/6 mx-auto flex flex-wrap justify-between gap-y-5 items-center mt-10
            md:w-11/12 md:justify-evenly
        ">
            <?php if (count($objects) == 0) : ?>
                <div class="no-posts text-center text-xl">Aucun objet trouvé...</div>
            <?php else : ?>
                <?php foreach ($objects as $object) :
                    $objectId = $object['id'];
                    $image = isset($object['images']) ? $object['images'][0] : null;
                    $classroom = $object['classroom'];
                    $title = $object['title'];
                    $description = $object['description'];
                    ?>
                    <?php require('components/object.php'); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="previous-next-page text-center mt-10 space-x-2 md:space-x-5">
            <?php if (isset($previousPage)) : ?>
                <a
                    class="bg-primary py-4 px-5 md:px-10 text-white"
                    href="/account/my-objects?
                    <?=isset($byType) ? "type=" . $byType . "&" : ""?>
                    <?=isset($byStatus) ? "status=" . $byStatus . "&" : ""?>
                    page=<?=$previousPage?>"
                >Page précédente</a>
            <?php endif; ?>

            <?php if (isset($nextPage)) : ?>
                <a
                    class="bg-primary py-4 px-5 md:px-10 text-white"
                    href="/account/my-objects?
                    <?=isset($byType) ? "type=" . $byType . "&" : ""?>
                    <?=isset($byStatus) ? "status=" . $byStatus . "&" : ""?>
                    page=<?=$nextPage?>"
                >Page suivante</a>
            <?php endif; ?>
        </div>
    </div>
</div>