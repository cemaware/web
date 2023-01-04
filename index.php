<?php
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireGuest();

$amount =  $cema->query("SELECT COUNT(*) FROM users", false, false)->fetch()['COUNT(*)'];
if ($amount < 100) {
   $size = "small";
} elseif ($amount <= 1000 && $amount >= 100) {
   $size = "medium-sized";
} elseif ($amount > 1000) {
   $size = "large";
}

$users = $cema->query("SELECT * FROM users ORDER BY RAND() LIMIT 8", false, true);
$items = $cema->query("SELECT * FROM item WHERE verified = 1 ORDER BY RAND() LIMIT 8")
?>
</div>

<head>
   <!-- Primary Meta Tags -->
   <meta name="title" content="Cemaware">
   <meta name="description" content="A 3D user generated sandbox where users can meet new friends, buy and sell shop items, join clubs, customize your 3D avatar, and more. It's yours at Cemaware.">

   <!-- Open Graph / Facebook -->
   <meta property="og:type" content="website">
   <meta property="og:url" content="https://cemaware.ml/">
   <meta property="og:title" content="Cemaware">
   <meta property="og:description" content="A 3D user generated sandbox where users can meet new friends, buy and sell shop items, join clubs, customize your 3D avatar, and more. It's yours at Cemaware.">
   <meta property="og:image" content="/cdn/img/meta_img.png">

   <!-- Twitter -->
   <meta property="twitter:card" content="summary_large_image">
   <meta property="twitter:url" content="https://cemaware.ml/">
   <meta property="twitter:title" content="Cemaware">
   <meta property="twitter:description" content="A 3D user generated sandbox where users can meet new friends, buy and sell shop items, join clubs, customize your 3D avatar, and more. It's yours at Cemaware.">
   <meta property="twitter:image" content="/cdn/img/meta_img.png">
</head>
<br><br>
<div class="landing shadow">
   <img src="/cdn/img/banner.png" alt="Cemaware" width="500" style="margin-left: auto; margin-right: auto; display: block;" class="img-fluid">
   <div class="text-center">
   </div>
</div>
<div class="row">
   <div class="col"></div>
   <div class="col-lg-8 col-md" style="padding: 50px;">
      <h2 class="fw-bold">
         What is Cemaware?
      </h2>
      <p>
         Cemaware is a 3D sandbox community with a <?= $size ?> growing community of <?= $amount ?> users. You can chat with other users of Cemaware on the fourms, play games, make clothing, and more.
      </p>
   </div>
   <div class="col"></div>
</div>
<div class="landing">
   <div class="row" style="padding: 0 50px;">
      <div class="col"></div>
      <div class="col-lg-4 col-md">
         <div class="row">
            <?php foreach ($users as $user) {
            ?>
               <div class="col-lg-3 col-md">
                  <img src="/cdn/img/avatar/thumbnail/<?= $user['avatar_link'] ?>.png" alt="<?= $user['name'] ?>'s avatar" class="img-fluid rounded bg-secondary">
                  <p class="text-center users-label">
                     <?= $user['name'] ?>
                  </p>
               </div>
            <?php } ?>
         </div>
      </div>
      <div class="col-lg-4 col-md">
         <h2 class="fw-bold">
            Users
         </h2>
         <p>
            On Cemaware, you can meet a variety of users who are customizing avatars, playing games, foruming, and more.
         </p>
      </div>
      <div class="col"></div>
   </div>
</div>
<br>
<div class="row" style="padding: 0 50px">
   <div class="col"></div>
   <div class="col-lg-4 col-md">
      <h2 class="fw-bold">
         Shop
      </h2>
      <p>
         Cemaware has a large and high variety of items to use on your avatar at your disposal. From shirts, hats, faces, and more, we have it all.
      </p>
   </div>
   <div class="col-lg-4 col-md">
      <div class="row">
         <?php foreach ($items as $item) {
         ?>
            <div class="col-lg-3 col-md">
               <img src="/cdn/img/shop/<?= md5($item['id']) ?>.png" alt="<?= $item['name'] ?>" class="img-fluid bg-secondary rounded">
               <p class="text-center users-label">
                  <?= $item['name'] ?>
               </p>
            </div>
         <?php } ?>
      </div>
   </div>
   <div class="col"></div>
</div>
<div>
   <?php

   $cema->footer();

   ?>