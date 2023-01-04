<?php
$DEBUG = true;
function isLocalhost($whitelist = ['127.0.0.1', '::1', '192.168.0.1','192.168.0.2', '192.168.0.3', '192.168.0.4', '192.168.0.5', '192.168.0.6', '192.168.0.8', '192.168.0.9', '192.168.0.10', '192.168.0.11', '192.168.0.12', '192.168.0.13', '192.168.0.14'])
{
  return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}
try {
  if (isLocalhost()) {
    $conn = new PDO("mysql:host=localhost;dbname=forum2", "root", "DatabasePass");
  } else {
    $conn = new PDO("mysql:host=localhost;dbname=forum2", "cemaDB", "MyG3yP4ssw0rd");
  }
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  date_default_timezone_set('America/New_York');
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// hide notices
