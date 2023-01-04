<?php
$name = 'Forum';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$id = intval($_GET['id']);
$page = intval($_GET['page']);
$limit = 12;
$offset = ($page - 1) * $limit;
$posts_pinned = $cema->query("SELECT * FROM posts WHERE cat = ? AND pinned=1 AND deleted=0 ORDER BY updated DESC", [$id], true);
$posts_regular = $cema->query("SELECT * FROM posts WHERE cat = ? AND pinned=0 AND deleted=0 ORDER BY updated DESC LIMIT {$limit} OFFSET {$offset}", [$id], true);
$cat = $cema->query("SELECT * FROM categories WHERE id = ?", [$id], true);

//INSERT INTO `posts` (`cat`, `title`, `body`, `created`, `locked`, `poster`, `updated`, `pinned`) VALUES
//(3, 'pinned test', 'real!!!', '2022-11-05 19:06:37', 1, 1, '0000-00-00 00:00:00', 1);
$posts = array_merge($posts_pinned, $posts_regular);
?>
<script>
   document.title = "<?= $cat[0]['name'] ?> // Cemaware"
</script>


<nav aria-label="breadcrumb">
   <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/forum">Forum</a></li>
      <li class="breadcrumb-item" aria-current="page"><a href=""><?= $cat[0]['name']; ?></a></li>
   </ol>
