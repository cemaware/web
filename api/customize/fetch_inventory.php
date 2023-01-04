<?php

include($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');

if(!$cema->auth()) {
   die("No authentication. (Log in)");
}

if(empty($_GET['req'])) {
   die("No request.");
}
$request = $_GET['req'];

$requests = array(
   "hat" => "hats",
   "face" => "faces",
   "shirt" => "shirts",
   "pants" => "pants",
   "all" => "",
   "tool" => "tools",
   "wearing" => "wearing"
);

if (!isset($requests[$request])) {
   die("Request not valid.");
}
if($requests[$request] != "wearing") {
   if($requests[$request] == "") {
      $inventory = $cema->query("SELECT * FROM inventory WHERE equipped = 0 AND user = ?", [$_SESSION['UserID']], true);
   } else {
      $inventory = $cema->query("SELECT * FROM inventory WHERE equipped = 0 AND user = ? AND type = ?", [$_SESSION['UserID'], $requests[$request]], true);
   }
} else {
   $inventory = $cema->query("SELECT * FROM inventory WHERE equipped = 1 AND user = ?", [$_SESSION['UserID']], true);
}
if(sizeof($inventory) == 0) {
   ?>
   <h4 class="fw-bold">
      No Items
   </h4>
   <p class="text-secondary small m-0">
      Visit the market and pick up a few things before coming back here.
   </p>
   <?php 
}

foreach($inventory as $item) {
   $item = (object) $cema->query("SELECT * FROM item WHERE id = ?", [$item['item']])->fetch();
   ?>
   <div class="col-4 mb-1">
      <button class="p-2 border-0 text-white bg-transparent">         
         <img src="/cdn/img//shop/<?=md5($item->id)?>.png" alt="Item" class="img-fluid bg-secondary p-2 rounded-top">
         <div class="p-2 bg-dark rounded-bottom">
            <?=$item->name?>
         </div>
      </button>
   </div>
   <?php 
}

?>