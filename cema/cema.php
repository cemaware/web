<?php

include 'config.php';
session_start();
ob_start();
class cema
{
   public $pdo = false;

   function isLocalhost($whitelist = ['127.0.0.1', '::1', '192.168.0.1', '192.168.0.2', '192.168.0.3', '192.168.0.4', '192.168.0.5', '192.168.0.6', '192.168.0.8', '192.168.0.9', '192.168.0.10', '192.168.0.11', '192.168.0.12', '192.168.0.13', '192.168.0.14'])
   {
      return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
   }

   public function __construct()
   {
      try {
         if ($this->isLocalhost()) {
            $this->pdo = new PDO("mysql:host=localhost;dbname=forum2", "root", "DatabasePass");
         } else {
            $this->pdo = new PDO("mysql:host=localhost;dbname=forum2", "cemaDB", "MyG3yP4ssw0rd");
         }
      } catch (Exception $e) {
         die();
      }
   }
   /*
		BASIC FUNCTIONS
		*/
   public function query(string $query, $values = null, bool $fetch_all = false)
   {
      //$bop->query("SELECT * FROM test WHERE column1 = ?", [1], false);
      try {
         $pdo = $this->pdo;
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //idk

         if ($values) //if values exist, bind to query
         {
            $query = $pdo->prepare($query); //prepare statement
            $query->execute($values); //execute and bind
         } else {
            $query = $pdo->query($query); //just execute plain
         }

         if ($fetch_all) {
            return $query->fetchAll(PDO::FETCH_ASSOC); //return array with result
         } else {
            return $query; //return just query for other things
         }
      } catch (PDOException $e) {
         echo $e;
         return false;
      }
   }

   public function number($n, $precision = 1)
   {
      if ($n < 900) {
         // 0 - 900
         $n_format = number_format($n, $precision);
         $suffix = '';
      } else if ($n < 900000) {
         // 0.9k-850k
         $n_format = number_format($n / 1000, $precision);
         $suffix = 'K';
      } else if ($n < 900000000) {
         // 0.9m-850m
         $n_format = number_format($n / 1000000, $precision);
         $suffix = 'M';
      } else if ($n < 900000000000) {
         // 0.9b-850b
         $n_format = number_format($n / 1000000000, $precision);
         $suffix = 'B';
      } else {
         // 0.9t+
         $n_format = number_format($n / 1000000000000, $precision);
         $suffix = 'T';
      }
      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
      if ($precision > 0) {
         $dotzero = '.' . str_repeat('0', $precision);
         $n_format = str_replace($dotzero, '', $n_format);
      }
      return $n_format . $suffix;
   }

   public function insert(string $table, array $values)
   {
      $pdo = $this->pdo;

      $binds = [];

      foreach ($values as $row) {
         array_push($binds, "?");
      }
      unset($row);

      $columns_array = [];
      $values_array = [];

      foreach ($values as $a => $b) {
         if ($b == NULL) {
            array_push($values_array, "NULL");
         } else {
            array_push($values_array, $b);
         }
         array_push($columns_array, $a);
      }

      $columns_string = "`" . implode("`, `", $columns_array) . "`";
      $values_string = "'" . implode("', '", $values_array) . "'";
      $binds_string = implode(", ", $binds);

      $query = $pdo->prepare("INSERT INTO {$table} ({$columns_string}) VALUES({$binds_string});");
      try {
         $query->execute($values_array);
         $id = $pdo->lastInsertId();
         $find = $this->query("SELECT {$columns_string} FROM {$table} WHERE id=?", [$id], false);
         $obj = (object) $find->fetchAll(PDO::FETCH_ASSOC);
         $obj->id = $id;

         return $obj;
      } catch (PDOException $e) {
         throw new Exception($e->getMessage());
      }
   }

   /*public function update (string $table, array $where) {
			$columns = array_keys($where);
			$values = array_values($where);
			var_dump($values);
			return $this->update_($table, $columns, $where);
		}*/

   public function update_(string $table, array $values, array $where)
   {
      $pdo = $this->pdo;

      $binds = [];

      unset($count);

      $values_sql = [];
      $where_sql = [];
      $where_values = [];
      $all_values = [];
      foreach ($values as $a => $b) {
         array_push($all_values, $b);
         array_push($values_sql, "`{$a}`=?");
      }
      unset($a, $b);

      foreach ($where as $a => $b) {
         array_push($all_values, $b);
         array_push($where_sql, "`{$a}`='{$b}'");
         array_push($binds, "`{$a}`=?");
      }

      $values_query = implode(", ", $values_sql);
      $where_query = implode(" AND ", $where_sql);
      $binds_query = implode(" AND ", $binds);

      $query = $pdo->prepare("UPDATE {$table} SET {$values_query} WHERE {$binds_query}");
      return $query->execute($all_values);
   }

