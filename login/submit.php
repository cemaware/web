<?php


require($_SERVER['DOCUMENT_ROOT']."/cema/cema.php");

if($cema->auth()) {
  die;
}
// gets failed attempts from current ip address from last 15 minutes
$failed_attempts = $cema->query("SELECT COUNT(1) FROM failed_logins WHERE attempted > DATE_SUB(NOW(), INTERVAL 45 minute) AND ip_address = ?", [$_SERVER['REMOTE_ADDR']])->fetchColumn();
if($failed_attempts > 25) {
   // too many attempts, ergo try again later
   die("err4");
}
if(empty($_POST['username']) || empty($_POST['password'])) {
   die("err1");
}

$name = $_POST['username'];
$password = $_POST['password'];

if(!$cema->user_exists($name)) {
   $cema->query("INSERT INTO failed_logins SET username = ?, attempted = CURRENT_TIMESTAMP, ip_address = ?", [$name, $_SERVER['REMOTE_ADDR']]);
   die("err2");
}

$user = (object) $cema->query("SELECT * FROM users WHERE name = ?", [$name])->fetch();
if(!password_verify($password, $user->password)) {
   $cema->query("INSERT INTO failed_logins SET username = ?, attempted = CURRENT_TIMESTAMP, ip_address = ?", [$name, $_SERVER['REMOTE_ADDR']]);
   die("err3");
}


// todo: user agents

// todo: FOR THE LOVE OF FUCK ADD RATE LIMTING BEFORE YOU GO INTO BETA

$_SESSION['UserID'] = $user->id;
$_SESSION['UserAdmin'] = $user->admin;
$_SESSION['note'] = "Welcome back, " . $user->name;
die("real");
?>