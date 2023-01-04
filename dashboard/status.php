<?php 
if(isset($_POST['submit'])){
  require("$_SERVER[DOCUMENT_ROOT]/cema/cema.php");
  session_start();
  $status = $_POST['status'];
  $status = $cema->bbCode($status);
  $status = $cema->filter($status);

  if(!$cema->is_csrf_valid()) {
    $_SESSION['error'] = "CSRF invalid!";
    header('location: /dashboard');
    die;
  }

  if(empty($status) || strlen($status) >= 250 || strlen($status) < 3) {
    $_SESSION['error'] = "Status must be between 3 and 250 characters.";
    header('location: /dashboard');
    die;
  }

  $cema->update_("users", [
    "status" => $status
  ],
    [
      "id" => $_SESSION['UserID']
    ]
  );

  $cema->insert("feed", [
    "creator" => $_SESSION['UserID'],
    "status" => $status

  ]);
  $_SESSION['note'] = "Successfully changed status!";
  header('location: /dashboard');
  die;
}