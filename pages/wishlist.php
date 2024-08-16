<?php
$title = 'Gallery';
$nav_reviews_class = 'active_page';

// The SQL query parts
$sql_select_clause = "SELECT * FROM wishlist";
$sql_order_clause = ''; // No order by default

// glue select clause to order clause
$sql_select_query = $sql_select_clause;

// query DB
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

    <!-- Display filter options as checkboxes -->

    <section>
        <?php
        // Query tags table to get list of tags
        $tags_sql = "SELECT *
        FROM wishlist
        INNER JOIN brand ON wishlist.brand_id = brand.id
        INNER JOIN clothing_type ON wishlist.clothing_type_id = clothing_type.id
        WHERE brand.id = :brand_id AND clothing_type.id = :clothing_type_id
        ";
        $tags = exec_sql_query($db, $tags_sql)->fetchAll();
        ?>
        <?php
        // Retrieve a list of brands from the database
        $brands = exec_sql_query($db, "SELECT * FROM brand")->fetchAll();

        // Retrieve a list of clothing types from the database
        $clothing_types = exec_sql_query($db, "SELECT * FROM clothing_type")->fetchAll();
        ?>

        <!-- Create a form with checkboxes for each brand and clothing type -->
        <form method="get" action="">
            <fieldset>
                <legend>Filter by:</legend>

                <!-- Create checkboxes for each brand -->
                <div>
                    <span>Brand:</span>
                    <?php foreach ($brands as $brand) { ?>
                        <label>
                            <input type="checkbox" name="brand_id[]" value="<?php echo htmlspecialchars($brand['id']); ?>">
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <!-- Create checkboxes for each clothing type -->
                <div>
                    <span>Clothing type:</span>
                    <?php foreach ($clothing_types as $clothing_type) { ?>
                        <label>
                            <input type="checkbox" name="clothing_type_id[]" value="<?php echo htmlspecialchars($clothing_type['id']); ?>">
                            <?php echo htmlspecialchars($clothing_type['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <?php if (isset($_GET['brand_id'])) {
                    $selected_brand_ids = $_GET['brand_id'];
                }

                if (isset($_GET['clothing_type_id'])) {
                    $selected_clothing_type_ids = $_GET['clothing_type_id'];
                }
                ?>


                <button type="submit">Filter</button>
            </fieldset>
    </section>


    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // form is submitted
        // process the form data here
        $selected_brand_ids = $_GET['brand_id'] ?? [];
        $selected_clothing_type_ids = $_GET['clothing_type_id'] ?? [];
        $sql_select_clause = "SELECT * FROM wishlist";
        $sql_join_clause = "
    INNER JOIN brand ON wishlist.brand_id = brand.id
    INNER JOIN clothing_type ON wishlist.clothing_type_id = clothing_type.id
";
        $sql_where_clause = "";
        $sql_params = [];

        // // Add brand filter to query
        // if (!empty($selected_brand_ids)) {
        //     $sql_where_clause .= " AND brand.id IN (" . str_repeat("?,", count($selected_brand_ids) - 1) . "?)";
        //     $sql_params = array_merge($sql_params, $selected_brand_ids);
        // }

        // // Add clothing type filter to query
        // if (!empty($selected_clothing_type_ids)) {
        //     $sql_where_clause .= " AND clothing_type.id IN (" . str_repeat("?,", count($selected_clothing_type_ids) - 1) . "?)";
        //     $sql_params = array_merge($sql_params, $selected_clothing_type_ids);
        // }

        // Concatenate the query clauses
        $sql_select_query = $sql_select_clause . $sql_join_clause . " WHERE 1=1 " . $sql_where_clause;

        $records = exec_sql_query($db, $sql_select_query, $sql_params)->fetchAll();
    }
    ?>


    <!-- Display view-all entries -->
    <!-- ALL IMAGES SOURCED FROM SSENSE.COM -->
    <!-- ALL TITLES SOURCED FROM SSENSE.COM -->
    <section class="view">
        <ul>
            <?php foreach ($records as $record) {
                $file_url = 'public/uploads/clothes/' . $record['id'] . '.' . $record['file_ext']; ?>
                <div class="thumbnail">
                    <a href="details?id=<?php echo $record['id']; ?>">
                        <img src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['file_name']); ?>">
                    </a>

                    <h3><?php echo htmlspecialchars($record['file_name']); ?></h3>

                </div>
            <?php } ?>
        </ul>

    </section>






</body>

</html>