   public function alert()
   {
      if (@$_SESSION['note']) {
         echo "<div class='alert bg-success'>$_SESSION[note]</div>";
         unset($_SESSION['note']);
      }
      if (@$_SESSION['error']) {
         echo "<div class='alert bg-danger'>$_SESSION[error]</div>";
         unset($_SESSION['error']);
      }
   }

   public function look_for(string $table, array $values, $limit = false)
   {
      $values_fixed = [];
      $true_values = [];
      $columns_fixed = [];
      $binds = [];
      $limit = (is_array($limit)) ? "LIMIT " . $limit[0] . "," . $limit[1] : "";

      foreach ($values as $a => $b) {
         array_push($columns_fixed, "`{$a}`");
         array_push($true_values, $b);
         array_push($values_fixed, "`{$a}`='{$b}'"); //prepare sql
         array_push($binds, "{$a}=?"); //add question marks to bind paramaters
      }
      $values_sql = implode(" AND ", $values_fixed);
      $columns_sql = implode(", ", $columns_fixed);
      $binds_sql = implode(" AND ", $binds);
      $query = "SELECT * FROM `{$table}` WHERE {$binds_sql};";
      $query_debug = "SELECT * FROM `{$table}` WHERE {$values_sql} {$limit};";
      //return [$query];
      $exec = $this->query($query, $true_values, false);
      return ($exec->rowCount() == 0) ? false : (object) $exec->fetchAll(PDO::FETCH_ASSOC)[0];
   }

   public function delete(string $table, array $values)
   {
      $values_fixed = [];
      $true_values = [];
      $columns_fixed = [];
      $binds = [];

      foreach ($values as $a => $b) {
         array_push($columns_fixed, "`{$a}`");
         array_push($true_values, $b);
         array_push($values_fixed, "`{$a}`='{$b}'"); //prepare sql
         array_push($binds, "{$a}=?"); //add question marks to bind paramaters
      }
      $values_sql = implode(" AND ", $values_fixed);
      $columns_sql = implode(", ", $columns_fixed);
      $binds_sql = implode(" AND ", $binds);
      $query = "DELETE FROM `{$table}` WHERE {$binds_sql};";
      $query_debug = "DELETE FROM `{$table}` WHERE {$values_sql};";
      //return [$query];
      $exec = $this->query($query, $true_values, false);
      return true;
   }


   public function footer()
   {
      require("$_SERVER[DOCUMENT_ROOT]/cema/footer.php");
   }

   public function auth()
   {
      if (isset($_SESSION['UserID'])) {
         return true;
      }
      return false;
   }

   public function user_exists($value)
   {
      if (is_numeric($value)) {
         $query = $this->query("SELECT COUNT(id) total FROM users WHERE id=?", [$value], true);
      } else {
         $query = $this->query("SELECT COUNT(id) as total FROM users WHERE name=?", [$value], true);
      }


      return ($query[0]['total'] > 0) ? true : false;
   }

   public function isBanned(int $uid)
   {
      if (!$this->user_exists($uid)) {
         return true;
      }
      $find = $this->query("SELECT * FROM punishment WHERE user=? AND ended=0 ORDER BY level ASC LIMIT 0,1", [$uid]);
      if ($find->rowCount() == 0) {
         return false;
      } else {
         return (object) $find->fetchAll()[0];
      }
   }

