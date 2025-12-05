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

    // --- Dynamic Room Loading ---
    (function () {
        const offsetInput = document.getElementById('room-offset');
        const totalInput = document.getElementById('room-total');
        const container = document.getElementById('rooms-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        if (!offsetInput || !totalInput || !container || !loadingIndicator) return;
        let currentOffset = parseInt(offsetInput.value);
        const totalRooms = parseInt(totalInput.value);
        let isLoading = false;

        const loadMoreRooms = () => {
            if (isLoading || currentOffset >= totalRooms) return;
            isLoading = true;
            loadingIndicator.style.display = 'block';

            const postBody = new URLSearchParams({
                offset: currentOffset,
                check_in: checkInInput.value,
                check_out: checkOutInput.value,
                guests: guestsInput.value
            }).toString();

            fetch('assets/ajax/load_rooms.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: postBody
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() !== "") {
                    container.insertAdjacentHTML('beforeend', data);
                    offsetInput.value = currentOffset;
                } else {
                    window.removeEventListener('scroll', handleRoomScroll);
                }
            })
            .catch(err => console.error('Error loading rooms:', err))
            .finally(() => {
                isLoading = false;
                loadingIndicator.style.display = 'none';
            });
        };
    })();

    // --- Dynamic Table Loading ---
    (function () {
        const loadBtn = document.getElementById('load-more-tables-btn');
        const offsetInput = document.getElementById('table-offset');
        const totalInput = document.getElementById('table-total');
        const limitInput = document.getElementById('table-limit');
        const container = document.getElementById('tables-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const loadMoreContainer = document.querySelector('.load-more-container');

        if (!loadBtn || !offsetInput || !totalInput || !limitInput || !container || !loadingIndicator) return;

        let currentOffset = parseInt(offsetInput.value);
        const totalTables = parseInt(totalInput.value);
        const limit = parseInt(limitInput.value);
        let isLoading = false;

        const updateButtonText = (remaining) => {
            if (remaining > 0) {
                loadBtn.textContent = `Load More Tables (${remaining} remaining)`;
            } else {
                loadBtn.style.display = 'none';
                if (loadMoreContainer) loadMoreContainer.innerHTML = '<p class="text-light">All available tables have been listed.</p>';
            }
        };

        const loadMoreTables = () => {
            if (isLoading || currentOffset >= totalTables) return;
            isLoading = true;
            loadingIndicator.style.display = 'block';
            loadBtn.disabled = true;

            fetch('assets/ajax/load_tables.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `offset=${currentOffset}&limit=${limit}`
            })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() !== "") {
                        container.insertAdjacentHTML('beforeend', data);
                        currentOffset += limit;
                        offsetInput.value = currentOffset;

                        const remaining = totalTables - currentOffset;
                        updateButtonText(remaining);
                    }
                })
                .catch(err => console.error('Error loading tables:', err))
                .finally(() => {
                    isLoading = false;
                    loadingIndicator.style.display = 'none';
                    loadBtn.disabled = false;
                });
        };

        loadBtn.addEventListener('click', loadMoreTables);
    })();
});