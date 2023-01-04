<?php
$name = '404';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

?>
<div class="row">
  <div class="col-6 mx-auto">
    <div class="card">
      <div class="card-body text-center">
        <i class="fad fa-times-circle text-danger" style="font-size:80px;"></i>
        <h4 class="font-weight-bold">Error 404</h4>
        <p class="mb-0">Requested page not found.</p>
      </div>
    </div>
  </div>
</div>
<?php

$cema->footer();

?>