   public function timeAgo($ptime)
   {
      $etime = time() - $ptime;

      if ($etime < 1) {
         return 'Just now';
      }

      $a = array(
         365 * 24 * 60 * 60  =>  'year',
         30 * 24 * 60 * 60  =>  'month',
         24 * 60 * 60  =>  'day',
         60 * 60  =>  'hour',
         60  =>  'minute',
         1  =>  'second'
      );
      $a_plural = array(
         'year'   => 'years',
         'month'  => 'months',
         'day'    => 'days',
         'hour'   => 'hours',
         'minute' => 'minutes',
         'second' => 'seconds'
      );

      foreach ($a as $secs => $str) {
         $d = $etime / $secs;
         if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
         }
      }
   }

   public function bbCode($text)
   {
      // BBcode array
      $text = $this->filter($text);
      $find = array(
         '~\[b\](.*?)\[/b\]~s',
         '~\[i\](.*?)\[/i\]~s',
         '~\[u\](.*?)\[/u\]~s',

      );
      $replace = array(
         '<b>$1</b>',
         '<i>$1</i>',
         '<u>$1</u>',
      );

      $text = preg_replace($find, $replace, $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.bopimo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.gyazo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.twitter.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.discord.gg)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.google.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.reddit.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.imgur.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(www.youtube.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(bopimo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(gyazo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(twitter.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(discord.gg)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(google.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(reddit.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(imgur.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $text = preg_replace('|([\w\d]*)\s?(https?://(youtube.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
      $emojis = array(
         ":smile:",
         ":sad:",
         ":glasses:",
         ":racing:",
         ":mad:",
      );
      $emojis_r = array(
         "<i height='25' style='background-image:url(/cdn/emojis/smile-32.png);padding:5px 16px;margin:1px 2px;' title=':smile:'></i> ",
         "<i height='25' style='background-image:url(/cdn/emojis/sad-32.png);padding:5px 16px;margin:1px 2px;' title=':sad:'></i> ",
         "<i height='25' style='background-image:url(/cdn/emojis/glasses-32.png);padding:5px 16px;margin:1px 2px;' title=':glasses:'></i> ",
         "<i height='25' style='background-image:url(/cdn/emojis/racing-32.png);padding:5px 16px;margin:1px 2px;' title=':racing:'></i> ",
         "<i height='25' style='background-image:url(/cdn/emojis/mad-32.png);padding:5px 16px;margin:1px 2px;' title=':mad:'></i> ",
      );
      $text = str_replace($emojis, $emojis_r, $text);
      return $text;
   }

   public function get_user(int $id, $props = false)
   {
      //for getting all properties, user $bop->get_user(1);
      //for selecting columns, use $bop->get_user(1, ["username", "password"]);

      if (is_array($props)) //if it's an array then it's set
      {
         $props_query = implode(", ", $props); //combine props for query
         $query = $this->query("SELECT {$props_query} FROM users WHERE id=?;", [$id], false);
      } else {
         $query = $this->query("SELECT * FROM users WHERE id=?;", [$id], false);
      }

      if (!$this->user_exists($id) || !$query) {
         return false; //return false if user doesn't exist
      } else {
         $real = $query->fetchAll(PDO::FETCH_ASSOC);
         $real = $real[0];

         return (object) $real; //return array of user
      }
   }

   public function localUser()
   {
      return $this->query("SELECT * FROM users WHERE id = ?",  [$_SESSION['UserID']])->fetch();
   }

   public function trueUsername(int $uid)
   {
      if (!$this->user_exists($uid)) {
         return false;
      }
      $user = $this->get_user($uid, ["username", "id", 'hidden']);
      if ($user->hidden == 0 && !$this->isBanned($uid)) {
         return htmlentities($user->username);
      } else {
         return "[cemaware " . $user->id . "]";
      }
   }

   public function requireAuth()
   {
      if (!$this->auth()) {
         $_SESSION['error'] = "Must be logged in to perform action!";
         header('location: /login');
         die;
      }
   }
   public function requireGuest()
   {
      if ($this->auth()) {
         header('location: /dashboard');
         die;
      }
   }

   public function requireAdmin()
   {
      if (!$_SESSION['UserAdmin'] > 0) {
         $_SESSION['error'] = "Must be admin!";
         header('location: /dashboard');
         die;
      }
   }
   function set_csrf()
   {
      if (!isset($_SESSION["csrf"])) {
         $_SESSION["csrf"] = bin2hex(random_bytes(50));
      }
      echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
   }
   function is_csrf_valid()
   {
      if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
         return false;
      }
      if ($_SESSION['csrf'] != $_POST['csrf']) {
         return false;
      }
      return true;
   }

   public function local_info($props = false)
   {
      if ($this->auth()) {
         return $this->get_user($_SESSION['UserID'], $props);
      }
   }

   public function tooFast(int $limit = 35)
   {
      $user = $this->local_info(["flood"]);
      return (time() - $user->lastaction < $limit) ? true : false;
   }

   public function updateFast()
   {
      $user = $this->local_info(["id"]);
      $this->update_("users", ["flood" => time()], ["id" => $user->id]);
      return true;
   }

   public function isAdmin($userid = null)
   {
      if($userid) {
         if($this->query("SELECT admin FROM users WHERE id = ?", [$userid])->fetchColumn() > 0) {
            return true;
         }
         return false;
      }
      if ($this->local_info($_SESSION['UserID'])->admin > 0) {
         return true;
      }
      return false;
   }

   public function filter($text)
   {
      $filterWords = array(
         "nigger",
         "niglet",
         "faggot",
         "cunt",
         "dickwad",
         "furfag",
         "russian pig",
         "rigging",
         "fag",
         "swine",
         "cracker",
         "squaw",
         "nigga",
         "mong",
         "cock and ball torture",
         "cbt",
         "isaac hymer",
         "spacebuilder",
         "brick luke",
         "brick-luke",
         "brick-hill",
         "brickhill",
         "porn",
         "ejaculation",
         "cum",
         "sperm",
         "semen",
         "cowgirl",
         "blowjob",
         "doggly style",
         "pornography",
         "jacking off",
         "jerking off",
         "sex",
         "pornhub",
         "stripper",
         "prostitute",
         "xvideos",
         "e621",
         "rule34",
         ".onion",
         "wanker",
         "hitler",
         "rule34.xxx",
         "e621.net",
         "brick luke deez",
         "brick deez luke nutz",
         "mentally retarded",
         "mommy milker",
         "cock",
         // Roblox clones
         "brickplanet",
         "bopimo",
         "worldtobuild",
         "polytoria",
         "xxx",
         "bp",
         "suckmydick",
      );

      $filterCount = sizeof($filterWords);
      for ($i = 0; $i < $filterCount; $i++) {
         $text = preg_replace_callback('/\b' . $filterWords[$i] . '/i', function ($matches) {
            return str_repeat('*', strlen($matches[0]));
         }, $text);
      }
      return $text;
   }

   public function breaksFilter($text)
   {
      if ($text != $this->filter($text)) {
         return true;
      } else {
         return false;
      }
   }
   /**
    * This variable defines the levels and their XP caps to determine the users level onsite.
    *
    * @var array
    */
   public $levelCaps = array(
      'level_0' => 0,
      'level_1' => 100,
      'level_2' => 250,
      'level_3' => 425,
      'level_4' => 625,
      'level_5' => 875,
      'level_6' => 1225,
      'level_7' => 1725,
      'level_8' => 2450,
      'level_9' => 3350,
      'level_10' => 4600,
      'level_11' => 6350,
      'level_12' => 8850,
      'level_13' => 11750,
      'level_14' => 15150,
      'level_15' => 21150,
      'level_16' => 47600,
      'level_17' => 71400,
      'level_18' => 124950,
      'level_19' => 187425,
      'level_20' => 356107,

   );

   public function determineUserLevel($exp)
   {
      $level = 0;
      $i = $level;
      /*
      SING WITH ME
      SING FOR THE YEAR
      SING FOR THE LAUGHTER
      SING FOR THE TEAR
      SING WITH ME IF IT'S JUST FOR TODAY
      MAYBE TOMORROW THE GOOD LORD WILL TAKE YOU AWAY

      DREAM ON
      DREAM ON
      DREAM ON
      DREAM ON
      DREEEEEEEAM ON
      DREEEEEEEEAM ON
      DREAAAAAAAM ON
      DREAAAAAAMAMAMAMAMAMAON
      */
      while ($i <= count($this->levelCaps) - 1) {
         if ($exp >= $this->levelCaps['level_' . $i]) {
            $level = $i;
         }

         $i++;
      }

      return $level;
   }

   public function determinePercentageEXP($exp) {
      // if exp is 0 return 0 as to avoid divide by 0 exception
      if(in_array($exp, $this->levelCaps)) return 0;
      // determines the users level and then gets the caps for those levels
      $level = $this->determineUserLevel($exp);

      $nextLevelXP = $this->levelCaps['level_' . ($level + 1)];
      $currentLevelXP = $this->levelCaps['level_' . $level];
      
      /*
         100 divded by (the current level's xp cap subtract the next level's xp cap) divided by (the current level's xp subtract the user's xp) rounded to the nearest whole number
      */
      $percentage = round(100/(($currentLevelXP - $nextLevelXP)/($currentLevelXP - $exp)));
      return $percentage;
   }

   public function determineXPtoLevelUp($exp) {
      $level = $this->determineUserLevel($exp);

      $nextLevelXP = $this->levelCaps['level_' . ($level + 1)];
      $currentLevelXP = $this->levelCaps['level_' . $level];

      $xpToLevelUp = abs(($currentLevelXP - $nextLevelXP)) - ($exp - $currentLevelXP);
      return $xpToLevelUp;
   }

   public function incrementUserXP($exp) {

      $preUpdateLevel = $this->determineUserLevel($this->localUser()['exp']);

      /*
         //double xp weekend

         $exp = $exp*2;
      */

      $this->update_("users", ['exp' => $this->localUser()['exp'] + $exp], ['id' => $this->localUser()['id']]);

      $postUpdateLevel = $this->determineUserLevel($this->localUser()['exp']);

      // notify user they have leveled up
      if($preUpdateLevel != $postUpdateLevel) {
         $this->insert("notifications", [
            "to" => $this->localUser()['id'],
            "from" => 1,
            "msg" => "Congratulations! You have leveled up to level " . $postUpdateLevel . "!",
            "redirect" => '/home'
         ]);
      }

   }
}

$cema = new cema;
