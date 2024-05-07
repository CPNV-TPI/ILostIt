<?php

namespace ILostIt\View;

?>
<a href="/objects" class="absolute p-2">< Retour</a>

<div class="post mt-10">
    <div class="content space-y-10 lg:space-y-20 w-11/12 mx-auto">
        <div class="title text-center text-2xl">
            <?=$object['title']?>
        </div>

        <?php if (count($object['image']) == 0) : ?>
            <img src="../src/img/base_image.png" alt="" class="w-[200px] md:w-[300px] mx-auto shadow-md">
        <?php endif; ?>

        <?php if (count($object['image']) == 1) : ?>
            <img src=<?=$object['image'][0]?> alt="" class="w-[200px] md:w-[300px] mx-auto shadow-md">
        <?php endif; ?>

        <?php if (count($object['image']) > 1) : ?>
            <div class="slider">
                <div class="splide slider-last-posts md:w-8/12 lg:w-6/12 mx-auto" id="sliderPost">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($object['image'] as $image) : ?>
                                <li class="splide__slide">
                                    <div class="splide__slide__container">
                                        <img src=<?=$image?> alt="" class="w-[200px] md:w-[300px] mx-auto shadow-md">
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="space-y-5 lg:flex lg:space-x-10 lg:space-y-0">
            <div class="description mx-auto lg:w-1/2">
                <div class="w-full border-b border-black">Description :</div>
                <div class="test text-justify mt-2">
                    <?=$object['description']?>
                </div>
            </div>

            <div class="attributes mx-auto lg:w-1/2">
                <div class="w-full border-b border-black">Attributs :</div>
                <table class="w-full mt-2 border-collapse">
                    <tr>
                        <td class="border-r border-black">Classe</td>
                        <td class="text-right"><?=$object['classroom']?></td>
                    </tr>
                    <?php if ($object['brand'] != null) : ?>
                        <tr>
                            <td class="border-r border-black">Marque</td>
                            <td class="text-right"><?=$object['brand']?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($object['color'] != null) : ?>
                        <tr>
                            <td class="border-r border-black">Couleur</td>
                            <td class="text-right"><?=$object['color']?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($object['value'] != null) : ?>
                        <tr>
                            <td class="border-r border-black">Valeur</td>
                            <td class="text-right"><?=$object['value']?> CHF</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <a
            href="/posts/<?=$object['id']?>"
            class="absolute bg-primary text-white w-11/12 py-3 text-center"
        >Je l'ai trouvé !</a>
    </div>
</div>

<script>
    /* Slider images post */
    let sliderPost = new Splide('#sliderPost', {
        type: 'loop',
        pagination: false,
        perMove: 1
    }).mount()
</script>
