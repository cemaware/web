<?php

include($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');

header('Content-Type: application/json; charset=utf-8');

$data = array();
$username = $_POST['username'];


function usernameExists($username, $cema)
{
  $i = $cema->query("SELECT * FROM users WHERE name = ?", [$username])->fetch();
  if (!empty($i)) {
    return true;
  } else {
    return false;
  }
}

if (usernameExists($username, $cema)) {
  $data["username"] = $username;
  $data["result"] = true;
} else {
  $data["username"] = $username;
  $data["result"] = false;
}

if ($data["result"] == true) {
  $avatar = $cema->query("SELECT avatar_link FROM users WHERE name = ?", [$username])->fetchColumn();
  $data["avatar"] = $avatar;
}

echo json_encode($data, JSON_PRETTY_PRINT);
