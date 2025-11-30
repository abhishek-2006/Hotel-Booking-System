<?php 
include('includes/header.php');
include('includes/config.php'); 

error_reporting(E_ALL);

// Define pagination parameters
$initial_limit = 4;
$initial_offset = 0;

// Get total table count for checking when to stop loading
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tables");
$total_tables = mysqli_fetch_assoc($count_query)['total'];
?>
<div class="container tables-page-container">
    <h2 class="page-title text-center">Our Dining Tables</h2>

    <div class="tables-grid" id="tables-container">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM tables ORDER BY capacity ASC LIMIT $initial_limit OFFSET $initial_offset");
        
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                // Use the ENUM status string directly from the DB
                $isAvailable = ($row['status'] === 'Available');
                $availabilityText = $row['status']; // e.g., 'Available', 'Unavailable'
        ?>
        
        <div class="table-card card <?= !$isAvailable ? 'card-reserved' : ''; ?>">
            <div class="table-image-wrapper">
                <img src="assets/images/tables/<?= htmlspecialchars($row['image']); ?>" 
                    alt="Table for <?= htmlspecialchars($row['capacity']); ?> Image" 
                    class="table-image">
                <span class="table-status table-status-<?= $isAvailable ? 'available' : 'reserved'; ?>">
                    <?= $availabilityText; ?>
                </span>
            </div>
            
            <div class="table-details">
                <!-- Use table_type for better display name -->
                <h3><?= htmlspecialchars($row['table_type']); ?></h3> 
                
                <p class="table-location">
                    <span>Capacity:</span> 
                    <strong><?= htmlspecialchars($row['capacity']); ?> Guests</strong>
                </p>
                <p class="table-price">
                    <span>Hourly Price:</span> 
                    <strong>₹<?= number_format($row['price_per_hour']); ?></strong>
                </p>
                
                <a href="user/reserve_table.php?table_id=<?= $row['table_id']; ?>" 
                    class="btn btn-action btn-full-width"
                    <?php 
                    // Safely output the 'disabled' attribute and inline style if NOT available
                    if (!$isAvailable) {
                        echo 'disabled style="pointer-events: none; opacity: 0.6;"'; 
                    } 
                    ?>>
                    <?= $isAvailable ? 'Reserve This Table' : 'View Details'; ?>
                </a>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p class='text-center empty-state'>No tables available at the moment. Please check back later.</p>";
        }
        ?>
    </div>
    
    <!-- Loading indicator and metadata for JavaScript -->
    <div class="text-center" id="loading-indicator" style="display:none; margin:20px 0;">
        <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--color-brand);"></i>
        <p style="color:var(--color-text-light);">Fetching more tables...</p>
    </div>

    <!-- Metadata for JavaScript to track state -->
    <input type="hidden" id="table-offset" value="<?= $initial_limit; ?>">
    <input type="hidden" id="table-total" value="<?= $total_tables; ?>">
    <input type="hidden" id="table-limit" value="<?= $initial_limit; ?>">
    
    <?php if ($total_tables > $initial_limit): ?>
    <div class="text-center load-more-container">
        <button id="load-more-tables-btn" class="btn btn-primary">
            Load More Tables (<?= $total_tables - $initial_limit; ?> remaining)
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- Use main.js for general logic, or tables.js for dedicated logic -->
<script src="assets/js/main.js"></script> 
<?php include('includes/footer.php'); ?>