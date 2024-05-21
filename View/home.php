<?php

namespace ILostIt\View;

?>

<div class="home">
    <div class="content">
        <div class="last-posts space-y-5 md:space-y-10 text-center">
            <div class="title text-4xl pt-5 md:pt-10">Bienvenue sur la plateforme !</div>
            <div class="sep w-1/3 h-px bg-black mx-auto"></div>
            <div class="desc">Les dernièrs objets :</div>

            <div class="slider">
                <?php if (count($objects) != 0) : ?>
                    <div class="splide slider-last-posts md:w-7/9 mx-auto" id="sliderHome">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php
                                foreach ($objects as $object) :
                                    $title = $object['title'];
                                    $description = $object['description'];
                                    $image = $object['images'][0] ?? null;
                                    ?>
                                    <li class="splide__slide">
                                        <div
                                            class="
                                                splide__slide__container
                                                w-2/3
                                                mx-auto
                                                p-4
                                                h-96
                                                shadow-lg
                                                flex
                                                flex-col
                                                space-y-
                                            "
                                        >
                                            <img
                                                src="<?=!isset($image) ? "src/img/base_image.png" : $image?>"
                                                alt="Object image"
                                                class="w-[150px] mx-auto"
                                            >
                                            <div class="text-2xl"><?=$title?></div>
                                            <div class="text-justify"><?=$description?></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="text-center text-2xl">Aucun objet trouvé...</div>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['email'])) : ?>
                <div class="see-more pt-10">
                    <a href="/objects" class="bg-primary px-20 py-5 text-white">Voir plus</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    /* Last posts slider - Home */
    let sliderHome = new Splide('#sliderHome', {
        type: 'loop',
        pagination: false,
        perMove: 1
    })

    function changeNbOfPostsBasedOnWidth()
    {
        if (window.innerWidth > 425) {
            sliderHome.options.perPage = 2
        }

        if (window.innerWidth > 768) {
            sliderHome.options.perPage = 3
        }

        if (window.innerWidth <= 425) {
            sliderHome.options.perPage = 1
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        changeNbOfPostsBasedOnWidth()
    })

    window.onresize = () => {
        changeNbOfPostsBasedOnWidth()
    }

    sliderHome.mount()
</script>
