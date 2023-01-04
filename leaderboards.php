<?php
$name = 'Leaderboards';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
?>
<div class="row">
   <div class="col-8 mx-auto">
      <div class="text-end">
         <h2 class="fw-bold d-flex align-items-center">
            <i class="far fa-list pe-2"></i>
            Leaderboards
         </h2>
      </div>
      <nav class="nav nav-pills flex-column flex-sm-row" id="tabs" role="tablist">
         <button class="flex-sm-fill text-sm-center nav-link active" aria-current="page" id="posts-tab" role="tab" data-bs-toggle="tab" data-bs-target="#posts">Forum Posts</button>
         <button class="flex-sm-fill text-sm-center nav-link" aria-current="page" id="worth-tab" role="tab" data-bs-toggle="tab" data-bs-target="#worth">Net Worth</button>
         <button class="flex-sm-fill text-sm-center nav-link" id="comments-tab" role="tab" data-bs-toggle="tab" data-bs-target="#comments">Comments</button>
         <button class="flex-sm-fill text-sm-center nav-link" id="sales-tab" role="tab" data-bs-toggle="tab" data-bs-target="#sales">Item Sales</button>
      </nav>
      <div class="my-2"></div>
      <div class="tab-content">
         <div class="tab-pane fade show active" id="posts">
            <?php
            $users = $cema->query("SELECT * FROM users ORDER BY posts DESC LIMIT 10", false, true);
            $index = 0;

            foreach ($users as $user) {
               $user = (object) $user;
               $index++;
            ?>
               <div class="row bg-secondary my-1 rounded py-2">
                  <div class="col-1 text-center d-flex align-items-center justify-content-center">
                     #<?= $index ?>
                  </div>
                  <div class="col-1">
                     <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" class="img-fluid bg-dark rounded" alt="<?= $user->name ?>">
                  </div>
                  <div class="col-8 d-flex align-items-center">
                     <a href="/profile/<?= $user->id ?>">
                        <?= $user->name ?>
                     </a>
                  </div>
                  <div class="col-2 text-center d-flex align-items-center justify-content-center m-0">
                     <?= $user->posts ?> posts
                  </div>
               </div>
            <?php
            }
            ?>
         </div>
         <div class="tab-pane fade" id="sales">
            <?php
            $users = $cema->query("SELECT COUNT(*)");
            $index = 0;

            foreach ($users as $user) {
               $user = (object) $user;
               $index++;
            ?>
               <div class="row bg-secondary my-1 rounded py-2">
                  <div class="col-1 text-center d-flex align-items-center justify-content-center">
                     #<?= $index ?>
                  </div>
                  <div class="col-1">
                     <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" class="img-fluid bg-dark rounded" alt="<?= $user->name ?>">
                  </div>
                  <div class="col-8 d-flex align-items-center">
                     <a href="/profile/<?= $user->id ?>">
                        <?= $user->name ?>
                     </a>
                  </div>
                  <div class="col-2 text-center d-flex align-items-center justify-content-center m-0">
                     <?= $user->posts ?> posts
                  </div>
               </div>
            <?php
            }
            ?>
         </div>
      </div>
   </div>
</div>

<?php

$cema->footer();

?>