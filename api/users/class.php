<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');
class users extends cema {
  function searchUsers($username = "", $page = 1, $perPage = 20, $total = false)
  {

    $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $page = $this->page($page, $perPage);

    if ($total) {
      $query = $this->query("SELECT COUNT(id) as total FROM users WHERE name LIKE ?", ["%" . $username . "%"]);
    } else {
      $query = $this->query("SELECT name,id,bio, updated, avatar_link FROM users WHERE name LIKE ? ORDER BY updated DESC LIMIT ?,? ", ["%" . $username . "%", $page[0], $page[1]]);
    }

    if ($query) {
      if ($query->rowCount() > 0) {
        $result = ($total) ? $query->fetchColumn() : $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      }
    }

    throw new Exception("No results");
  }

  function page($page, $perPage)
  {

    if (is_numeric($perPage)) {
      $page = ($page - 1) * $perPage;
      return [$page, $perPage];
    }
  }

}

$users = new users;