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
    <?php if (is_user_logged_in()) { ?>
        <p>Welcome <strong><?php echo htmlspecialchars($current_user['name']); ?></strong>!</p>
    <?php } ?>

    <?php
    // Access controls, only logged in user may upload or view
    if (is_user_logged_in()) { ?>
        <!-- Build the filter form -->
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
                            <input type="checkbox" name="brand_id[]" value="<?php echo htmlspecialchars($brand['id']); ?>" <?php if (isset($_GET['brand_id']) && in_array($brand['id'], $_GET['brand_id'])) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <!-- Create checkboxes for each clothing type -->
                <div>
                    <span>Clothing Type:</span>
                    <?php foreach ($clothing_types as $clothing_type) { ?>
                        <label>
                            <input type="checkbox" name="clothing_type_id[]" value="<?php echo htmlspecialchars($clothing_type['id']); ?>" <?php if (isset($_GET['clothing_type_id']) && in_array($clothing_type['id'], $_GET['clothing_type_id'])) {
                                                                                                                                                echo 'checked';
                                                                                                                                            } ?>>
                            <?php echo htmlspecialchars($clothing_type['name']); ?>
                        </label>
                    <?php } ?>
                </div>

                <button type="submit">Filter</button>
            </fieldset>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $query = "SELECT * FROM wishlist WHERE ";
            $params = array();
            $filter_brand = isset($_GET['brand_id']) ? $_GET['brand_id'] : array();
            $filter_clothing = isset($_GET['clothing_type_id']) ? $_GET['clothing_type_id'] : array();

            if (!empty($filter_brand)) {
                $filter_brand = "brand_id IN (" . implode(",", $filter_brand) . ")";
            }

            if (!empty($filter_clothing)) {
                $filter_clothing = "clothing_type_id IN (" . implode(",", $filter_clothing) . ")";
            }

            if (!empty($filter_brand) && !empty($filter_clothing)) {
                $query .= $filter_brand . " AND " . $filter_clothing;
            } elseif (!empty($filter_brand)) {
                $query .= $filter_brand;
            } elseif (!empty($filter_clothing)) {
                $query .= $filter_clothing;
            } else {
                $query = "SELECT * FROM wishlist";
            }

            $records = exec_sql_query($db, $query, $params)->fetchAll();
        } else {
            $sql_select_query = "SELECT * FROM wishlist";
            $sql_params = array();
            $records = exec_sql_query($db, $sql_select_query, $sql_params)->fetchAll();
        }
        ?>


        <section class="view">
            <ul>
                <?php foreach ($records as $record) {
                    $file_url = 'public/uploads/clothes/' . $record['id'] . '.' . $record['file_ext']; ?>
                    <div class="thumbnail">
                        <a href="details?id=<?php echo $record['id']; ?>">
                            <img src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['file_name']); ?>">
                        </a>

                        <h3><?php echo htmlspecialchars($record['file_name']); ?></h3>
                        <cite><a href="https//www.ssense.com">ssense.com</a></cite>

                    </div>
                <?php } ?>
            </ul>

        </section>
    <?php } else {
        // user is not logged in. show login form
    ?>

        <h2>Sign In</h2>

        <p>Please login to upload or view clothing in the Wishlist</p>

    <?php echo login_form('/', $session_messages);
    } ?>
</body>

</html>
