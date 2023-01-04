<?php
$name = 'Friends';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
$user = $cema->localUser();
$cema->alert();

$sentCount = $cema->query("SELECT COUNT(id) as total FROM friends WHERE friends.from = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchColumn();
$pendingCount = $cema->query("SELECT COUNT(id) as total FROM friends WHERE friends.to = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchColumn();
$totalFriends = $cema->query("SELECT COUNT(id) as total FROM friends WHERE (friends.from = :id OR friends.to = :id) AND friends.status = 1;", [":id" => $_SESSION["UserID"]])->fetchColumn();

$friends = $cema->query("SELECT * FROM friends WHERE friends.to = ? AND friends.status = 0;", [$_SESSION["UserID"]])->fetchAll();
?>
<div class="row">
  <div class="col-6 mx-auto">
    <div class="card">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/friends/requests/">Requests (<?= $pendingCount ?>)</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Sent (<?= $sentCount ?>)</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/friends">Friends (<?= $totalFriends ?>)</a>
        </li>
      </ul>
      <div class="p-4">
        <h4>Friend Requests</h4>
        <?php
        foreach ($friends as $friend) {
          $user = $cema->get_user($friend['from']);
        ?>
          <div class="row">
            <div class="col-3">
              <a href="/profile/<?= $user->id ?>" class="users-link">
                <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" alt="Avatar" class="img-fluid thumbnail">
              </a>
            </div>
            <div class="col-9">
              <a href="/profile/<?= $user->id ?>" class="users-link">
                <?= $user->name ?>
              </a>
              <br>
              <form action="/friend/accept/<?= $user->id ?>" method="POST" style="display: inline-block;">
                <button class="btn-success btn" name="submit">Accept</button>
              </form>
              <form action="/friend/remove/<?= $user->id ?>" method="POST" style="display: inline-block;">
                <button class="btn-danger btn" name="submit">Deny</button>
              </form>
            </div>
          </div>
          <hr>
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