</nav>
<div class="row">
   <div class="col-lg-9 col-md">

      <div class="card m-0">
         <div class="card-header bg-theme">
            <div class="row">
               <div class="col-8">
                  Posts
               </div>
               <div class="col-2 text-center users-label d-none d-lg-block text-truncate">
                  Replies
               </div>
               <div class="col-2 users-label text-end d-none d-lg-block">
                  Updated
               </div>
            </div>
         </div>
         <?php
         $count = 0;
         foreach ($posts as $post) { ?>
            <?php
            $replyCount = $cema->query("SELECT COUNT(*) FROM replies WHERE post = ?", [$post['id']])->fetchColumn();
            $user = $cema->get_user($post['poster']);
            $count++;
            ?>
            <div class="py-2 px-4">
               <div class="row">
                  <div class="col-1 p-0 d-flex align-items-center">
                     <a href="/profile/<?= $user->id ?>">
                        <img src="/cdn/img/avatar/thumbnail/<?= $user->avatar_link ?>.png" alt="Avatar" class="img-fluid bg-secondary rounded">
                     </a>
                  </div>
                  <div class="col">
                     <a href="/forum/thread/<?= $post['id'] ?>/1" class="forum-title m-0" style="  width: 450px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis; display: block;">
                        <?php
                        if ($post['pinned'] == 1) {
                        ?>
                           <i class="fad fa-thumbtack text-secondary"></i>
                        <?php
                        }
                        if ($post['locked'] == 1) {
                        ?>
                           <i class="fad fa-lock text-secondary"></i>
                        <?php
                        }
                        ?>
                        <?= ($post['deleted'] == "0") ? $post['title'] : "[cemaware " . $post['id'] . "]" ?>
                     </a>
                     <span>
                        by
                        <a href="/profile/<?= $user->id ?>"><?= $user->name ?></a>
                     </span>
                     -
                     <span>
                        <?= $cema->timeAgo(strtotime($post['created'])) ?>
                     </span>
                  </div>
                  <div class="col-2 d-none d-lg-block">
                     <p style="text-align: center; margin: 0;">
                        <?= $replyCount ?>
                     </p>
                  </div>
                  <div class="col-2 text-end d-none d-lg-block">
                     <?php
                     if ($replyCount == 0) {
                     ?>
                        <?= $cema->timeAgo(strtotime($post['created'])) ?>
                        <br>
                        <span>
                           by <a href="/profile/<?= $user->id ?>">
                              <?= $user->name ?>
                           </a>
                        </span>
                     <?php
                     } else {
                        $reply = (object) $cema->query("SELECT * FROM replies WHERE post = ? ORDER BY id DESC LIMIT 1", [$post['id']])->fetch();
                        $replyUser = $cema->get_user($reply->poster);
                     ?>
                        <?= $cema->timeAgo(strtotime($reply->created)) ?>
                        <span>
                           by <a href="/profile/<?= $user->id ?>">
                              <?= $replyUser->name ?>
                           </a>
                        </span>
                     <?php
                     }
                     ?>
                  </div>
               </div>
            </div>
         <?php } ?>
         <?php
         if (!$posts) {
         ?>
            <div class="py-2 px-4">
               <h4>No posts here!</h4>
            </div>
         <?php
         }
         ?>
      </div>
      <?php
      if ($page != 1) {
         if ($count != $limit + count($posts_pinned)) {
      ?>
            <div class="float-start">
               <a href="/forum/c/<?= $cat[0]['id'] ?>/<?= $page - 1 ?>">
                  <button class="btn btn-primary">
                     <i class="fa fa-arrow-left"></i>
                     Previous Page
                  </button>
               </a>
            </div>
            <div class="float-end">

            </div>
         <?php
         } else {
         ?>
            <div class="float-start">
               <a href="/forum/c/<?= $cat[0]['id'] ?>/<?= $page - 1 ?>">
                  <button class="btn btn-primary">
                     <i class="fa fa-arrow-left"></i>
                     Previous Page
                  </button>
               </a>
            </div>
            <div class="float-end">
               <a href="/forum/c/<?= $cat[0]['id'] ?>/<?= $page + 1 ?>">
                  <button class="btn btn-primary">
                     Next Page
                     <i class="fa fa-arrow-right"></i>
                  </button>
               </a>
            </div>
         <?php
         }
      } else {
         if ($count != $limit + count($posts_pinned)) {
         } else {
         ?>
            <div class="float-end">
               <a href="/forum/c/<?= $cat[0]['id'] ?>/<?= $page + 1 ?>">
                  <button class="btn btn-primary">
                     Next Page
                     <i class="fa fa-arrow-right"></i>
                  </button>
               </a>
            </div>
      <?php
         }
      }
      ?>
   </div>
   <div class="col-lg-3 col-md">
      <div class="card m-0">
         <div class="card-header bg-theme">
            Controls
         </div>
         <div class="text-end">
            <a href="/forum/create/<?= $id ?>" class="p-2 w-100 link-unstyled d-flex align-items-center gap-2">
               <i class="fa fa-plus text-secondary"></i>
               Create Thread
            </a>
            <hr>
            <a href="/forum/my/drafts" class="p-2 w-100 link-unstyled d-flex align-items-center gap-2">
               <i class="fa fa-bookmark text-warning"></i>
               Bookmarks
            </a>
            <hr>
            <a href="/forum/my/posts" class="p-2 w-100 link-unstyled d-flex align-items-center gap-2">
               <i class="fa fa-list text-primary"></i>
               My Posts
            </a>
            <hr>
            <a href="/forum/my/drafts" class="p-2 w-100 link-unstyled d-flex align-items-center gap-2">
               <i class="fad fa-pencil text-danger"></i>
               Drafts
            </a>
         </div>
      </div>
      <?php

      if ($cema->auth()) {
      ?>
         <div class="card m-0 mt-1">
            <div class="card-header bg-theme px-2">
               <i class="far fa-rocket"></i>
               Level Up
            </div>

            <div class="p-2">
               <?php
               $exp = $cema->local_info()->exp;
               $percentageToLevelUp = $cema->determinePercentageEXP($exp);
               $XPtoLevelUp = $cema->determineXPtoLevelUp($exp);
               $userLevel = $cema->determineUserLevel($exp);
               ?>
               <div class="progress fs-6" style="height: 1.5em; margin-top: 2.5px;">
                  <div class="progress-bar progress-bar-striped bg-theme fw-bold progress-bar-animated" role="progressbar" style="width: <?= $percentageToLevelUp ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $percentageToLevelUp ?>%</div>
               </div>
               <span class="mt-1 fw-bold">
                  Level <?= $userLevel ?> <span class="small">(<?= $exp ?> XP total)</span>
               </span>
               <br>
               <small>
                  <?= $XPtoLevelUp ?> XP to level up.
               </small>
            </div>
         </div>
      <?php
      }
      ?>
   </div>
</div>


<?php

$cema->footer();

?>