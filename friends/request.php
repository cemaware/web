<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
   require($_SERVER['DOCUMENT_ROOT'] . "/cema/cema.php");

   $action = $_GET['action'];
   $id = intval($_GET['id']);

   if (empty($id) || empty($action)) {
      throw new Exception("What you tryna do brother?");
      die;
   }

   $cema->requireAuth();

   $conn = $cema->pdo;
   if ($action == "accept") {
      $sql = "SELECT * FROM friends WHERE friends.to = ? AND friends.from = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$_SESSION['UserID'], $id]);
      $request = $stmt->fetch();;
      if ($request) {
         $sql = "UPDATE friends SET friends.status = 1 WHERE friends.to = ? AND friends.from = ?";
         $stmt = $conn->prepare($sql);
         $stmt->execute([$_SESSION['UserID'], $id]);
         $_SESSION['note'] = "Friend request accepted!";
         header('location: /friends');
      }
   }

   if ($action == "send") {
      $request = $cema->query("SELECT * FROM friends WHERE friends.to = ? AND friends.from = ?", [$_SESSION['UserID'], $id])->fetch();
      if (!$request) {
         $notifCreator = $id;
         $notifMsg = $cema->localUser($_SESSION["UserID"])['name'] . " added you as a friend.";
         $notifRedir = "/friends/requests";
         $notification = $cema->insert("notifications", [
            "to" => $notifCreator,
            "from" => $_SESSION['UserID'],
            "msg" => $notifMsg,
            "redirect" => $notifRedir
         ]);
         $insert = $cema->insert("friends", ["status" => '0', "to" => $id, "from" => $_SESSION['UserID']]);
         header("location: /profile/$id");
         die;
      }
   }

   if ($action == "remove") {
      $cema->delete("friends", ["`to`" => $id, "`from`" => $_SESSION['UserID']]);
      $cema->delete("friends", ["`from`" => $id, "`to`" => $_SESSION['UserID']]);
      header("location: /profile/$id");
      die;
   }
}
