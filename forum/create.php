<?php
$name = 'Forum';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
if (isset($_POST['submit'])) {
  $id = intval($_GET['id']);
  
  if (!$cema->is_csrf_valid()) {
    $_SESSION['error'] = "CSRF invalid!";
    header("location: /forum/create/$id");
    die;
  }

  $title = htmlspecialchars($_POST['title']);
  $body = $_POST['body'];
  $cat = htmlspecialchars($_POST['cat']);
  $poster = $_SESSION['UserID'];

  if (empty($cat) || empty($body) || empty($title)) {
    $_SESSION['error'] = "Empty inputs!";
    header("location: /forum/create/$id");
    die;
  }
  $cat = (object) $cema->query("SELECT * FROM categories WHERE id = ?", [$id])->fetch();

  if($cat->admin && !$cema->isAdmin()) {
    $_SESSION['error'] = "You're not admin!";
    header("location: /forum/create/$id");
    die;
  }

  $post = $cema->insert("posts", [
    "title" => $title,
    "body" => $body,
    "poster" => $poster,
    "cat" => $id
  ]);

  // increase user's xp

  $cema->incrementUserXP(2);

  $webhookurl = "https://discord.com/api/webhooks/1041030653967351958/8RM-zKjaaODS656MtKSZ1-TGpbQQfTppDGWp0V5bFJ-1UKhss8Up6IRtQYEkj4K0VWlN";
  $timestamp = date("c", strtotime("now"));

  $json_data = json_encode([
    // Message
    "content" => "New post in '" . $cat->name . "' category.",

    // Username
    "username" => "Forum Robot",

    "embeds" => [
      [
        // Embed Title
        "title" => "'" .  $title . "' by " . $cema->get_user($poster)->name,

        // Embed Type
        "type" => "rich",

        // Embed Description
        "description" => $body,

        // Timestamp of embed must be formatted as ISO8601
        "timestamp" => $timestamp,

        // Embed left border color in HEX
        "color" => hexdec("3366ff"),

        // Thumbnail
        //"thumbnail" => [
        //    "url" => "https://ru.gravatar.com/userimage/28503754/bc1qs8x6caxpkns9szm37d8csd6ej4nr67qx3hk732.jpg?size=400"
        //],

        // Author
        "author" => [
          "name" => $cema->get_user($poster)->name,
        ],
      ]
    ]

  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


  $ch = curl_init($webhookurl);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);
  // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
  // echo $response;
  curl_close($ch);

  header("location: /forum/thread/$post->id/1");
  die;
}
$id = intval($_GET['id']);
$cat = $cema->query("SELECT * FROM categories WHERE id = ?", [$id])->fetch();
?>

<div class="row">
  <div class="col-3"></div>
  <div class="col-6">
    <?php $cema->alert() ?>
    <div class="card">
      <div class="card-header bg-theme">
        Create Post in <?= $cat['name'] ?>
      </div>
      <div class="card-body">
        <form action="" method="POST">
          <input type="text" placeholder="Post title..." name="title" class="mb-1">
          <textarea name="body" id="body" rows="10" placeholder="Post body..."></textarea>
          <input type="hidden" name="cat" value="<?= $cat['id'] ?>">
          <?php $cema->set_csrf(); ?>
          <button class="btn btn-theme w-100" name="submit">
            <i class="fa fa-plus"></i>
            Create Thread
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<?php

$cema->footer();

?>