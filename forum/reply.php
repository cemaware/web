<?php
$name = 'undefined';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
if (isset($_POST['submit'])) {
   $id = intval($_GET['id']);
   $post = (object) $cema->query("SELECT * FROM posts WHERE id = ?", [$id])->fetch();

   if (!$cema->is_csrf_valid()) {
      $_SESSION['error'] = "CSRF invalid!";
      header("location: /forum/reply/$id");
      die;
   }
   if (!$post) {
      $_SESSION['error'] = "No such thread exists!";
      header("location: /forum");
      die;
   }

   if ($post->locked && !$cema->isAdmin()) {
      $_SESSION['error'] = "This thread is locked!";
      header("location: /forum/thread/$post->id/1");
      die;
   }

   $body = htmlspecialchars($_POST['body']);

   if (empty($body)) {
      $_SESSION['eror'] = "Empty input!";
      header("location: /forum/reply/$id");
      die;
   }

   if (!empty($_GET['quote'])) {
      $quote = $_GET['quote'];
   }

   $reply = $body;
   $replyPoster = $_SESSION['UserID'];
   $replyPost = $post->id;
   $replyCat = $post->cat;
   $replyQuote = '0';
   if (isset($quote)) {
      $quoteCheck = (object) $cema->query("SELECT * FROM replies WHERE id = ?", [$quote])->fetch();
      if (!$quoteCheck) {
         $_SESSION['error'] = "No such reply exists as quote.";
         header("location: /forum/thread/$post->id/1");
         die;
      }
      $replyQuote = $quote;
   }

   $update = $cema->update_(
      "posts",
      [
         "updated" => date("Y-m-d H:i:s"),
      ],
      [
         "id" => $replyPost
      ]
   );
   var_dump($replyQuote);
   $reply = $cema->insert("replies", [
      "body" => $reply,
      "post" => $replyPost,
      "poster" => $replyPoster,
      "cat" => $replyCat,
      "quote" => $replyQuote
   ]);

   $cema->incrementUserXP(1);
   
   $cema->update_("users", ["posts" => $cema->local_info()->posts + 1], ["id" => $_SESSION['UserID']]);


   $notifCreator = $post->poster;
   $notifMsg = $cema->localUser($_SESSION["UserID"])['name'] . " replied to your forum post.";
   $notifRedir = "/forum/thread/$post->id/1#reply$reply->id";
   $notification = $cema->insert("notifications", [
      "to" => $notifCreator,
      "from" => $_SESSION['UserID'],
      "msg" => $notifMsg,
      "redirect" => $notifRedir
   ]);
   $_SESSION['note'] = "Successfully created reply.";
   header("location: /forum/thread/$post->id/1#reply$reply->id");
   die;
}
$quote = 0;
if (!empty($_GET['quote'])) {
   $quote = $_GET['quote'];
}

$id = intval($_GET['id']);
$post = (object) $cema->query("SELECT * FROM posts WHERE id = ?", [$id])->fetch();

if ($post->locked && !$cema->isAdmin()) {
   $_SESSION['error'] = "This thread is locked!";
   header("location: /forum/thread/$post->id/1");
   die;
}
?>

<div class="row">
   <div class="col-3"></div>
   <div class="col-6">
      <?php $cema->alert() ?>
      <div class="card">
         <div class="card-header bg-theme">
            Reply to '<?= $post->title ?>''
         </div>
         <div class="card-body">
            <form action="" method="POST">
               <?php
               if ($quote != 0) {
                  $quote = (object) $cema->query("SELECT * FROM replies WHERE id = ?", [$quote])->fetch();
                  if($quote->id != $_GET['quote'] || $quote->post != $id) {
                     header('location: /forum');
                     die;
                  }
                  $quotePoster = $cema->get_user($quote->poster);
               ?>
                  <blockquote>
                     <div class="row">
                        <div class="col-2">
                           <a href="/profile/<?= $quotePoster->id ?>" class="link-unstyled">
                              <img src="/cdn/img/avatar/thumbnail/<?= $quotePoster->avatar_link ?>.png" alt="Avatar" class="img-fluid thumbnail">
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
               <textarea name="body" id="body" rows="10" placeholder="Post body..."></textarea>
               <?php $cema->set_csrf(); ?>
               <?php
               if ($post->locked) {
               ?>
                  <p>
                     <i class="fa fa-lock"></i>
                     This post is locked.
                  </p>
               <?php
               }
               ?>
               <button class="btn btn-theme w-100" name="submit">
                  <i class="fa fa-plus"></i>
                  Create Reply
               </button>
            </form>
         </div>
      </div>
   </div>
</div>


<?php

$cema->footer();

?>