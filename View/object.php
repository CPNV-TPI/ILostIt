<?php

namespace ILostIt\View;

?>
<a href="/objects" class="absolute p-2">< Retour</a>

<div class="post mt-10">
    <div class="content space-y-10 lg:space-y-20 w-11/12 mx-auto">
        <div class="title text-center text-2xl">
            <?=$object['title']?>
        </div>

        <?php if (!isset($object['images']) || count($object['images']) == 0) : ?>
            <img src="../src/img/base_image.png" alt="" class="h-[150px] md:h-[300px] mx-auto shadow-md">
        <?php endif; ?>

        <?php if (isset($object['images']) && count($object['images']) == 1) : ?>
            <img
                src="../src/img/objects/<?=$object['id']?>/<?=$object['images'][0]?>"
                alt=""
                class="h-[150px] md:h-[300px] mx-auto shadow-md"
            >
        <?php endif; ?>

        <?php if (isset($object['images']) && count($object['images']) > 1) : ?>
            <div class="slider">
                <div class="splide slider-last-posts lg:w-8/12 mx-auto" id="sliderPost">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($object['images'] as $image) : ?>
                                <li class="splide__slide">
                                    <div class="splide__slide__container">
                                        <img
                                            src="../src/img/objects/<?=$object['id']?>/<?=$image?>"
                                            alt=""
                                            class="h-[150px] md:h-[300px] mx-auto shadow-md"
                                        >
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

        <?php if ($object['status'] == 1) : ?>
            <a
                href="/posts/<?=$object['id']?>"
                class="absolute bg-primary text-white w-11/12 py-3 text-center"
            >Je l'ai trouvé !</a>
        <?php endif; ?>

        <?php if ($object['status'] == 0 && $_SESSION['isMod'] == 1) : ?>
            <div class="absolute flex justify-around w-11/12">
                <button id="accept_btn" class="bg-primary text-white w-5/12 py-3 text-center">Accepter</button>
                <button id="refuse_btn" class="bg-red-500 text-white w-5/12 py-3 text-center">Refuser</button>
            </div>

            <div id="error" class="text-center text-red-500">

            </div>

            <dialog id="refuse_dialog">
                <div class="dialog p-5 w-[300px] md:w-[500px]">
                    <button
                            id="refuse_dialog_close"
                            class="text-gray-300 hover:text-red-600 absolute right-5 top-5 text-xl"
                    >
                        X
                    </button>
                    <form id="refuse_form" class="mt-10">
                        <label
                                for="reason"
                                class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                        >Raison</label>
                        <textarea
                                id="reason"
                                name="reason"
                                class="w-full resize-y border-2 p-2"
                                required
                        ></textarea>
                        <input
                                type="submit"
                                id="refuse_submit"
                                class="bg-red-500 text-white w-full py-3 mt-2 text-center cursor-pointer"
                                value="Refuser"
                        >
                    </form>
                </div>
            </dialog>
            <script>
                const accept_btn = document.getElementById('accept_btn')
                const refuse_btn = document.getElementById('refuse_btn')
                const refuse_dialog = document.getElementById('refuse_dialog')
                const refuse_dialog_close = document.getElementById('refuse_dialog_close')
                const refuse_form = document.getElementById('refuse_form')
                const error = document.getElementById('error')

                const apiUrl = '/objects/<?=$object['id']?>/validation'

                /**
                 * This function is designed to send the validation of the object
                 * @returns {Promise<boolean>}
                 */
                const validateObject = async (form = null) => {
                    let data;
                    if (form != null) {
                        data = new URLSearchParams(new FormData(form));
                    }

                    const response = await fetch(apiUrl, {
                        method: 'PATCH',
                        body: data
                    })

                    if (!response.ok) {
                        error.innerHTML = "Une erreur est survenue !"

                        return false
                    }

                    return true
                }

                /* Refuse form show */
                refuse_btn.addEventListener('click', () => {
                    refuse_dialog.showModal()
                })

                refuse_dialog_close.addEventListener('click', () => {
                    refuse_dialog.close()
                })

                /* Submit validation */
                accept_btn.addEventListener('click', () => {
                    if(!validateObject()) {
                        return
                    }

                    document.location.href = "/objects/<?=$object['id']?>"
                    document.location.reload()
                })

                refuse_form.addEventListener('submit', (event) => {
                    event.preventDefault()
                    refuse_dialog.close()

                    if(!validateObject(refuse_form)) {
                        return
                    }

                    document.location.href = "/mod"
                })
            </script>
        <?php endif; ?>
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
