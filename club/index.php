<?php
$name = 'Clubs';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

?>

<div class="row">
   <?php
   for ($x = 0; $x <= 11; $x++) {
   ?>
      <div class="col-4">
         <div class="card">
            <div class="card-body">
               <div class="row">
                  <div class="col-3 d-flex align-items-center">
                     <a href="/club/1" class="link-unstyled">
                        <img src="/cdn/img/rejected.png" alt="Guild" class="img-fluid rounded">
                     </a>
                  </div>
                  <div class="col">
                     <a href="/club/view/1" class="link-unstyled">
                        <h4>
                           Club Name
                        </h4>
                     </a>
                     <p class="small m-0">
                        4.41k members
                     </p>
                     <div class="small">
                        by
                        <a href="/profile/1">
                           eifo1
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php
   }
   ?>
</div>

<?php

$cema->footer();

?>