<?php
$name = 'games';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

?>
<div class="row">
   <div class="col-8 mx-auto">
      <h4 class="fw-bold px-2">
         Game Showcase
      </h4>
      <div class="card card-body">
         <div class="row">
            <div class="col-5">
               <div class="img-container">
                  <img src="https://images.unsplash.com/photo-1600456899121-68eda5705257?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8M3x8fGVufDB8fHx8&w=1000&q=80" alt="test" srcset="" class="img-fluid rounded">
                  <div class="top-left badge" style="background-color: #198754;">
                     <i class="fa fa-users"></i>
                     <?= rand(1, 90) ?> playing
                  </div>
               </div>
            </div>
            <div class="col-7">
               <div class="float-end">
                  <div class="dropdown">
                     <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-ellipsis-v text-muted"></i>
                     </a>
                     <ul class="dropdown-menu bg-secondary">
                        <li>
                           <a href="/report/feed/<?= $post['id'] ?>" class="dropdown-item">
                              <div class="text-danger">
                                 <i class="fad fa-flag"></i>
                                 Report
                              </div>
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
               <h4 class="fw-bold align-items-center d-flex">
                  Game Title
               </h4>
               <div class="align-items-center d-flex">
                  <img src="/cdn/img/avatar/thumbnail/1f0e3dad99908345f7439f8ffabdffc4.png" alt="img" width="25" class="bg-secondary rounded">
                  <a href="/profile/1" class="ms-2 fs-6">
                     Username
                  </a>
               </div>
               <style>
                  .lines {
                     display: -webkit-box;
                     max-width: 100%;
                     -webkit-line-clamp: 3;
                     -webkit-box-orient: vertical;
                     overflow: hidden;
                  }
               </style>
               <p class="lines fs-6">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque itaque numquam nostrum amet accusamus iste alias molestiae maiores, reiciendis eligendi sint excepturi ipsum tempora, blanditiis perspiciatis quasi, error porro sapiente.
               </p>
               <div style="height: 3px;"></div>
               <button class="btn btn-success btn-sm text-align-center w-100 fs-5 fw-semibold">
                  <i class="fa fa-play"></i> Play
               </button>
            </div>
         </div>
      </div>
      <h4 class="px-2 fw-bold">
         Games
      </h4>
      <div class="px-2">
         <input type="text" placeholder="Search..." class="w-100">
      </div>
      <div class="row">
         <?php
         for ($i = 1; $i < 12; $i++) {
         ?>
            <div class="col-3">
               <div class="card">
                  <style>
                     .img-container {
                        position: relative;
                        text-align: center;
                        color: white;
                     }

                     .top-left {
                        position: absolute;
                        top: 8px;
                        left: 8px;
                     }
                  </style>
                  <div class="img-container">
                     <img src="https://cdn.wallpapersafari.com/17/55/jwfnr2.jpg" alt="gray" class="rounded-top img-fluid">
                     <div class="top-left badge" style="background-color: #198754;">
                        <i class="fa fa-users"></i>
                        <?= rand(1, 30) ?> playing
                     </div>
                  </div>
                  <div class="card-body px-2 py-2">
                     Game Name
                     <div class="align-items-center d-flex">
                        <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" alt="img" width="25" class="bg-secondary rounded">
                        <a href="/profile/1" class="ms-2 fs-6">
                           Username
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         <?php
         }
         ?>
      </div>
   </div>
</div>
<?php

$cema->footer();

?>