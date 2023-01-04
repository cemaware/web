<?php

include($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');

if (empty($_GET['req'])) {
   die("No request.");
}
$request = $_GET['req'];
$user = $_GET['user'];

$requests = array(
   "hat" => "hats",
   "face" => "faces",
   "shirt" => "shirts",
   "pants" => "pants",
   "all" => "",
   "tool" => "tools",
   "collectible" => "collectible"
);

if (!isset($requests[$request])) {
   die("Request not valid.");
}
if ($requests[$request] != "collectible") {
   if ($requests[$request] == "") {
      $inventory = $cema->query("SELECT * FROM inventory WHERE equipped = 0 AND user = ?", [$user], true);
   } else {
      $inventory = $cema->query("SELECT * FROM inventory WHERE equipped = 0 AND user = ? AND type = ?", [$user, $requests[$request]], true);
   }
} else {
   $inventory = $cema->query("SELECT * FROM inventory WHERE serial != 0 AND user = ?", [$user], true);
}
if (sizeof($inventory) == 0) {
?>
   <h4 class="fw-bold">
      No Items
   </h4>
   <p class="text-secondary small m-0">
      This user is, by definition, poor.
   </p>
<?php
}

foreach ($inventory as $item) {
   $item = (object) $cema->query("SELECT * FROM item WHERE id = ?", [$item['item']])->fetch();
   $creator = $cema->get_user($item->creator);
?>
   <div class="col-lg-3 col-md-6 col-sm p-0 mb-1 m-0">
      <a href="/market/item/<?= $item->id ?>" class="fw-light text-white">
         <div class="card mt-0">
            <div class="card-body">
               <img src="/cdn/img/shop/<?= md5($item->id) ?>.png" alt="Item" class="img-fluid rounded bg-secondary p-2">
               <span class="text-truncate" style="width: 95%; display: block; margin-top: 5px">
                  <?= $item->name ?>
               </span>
               <span class="small d-inline-block text-truncate">
                  by <span class="fw-bold text-danger"><?= $creator->name ?></span>
               </span>
               <br>
               <i class="far fa-cubes"></i> <?= $item->price ?>
            </div>
         </div>
      </a>
   </div>
<?php
}

?>