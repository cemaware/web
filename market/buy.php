<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
   require($_SERVER['DOCUMENT_ROOT'] . "/cema/cema.php");

   $item_id = $_POST['id'];

   $item = (object) $cema->query("SELECT * FROM item WHERE id = ?", [$item_id])->fetch();

   $cubes = $cema->local_info()->cubes;

   if(($cubes - $item->price) < 0) {
      die("can't afford");
   }

   $userOwns = $cema->query("SELECT * FROM inventory WHERE item = ?", [$_GET['item_id']])->fetch();

   if (!empty($userOwns)) {
      $userOwns = true;
   } else {
      $userOwns = false;
   }

   if($userOwns) {
      die("already owns");
   }


   // add it to inventory

   $inventory = $cema->insert("inventory", ["item" => $item->id, "type" => $item->type, "user" => $cema->local_info()->id]);
   $subtract_cubes = $cema->update_("users", ["cubes" => ($cema->local_info()->cubes - $item->price)], ["id" => $cema->local_info()->id]);
   
   header('location: /market/item/' . $item->id);
   die();
}