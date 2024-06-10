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
    if (nav_menu.classList.contains('hidden')) {
        nav_menu.classList.replace('hidden', 'flex')
    } else {
        nav_menu.classList.replace('flex', 'hidden')
    }
})

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