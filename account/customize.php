<?php
$name = 'Customize';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
$user = $cema->localUser();
$cema->alert();
$enabled = $cema->query("SELECT rendering_enabled FROM site_settings WHERE id = 1")->fetchColumn();
?>

<?php
if ($enabled) {
?>
   <div class="row">
      <style>
         .color {
            border-radius: 5px;
            padding: 15px;
         }
      </style>
      <div class="col-1"></div>
      <div class="col-3">
         <h4 class="text-center fw-bold">
            Avatar
         </h4>
         <div class="card card-body">
            <img src="/cdn/img/avatar/<?= $cema->local_info()->avatar_link ?>.png" alt="Avatar" id="avatar" class="img-fluid">
         </div>
         <h4 class="text-center fw-bold">
            Colors
         </h4>
         <div class="card card-body">
            <div class="d-flex justify-content-around">
               <?php
               $colors = [
                  "FEE3D4", "F2CCB7", "E0AB8B", "B06C49", "743D2B", "4A332D"
               ];
               ?>
               <?php
               foreach ($colors as $color) {
               ?>
                  <div>
                     <button class="color rounded" color="<?= $color ?>" style="background-color:#<?= $color ?>;"></button>
                  </div>
               <?php
               }
               ?>
            </div>
         </div>
      </div>
      <div class="col-7">
         <h4 class="fw-bold text-center">
            Inventory
         </h4>
         <ul class="nav nav-pills nav-fill px-4 bg-dark mx-2 rounded">
            <li class="nav-item">
               <a class="nav-link" href="#" onclick="loadInventory('all')">All</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="#" onclick="loadInventory('hat')">Hats</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="#" onclick="loadInventory('tool')">Tools</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="#" onclick="loadInventory('shirt')">Shirts</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="#" onclick="loadInventory('pants')">Pants</a>
            </li>
         </ul>
         <div class="card">
            <div id="inventory" class="row card-body">

            </div>
         </div>
      </div>
   </div>
   <script>
      $(document).ready(function() {
         loadInventory("all");
      });

      function loadInventory(category) {
         $("#inventory").load("/api/customize/fetch_inventory.php?req=" + category);
      }
   </script>
<?php
} else {
?>
   <div class="row">
      <div class="col"></div>
      <div class="col-5">
         <div class="card">
            <div class="card-body text-center">
               <h4 class="fs-3">
                  <i class="fad fa-hammer text-danger"></i>
                  Customizing Disabled
                  <i class="fad fa-hard-hat text-warning"></i>
               </h4>
               <p class="small m-0 text-secondary">
                  Come back later, it'll be back soon.
               </p>
               <a href="/dashboard" class="small text-uppercase link-unstyled text-theme fs-5">
                  Go Home
               </a>
            </div>
         </div>
      </div>
      <div class="col"></div>
   </div>
<?php
}
?>

<?php

$cema->footer();

?>