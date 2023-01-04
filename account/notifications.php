<?php
$name = "undefined";
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();

$update = $cema->update_(
   "notifications",
   ["read" => 1],
   ["to" => $_SESSION['UserID']]
);
$allNotifications = $cema->query("SELECT COUNT(*) FROM notifications WHERE `to`=?", [$_SESSION['UserID']])->fetchColumn();

$page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : "1";

$limit = 6;
$offset = ($page - 1) * $limit;

$maxPage = $allNotifications / 6.0;
$maxPage = ceil($maxPage);

$notifications = $cema->query("SELECT * FROM notifications WHERE `to`=? ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}", [$_SESSION['UserID']], true);
?>

<script>
   document.title = "Notifications <?= ($allNotifications) ? "($allNotifications)" : "" ?> // Cemaware";
</script>
<br>
<div class="row">
   <div class="col-8 mx-auto">
      <h4 class="mb-1">
         Notifications
      </h4>
      <div class="card m-0">
         <div class="card-body">
            <?php
            foreach ($notifications as $notification) {
               $notification = (object) $notification;
               $user = $cema->get_user($notification->from);
            ?>
               <a class="text-white link-unstyled bg-transparent border-0 text-center" href="<?= $notification->redirect ?>">
                  <div class="px-3 py-1">
                     <div class="row px-2 d-flex align-items-center gap-2">
                        <div class="col p-0">
                           <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" alt="Avatar" class="img-fluid rounded bg-secondary">
                        </div>
                        <div class="col-10 p-0">
                           <span class="fw-semibold">
                              <?= $notification->msg ?>
                           </span>
                           <br>
                           <span class="text-secondary small fw-semibold">
                              <i class="fad fa-clock"></i>
                              <?= $cema->timeAgo(strtotime($notification->time)) ?>
                           </span>
                        </div>
                     </div>
                  </div>
               </a>
            <?php
            }
            ?>
         </div>
      </div>
      <br>
      <div class="text-center">
         <div class="btn-group">
            <a href="/account/notifications?page=<?= $page - 1 ?>" class="btn btn-theme">
               <i class="fa fa-arrow-left"></i>
               Previous Page
            </a>
            <a href="/account/notifications?page=1" class="btn btn-theme">
               Page 1
            </a>
            <a href="/account/notifications?page=<?=$maxPage?>" class="btn btn-theme">
               Page
               <?= $maxPage ?>
            </a>
            <a href="/account/notifications?page=<?= $page + 1?>" class="btn btn-theme">
               Next Page
               <i class="fa fa-arrow-right"></i>
            </a>
         </div>
      </div>
   </div>
</div>

<?php

$cema->footer();

?>