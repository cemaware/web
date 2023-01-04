<?php
// see errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
$name = 'Item';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

function marketRedirect()
{
   header('location: /market');
   die;
}
// Redirect if no item ID
if (empty($_GET['item_id'])) {
   marketRedirect();
}

$item = (object) $cema->query("SELECT * FROM item WHERE id = ?", [$_GET['item_id']])->fetch();
$item_creator = $cema->get_user($item->creator);

$likes = $cema->query("SELECT COUNT(1) FROM item_likes WHERE item = ?", [$_GET['item_id']])->fetchColumn();
$wishlist = $cema->query("SELECT COUNT(1) FROM item_wishlist WHERE item = ?", [$_GET['item_id']])->fetchColumn();
$count = $cema->query("SELECT COUNT(1) FROM inventory WHERE item = ?", [$_GET['item_id']])->fetchColumn();
$userOwns = false;
if ($cema->auth()) {
   $userOwns = $cema->query("SELECT * FROM inventory WHERE item = ? AND user = ?", [$_GET['item_id'], $cema->local_info()->id])->fetch();
   if (!empty($userOwns)) {
      $userOwns = true;
   } else {
      $userOwns = false;
   }
}


if (!$item->id) {
   marketRedirect();
}

?>

<script>
   document.title = "<?= $item->name ?> // Cemaware";
</script>

<div class="row">
   <div class="col"></div>
   <div class="col-8">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-md-4 col-12">
                  <div id="item" class="img-fluid bg-dark p-2 rounded text-center <?= ($userOwns) ? "border border-success border-5" : "" ?>">
                     <img src=" /cdn/img/shop/<?= md5($item->id); ?>.png?<?= time() ?>" alt="Item" class="img-fluid">
                  </div>
               </div>
               <div class="col">
                  <?php
                  if ($cema->auth() && $cema->isAdmin()) {
                  ?>
                     <div class="float-end">
                        <div class="dropdown">
                           <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="far fa-ellipsis-v text-muted"></i>
                           </a>
                           <ul class="dropdown-menu">
                              <li>
                                 <button class="dropdown-item" id="rerender_btn">
                                    <i class="fas fa-redo"></i>
                                    Rerender
                                 </button>
                              </li>
                           </ul>
                        </div>
                     </div>
                  <?php
                  }
                  ?>
                  <h4>
                     <?= $item->name ?>
                  </h4>
                  <div class="d-flex align-items-center gap-2 text-muted">
                     <span class="text-uppercase fw-bold small">by</span>
                     <a href="/profile/<?= $item_creator->id ?>" class="d-inline-flex align-items-center gap-2">
                        <img src="/cdn/img/avatar/thumbnail/<?= $item_creator->avatar_link ?>.png" alt="Avatar" class="img-fluid thumbnail" width="40">
                        <span>
                           <?= $item_creator->name ?>
                        </span>
                     </a>
                  </div>
                  <?php
                  if ($cema->auth() && !$userOwns) {
                  ?>
                     <div class="m-1"></div>
                     <button class="btn btn-theme btn-sm" type="button" name="submit" data-bs-toggle="modal" data-bs-target="#buy-modal">
                        Purchase for
                        <i class="far fa-cubes"></i>
                        <?= $item->price ?>
                        Cubes
                     </button>
                     <?php
                     if (($cema->local_info()->cubes - $item->price) >= 0) {
                     ?>
                        <div class="modal fade" id="buy-modal" tabindex="-1" aria-labelledby="buy-modal" aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Buy <?= $item->name ?></h5>
                                 </div>
                                 <div class="modal-body">
                                    Your total after purchasing will be <b><i class="far fa-cubes"></i> <?= ($cema->local_info()->cubes - $item->price) ?> Cubes</b>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    <form action="/market/buy.php?id=<?= $item->id ?>" method="POST">
                                       <input type="hidden" name="id" value="<?= $item->id ?>">
                                       <button class="btn btn-theme btn-sm" type="submit" name="submit">
                                          Purchase for
                                          <i class="far fa-cubes"></i>
                                          <?= $item->price ?>
                                          Cubes
                                       </button>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php
                     } else {
                     ?>
                        <div class="modal fade" id="buy-modal" tabindex="-1" aria-labelledby="buy-modal" aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">You can't afford this item.</h5>
                                 </div>
                                 <div class="modal-body">
                                    You don't have enough cubes to purchase this item!
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php
                     }
                     ?>
                     <br>
                  <?php
                  }
                  ?>
                  <?php
                  if ($item->descr) {
                  ?>
                     <label for="description" class="small text-uppercase text-muted fw-bold">
                        Description
                     </label>
                     <p>
                        <?= $item->descr ?>
                     </p>
                  <?php
                  }
                  ?>
                  <div class="d-flex justify-content-around text-center">
                     <div>
                        <div>
                           <?= date("M d, Y", strtotime($item->created)) ?>
                        </div>
                        <label for="date" class="small text-muted text-uppercase fw-bold">
                           Created On
                        </label>
                     </div>
                     <div>
                        <div>
                           <?= $cema->timeAgo(strtotime($item->updated)) ?>
                        </div>
                        <label for="updated" class="small text-muted text-uppercase fw-bold">
                           Last Update
                        </label>
                     </div>
                     <div>
                        <div>
                           <?= $count ?>
                        </div>
                        <label for="copies" class="small text-muted text-uppercase fw-bold">
                           Copies Sold
                        </label>
                     </div>
                  </div>
               </div>
            </div>
            <div class="mb-2"></div>
            <div class="d-flex align-items-center gap-3 fs-5">
               <div class="d-flex align-items-center gap-1 fs-5">
                  <a href="#"><i class="fa fa-heart text-danger"></i></a>
                  <span><?= $likes ?></span>
               </div>
               <div class="d-flex align-items-center gap-1 fs-5">
                  <a href="#"><i class="fa fa-list text-secondary"></i></a>
                  <span><?= $wishlist ?></span>
               </div>
            </div>
         </div>
      </div>
      <br>
      <h4 class="fw-bold">
         Comments
      </h4>
      <div class="card">
         <div class="card-body">
            <form action="/market/comment.php">
               <input type="hidden" name="id">
               <textarea name="comment" id="comment" placeholder="Comment here..."></textarea>
               <div class="my-1"></div>
               <button class="btn btn-sm btn-theme">Submit</button>
            </form>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-2">
                  <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" alt="avatar" class="img-fluid bg-secondary rounded">
                  <a href="/profile/2" class="text-center d-block my-1 link-unstyled">
                     eifo1
                  </a>
               </div>
               <div class="col-10">
                  <span class="text-secondary small fw-bold">
                     <i class="fa fa-clock"></i>
                     30 seconds ago
                  </span>
                  <br>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum nesciunt sint nostrum nulla, cum excepturi saepe? Iure tempora, odio maiores illo assumenda inventore? Tenetur quasi illum quam accusamus rem odio?
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-2">
                  <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" alt="avatar" class="img-fluid bg-secondary rounded">
                  <a href="/profile/2" class="text-center d-block my-1 link-unstyled">
                     eifo1
                  </a>
               </div>
               <div class="col-10">
                  <span class="text-secondary small fw-bold">
                     <i class="fa fa-clock"></i>
                     46 seconds ago
                  </span>
                  <br>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum nesciunt sint nostrum nulla, cum excepturi saepe? Iure tempora, odio maiores illo assumenda inventore? Tenetur quasi illum quam accusamus rem odio?
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col"></div>
</div>

