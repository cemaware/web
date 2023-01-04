<?php
$name = 'Forum';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$page = intval($_GET['page']);

$limit = 10;
$offset = ($page - 1) * $limit;
$id = intval($_GET['id']);
$post = (object) $cema->query("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
if(isset($post->scalar)) {
   header('location: /forum');
   die;
}
$replies = (object) $cema->query("SELECT * FROM replies WHERE post = ? LIMIT {$limit} OFFSET {$offset}", [$id], true);
$poster = $cema->get_user($post->poster);
$cat = $cema->query("SELECT * FROM categories WHERE id = ?", [$post->cat])->fetch();

$post->body = nl2br($cema->bbCode($post->body));
$count = 0;

$post->title = ($post->deleted == "0") ? $post->title : "[cemaware " . $post->id . "]";

?>
<script>
   document.title = "<?= $post->title ?> - Cemaware";
</script>

<div class="row">
   <div class="col-1"></div>
   <div class="col-10">
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/forum">Forum</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="/forum/c/<?= $cat['id'] ?>/1"><?= $cat['name']; ?></a></li>
            <li class="breadcrumb-item"><?= $post->title ?></li>
         </ol>
      </nav>
      <a href="/forum/reply/<?= $id ?>" class="btn btn-theme float-end btn-sm">
         <i class="fa fa-plus"></i>
         Reply
      </a>
      <a href="/forum/create/<?= $cat['id'] ?>" class="float-end btn btn-theme btn-sm me-2">
         <i class="fa fa-plus"></i>
         Create Post
      </a>
      <h4 class="fw-bold">
         <?php
         if ($post->pinned == 1) {
         ?>
            <i class="fad fa-thumbtack text-secondary"></i>
         <?php
         }
         if ($post->locked == 1) {
         ?>
            <i class="fad fa-lock text-secondary"></i>
         <?php
         }
         ?>
         <?= $post->title ?>
      </h4>
      <br>
      <?php
      $cema->alert();
      ?>

      <head>
         <meta property="og:type" content="website">
         <meta property="og:site_name" content="Cemaware">
         <meta property="og:title" content="<?= $post->title ?> - Cemaware">
         <meta property="og:description" content="<?= $post->body ?>">
         <meta name="theme-color" content="#4285f4">
         <meta property="og:image" content="https://cemaware.ml/cdn/img/avatar/thumbnail/<?= $poster->avatar_link ?>.png">
      </head>

      <div class="card mx-0">
         <div class="card-body border-bottom border-dark">
            <div class="row">
               <div class="col-3">
                  <a href="/profile/<?= $poster->id ?>">
                     <img src="/cdn/img/avatar/<?= $poster->avatar_link ?>.png" alt="Avatar" class="img-fluid p-2 bg-secondary rounded mb-2">
                  </a>
                  <a href="/profile/<?= $poster->id ?>" class="text-white">
                     <?= $poster->name ?>
                     <?php if (strtotime($poster->updated) > strtotime("-120 seconds")) {
                     ?>
                        <i class="fas fa-circle text-success small"></i>
                     <?php
                     } else {
                     ?>
                        <i class="fas fa-circle text-secondary small"></i>
                     <?php
                     }
                     ?>
                  </a>
                  <?php
                  // User Level
                  $level = $cema->determineUserLevel($poster->exp);
                  // (shitty way of) getting post count
                  $postCount = $poster->posts;
                  ?>
                  <label for="level" class="small d-flex justify-content-between">
                     <span>
                        Join Date:
                     </span>
                     <b>
                        <?= date("m/d/y", strtotime($poster->created)) ?>
                     </b>
                  </label>
                  <label for="level" class="small d-flex justify-content-between">
                     <span>
                        Level:
                     </span>
                     <b>
                        <?= $level ?>
                     </b>
                  </label>
                  <label for="level" class="small d-flex justify-content-between">
                     <span>
                        Post Count:
                     </span>
                     <b>
                        <?= $postCount ?>
                     </b>
                  </label>
               </div>
               <div class="col-9">
                  <div id="time" class="mb-2 text-secondary fw-bold small">
                     <i class="fas fa-clock"></i>
                     Posted on <?= date("D, M d Y", strtotime($post->created)) ?>
                     at <?= date("h:i a", strtotime($post->created)) ?>
                  </div>
                  <?= $post->body ?>
               </div>
            </div>
         </div>
      </div>

      <?php
      foreach ($replies as $reply) {
         $reply = (object) $reply;
         $count++;
         $poster = (object) $cema->query("SELECT * FROM users WHERE id = ?", [$reply->poster])->fetch();
         $post = $reply;
         $post->body = nl2br($cema->bbCode($post->body));
         if ($post->quote != 0) {
            $quote = (object) $cema->query("SELECT * FROM replies WHERE id = ?", [$post->quote])->fetch();
         }
      ?>
         <div class="card mx-0">
            <div class="card-body border-bottom border-dark">
               <div class="row">
                  <div class="col-3">
                     <a href="/profile/<?= $poster->id ?>">
                        <img src="/cdn/img/avatar/<?= $poster->avatar_link ?>.png" alt="Avatar" class="img-fluid p-2 bg-secondary rounded mb-2">
                     </a>
                     <a href="/profile/<?= $poster->id ?>" class="text-white">
                        <?= $poster->name ?>
                        <?php if (strtotime($poster->updated) > strtotime("-120 seconds")) {
                        ?>
                           <i class="fas fa-circle text-success small"></i>
                        <?php
                        } else {
                        ?>
                           <i class="fas fa-circle text-secondary small"></i>
                        <?php
                        }
                        ?>
                     </a>
                     <?php
                     // User Level
                     $level = $cema->determineUserLevel($poster->exp);
                     $postCount = $poster->posts;
                     ?>
                     <label for="level" class="small d-flex justify-content-between">
                        <span>
                           Join Date:
                        </span>
                        <b>
                           <?= date("m/d/y", strtotime($poster->created)) ?>
                        </b>
                     </label>
                     <label for="level" class="small d-flex justify-content-between">
                        <span>
                           Level:
                        </span>
                        <b>
                           <?= $level ?>
                        </b>
                     </label>
                     <label for="level" class="small d-flex justify-content-between">
                        <span>
                           Post Count:
                        </span>
                        <b>
                           <?= $postCount ?>
                        </b>
                     </label>
                  </div>
                  <div class="col-9">
                     <div class="d-flex justify-content-between">
                        <div id="time" class="mb-2 text-secondary fw-bold small">
                           <i class="fas fa-clock"></i>
                           Posted on <?= date("D, M d Y", strtotime($post->created)) ?>
                           at <?= date("h:i a", strtotime($post->created)) ?>
                        </div>
                        <div class="dropdown">
                           <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="far fa-ellipsis-v text-muted"></i>
                           </a>
                           <ul class="dropdown-menu">
                              <li>
                                 <a href="/forum/reply/<?= $id ?>/<?= $post->id ?>" class="dropdown-item">
                                    <i class="far fa-cloud"></i>
                                    Quote
                                 </a>
                              </li>
                              <div class="dropdown-divider"></div>
                              <li>
                                 <a href="/report/reply/<?= $post->id ?>" class="dropdown-item">
                                    <div class="text-danger">
                                       <i class="far fa-flag"></i>
                                       Report
                                    </div>
                                 </a>
                              </li>
                           </ul>
                        </div>

                     </div>
                     <?php
                     if ($post->quote != 0) {
                        $quote = (object) $cema->query("SELECT * FROM replies WHERE id = ?", [$post->quote])->fetch();
                        $quotePoster = $cema->get_user($quote->poster);
                        $quote->body = nl2br($cema->bbCode($quote->body));
                     ?>
                        <blockquote>
                           <div class="row">
                              <div class="col-2">
                                 <a href="/profile/<?= $quotePoster->id ?>" class="link-unstyled">
                                    <img src="/cdn/img/avatar/thumbnail/<?= $quotePoster->avatar_link ?>.png" alt="Avatar" class="img-fluid bg-secondary rounded">
                                 </a>
                              </div>
                              <div class="col">
                                 <div class="small text-secondary fw-bold">
                                    <a href="/profile/<?= $quotePoster->id ?>" class="link-unstyled text-white">
                                       <?= $quotePoster->name ?>
                                    </a>
                                    &bullet;
                                    <i class="fad fa-clock"></i>
                                    <?= date("F j, Y, g:i a", strtotime($quote->created)) ?>
                                 </div>
                                 <p style="margin: 0;">
                                    <?= $quote->body ?>
                                 </p>
                              </div>
                           </div>
                        </blockquote>
                     <?php
                     }
                     ?>
                     <?= $post->body ?>
                  </div>
               </div>
            </div>
         </div>
      <?php
      }
      ?>
      <div class="my-3"></div>
      <div id="pages">
         <?php
         $post->id = $id;
         if ($page != 1) {
            if ($count != $limit) {
         ?>
               <div class="float-start">
                  <a href="/forum/thread/<?= $post->id ?>/<?= $page - 1 ?>#pages">
                     <button class="btn btn-theme btn-sm">
                        <i class="far fa-arrow-left"></i>
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
                  <a href="/forum/thread/<?= $post->id ?>/<?= $page - 1 ?>#pages">
                     <button class="btn btn-theme btn-sm">
                        <i class="far fa-arrow-left"></i>
                        Previous Page
                     </button>
                  </a>
               </div>
               <div class="float-end">
                  <a href="/forum/thread/<?= $post->id ?>/<?= $page + 1 ?>#pages">
                     <button class="btn btn-theme btn-sm">
                        Next Page
                        <i class="far fa-arrow-right"></i>
                     </button>
                  </a>
               </div>
            <?php
            }
         } else {
            if ($count != $limit) {
            } else {
            ?>
               <div class="float-end">
                  <a href="/forum/thread/<?= $post->id ?>/<?= $page + 1 ?>#pages">
                     <button class="btn btn-theme btn-sm">
                        Next Page
                        <i class="far fa-arrow-right"></i>
                     </button>
                  </a>
               </div>
         <?php
            }
         }
         ?>
      </div>
      <br>
      <br>

      <div class="text-center m-1">
         <button class="btn btn-theme">
            <a href="/forum/reply/<?= $id ?>" style="text-align: center; display: block; color: #FFF; text-decoration: none;">
               <i class="fa fa-plus"></i>
               Reply
            </a>
         </button>
      </div>
   </div>
   <div class="col-1"></div>
</div>

<?php

$cema->footer();

?>