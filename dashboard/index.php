<?php
$name = 'Dashboard';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
$user = $cema->localUser();
$cema->alert();
?>
<div class="row">
   <div class="col-lg-4 col-md">
      <h3>
         Dashboard
      </h3>
      <div class="card">
         <div class="card-body">
            <?= $user['status'] ?>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-3">
                  <img src="/cdn/img/avatar/thumbnail/<?= $user['avatar_link']; ?>.png" class="img-fluid bg-secondary rounded">
               </div>
               <div class="col-9">
                  <h6 class="text-secondary fw-bold small">
                     WELCOME BACK,
                  </h6>
                  <h4>
                     <?= $user['name'] ?>
                  </h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-8 col-md">
      <h3>Status</h3>
      <div class="card">
         <div class="card-body">
            <form action="/dashboard/status.php" method="POST">
               <div class="input-group">
                  <input name="status" class="form-control" placeholder="How are you today...">
                  <?php $cema->set_csrf() ?>
                  <button class="btn btn-theme btn-sm" id="button-addon2" name="submit" type="submit">Update Status</button>
               </div>
            </form>
         </div>
      </div>
      <h4>
         Feed
      </h4>
      <?php
      $sql = $cema->query("SELECT feed.* FROM friends INNER JOIN feed ON friends.to = feed.creator OR friends.from = feed.creator WHERE (friends.to = ? OR friends.from = ?) AND friends.status = 1 AND feed.creator != ? ORDER BY `id` DESC LIMIT 0, 5", [$_SESSION['UserID'], $_SESSION['UserID'], $_SESSION['UserID']]);
      if ($sql->rowCount() == 0) {
      ?>
         <div class="card">
            <div class="card-body">
               No results
            </div>
         </div>
         <?php
      } else {
         foreach ($sql->fetchAll() as $post) {
            $user = $cema->get_user($post['creator']);
         ?>
            <div class="card">
               <div class="card-body">
                  <div class="row align-items-center mb-2">
                     <div class="col">
                        <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" class="img-fluid rounded bg-secondary" style="border-radius:100px;" width="75px">
                     </div>
                     <div class="col-9">
                        <a href="/profile/<?= $user->id ?>" class="link-unstyled">
                           <h4 class="fs-5 fw-bold"><?= $user->name ?></h4>
                        </a>
                        <div class="text-secondary fw-bold" style="font-size: 12px;">
                           <i class="fad fa-clock"></i>
                           Posted:
                           <?= $cema->timeAgo(strtotime($post['created'])) ?>
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
                                    <a href="/report/feed/<?= $post['id'] ?>" class="dropdown-item">
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
                     <?= $post['status'] ?>
                  </p>
               </div>
            </div>
      <?php
         }
      }
      ?>
   </div>
</div>
<?php

$cema->footer();

?>