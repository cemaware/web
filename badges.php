<?php
$name = 'Badges';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

?>

<?=$cema->determineUserLevel(5600)?>
<br>
<?=$cema->determinePercentageEXP(5600);?>
<br>
<?=$cema->determineXPtoLevelUp(101)?>

<?php

$cema->footer();

?>