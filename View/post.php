<?php

namespace ILostIt\View;

?>
<a href="/posts" class="absolute p-2">< Retour</a>

<div class="post mt-10">
    <div class="content space-y-10 lg:space-y-20 w-11/12 mx-auto">
        <div class="title text-center text-2xl">
            <?=$post['title']?>
        </div>

        <?php if (count($post['images']) == 0) : ?>
            <img src="../src/img/post_base_image.png" alt="" class="w-1/3 mx-auto shadow-md">
        <?php endif; ?>

        <?php if (count($post['images']) == 1) : ?>
            <img src=<?=$post['images'][0]?> alt="" class="w-1/3 mx-auto shadow-md">
        <?php endif; ?>

        <?php if (count($post['images']) > 1) : ?>
            <div class="slider">
                <div class="splide slider-last-posts md:w-8/12 lg:w-6/12 mx-auto" id="sliderPost">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($post['images'] as $image) : ?>
                                <li class="splide__slide">
                                    <div class="splide__slide__container">
                                        <img src=<?=$image?> alt="" class="m-1/3 mx-auto shadow-md">
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="lg:flex lg:space-x-10 space-y-5">
            <div class="description mx-auto lg:w-1/2">
                <div class="w-full border-b border-black">Description :</div>
                <div class="test text-justify mt-2">
                    <?=$post['description']?>
                </div>
            </div>

            <div class="attributs mx-auto lg:w-1/2">
                <div class="w-full border-b border-black">Attributs :</div>
                <table class="w-full mt-2 border-collapse">
                    <tr>
                        <td class="border-r border-black">Classe</td>
                        <td class="text-right"><?=$post['classroomNumber']?></td>
                    </tr>
                </table>
            </div>
        </div>

        <a
            href="/posts/<?=$post['id']?>"
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
