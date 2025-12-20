document.addEventListener('DOMContentLoaded', () => {
    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // --- Date Validation from index.php ---
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const guestsInput = document.getElementById('guests');

    if (checkInInput && checkOutInput && guestsInput) {
        const today = new Date().toISOString().split('T')[0];
        checkInInput.setAttribute('min', today);
        checkOutInput.setAttribute('min', today);
        guestsInput.setAttribute('min', '1');

        checkOutInput.addEventListener('change', function () {
            if (checkInInput.value > this.value) {
                checkInInput.value = this.value;
            }
            checkInInput.setAttribute('max', this.value);
        });

        checkInInput.addEventListener('change', function () {
            if (checkOutInput.value < this.value) {
                checkOutInput.value = this.value;
            }
            checkOutInput.setAttribute('min', this.value);
        });

        guestsInput.addEventListener('input', function () {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    }
});