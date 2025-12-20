<?php 
// NOTE: This file is assumed to be in the project root.
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include('includes/header.php'); 
error_reporting(E_ALL);

// Fetch all menu items, ordered by food_type
$menu_query = mysqli_query($conn, "SELECT * FROM food_menu ORDER BY food_type, food_name ASC"); 

// Check for query failure and group items
$menu_items = [];
if ($menu_query) {
    while ($row = mysqli_fetch_assoc($menu_query)) {
        $menu_items[$row['food_type']][] = $row; 
    }
}
$item_count = 0; // Initialize counter for animation delay
?>

<div class="container menu-page-container">
    <section class="menu-header text-center">
        <h1>The Sprout Menu</h1>
        <p class="lead-text">Experience culinary mastery using the freshest seasonal ingredients, prepared exclusively for guests of The Citadel Retreat.</p>
    </section>

    <?php if (empty($menu_items)): ?>
        <p class="text-center empty-state">Our kitchen is currently finalizing the seasonal menu. Please check back soon!</p>
    <?php else: ?>
        <div class="menu-sections">
            <?php foreach ($menu_items as $type => $items): ?>
                <section class="menu-section">
                    <h2 class="menu-category-title">
                        <i class="<?= get_food_icon($type); ?>"></i>
                        <span><?= htmlspecialchars($type); ?></span>
                    </h2>
                    <div class="menu-grid">
                        <?php foreach ($items as $item): 
                            $item_count++;
                        ?>
                            <!-- Item Card: Purely presentation, no buttons or actions -->
                            <div class="menu-grid-item card fade-in-card" data-delay="<?= $item_count * 0.05; ?>"> 
                                <div class="item-details-static">
                                    <div class="item-header">
                                        <h3><?= htmlspecialchars($item['food_name']); ?></h3>
                                        <span class="item-price">₹<?= number_format($item['price'], 2); ?></span>
                                    </div>
                                    <p class="item-description"><?= htmlspecialchars($item['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>