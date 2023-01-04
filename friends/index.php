  <?php
   $name = 'Friends';
   include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
   $cema->requireAuth();
   $user = $cema->localUser();
   $cema->alert();

   $sentCount = $cema->query("SELECT COUNT(id) as total FROM friends WHERE friends.from = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchColumn();
   $pendingCount = $cema->query("SELECT COUNT(id) as total FROM friends WHERE friends.to = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchColumn();
   $totalFriends = $cema->query("SELECT COUNT(id) as total FROM friends WHERE (friends.from = :id OR friends.to = :id) AND friends.status = 1;", [":id" => $_SESSION["UserID"]])->fetchColumn();

   $friends = $cema->query("SELECT * FROM friends WHERE friends.status = 1  AND (friends.to = ? OR friends.from = ?)", [$_SESSION["UserID"], $_SESSION['UserID']])->fetchAll();
   ?>
   
  <div class="row">
     <div class="col-8 mx-auto">
        <h4 class="fw-bold">Friends</h4>
        <div class="card mx-0">
           <div class="p-4">
              <div class="row">
              <?php
               foreach ($friends as $friend) {
                  $user = $cema->get_user($friend['from']);
                  if ($user->id == $_SESSION['UserID']) {
                     $user = $cema->get_user($friend['to']);
                  }
               ?>
                    <div class="col-3 p-2">
                       <a href="/profile/<?= $user->id ?>" class="users-link d-block text-center">
                          <img src="/cdn/img/avatar/<?= $user->avatar_link ?>.png" alt="Avatar" class="img-fluid bg-secondary rounded p-2">
                          <br>
                          <?= $user->name ?>
                       </a>
                    </div>
              <?php
               }
               ?>
              </div>
           </div>
        </div>
     </div>
  </div>
  <?php

   $cema->footer();

   ?>