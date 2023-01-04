<?php
$name = 'Forum';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$categories = $cema->query("SELECT * FROM categories ORDER BY sort", false, true);
$latestPosts = $cema->query("SELECT * FROM posts WHERE deleted = 0 ORDER BY created DESC LIMIT 5", false, true);
?>

<div class="row">
   <div class="col-lg-8 col-md">
      <div class="card">
         <div class="card-header bg-dark">
            <div class="row">
               <div class="p-0 px-2 col-lg-6 col-md">
                  Bulletin
               </div>
               <div class="p-0 col-1 users-label text-center d-none d-lg-block text-truncate">
                  Posts
               </div>
               <div class="p-0 col-2 users-label text-center d-none d-lg-block text-truncate">
                  Replies
               </div>
               <div class="p-0 col text-end d-none d-lg-block px-2">
                  Last Post
               </div>
            </div>
         </div>
         <?php
         $count = 0;
         foreach ($categories as $cat) {
            $count++;
            $postCount = $cema->query("SELECT COUNT(*) FROM posts WHERE cat = ? AND deleted=0", [$cat['id']])->fetchColumn();
            $replyCount = $cema->query("SELECT COUNT(*) FROM replies WHERE cat = ?", [$cat['id']])->fetchColumn();
            $lastPost = $cema->query("SELECT * FROM posts WHERE cat = ? AND deleted = 0 ORDER BY updated DESC LIMIT 1", [$cat['id']], false)->fetch();
            if ($count == 2) {
         ?>
               <div class="card-header bg-dark">
                  <div class="row">
                     <div class="p-0 px-2 col-lg-6 col-md">
                        Cemaware
                     </div>
                     <div class="p-0 col-1 users-label text-center d-none d-lg-block text-truncate">
                        Posts
                     </div>
                     <div class="p-0 col-2 users-label text-center d-none d-lg-block text-truncate">
                        Replies
                     </div>
                     <div class="p-0 col text-end d-none d-lg-block px-2">
                        Last Post
                     </div>
                  </div>
               </div>
            <?php
            }
            if ($count == 7) {
            ?>
               <div class="card-header bg-dark">
                  <div class="row">
                     <div class="p-0 px-2 col-lg-6 col-md">
                        Help Center
                     </div>
                     <div class="p-0 col-1 users-label text-center d-none d-lg-block text-truncate">
                        Posts
                     </div>
                     <div class="p-0 col-2 users-label text-center d-none d-lg-block text-truncate">
                        Replies
                     </div>
                     <div class="p-0 col text-end d-none d-lg-block px-2">
                        Last Post
                     </div>
                  </div>
               </div>
            <?php
            }
            ?>
            <div class="py-2 px-4">
               <div class="row">
                  <div class="col-6 p-0">
                     <a href="/forum/c/<?= $cat['id'] ?>/1" class="forum-title">
                        <h4 style="display: inline-block;">
                           <?= $cat['name'] ?>
                        </h4>
                     </a>
                     <p class="forum-description small">
                        <?= $cat['desc'] ?>
                     </p>
                  </div>
                  <div class="col-1 p-0 d-none d-lg-block">
                     <p style="text-align: center; margin: 0;">
                        <?= $postCount ?>
                     </p>
                  </div>
                  <div class="col-2 p-0 d-none d-lg-block">
                     <p style="text-align: center; margin: 0;">
                        <?= $replyCount ?>
                     </p>
                  </div>
                  <div class="col p-0 users-label text-end d-none d-lg-block">
                     <?php
                     if ($lastPost) {
                        $creator = $cema->get_user($lastPost['poster']);
                     ?>
                        <a class="users-label" href="/forum/thread/<?= $lastPost['id'] ?>/1"><?= $lastPost['title'] ?></a>
                        <span class="text-truncate">
                           by
                           <a href="/profile/<?= $creator->id ?>"><?= $creator->name ?></a> &bullet; <?= $cema->timeAgo(strtotime($lastPost['updated'])) ?>
                        </span>
                     <?php
                     } else {
                        echo "No posts.";
                     }
                     ?>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   </div>
   <div class="col-lg-4 col-md">
      <div class="card">
         <div class="card-header bg-dark">
            Recent Topics
         </div>
         <?php
         foreach ($latestPosts as $post) {
            $post = (object) $post;
            $replyCount = $cema->query("SELECT COUNT(*) FROM replies WHERE post = ?", [$post->id])->fetchColumn();
            $author = $cema->get_user($post->poster);
            $cat = $cema->query("SELECT * FROM categories WHERE id = ?", [$post->cat])->fetch();
         ?>
            <div class="p-2">
               <div class="row">
                  <div class="col-2 d-flex align-items-center">
                     <a href="/profile/<?= $author->id ?>">
                        <img src="/cdn/img/avatar/thumbnail/<?= $author->avatar_link ?>.png" alt="Avatar" class="img-fluid rounded bg-secondary">
                     </a>
                  </div>
                  <div class="col-10 p-0">
                     <a href="/forum/thread/<?= $post->id ?>/1" class="link-unstyled d-block text-truncate" style="width: 90%;">
                        <?= $post->title ?>
                     </a>
                     <small>
                        by
                        <a href="/profile/<?= $author->id ?>"><?= $author->name ?></a> ,
                        in 
                        <a href="/forum/c/<?=$cat['id']?>/1"><?=$cat['name']?></a>
                     </small>
                  </div>
               </div>
            </div>
         <?php
         }
         ?>
      </div>
   </div>
</div>


<?php

$cema->footer();

?>