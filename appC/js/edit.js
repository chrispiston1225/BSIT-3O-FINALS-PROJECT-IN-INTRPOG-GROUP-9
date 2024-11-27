document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModal = document.getElementById('editModal');
    const closeEditModal = editModal.querySelector('.close');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Extract data attributes from the clicked button
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const image = this.dataset.image;

            // Populate modal input fields
            document.getElementById('edit-menu-id').value = id;
            document.getElementById('edit-menuname').value = name;
            document.getElementById('edit-price').value = price;

            // Handle image preview (if applicable)
            const imgPreview = document.getElementById('edit-image-preview');
            if (imgPreview && image) {
                imgPreview.src = image;
                imgPreview.style.display = 'block';
            } else if (imgPreview) {
                imgPreview.style.display = 'none';
            }

            // Show the modal
            editModal.style.display = 'block';
        });
    });

    // Close modal logic
    closeEditModal.addEventListener('click', function () {
        editModal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });
});
