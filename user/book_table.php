<?php 
$PROJECT_ROOT = 'Hotel%20Management%20system'; 
include('../includes/header.php'); 
?>

<div class="container auth-page"> 
    <div class="auth-container" style="max-width: 600px;"> 
        <div class="auth-header">
            <h2>Reserve a Table</h2>
            <p>Secure your spot at The Sprout, our signature dining experience.</p>
        </div>

        <form action="<?= $PROJECT_ROOT ?>/bookings/booking_process.php" method="POST" class="table-booking-form">
            <input type="hidden" name="action" value="book_table">
            
            <!-- Step 1: Dining Requirements -->
            <div class="form-group">
                <label for="dining_date">Date</label>
                <input type="date" id="dining_date" name="dining_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="party_size">Number of Guests</label>
                <input type="number" id="party_size" name="party_size" class="form-control" placeholder="1 - 10" min="1" max="10" required>
            </div>
            
            <div class="form-group">
                <label for="restaurant_location">Select Location</label>
                <select id="restaurant_location" name="restaurant_location" class="form-control" required>
                    <!-- Options fetched dynamically from DB in a real app -->
                    <option value="">-- Choose a Dining Spot --</option>
                    <option value="main_sprout">The Sprout (Main Floor)</option>
                    <option value="rooftop">Rooftop Lounge (Limited Seating)</option>
                    <option value="private_room">Private Dining Room (4+ Guests)</option>
                </select>
            </div>

            <!-- Step 2: Time Slot Availability (Dynamic Loading) -->
            <div class="availability-section">
                <label>Available Time Slots (Select One)</label>
                <div id="time-slots-container" class="time-slots-grid">
                    <p class="text-center text-light">Select a date and number of guests above to view available times.</p>
                    <!-- Time slots loaded here via AJAX -->
                </div>
            </div>

            <button type="submit" class="btn btn-action btn-auth-submit" id="reserve-table-btn" disabled>
                Confirm Table Reservation
            </button>
            <p id="table-message" class="text-center" style="margin-top: 15px; color: var(--color-text-light); font-size: 0.9em;">Minimum 24 hours required for private room bookings.</p>
        </form>
    </div>
</div>

<script src="<?= $PROJECT_ROOT ?>/assets/js/ajax.js"></script>
<script>
    // Frontend JS logic for dynamically loading time slots and enabling the button
    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('dining_date');
        const sizeInput = document.getElementById('party_size');
        const locationInput = document.getElementById('restaurant_location');
        const slotsContainer = document.getElementById('time-slots-container');
        const reserveBtn = document.getElementById('reserve-table-btn');

        const updateTimeSlots = () => {
            const date = dateInput.value;
            const size = sizeInput.value;
            const location = locationInput.value;

            if (date && size && location && size > 0) {
                // In a real app, this would use AJAX (like in rooms.php)
                // fetch('/assets/ajax/check_table_slots.php', { ... })...
                
                // For demonstration, we simulate loading slots:
                slotsContainer.innerHTML = `
                    <button type="button" class="btn btn-slot">18:00</button>
                    <button type="button" class="btn btn-slot">19:30</button>
                    <button type="button" class="btn btn-slot btn-slot-booked">20:00</button>
                    <button type="button" class="btn btn-slot">21:15</button>
                `;
                reserveBtn.disabled = false;
            } else {
                slotsContainer.innerHTML = '<p class="text-center text-light">Please select all dining criteria.</p>';
                reserveBtn.disabled = true;
            }
        };

        // Event listeners to trigger slot update
        dateInput.addEventListener('change', updateTimeSlots);
        sizeInput.addEventListener('change', updateTimeSlots);
        locationInput.addEventListener('change', updateTimeSlots);

        // Logic to handle time slot selection
        slotsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-slot') && !e.target.classList.contains('btn-slot-booked')) {
                // Deselect all, select the clicked one
                slotsContainer.querySelectorAll('.btn-slot').forEach(btn => btn.classList.remove('selected'));
                e.target.classList.add('selected');
            }
        });
    });
</script>

<?php include('../includes/footer.php'); ?>