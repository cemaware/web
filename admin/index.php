<?php
$name = 'Admin';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAdmin();
?>

<h4>
   Admin Panel
</h4>

<?php

$cema->footer();

?>