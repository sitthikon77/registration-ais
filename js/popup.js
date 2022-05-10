let imageBox = document.querySelectorAll('.img-box');
imageBox.forEach(popup => popup.addEventListener('click', () => {
    popup.classList.toggle('active');
}));