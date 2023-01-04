<?php
$name = 'Account';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
?>
<div class="text-center fs-2">
   <i class="far fa-user"></i>
   <?= $cema->local_info()->name ?>
</div>
<div class="row">
   <div class="col-3 p-0">
      <a href="/account/customize">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-pencil"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Customize
                     </h4>
                     <p class="m-0 small text-secondary">
                        Edit your account's avatar.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/invites">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-plus"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Invites
                     </h4>
                     <p class="m-0 small text-secondary">
                        Invite your friends!
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/portal">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-hammer"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Portal
                     </h4>
                     <p class="m-0 small text-secondary">
                        Make & sell items.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/notifications">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-bell"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Notifications
                     </h4>
                     <p class="m-0 small text-secondary">
                        Stay connected!
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/messages">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-envelope"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Messages
                     </h4>
                     <p class="m-0 small text-secondary">
                        Whisper to your friends!
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/inventory">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-archive"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Inventory
                     </h4>
                     <p class="m-0 small text-secondary">
                        All of your stuff is here.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/friends">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-user-plus"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Friends
                     </h4>
                     <p class="m-0 small text-secondary">
                        Facebook reference.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/profile/<?=$_SESSION['UserID']?>">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-user"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Profile
                     </h4>
                     <p class="m-0 small text-secondary">
                        Your public corner.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/settings">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-cog"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Settings
                     </h4>
                     <p class="m-0 small text-secondary">
                        Control your account here.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/currency">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-cubes"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Currency
                     </h4>
                     <p class="m-0 small text-secondary">
                        Control your money!
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
   <div class="col-3 p-0">
      <a href="/account/levels">
         <div class="card">
            <div class="card-body link-unstyled text-white">
               <div class="row">
                  <div class="col-2 align-items-center d-flex text-center fs-4">
                     <i class="far fa-rocket"></i>
                  </div>
                  <div class="col-10">
                     <h4>
                        Levels
                     </h4>
                     <p class="m-0 small text-secondary">
                        Rewards + info
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </a>
   </div>
</div>
<?php

$cema->footer();

?>