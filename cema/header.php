<?php

include 'cema.php';
if ($cema->auth()) {
   $user = $cema->local_info($_SESSION['UserID']);
   $cema->update_("users", ["updated" => date('Y-m-d H:i:s')], ["id" => $_SESSION['UserID']]);
}
session_regenerate_id();
// test message
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= (isset($name)) ? $name . " // Cemaware" : "Cemaware" ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <link rel="stylesheet" href="/cdn/css/style.css?<?= time(); ?>">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap&family=Inter:wght@100;200;300;400;500;600" rel="stylesheet">
   <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="/cdn/js/api.js"></script>
   <script src="/cdn/js/cema.js"></script>
   <link href="https://fonts.googleapis.com/css?family=Varela+Round:300,400,500,700" rel="stylesheet">
</head>

<body style="overflow-x: hidden">
   <div class="page-container">
      <div class="content-wrap">
         <nav class="fixed-top" id="nav">
            <!--<div class="navbar navbar-expand-lg bg<?php if ($cema->auth()) echo "-secondary" ?>-dark navbar-dark shadow">-->
            <div class="navbar navbar-expand-lg bg-theme navbar-dark shadow">
               <!-- Navbar content -->
               <div class="container-fluid container">
                  <a class="navbar-brand" href="#">
                     <img src="/cdn/img/banner.png" alt="logo" height="23.5">
                  </a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarText">
                     <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        if (!$cema->auth()) {
                        ?>

                           <li class="nav-item">
                              <a class="nav-link" aria-current="page" href="/">
                                 <i class="fas fa-plane"></i>
                                 Landing
                              </a>
                           </li>
                        <?php
                        } else {
                        ?>
                           <li class="nav-item">
                              <a class="nav-link" aria-current="page" href="/dashboard/">
                                 <i class="far fa-home"></i>
                                 Dashboard
                              </a>
                           </li>
                        <?php
                        }
                        ?>
                        <li class="nav-item">
                           <a class="nav-link" href="/market">
                              <i class="far fa-tshirt"></i>
                              Market
                           </a>
                        </li>
                        <ul class="navbar-nav">
                           <li class="nav-item dropdown hover-dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                                 <i class="far fa-cloud"></i>
                                 Community
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                 <li>
                                    <a class="dropdown-item" href="/forum">
                                       <i class="far fa-comments"></i>
                                       Forum
                                    </a>
                                 </li>
                                 <li>
                                    <a href="/users" class="dropdown-item">
                                       <i class="far fa-users"></i>
                                       Users
                                    </a>
                                 </li>
                                 <li>
                                    <a class="dropdown-item" href="/club">
                                       <i class="far fa-building"></i>
                                       Clubs
                                    </a>
                                 </li>
                                 <li>
                                    <a href="/discord" class="dropdown-item">
                                       <i class="fab fa-discord"></i>
                                       Discord
                                    </a>
                                 </li>
                              </ul>
                           </li>
                        </ul>
                        <ul class="navbar-nav">
                           <li class="nav-item dropdown hover-dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                                 <i class="far fa-ellipsis-h"></i>
                                 More
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                 <li>
                                    <a href="/account" class="dropdown-item">
                                       <i class="far fa-user"></i>
                                       Account
                                    </a>
                                 </li>
                              </ul>
                           </li>
                        </ul>
                     </ul>
                     <?php
                     if ($cema->auth()) {
                        $notifications = $cema->query("SELECT COUNT(*) FROM notifications WHERE `to`=? AND `read`=0", [$_SESSION['UserID']])->fetchColumn();
                        $allNotifications = $cema->query("SELECT COUNT(*) FROM notifications WHERE `to`=?", [$_SESSION['UserID']])->fetchColumn();
                        $msgCount = $cema->query("SELECT COUNT(*) FROM messages WHERE messages.to = ? AND messages.read = 0;", [$_SESSION["UserID"]])->fetchColumn();
                     ?>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="margin-right: 15px;">
                           <li class="nav-item">
                              <a href="/account/messages" class="nav-link position-relative">
                                 <i class="far fa-envelope"></i>
                                 <span class="position-absolute start-100 translate-middle badge bg-primary"><?= ($msgCount) ? $msgCount : "" ?></span>
                              </a>
                           </li>
                           <li class="nav-item me-2 d-flex justify-content-between">
                              <a class="nav-link position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal" href="#">
                                 <i class="far fa-bell"></i>
                                 <span class="position-absolute start-100 translate-middle badge bg-primary"><?= ($notifications) ? $notifications : "" ?></span>
                              </a>
                           </li>
                           <a href="/profile/<?= $user->id ?>">
                              <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" alt="Avatar" width="35px" height="35px" class="rounded bg-theme-secondary">
                           </a>
                           <li class="nav-item dropdown hover-dropdown">
                              <a class="nav-link d-flex gap-2 align-items-center" href="#" role="button">
                                 <div>
                                    <i class="far fa-user"></i>
                                    <?= $cema->localUser()['name']; ?>
                                 </div>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                 <li>
                                    <a href="/account/customize" class="dropdown-item">
                                       <i class="fad fa-pencil"></i>
                                       Customize
                                    </a>
                                 </li>
                                 <li>
                                    <a class="dropdown-item" href="/account/settings">
                                       <i class="far fa-cog"></i>
                                       Settings
                                    </a>
                                 </li>
                                 <div class="dropdown-divider"></div>
                                 <li>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal" href="#">
                                       <i class="far fa-sign-out"></i>
                                       Logout
                                    </a>
                                 </li>
                              </ul>
                           </li>
                           </a>
                           </li>
                        </ul>
                        <ul class="navbar-nav">
                        </ul>


                     <?php
                     } else {
                     ?>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                           <li class="nav-item">
                              <a class="nav-link" href="/login">
                                 <i class="fa fa-sign-in"></i>
                                 Login
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="/register/">
                                 <i class="fa fa-user-plus"></i>
                                 Register
                              </a>
                           </li>
                        <?php
                     }
                        ?>
                        </ul>
                  </div>
               </div>
            </div>
            <?php
            if ($cema->auth()) {
            ?>
               <?php
               $requests = $cema->query("SELECT COUNT(id) as total FROM friends WHERE friends.to = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchColumn();
               $friendsText = ($requests > 0) ? "Friends (" . $requests . ")" : "Friends";
               ?>
               <div class="navbar secondary-nav navbar-expand-lg bg-dark navbar-dark shadow">
                  <!-- Navbar content -->
                  <div class="container-fluid container">
                     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                           <li class="nav-item">
                              <a class="nav-link" href="/profile/<?= $_SESSION['UserID']; ?>">
                                 <i class="far fa-user-circle"></i>
                                 Profile
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="/friends/requests">
                                 <i class="far fa-user-friends"></i>
                                 <?= $friendsText ?>
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="/account/invites">
                                 <i class="far fa-plus"></i>
                                 Invites
                              </a>
                           </li>
                           <li class="nav-item">
                              <a href="/account/customize/" class="nav-link">
                                 <i class="far fa-user-edit"></i>
                                 Customize
                              </a>
                           </li>
                           <li class="nav-item">
                              <a href="/leaderboards" class="nav-link">
                                 <i class="fa far fa-list"></i>
                                 Leaderboards
                              </a>
                           </li>
                           <?php
                           if ($cema->isAdmin()) {
                           ?>
                              <li class="nav-item">
                                 <a class="nav-link" href="/admin">
                                    <i class="fad fa-gavel"></i>
                                    Admin
                                 </a>
                              </li>
                           <?php
                           }
                           ?>
                        </ul>
                        <ul class="navbar-nav">
                           <li class="nav-item">
                              <a href="/account/levels" class="nav-link">
                                 <i class="far fa-rocket"></i>
                                 Leveling
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="/account/currency" title="<?= $user->cubes ?>">
                                 <i class="far fa-cubes"></i>
                                 <?= $cema->number($user->cubes) ?>
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="/account/portal">
                                 <i class="far fa-hammer"></i>
                                 Developer
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            <?php
            }
            ?>
         </nav>

         <script>
            $(function() {
               // this will get the full URL at the address bar
               const url = window.location.href;

               // passes on every "a" tag
               $("#nav a").each(function() {
                  // checks if it's the same on the address bar
                  if (url === (this.href) + '/' || url === (this.href)) {
                     $(this).closest("a").addClass("active");
                     //for making parent of submenu active
                     $(this).closest("a").parent().parent().addClass("active");
                  }
               });
            });
         </script>
         <?php
         if (isset($name) && $name != "Landing") {
            echo "<div class='navbar-push'></div>";
         }
         ?>
         <?php
         if ($cema->auth()) {
         ?>
            <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h4 class="modal-title fw-bold">Notifications</h4>
                     </div>
                     <div class="modal-body">
                        <?php
                        $notifications = $cema->query("SELECT * FROM notifications WHERE `to`=? AND `read`=0 ORDER BY id DESC LIMIT 6", [$_SESSION['UserID']], true);

                        foreach ($notifications as $notif) {
                           $notif = (object) $notif;
                           $notifCreator = $cema->get_user($notif->from);
                        ?>
                           <form action="/api/account/read_notification.php?id=<?= $notif->id ?>" method="POST">
                              <button class="text-white link-unstyled bg-transparent border-0">
                                 <div class="px-3 py-1">
                                    <div class="row px-2 d-flex align-items-center gap-2">
                                       <div class="col p-0">
                                          <img src="/cdn/img/avatar/thumbnail/<?= $notifCreator->avatar_link ?>.png" alt="Avatar" class="img-fluid rounded bg-secondary">
                                       </div>
                                       <div class="col-10 p-0">
                                          <?= $notif->msg ?>
                                          <br>
                                          <span class="text-secondary" style="font-size: 13px;">
                                             <?= $cema->timeAgo(strtotime($notif->time)) ?>
                                          </span>
                                       </div>
                                    </div>
                                 </div>
                              </button>
                           </form>
                        <?php
                        }

                        if (!$notifications) {
                        ?>
                           <div class="px-3 py-1">
                              No Notifications.
                           </div>
                        <?php
                        }
                        ?>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <a href="/account/notifications" type="button" class="btn btn-theme btn-sm">View All Notifications</a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h4 class="modal-title fw-bold">Logout</h4>
                     </div>
                     <div class="modal-body">
                        Are you sure you want to log out?
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">No</button>
                        <a href="/logout" type="button" class="btn btn-theme btn-sm">Yes</a>
                     </div>
                  </div>
               </div>
            </div>
         <?php
         }
         ?>
         <div class="container-fluid">
            <div class="row flex-nowrap">
               <div class="col py-3 p-0">
                  <div class="container">
                     <?php
                     $alert = $cema->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
                     if ($alert['alert'] == 1 && isset($name) && $name != "Landing" && $name != "Login" && $name != "Register") {
                     ?>
                        <div class="alert bg-theme">
                           <div class="row">
                              <div class="col-1">
                                 <i class="fas fa-exclamation-circle"></i>
                              </div>
                              <div class="col-10">
                                 <?= $alert['alert_text'] ?>
                              </div>
                              <div class="col-1">
                                 <i class="fas fa-exclamation-circle"></i>
                              </div>
                           </div>
                        </div>
                     <?php
                     }
                     ?>