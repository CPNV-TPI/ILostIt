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

        <?php if (isset($error) && $error != null) : ?>
            <div class="error text-red-500 text-center">
                <?=$error?>
            </div>
        <?php endif; ?>

        <?php if ($object['status'] == 1 && $object['memberOwner_id'] == $_SESSION['id']) : ?>
            <div class="flex justify-around">
                <button
                        id="found_btn"
                        class="bg-primary text-white w-5/12 py-3 text-center"
                >Objet trouvé !</button>

                <button
                        id="delete_btn"
                        class="bg-red-500 text-white w-5/12 py-3 text-center"
                >Supprimer</button>
            </div>

            <dialog id="delete_dialog">
                <div class="dialog p-5 w-[300px] md:w-[500px]">
                    <div class="text-center text-2xl mb-10">
                        Êtes vous sur ?
                    </div>

                    <div class="flex justify-around">
                        <button
                                id="delete_confirm"
                                class="bg-red-500 text-white w-5/12 py-3 mt-2 text-center cursor-pointer"
                        >
                            Oui
                        </button>
                        <button
                                id="delete_quit"
                                class="bg-primary text-white w-5/12 py-3 mt-2 text-center cursor-pointer"
                        >
                            Non
                        </button>
                    </div>
                </div>
            </dialog>

            <dialog id="found_dialog">
                <div class="dialog p-5 w-[300px] md:w-[500px]">
                    <button
                            id="found_dialog_close"
                            class="text-gray-300 hover:text-red-600 absolute right-5 top-5 text-xl"
                    >
                        X
                    </button>
                    <form id="found_form" method="POST" class="mt-10">
                        <label
                                for="finder_email"
                                class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                        >Email de l'utilisateur vous ayant aidé :</label>
                        <input
                                type="email"
                                name="finder_email"
                                id="finder_email"
                                class="w-full border-2 p-2 mb-5"
                                pattern="([a-z]+)\.([a-z]+[\-]*[0-9]*)+(@eduvaud\.ch)"
                                required
                        >

                        <input type="checkbox" id="found_alone" name="found_alone" class="mr-2 mb-5">
                        <label for="found_alone">Je l'ai trouvé moi même</label>

                        <input
                                type="submit"
                                id="found_submit"
                                class="bg-primary text-white w-full py-3 mt-2 text-center cursor-pointer"
                                value="Retrouvé"
                        >
                    </form>
                </div>
            </dialog>

            <script>
                /* Delete dialog */
                const delete_btn = document.getElementById('delete_btn')
                const delete_dialog = document.getElementById('delete_dialog')
                const delete_quit = document.getElementById('delete_quit')
                const delete_confirm = document.getElementById('delete_confirm')

                delete_btn.addEventListener('click', () => {
                    delete_dialog.showModal()
                })

                delete_quit.addEventListener('click', () => {
                    delete_dialog.close()
                })

                delete_confirm.addEventListener('click', async () => {
                    const apiUrl = "/objects/<?=$object['id']?>";

                    const response = await fetch(apiUrl, {
                        method: 'DELETE'
                    })

                    if (!response.ok) {
                        error.innerHTML = "Une erreur est survenue !"

                        return false
                    }

                    document.location.href = "/account/my-objects"
                })

                /* Found dialog */
                const found_btn = document.getElementById('found_btn')
                const found_dialog = document.getElementById('found_dialog')
                const found_dialog_close = document.getElementById('found_dialog_close')
                const found_form = document.getElementById('found_form')
                const found_alone = document.getElementById('found_alone')
                const finder_email = document.getElementById('finder_email')

                found_btn.addEventListener('click', () => {
                    found_dialog.showModal()
                })

                found_dialog_close.addEventListener('click', () => {
                    found_dialog.close()
                })

                finder_email.addEventListener('input', () => {
                    found_alone.disabled = finder_email.value !== "";
                })

                found_alone.addEventListener('click', () => {
                    if (found_alone.checked) {
                        finder_email.value = ""
                    }

                    finder_email.disabled = found_alone.checked;
                })

                found_form.addEventListener('submit', async (event) => {
                    event.preventDefault()
                    found_dialog.close()

                    const apiUrl = "/objects/<?=$object['id']?>/solve";
                    const data = new URLSearchParams(new FormData(form));

                    const response = await fetch(apiUrl, {
                        method: 'PATCH',
                        body: data
                    })

                    if (!response.ok) {
                        error.innerHTML = "Une erreur est survenue !"

                        return false
                    }

                    document.location.href = "/account/my-objects"
                })
            </script>

        <?php elseif ($object['status'] == 1) : ?>
            <button
                id="found_btn"
                class="absolute bg-primary text-white w-11/12 py-3 text-center"
            >Je l'ai trouvé !</button>

            <dialog id="message_dialog">
                <div class="dialog p-5 w-[300px] md:w-[500px]">
                    <button
                            id="message_dialog_close"
                            class="text-gray-300 hover:text-red-600 absolute right-5 top-5 text-xl"
                    >
                        X
                    </button>
                    <form id="message_form" action="/objects/<?=$object['id']?>/contact"  method="POST" class="mt-10">
                        <label
                                for="message"
                                class="mb-2 after:content-['*'] after:text-red-600 after:text-sm"
                        >Votre message</label>
                        <textarea
                                id="message"
                                name="message"
                                class="w-full resize-y border-2 p-2"
                                required
                        ></textarea>
                        <input
                                type="submit"
                                id="message_submit"
                                class="bg-primary text-white w-full py-3 mt-2 text-center cursor-pointer"
                                value="Contacter"
                        >
                    </form>
                </div>
            </dialog>
            <script>
                const found_btn = document.getElementById('found_btn')
                const message_dialog = document.getElementById('message_dialog')
                const message_dialog_close = document.getElementById('message_dialog_close')

                /* Message form show */
                found_btn.addEventListener('click', () => {
                    message_dialog.showModal()
                })

                message_dialog_close.addEventListener('click', () => {
                    message_dialog.close()
                })
            </script>

        <?php elseif ($object['status'] == 0 && $_SESSION['isMod'] == 1) : ?>
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
