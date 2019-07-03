<?php
  $save_loc = "./asset/img/";
  $dest_file = $save_loc.basename($_FILES['passport']['name']);
  $temp_file = $_FILES['passport']['tmp_name'];
  try {
    move_uploaded_file($temp_file, $dest_file);
    print_r("Succefully Uploaded");
  }
  catch(Execption $e) {
    print_r($e);
  }
?>
