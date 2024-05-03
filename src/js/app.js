const post_btn = document.getElementById('post_btn')
const post_dialog = document.getElementById('post_dialog')
const post_dialog_close = document.getElementById('post_dialog_close')

post_btn.addEventListener('click', () => {
    post_dialog.showModal()
})

post_dialog_close.addEventListener('click', () => {
    post_dialog.close()
})