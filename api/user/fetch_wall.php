<?php

include($_SERVER['DOCUMENT_ROOT'] . '/cema/cema.php');

$page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : "1";

$user = (isset($_GET['user'])) ? intval($_GET['user']) : die("no user");   



