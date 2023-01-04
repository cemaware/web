  <?php

  require "../rest.php";
  require "class.php";

  if (isset($_GET["query"])) {

    if (is_string($_GET["query"])) {

      $page = 1;
      if (isset($_GET["page"])) {
        if (is_numeric($_GET["page"])) {
          $page = $_GET["page"];
        }
      }
      try {
        $data = $users->searchUsers($_GET["query"], $page, 20, false);
        foreach ($data as &$user) {
          $user["bio"] = htmlentities($user["bio"]);
          if (strtotime($user['updated']) > strtotime("-120 seconds")) {
            $user['online'] = true;
          } else {
            $user['online'] = false;
          }
          $user['username'] = $user['name'];
        }
        $total = $users->searchUsers($_GET["query"], $page, 20, true);
        $rest->success(["data" => $data, "total" => $total]);
      } catch (Exception $e) {
        $rest->error($e->getMessage());
      }
    }
  }
