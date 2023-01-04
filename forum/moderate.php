<?php 

if(isset($_POST['form'])) {
  require("$_SERVER[DOCUMENT_ROOT]/cema/cema.php");

  $type = $_GET['type'];
  $action = $_GET['action'];
  $id = intval($_GET['id']);

  if(empty($id) || empty($action) || empty($type)) {
    throw new Exception("What you tryna do brother?");
    die;
  }

  $cema->requireAdmin();

  if ($type == "reply") {
    $reply = $cema->query("SELECT * FROM replies WHERE id = ?", [$id])->fetch();
    if ($action == "delete") {
      if ($reply['deleted'] != 0) {
        $cema->update_("replies", ["deleted" => 0], ["id" => $id]);
      } else {
        $cema->update_("replies", ["deleted" => 1], ["id" => $id]);
      }
    }
  }

  if($type == "thread") {
    $thread = $cema->query("SELECT * FROM posts WHERE id = ?", [$id])->fetch();
    if($action == "pin") {
      if($thread['pinned'] != 0) {
        $cema->update_("posts", ["pinned" => 0], ["id" => $id]);
      } else {
        $cema->update_("posts", ["pinned" => 1], ["id" => $id]);
      }
    } elseif ($action == "lock") {
      if ($thread['locked'] != 0) {
        $cema->update_("posts", ["locked" => 0], ["id" => $id]);
      } else {
        $cema->update_("posts", ["locked" => 1], ["id" => $id]);
      } 
    } elseif ($action == "delete") {
      if ($thread['deleted'] != 0) {
        $cema->update_("posts", ["deleted" => 0], ["id" => $id]);
      } else {
        $cema->update_("posts", ["deleted" => 1], ["id" => $id]);
      }
    }
  }
  header("location: /forum/thread/$id/1");
  die;
}
