/**
 * Author      : Diogo da Silva Fernandes
 * Project     : TPI - I Lost It
 * Description : This file is designed to handle some simple features of the app like the hamgurger menu.
 * Last update : 03.05.2024 - @diogof648-dev
 */

/* Hamburger menu */
const nav_hamburger = document.querySelector('.hamburger')
const nav_menu = document.querySelector('.links')

nav_hamburger.addEventListener('click', () => {
    if (nav_menu.classList.contains('hidden'))
        nav_menu.classList.replace('hidden', 'flex')
    else
        nav_menu.classList.replace('flex', 'hidden')
})

/* Last posts slider */
var splide = new Splide( '.splide', {
    type: 'loop',
    pagination: false
});

function changeNbOfPostsBasedOnWidth() {
    if(window.innerWidth > 425)
        splide.options.perPage = 2

    if(window.innerWidth > 768)
        splide.options.perPage = 3

    if(window.innerWidth <= 425)
        splide.options.perPage = 1
}

document.addEventListener( 'DOMContentLoaded', function() {
    changeNbOfPostsBasedOnWidth()
} );

window.onresize = () => {
    changeNbOfPostsBasedOnWidth()
}

splide.mount();

/* Post menu */
const post_btn = document.getElementById('post_btn')
const post_dialog = document.getElementById('post_dialog')
const post_dialog_close = document.getElementById('post_dialog_close')

post_btn.addEventListener('click', () => {
    post_dialog.showModal()
})

post_dialog_close.addEventListener('click', () => {
    post_dialog.close()
})