document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('menuModal');
    const addMenuBtn = document.getElementById('addMenuBtn');
    const closeModalBtn = document.querySelector('.close');

    addMenuBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});


