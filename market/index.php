<?php
// see errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
$name = 'Market';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

$cat = 1;
if (!empty($_GET['category'])) {
   $cat = $_GET['category'];
}
if ($cat == 1) {
   $sql = "SELECT * FROM item WHERE public = 1 AND type = 'hats' OR type = 'tools' OR type = 'faces' OR collectible = 'yes' ORDER BY ID DESC LIMIT 20";
   $items = $cema->query($sql, false, true);
} else {
   switch ($cat) {
      case 2:
         $cat_string = "hats";
         break;
      case 3:
         $cat_string = "faces";
         break;
      case 4:
         $cat_string = "tools";
         break;
      case 5:
         $cat_string = "shirts";
         break;
      case 6:
         $cat_string = "pants";
         break;
      default:
         $cat_string = "hats";
   }
   $sql = "SELECT * FROM item WHERE type = ? AND public = 1 ORDER BY ID DESC LIMIT 20";
   $items = $cema->query($sql, [$cat_string], true);
}
?>

<div class="row">
   <div class="col">
   </div>
   <div class="col-10 p-0">
      <div class="card">
         <div class="card-body">
            <nav class="nav nav-pills flex-column flex-sm-row" id="tabs" role="tablist">
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 1) ? "active" : "" ?>" href="/market">
                  <i class="fa fa-star text-warning"></i>
                  Featured
               </a>
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 2) ? "active" : "" ?>" href="/market/2">
                  <i class="fa fa-hard-hat text-warning"></i>
                  Hats
               </a>
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 3) ? "active" : "" ?>" href="/market/3">
                  <i class="fa fa-smile-wink"></i>
                  Faces
               </a>
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 4) ? "active" : "" ?>" href="/market/4">
                  <i class="fad fa-hammer text-warning"></i>
                  Tools
               </a>
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 5) ? "active" : "" ?>" href="/market/5">
                  <i class="fa fa-tshirt text-danger"></i>
                  Shirt
               </a>
               <a class="flex-sm-fill text-sm-center nav-link <?= ($cat == 6) ? "active" : "" ?>" href="/market/6">
                  <i class="fad fa-columns text-primary"></i>
                  Pants
               </a>
            </nav>
         </div>
      </div>
      <div class="row">
         <div class="col-8">
            <div class="card">
               <div class="card-body">
                  <form action="/market/search.php">
                     <?php
                     // todo: make this actually work
                     ?>
                     <input type="text" placeholder="Search..." class="w-100">
                  </form>
               </div>
            </div>
         </div>
         <div class="col-4">
            <div class="card">
               <div class="card-body">
                  <select class="form-select" id="sort_by">
                     <option value="1" selected>
                        Newest
                     </option>
                     <option value="4">
                        Oldest
                     </option>
                     <option value="5">
                        Best Selling
                     </option>
                     <option value="6">
                        Worst Selling
                     </option>
                     <option value="2">
                        High Price
                     </option>
                     <option value="3">
                        Low Price
                     </option>
                  </select>
                  <script>
                     var select = document.getElementById("sort_by");

                     // todo: make this actually work
                  </script>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col"></div>
</div>
<div class="row">
   <div class="col"></div>
   <div class="col-10">
      <div class="row">
         <?php
         if (!$items) {
         ?>
            <h4 class="mt-3 fw-bold">
               No Items Here
            </h4>
            <p>
               Make some at <a href="/account/portal">https://cemaware.ml/account/portal</a>
            </p>
         <?php
         }
         ?>
         <?php
         foreach ($items as $item) {
            $creator = $cema->get_user($item['creator']);
            $likes = $cema->query("SELECT COUNT(1) FROM item_likes WHERE item = ?", [$item['id']])->fetchColumn();
            $wishlist = $cema->query("SELECT COUNT(1) FROM item_wishlist WHERE item = ?", [$item['id']])->fetchColumn();
         ?>
            <div class="col-lg-3 p-0 px-1">
               <div class="card m-0 my-1">
                  <div class="p-1 bg-secondary rounded-top">
                     <a href="/market/item/<?= $item['id'] ?>">
                        <div class="<?= ($item['collectible'] == "yes") ? "border border-5 border-warning" : "" ?> rounded p-1">
                           <img src="/cdn/img/shop/<?php echo md5($item['id']) ?>.png" alt="Item" class="img-fluid rounded">
                        </div>
                     </a>
                  </div>
                  <hr>
                  <div class="px-4 py-2">
                     <div class="d-flex align-items-center gap-2 mb-3">
                        <a href="/profile/<?= $creator->id ?>" class="flex-shrink">
                           <img src="/cdn/img/avatar/thumbnail/<?= $creator->avatar_link ?>.png" alt="Avatar" height="42" class="bg-secondary rounded">
                        </a>
                        <div class="min-w-0">
                           <a href="/market/item/<?= $item['id'] ?>" class="d-block text-truncate text-decoration-none fw-semibold text-light mb-1">
                              <?= $item['name'] ?>
                           </a>
                           <div class="text-muted truncate text-xs fw-bold lh-1 small">
                              By:
                              <a href="/profile/<?= $creator->id ?>">
                                 <?= $creator->name ?>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="text-center text-sm">
                        <span>
                           <i class="far fa-cubes"></i>
                           <?= $item['price'] ?>
                        </span>
                        |
                        <i class="fa fa-heart text-danger"></i>
                        <?= $likes ?>
                        |
                        <i class="fa fa-list text-secondary"></i>
                        <?= $wishlist ?>
                     </div>
                  </div>
               </div>
            </div>
         <?php
         }
         ?>
      </div>
   </div>
   <div class="col"></div>
</div>
<?php

$cema->footer();

?>