<?php
if ($cema->auth() && $cema->isAdmin()) {
?>
   <!-- Rerender script for admins -->
   <script>
      $(function() {
         $("#rerender_btn").on("click", function() {
            console.log("test");
            loading();
            renderItem();
         });
      });

      function wait(sec) {
         const date = Date.now();
         let currentDate = null;
         do {
            currentDate = Date.now();
         } while (currentDate - date < sec * 1000);
      }

      function loading() {
         $("#item").html("<img src='/cdn/img/loading.gif' / style='width: 125px; display: block; margin-left: auto; margin-right: auto; padding: 12px;'><label style='text-align:center; color: #fff;'>Loading...</label>");
      }

      function renderItem() {
         const Http = new XMLHttpRequest();
         const url = '/renderer/shop_render.php?id=<?= $item->id ?>';
         Http.open("GET", url);
         Http.send();
         console.log("Rendering item...");

         Http.onreadystatechange = (e) => {
            console.log(Http.responseText);
            if (Http.responseText == "err1") {
               console.log("fuck you");
            }
            if (Http.responseText == "err2") {
               console.log("slow down buster");
            }
            if (Http.responseText == "success") {
               wait(0.5);
               $("#item").html("<img src='/cdn/img/shop/<?= md5($item->id) ?>.png?<?= time() ?>' style='width:100%;' class='img-fluid' />");
            }
         }
      }
   </script>
<?php
}
?>

<?php

$cema->footer();

?>