<?php
  include ("settings.php");

  //Page variables
  $page_title = "Technical Theatre | James Box";
  $page_file = basename($_SERVER['PHP_SELF'],".php")
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php print $page_title; ?></title>

    <?php include "css.php"; ?>
  </head>
  <body>
    <?php include "navbar.php"; ?>
  </body>
</html>
