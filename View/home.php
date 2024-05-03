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
                <div class="splide slider-last-posts md:w-7/9 mx-auto">
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

            <?php if(!isset($_SESSION)): ?>
                <a href="/posts" class="bg-primary px-20 py-5 text-white">Voir plus</a>
            <?php endif; ?>
        </div>
    </div>
</div>
