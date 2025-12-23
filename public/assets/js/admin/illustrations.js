document.addEventListener('click', function (e) {
    if (e.target.matches('.ea-image, .ea-thumb')) {
        const modal = document.getElementById('ea-image-modal');
        const modalImg = document.getElementById('ea-modal-img');

        modalImg.src = e.target.dataset.full;
        modal.style.display = 'block';
    }

    if (e.target.matches('.ea-modal, .ea-modal-close')) {
        document.getElementById('ea-image-modal').style.display = 'none';
    }
});
