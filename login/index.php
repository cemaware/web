<?php
$name = 'Login';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireGuest();
?>
<div class="row">
   <div class="col-lg-6 col-md mx-auto">
      <h1>Login</h1>
      <style>
         .hidden {
            display: none;
         }
      </style>
      <div class="card">
         <div class="card-body">
            <form method="POST" id="main-form">
               <style>
                  #img img {
                     animation: fadeIn 0.5s;
                     opacity: 1;
                     margin-bottom: 5px;
                  }

                  @keyframes fadeIn {
                     from {
                        transform: scale(0);
                     }

                     to {
                        transform: scale(1);
                     }
                  }
               </style>
               <div id="img">
                  <img src="/cdn/img/icon.png" alt="" class="img-fluid bg-secondary rounded" width="75" style="margin-left: auto; margin-right: auto; display: block;" id="realImg">
               </div>
               <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                  <input type="text" class="form-control" placeholder="Username" aria-label="Username" name="username" aria-describedby="basic-addon1" id="username">
               </div>
               <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                  <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password" aria-describedby="basic-addon1" id="password">
               </div>
               <div class="d-grid gap-2">
                  <button class="btn btn-theme btn-block" name="submit" id="submit">
                     Submit
                  </button>
               </div>
            </form>
            <br>
            <span>
               Forgot your password?
               <a href="/login/support">
                  Click me!
               </a> 
            </span>
         </div>
      </div>
   </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999" id="errors">
</div>

<script src="login.js"></script>
<script>
   function wait(sec) {
      const date = Date.now();
      let currentDate = null;
      do {
         currentDate = Date.now();
      } while (currentDate - date < sec * 1000);
   }
</script>
<?php

$cema->footer();

?>