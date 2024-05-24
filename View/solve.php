<?php

namespace ILostIt\View;

?>

<div class="verify w-3/4 mx-auto text-center mt-20 text-2xl">
    <p id="status"></p>
    <div id="timer" class="hidden mt-10">
        Vous allez être redirigé vers la page de connexion dans : <span id="seconds">5</span>
    </div>
</div>

<script>
    const status = document.getElementById('status')
    const timer = document.getElementById('timer')
    const seconds = document.getElementById('seconds')
    const apiUrl = '/objects/<?=$id?>/solve?confirm=true'
    let redirectInSeconds = 5

    document.addEventListener('DOMContentLoaded', function () {
        fetch(apiUrl, {
            method: 'PATCH',
        })
            .then(response => {
                if(!response.ok){
                    status.classList.add('text-red-500')
                    status.innerHTML = "Une erreur s'est produite... Merci de réssayer plus tard !"
                } else {
                    status.classList.add('text-green-500')
                    status.innerHTML = 'La publication a été résolue ! Merci à vous !'
                    timer.classList.replace('hidden', 'block')

                    // Visual timer for redirect
                    setInterval(function () {
                        redirectInSeconds -= 1

                        seconds.innerHTML = redirectInSeconds
                    }, 1000)

                    // Redirect in x defined seconds
                    setTimeout(function () {
                        document.location.href = '/'
                    }, redirectInSeconds * 1000)
                }
            })
    })
</script>
