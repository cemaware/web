<?php
$name = 'Discord';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
?>
<div class="row">
   <div class="col"></div>
   <div class="col-lg-3 col-md-12">
      <iframe src="https://discord.com/widget?id=1037845484741603490&theme=dark&username=<?=$user->name?>" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts" style="width: 100%;"></iframe>
   </div>
   <div class="col-lg-4 col-md-12">
      <h4>
         Join our Discord Server
      </h4>
      <p>
         Want to chat with users, get sneak peaks on new updates and more? Join our Discord server. We have regular sneak peaks into events, updates, and more. Get to know Cemaware a little better.
      </p>
   </div>
   <div class="col"></div>
</div>
<?php

$cema->footer();

?>