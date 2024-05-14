<?php

namespace ILostIt\View;

?>

<div class="register w-[300px] md:w-[400px] lg:w-[500px] mx-auto mt-10 space-y-10">
    <div class="title text-center text-2xl">Se connecter</div>
    <form action="/auth/login" method="post" class="space-y-10">
        <div class="email">
            <label for="email" class="after:content-['*'] after:text-red-600 after:text-sm">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                class="w-full border border-black p-2"
                pattern="/([a-z]+)\.([a-z]+[-]*[0-9]*)+(@eduvaud\.ch)/g"
                required
            >
        </div>

        <div class="password">
            <label for="password" class="after:content-['*'] after:text-red-600 after:text-sm">Mot de passe</label>
            <input type="password" name="password" id="password" class="w-full border border-black p-2" required>
        </div>

        <?php if (isset($error)) : ?>
            <div class="text-center text-red-600 text-lg">
                <?=$error?>
            </div>
        <?php endif; ?>

        <input type="submit" value="Envoyer" class="w-full py-2 bg-primary text-white">
    </form>
</div>
