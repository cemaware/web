<?php

$name = 'User Profile';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$id = intval($_GET['id']);
$user = $cema->get_user($id);
$friendCount = $cema->query("SELECT COUNT(*) FROM friends WHERE (`to`=? OR `from`=?) AND status=1", [$user->id, $user->id])->fetchColumn();
$friends = $cema->query("SELECT * FROM friends WHERE (`to`=? OR `from`=?) AND status=1 ORDER BY RAND() LIMIT 6", [$user->id, $user->id], true);
if (empty($user)) {
   header('location: /dashboard');
   die;
}
$level = $cema->determineUserLevel($user->exp);
?>

<script>
   document.title = "<?= $user->name ?> // Cemaware";
</script>

<div class="row">
   <div class="col col-md">
      <?php
      $cema->alert();
      ?>
      <div class="d-flex justify-content-between">
         <?php
         if ($cema->isAdmin($user->id)) {
         ?>
            <h4 class="flex-start fw-bold text-danger">
               <i class="fad fa-gavel"></i>
               <?= $user->name ?>
            </h4>
         <?php
         } else {
         ?>
            <h4 class="flex-start fw-bold">
               <?= $user->name ?>
            </h4>
         <?php
         }
         ?>
         <div>
            <span class="badge badge-pill bg-theme">
               Level <?= $level ?>
            </span>
         </div>
      </div>
      <div class="card m-0" <?php if (strtotime($user->updated) > strtotime("-120 seconds")) {
                                 echo 'style="border-bottom: 5px solid var(--bs-success);"';
                              } ?>>
         <div class="card-body">
            <img src="/cdn/img/avatar/<?= $user->avatar_link ?>.png" alt="avatar" class="img-fluid p-2">
         </div>
      </div>
      <?php
      if ($cema->auth()) {
         if ($user->id != $_SESSION['UserID']) {
      ?>
            <a href="/message/send/<?= $user->id ?>" class="btn btn-small btn-primary w-100 mt-1">
               <i class="fas fa-envelope"></i>
               Message
            </a>
            <?php
            $friendCheck = $cema->query("SELECT * FROM friends WHERE (`to`=? AND `from`=?) OR (`to`=? AND `from`=?)", [$_SESSION['UserID'], $user->id, $user->id, $_SESSION['UserID']])->fetchAll();
            if (empty($friendCheck)) {
            ?>
               <form action="/friend/send/<?= $user->id ?>" method="POST" style='margin-top: 5px;'>
                  <button type="submit" name="submit" class="btn btn-success w-100">
                     <i class="fa fa-user-plus"></i>
                     Add Friend
                  </button>
               </form>
               <?php
            } elseif (!empty($friendCheck)) {
               if ($friendCheck[0]['status'] == 0) {
               ?>
                  <button class="btn btn-theme w-100" style="margin-top: 5px;">
                     <i class="fas fa-user"></i>
                     Pending Friend Request
                  </button>
               <?php
               } elseif ($friendCheck[0]['status'] == 1) {
               ?>
                  <form action="/friend/remove/<?= $user->id ?>" method="POST" style='margin-top: 5px;'>
                     <button type="submit" name="submit" class="btn btn-danger w-100">
                        <i class="fa fa-user-minus"></i>
                        Remove Friend
                     </button>
                  </form>
               <?php
               } elseif ($friendCheck[0]['status'] == -1) {
               ?>
                  <form action="/friend/send/<?= $user->id ?>" method="POST" style='margin-top: 5px;'>
                     <button type="submit" name="submit" class="btn btn-success w-100">
                        <i class="fa fa-user-plus"></i>
                        Add Friend
                     </button>
                  </form>
         <?php
               }
            }
         }
         ?>
      <?php
      }
      ?>
      <h4 class="fw-bold">
         Description
      </h4>
      <div class="card m-0">
         <div class="card-body">
            <p class="m-0 text-break">
               <?= $user->bio ?>
            </p>
         </div>
      </div>
      <h4 class="fw-bold">
         Statistics
      </h4>
      <?php
      $poster_posts = $cema->query("SELECT COUNT(*) FROM posts WHERE poster = ?", [$user->id])->fetchColumn();
      $poster_replies = $cema->query("SELECT COUNT(*) FROM replies WHERE poster = ?", [$user->id])->fetchColumn();
      $postCount = $poster_posts + $poster_replies;
      ?>
      <div class="card m-0">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <span class="fa fa-clock"></span>
                  Last Online:
               </div>
               <span class="float-end">
                  <?= $cema->timeAgo(strtotime($user->updated)) ?>
               </span>
            </div>

            <div class="d-flex justify-content-between">
               <div>
                  <span class="fas fa-comments"></span>
                  Forum Posts:
               </div>
               <span class="float-end">
                  <?= $postCount ?>
               </span>
            </div>
            <?= $user->created?>
            <?= time() ?>
            <div class="d-flex justify-content-between">
               <div>
                  <span class="fas fa-user-plus"></span>
                  Join Date
               </div>
               <span class="float-end">
                  <?= date("M d, Y", $user->created) ?>
               </span>
            </div>

         </div>
      </div>
   </div>
   <div class="" style="width: 70.833333305%">
      <nav class="nav nav-pills flex-column flex-sm-row" id="tabs" role="tablist">
         <button class="flex-sm-fill text-sm-center nav-link active" aria-current="page" id="profile-tab" role="tab" data-bs-toggle="tab" data-bs-target="#profile"><?= $user->name ?></button>
         <button class="flex-sm-fill text-sm-center nav-link" aria-current="page" id="wall-tab" role="tab" data-bs-toggle="tab" data-bs-target="#wall">Wall</button>
         <button class="flex-sm-fill text-sm-center nav-link" id="inventory-tab" role="tab" data-bs-toggle="tab" data-bs-target="#inventory">Inventory</button>
      </nav>
      <div class="tab-content mt-3" id="profileContent">
         <div class="tab-pane fade show active" id="profile">
            <?php
            if ($friends) {
            ?>
               <div class="d-none d-lg-block">
                  <div class="d-block">
                     <h4 class="d-inline-block fw-bold">
                        Friends
                     </h4>
                     <a href="/friends/all/<?= $user->id ?>" class="btn btn-theme float-end btn-sm">
                        View All Friends
                     </a>
                  </div>
                  <div class="d-block m-2"></div>
                  <div class="card m-0">
                     <div class="card-body">
                        <div class="row">
                           <?php
                           foreach ($friends as $friend) {
                              $to = $friend['to'];
                              $from = $friend['from'];
                              $friend = $cema->get_user($to);
                              if ($friend->id == $id) {
                                 $friend = $cema->get_user($from);
                              }
                           ?>
                              <div class="col-2">
                                 <a href="/profile/<?= $friend->id ?>">
                                    <img src="/cdn/img/avatar/thumbnail/<?= $friend->avatar_link ?>.png" alt="<?= $friend->name ?>'s Avatar" class="img-fluid bg-secondary rounded">
                                 </a>
                                 <a href="/profile/<?= $friend->id ?>" class="text-center users-label" style="display: block; color: #FFF; text-decoration: none; white-space:nowrap">
                                    <?= $friend->name ?>
                                 </a>
                              </div>
                           <?php
                           }
                           ?>
                        </div>
                     </div>
                  </div>
                  <div class="my-1"></div>
               </div>
            <?php
            }
            ?>
            <h4 class="fw-bold">Badges</h4>
            <div class="card m-0">
               <div class="card-body text-center">
                  <div class="fs-4">
                     <i class="fad fa-award text-warning"></i>
                     No Badges
                  </div>
                  <p class="text-secondary m-0 small">
                     This user has no badges.
                  </p>
                  <a href="/badges" class="small text-uppercase link-unstyled text-theme">
                     View All Badges
                  </a>
               </div>
            </div>
         </div>
         <div class="tab-pane fade" id="wall">
            <h4 class="fw-bold">
               Profile Wall
            </h4>
            <div class="card m-0">
               <div class="card-body">
                  <form action="/profile/wall/1" method="POST">
                     <textarea name="wallMsg" placeholder="Write a post"></textarea>
                     <?php
                     $cema->set_csrf();
                     ?>
                     <button class="btn btn-theme btn-sm" type="submit" name="submit">
                        Submit
                     </button>
                  </form>
               </div>
            </div>
            <div class="card">
               <div class="card-body">
                  <div class="row align-items-center mb-2">
                     <div class="col">
                        <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" class="img-fluid rounded bg-secondary" width="75px">
                     </div>
                     <div class="col-9">
                        <a href="/profile/1" class="link-unstyled">
                           <h4 class="fs-5 fw-bold">eifo1</h4>
                        </a>
                        <div class="text-secondary fw-bold" style="font-size: 12px;">
                           <i class="fad fa-clock"></i>
                           Posted:
                           10 hours ago
                        </div>
                     </div>
                     <div class="col">
                        <div class="float-end">
                           <div class="dropdown">
                              <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="far fa-ellipsis-v text-muted"></i>
                              </a>
                              <ul class="dropdown-menu">
                                 <li>
                                    <a href="/report/wall/1 class="dropdown-item">
                                       <div class="text-danger">
                                          <i class="fad fa-flag"></i>
                                          Report
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <p>
                     Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio debitis incidunt laborum unde impedit, quo deleniti sunt totam minima reprehenderit ex hic maiores eveniet odit fugit molestias labore! Labore, assumenda.
                  </p>
               </div>
            </div>
         </div>
         <div class="tab-pane fade" id="inventory">
            <div class="d-flex align-items-start">
               <style>
                  .not-round .nav-link {
                     border-radius: 0px;
                  }
               </style>
               <div class="nav flex-column nav-pills not-round rounded me-3 bg-dark">
                  <button class="nav-link border-bottom border-dark" type="button" onclick="loadInventory('all');">All</button>
                  <button class="nav-link border-bottom border-dark" type="button" onclick="loadInventory('hat');">Hats</button>
                  <button class="nav-link border-bottom border-dark" type="button" onclick="loadInventory('tool');">Tools</button>
                  <button class="nav-link border-bottom border-dark" type="button" onclick="loadInventory('shirt');">Shirts</button>
                  <button class="nav-link border-bottom border-dark" type="button" onclick="loadInventory('pants');">Pants</button>
                  <button class="nav-link" type="button" onclick="loadInventory('collectible');">Collectibles</button>
               </div>
               <div id="inv" class="row"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   // inventory loading code

   $(document).ready(function() {
      loadInventory("all");
   });

   function loadInventory(category) {
      $("#inv").load("/api/user/fetch_inventory.php?user=<?= $user->id ?>&req=" + category);
   }
</script>
<?php

$cema->footer();

?>