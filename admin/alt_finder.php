<?php
$name = 'Admin';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAdmin();
$user = intval($_GET['id']);
$user = (object) $cema->query("SELECT * FROM users WHERE id = ?", [$user])->fetch();
$users = $cema->query("SELECT * FROM users WHERE user_ip = ? OR signup_ip = ? OR email = ?", [$user->user_ip, $user->user_ip, $user->email], true);
?>

<div class="row">
  <div class="col"></div>
  <div class="col-8">
    <h4>
      Alt Finder
    </h4>
    <div class="card p-4 m-0">
      <h3>
        Matching Account: <?= $user->name ?>
      </h3>
      <?php
      foreach ($users as $user) {
        $user = (object) $user;
      ?>
        <div class="row">
          <div class="col-12">
            <a href="/profile/<?= $user->id ?>" class="link-unstyled fs-5">
              <?= $user->name ?>
            </a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <div class="col"></div>
</div>

<?php

$cema->footer();

?>