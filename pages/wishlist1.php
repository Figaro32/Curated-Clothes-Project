<?php
$title = 'Wishlist';
$nav_wishlist_class = 'active_page';

// The SQL query parts
$sql_select_clause = "SELECT wishlist.id AS 'id', file_name AS 'file_name', brand.name AS 'brand_name', clothing_type.name AS 'clothing_type_name'
FROM wishlist INNER JOIN brand ON (wishlist.brand_id = brand.id) INNER JOIN clothing_type ON (wishlist.clothing_type_id = clothing_type.id)";
$sql_order_clause = ''; // Order by file name by default

// Check if form has been submitted with filters
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filtered_brands = $_GET['brand_id'] ?? array();
    $filtered_clothing_types = $_GET['clothing_type_id'] ?? array();

    // Add brand and clothing type filters to SQL query
    if (!empty($filtered_brands)) {
        $sql_select_clause .= " WHERE brand.id IN (" . implode(',', $filtered_brands) . ")";
    }
    if (!empty($filtered_clothing_types)) {
        $sql_select_clause .= (empty($filtered_brands) ? " WHERE" : " AND") . " clothing_type.id IN (" . implode(',', $filtered_clothing_types) . ")";
    }
}

// Glue select clause to order clause
$sql_select_query = $sql_select_clause . " " . $sql_order_clause;

// Query database
$records = exec_sql_query($db, $sql_select_query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="view">
        <h2><?php echo $title; ?></h2>

        <!-- Filter form with checkboxes for each brand and clothing type -->
        <form method="get" action="">
            <fieldset>
                <legend>Filter by:</legend>

                <!-- Checkboxes for each brand -->
                <div>
                    <span>Brand:</span>
                    <?php foreach ($brands as $brand) { ?>
                        <label>
                            <input type="checkbox" name="brand_id[]" value="<?php echo htmlspecialchars($brand['id']); ?>" <?php echo (in_array($brand['id'], $filtered_brands) ? 'checked' : ''); ?>>
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <!-- Checkboxes for each clothing type -->
                <div>
                    <span>Clothing Type:</span>
                    <?php foreach ($clothing_types as $clothing_type) { ?>
                        <label>
                            <input type="checkbox" name="clothing_type_id[]" value="<?php echo htmlspecialchars($clothing_type['id']); ?>" <?php echo (in_array($clothing_type['id'], $filtered_clothing_types) ? 'checked' : ''); ?>>
                            <?php echo htmlspecialchars($clothing_type['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <button type="submit">Filter</button>
            </fieldset>
        </form>

    </section>

</body>

</html>
