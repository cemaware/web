<?php

include($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');

(isset($_GET['id'])) ? $id = $_GET['id'] : die;

$notification = (object) $cema->query("SELECT * from notifications WHERE id = ?", [$id])->fetch();
if(!$notification) {
   die;
}
if($notification->to != $_SESSION['UserID']) {
   die;
}
$cema->update_("notifications", ["read" => 1], ["id" => $id]);
header("location: $notification->redirect");
die;
?>