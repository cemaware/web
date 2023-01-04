  <?php
   $name = 'Friends';
   include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
   $cema->requireAuth();
   $userID = (isset($_GET['user'])) ? intval($_GET['user']) : die("no user");
   $user = $userID;

   $friends = $cema->query("SELECT * FROM friends WHERE friends.status = 1  AND (friends.to = ? OR friends.from = ?)", [$user, $user])->fetchAll();
   $user = $cema->get_user($user);

   ?>
  <script>
     document.title = "<?= $user->name ?>'s Friends - Cemaware"
  </script>
  <div class="row">
     <div class="col-8 mx-auto">
        <h4 class="fw-bold d-flex align-items-center justify-content-between">
           <a href="/profile/<?= $user->id ?>" class="btn btn-theme btn-sm mb-2">
              <i class="fa fa-arrow-left"></i>
              Back To Profile
           </a>
           <?= $user->name ?>'s Friends
        </h4>
        <div class="card mx-0">
           <div class="p-4">
              <div class="row">
                 <?php
                  foreach ($friends as $friend) {
                     $user = $cema->get_user($friend['from']);
                     if ($user->id == $userID) {
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