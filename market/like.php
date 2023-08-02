<?php
if(isset($_POST['submit'])) {
   require($_SERVER['DOCUMENT_ROOT'] . "/cema/cema.php");
   $id = intval($_POST['id']);
   $cema->requireAuth();

   $likes = $cema->query("SELECT * FROM item_likes WHERE item = ? AND user = ?", [$id, $cema->localUser()->id]);

   if(!$likes) {
      
   }
}