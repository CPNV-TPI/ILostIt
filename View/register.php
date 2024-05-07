<?php

namespace ILostIt\View;

?>

<div class="register w-[300px] md:w-[400px] lg:w-[500px] mx-auto mt-10 space-y-10">
    <div class="title text-center text-2xl">S'enregistrer</div>
    <form action="/auth/register" method="post" class="space-y-10">
        <div class="lastname">
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" class="w-full border border-black p-2">
        </div>

        <div class="firstname">
            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" class="w-full border border-black p-2">
        </div>

        <div class="email">
            <label for="email" class="after:content-['*'] after:text-red-600 after:text-sm">Email</label>
            <input
                type="text"
                name="email"
                id="email"
                class="w-full border border-black p-2"
                required
                pattern="^([a-z]+)\.([a-z]+[-]*[0-9]*)+(\@eduvaud\.ch)$"
            >
        </div>

        <div class="password">
            <label for="password" class="after:content-['*'] after:text-red-600 after:text-sm">Mot de passe</label>
            <input type="password" name="password" id="password" class="w-full border border-black p-2" required>
        </div>

        <input type="submit" value="Envoyer" class="w-full py-2 bg-primary text-white">
    </form>
</div>
