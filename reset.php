<?php
  require 'pdo.php';
  $qry = $pdo->prepare("DELETE FROM test_questions where test_id = :tid");
  $qry->execute(array(
    ':tid' => htmlentities($_GET['test'])
  ));
  $_SESSION['success'] = "Reset Successful"
?>
<script type="text/javascript">
  window.close()
</script>
