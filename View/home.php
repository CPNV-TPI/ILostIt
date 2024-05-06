<?php

namespace ILostIt\View;

?>

<div class="home">
    <div class="content">
        <div class="last-posts space-y-5 md:space-y-10 text-center">
            <div class="title text-4xl pt-5 md:pt-10">Bienvenue sur la plateforme !</div>
            <div class="sep w-1/3 h-px bg-black mx-auto"></div>
            <div class="desc">Les dernières publications :</div>

            <div class="slider">
                <div class="splide slider-last-posts md:w-7/9 mx-auto" id="sliderHome">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <li class="splide__slide">
                                <div class="splide__slide__container w-2/3 mx-auto py-2 h-96 bg-green-700"></div>
                            </li>
                            <li class="splide__slide">
                                <div class="splide__slide__container w-2/3 mx-auto py-2 h-96 bg-blue-700"></div>
                            </li>
                            <li class="splide__slide">
                                <div class="splide__slide__container w-2/3 mx-auto py-2 h-96 bg-red-700"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (!isset($_SESSION)) : ?>
                <a href="/posts" class="bg-primary px-20 py-5 text-white">Voir plus</a>
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
