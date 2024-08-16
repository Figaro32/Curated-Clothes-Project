<?php
$title = 'Insert New Entry';
$nav_wishlist_class = 'active_page';


$title = 'Clipart Plop Box';
$nav_plopbox_class = 'active_page';

// Access Control - Only logged in users may upload
if (is_user_logged_in()) {

  // Set maximum file size for uploaded files.
  // MAX_FILE_SIZE must be set to bytes
  // 1 MB = 1000000 bytes
  define("MAX_FILE_SIZE", 1000000);

  $upload_feedback = array(
    'general_error' => False,
    'too_large' => False
  );

  // upload fields
  $upload_source = NULL;
  $upload_file_name = NULL;
  $upload_file_ext = NULL;

  // Users must be logged in to upload files!
  if (isset($_POST["upload"])) {

    $upload_source = trim($_POST['source']); // untrusted
    if (empty($upload_source)) {
      $upload_source = NULL;
    }

    // get the info about the uploaded files.
    $upload = $_FILES['svg-file'];

    // Assume the form is valid...
    $form_valid = True;

    // file is required
    if ($upload['error'] == UPLOAD_ERR_OK) {
      // The upload was successful!

      // Get the name of the uploaded file without any path
      $upload_file_name = basename($upload['name']);

      // Get the file extension of the uploaded file and convert to lowercase for consistency in DB
      $upload_file_ext = strtolower(pathinfo($upload_file_name, PATHINFO_EXTENSION));

      if (!in_array($upload_file_ext, array('png'))) {
        $form_valid = False;
        $upload_feedback['general_error'] = True;
      }
    } else if (($upload['error'] == UPLOAD_ERR_INI_SIZE) || ($upload['error'] == UPLOAD_ERR_FORM_SIZE)) {
      // file was too big, let's try again
      $form_valid = False;
      $upload_feedback['too_large'] = True;
    } else {
      // upload was not successful
      $form_valid = False;
      $upload_feedback['general_error'] = True;
    }

    if ($form_valid) {
      // insert upload into DB
      $result = exec_sql_query(
        $db,
        "INSERT INTO wishlist (file_name, file_ext, ) VALUES (:file_name, :file_ext)",
        array(
          ':file_name' => $upload_file_name,
          ':file_ext' => $upload_file_ext
        )
      );

      if ($result) {
        // We successfully inserted the record into the database, now we need to
        // move the uploaded file to it's final resting place: public/uploads directory

        // get the newly inserted record's id
        $record_id = $db->lastInsertId('id');

        // uploaded file should be in folder with same name as table with the primary key as the filename.
        // Note: THIS IS NOT A URL; this is a FILE PATH on the server!
        //       Do NOT include / at the beginning of the path; path should be a relative path.
        //          NO: /public/...
        //         YES: public/...
        $upload_storage_path = 'public/uploads/clipart/' . $record_id . '.' . $upload_file_ext;

        // Move the file to the public/uploads/clothes folder
        // Note: THIS FUNCTION REQUIRES A PATH. NOT A URL!
        if (move_uploaded_file($upload["tmp_name"], $upload_storage_path) == False) {
          error_log("Failed to permanently store the uploaded file on the file server. Please check that the server folder exists.");
        }
      }
    }
  }
}


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Item</title>
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
</head>

<body>
  <?php include 'includes/header.php'; ?>

  <h1>Add New Item</h1>
  <form action="/insert" method="post" enctype="multipart/form-data">

    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">

    <?php if ($upload_feedback['too_large']) { ?>
      <p class="feedback">We're sorry. The file failed to upload because it was too big. Please select a file that&apos;s no larger than 1MB. </p>
    <?php } ?>

    <?php if ($upload_feedback['general_error']) { ?>
      <p class="feedback">We're sorry. Something went wrong. Please select an PNG file to upload.</p>
    <?php } ?>



    <div class="label-input">
      <label for="upload-file">PNG File:</label>

      <input id="upload-file" type="file" name="png-file" accept=".png,image/png+xml">
    </div>

    <div class="label-input">
      <label for="tags">Select an existing tag:</label>
      <select id="tags" name="tags">
        <option value="">-- Select a tag --</option>
        <?php foreach ($tags as $tag) { ?>
          <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="align-right">
      <button type="submit" name="upload">Upload</button>
    </div>


  </form>



</body>

</html>
