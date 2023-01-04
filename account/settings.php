<?php
$name = 'Settings';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
$user = (object) $cema->query("SELECT * FROM users WHERE id = ?", [$_SESSION['UserID']])->fetch();
// obfuscate email func
function obfuscate_email($email)
{
   $em   = explode("@", $email);
   $name = implode('@', array_slice($em, 0, count($em) - 1));
   $len  = floor(strlen($name) / 2);

   return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}
?>

<div class="row">
   <div class="col-md"></div>
   <div class="col-lg-8 col-md-12">
      <div class="card">
         <div class="card-body">
            <nav class="nav nav-pills flex-column flex-sm-row" id="tabs" role="tablist">
               <button class="flex-sm-fill text-sm-center nav-link active" aria-current="page" id="general-tab" role="tab" data-bs-toggle="tab" data-bs-target="#general">General</button>
               <button class="flex-sm-fill text-sm-center nav-link" aria-current="page" id="info-tab" role="tab" data-bs-toggle="tab" data-bs-target="#info">Info</button>
               <button class="flex-sm-fill text-sm-center nav-link" id="privacy-tab" role="tab" data-bs-toggle="tab" data-bs-target="#privacy">Privacy</button>
               <button class="flex-sm-fill text-sm-center nav-link" id="payments-tab" role="tab" data-bs-toggle="tab" data-bs-target="#payments">Payments</button>
            </nav>
         </div>
      </div>
      <div class="card">
         <div class="card-header bg-theme">
            Account Settings
         </div>
         <div class="tab-content" id="tab_content">
            <div class="tab-pane show active" role="tabpanel" id="general" aria-labelledby="general-tab">
               <div class="card-body">
                  <label for="bio">
                     Bio
                  </label>
                  <form action="/account/bio.php" method="POST">
                     <textarea name="bio" id="bio" placeholder="Your life's story..."><?= $user->bio ?></textarea>
                     <div class="float-end mb-1">
                        <button class="btn btn-theme btn-sm flex-end">
                           Submit
                        </button>
                     </div>
                  </form>
                  <label for="theme">
                     Theme
                  </label>
                  <form action="/account/theme.php" method="post">
                     <select name="theme" id="theme" class="form-select">
                        <option value="1">
                           Default (Dark)
                        </option>
                        <option value="2" selected>
                           Christmas Theme
                        </option>
                     </select>
                     <div class="my-1"></div>
                     <div class="float-end mb-1">
                        <button class="btn btn-theme btn-sm flex-end">
                           Submit
                        </button>
                     </div>
                  </form>
               </div>
            </div>
            <div class="tab-pane show" role="tabpanel" id="info" aria-labelledby="info-tab">
               <div class="card-body">
                  <label for="id">
                     User ID
                  </label>
                  <input type="text" class="w-100" disabled value="<?= $user->id ?>">
                  <label for="username">
                     Username
                  </label>
                  <input type="text" class="w-100" disabled value="<?= $user->name ?>">
                  <label for="email">
                     Email
                  </label>
                  <input type="text" class="w-100" disabled value="<?= obfuscate_email($user->email) ?>">
               </div>
            </div>
            <div class="tab-pane" role="tabpanel" id="privacy" aria-labelledby="privacy-tab">
               <div class="card-body">
                  <label>
                     Privacy features coming soon.
                  </label>
               </div>
            </div>
            <div class="tab-pane" role="tabpanel" id="payments" aria-labelledby="payments-tab">
               <div class="card-body">
                  <label>
                     Payment features coming soon.
                  </label>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md"></div>
</div>

<?php

$cema->footer();

?>