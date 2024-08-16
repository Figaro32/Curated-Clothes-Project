<?php
$title = 'Details';
$nav_details_class = 'active_page';

// retrive entry ID from query string parameter
$recordID = $_GET['id'];
$recordEXT = 'png';

// The SQL query parts
$sql_select_clause = "SELECT file_name AS 'file_name', brand_id AS 'brand_id', clothing_type_id AS 'clothing_type_id', brand.name AS 'brand.name', clothing_type.name AS 'clothing_type.name'
FROM  wishlist INNER JOIN brand on (wishlist.brand_id = brand.id) INNER JOIN clothing_type on (wishlist.clothing_type_id = clothing_type.id) WHERE (wishlist.id = :id)  ";
$sql_order_clause = ''; // No order by default


// glue select clause to order clause
$sql_select_query = $sql_select_clause;

// query DB
$records = exec_sql_query($db, $sql_select_query, array(
    ':id' => $recordID
))->fetchAll();



// retrieve entry information from the database

// retrieve tags for the entry
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

        <ul>
            <?php
            $file_url = 'public/uploads/clothes/' . $recordID . '.' . $recordEXT; ?>
            <img src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['file_name']); ?>">

            <h3><?php echo htmlspecialchars($records[0]['file_name']); ?></h3>
            <p><?php echo htmlspecialchars($records[0]['brand.name']); ?></p>
            <p><?php echo htmlspecialchars($records[0]['clothing_type.name']); ?></p>
            <cite><a href="https//www.ssense.com">ssense.com</a></cite>

        </ul>

    </section>

</body>

</html>
