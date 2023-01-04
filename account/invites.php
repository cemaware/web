<?php
$name = 'Invites';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
$enabled = $cema->query("SELECT invites_enabled FROM site_settings WHERE id = 1")->fetchColumn();
if ($enabled) {
  $invites = $cema->query("SELECT * FROM invites WHERE creator = ?", [$_SESSION['UserID']], true);
}
?>

<?php
if ($enabled) {
?>
  <div class="row">
    <div class="col-lg-6 col-sm-6">
      <div style="display: block">
        <h4 style="display: inline-block;">
          Invites
        </h4>
        <button class="btn-theme btn btn-sm float-end">
          <i class="fa fa-plus"></i>
          Make Invite
        </button>
      </div>
      <div class="card mx-0">
        <?php
        if (!$invites) {
        ?>
          <div class="card-body">
            <h4>No Invites!</h4>
            <div class="text-muted small">
              Make some with the button on the top right.
            </div>
        </div>
          <?php
        }
          ?>
          <?php
          $number = 0;
          foreach ($invites as $invite) {
            $invite = (object) $invite;
            $number++;
          ?>
          <div class="card-body">
            <h4>Invite #<?= $number ?></h4>
            <div class="small">
              <?= ($invite->user) ? 'Used' : 'Not Used' ?>
            </div>
            <div style="color: grey;">
              <i class="fad fa-clock"></i> <span class="fw-bold">Created: <?= $cema->timeAgo(strtotime($invite->created)) ?></span>
            </div>
          </div>
          <hr>  
          <?php
          }
          ?>
          </div>
    </div>
    <div class="col-lg-6 col-sm-6">
      <h1>
        What are invites?
      </h1>
      <p class="small" style="color:rgb(220,220,220);">
        If you want to invite your friends to Cemaware, instead of giving them the website's link, generate a special invite so that you can both benefit! You get 10 cubes for every user you invite, and the user you invite
        gets 5 cubes.
      </p>
    </div>
  </div>

<?php
} else {
?>
  <div class="row">
    <div class="col"></div>
    <div class="col-5">
      <div class="card">
        <div class="card-body text-center">
          <h4 class="fs-3">
            <i class="fad fa-hammer text-danger"></i>
            Invites Disabled
            <i class="fad fa-hard-hat text-warning"></i>
          </h4>
          <p class="small m-0 text-secondary">
            Come back later, it'll be back soon.
          </p>
          <a href="/dashboard" class="small text-uppercase link-unstyled text-theme fs-5">
            Go Home
          </a>
        </div>
      </div>
    </div>
    <div class="col"></div>
  </div>
<?php
}
?>

<?php

$cema->footer();

?>