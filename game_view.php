<?php
$name = 'games';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");

?>

<div class="row">
   <div class="col-8 mx-auto">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-5">
                  <img src="https://images.unsplash.com/photo-1600456899121-68eda5705257?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8M3x8fGVufDB8fHx8&w=1000&q=80" alt="img" class="img-fluid rounded">
               </div>
               <div class="col-7">
                  <div class="float-end">
                     <div class="dropdown">
                        <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="far fa-ellipsis-v text-muted"></i>
                        </a>
                        <ul class="dropdown-menu bg-secondary">
                           <li>
                              <a href="/copylock" class="dropdown-item">
                                 <i class="fa fa-pencil"></i>
                                 Edit
                              </a>
                           </li>
                           <li>
                              <a href="/copylock" class="dropdown-item">
                                 <i class="fa fa-clone"></i>
                                 Copy
                              </a>
                           </li>
                           <div class="dropdown-divider"></div>
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
                  <h4 class="fw-bold">
                     Game Title
                  </h4>
                  <div class="align-items-center d-flex">
                     <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" alt="img" width="25" class="bg-secondary rounded">
                     <a href="/profile/1" class="ms-2 fs-6" style="color: rgba(0,150,255, 1);">
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
                  <p class="lines fs-6 m-6">
                     Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque itaque numquam nostrum amet accusamus iste ali ... <a href="#" class="fw-bold" style="color: rgba(0,150,255, 1);">Read More</a>
                  </p>
                  <div style="height: 3px;"></div>
                  <button class="btn btn-success btn-sm text-align-center w-100 fs-5 fw-semibold">
                     <i class="fa fa-play"></i> Play
                  </button>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="d-flex justify-content-around text-center">
               <div>
                  <div>
                     25
                  </div>
                  <label for="copies" class="small text-muted text-uppercase fw-bold">
                     PLAYERS
                  </label>
               </div>
               <div>
                  <div>
                     November 13th, 2022
                  </div>
                  <label for="date" class="small text-muted text-uppercase fw-bold">
                     Created On
                  </label>
               </div>
               <div>
                  <div>
                     25 seconds ago
                  </div>
                  <label for="updated" class="small text-muted text-uppercase fw-bold">
                     UPDATED
                  </label>
               </div>
            </div>
         </div>
      </div>
      <div class="row px-2">
         <div class="col-6">
            <h4 class="fw-bold">
               Comments
            </h4>
            <div class="card mx-0">
               <div class="card-body">
                  <div class="row align-items-center mb-2">
                     <div class="col-3">
                        <img src="/cdn/img/avatar/thumbnail/6512bd43d9caa6e02c990b0a82652dca.png" class="img-fluid rounded bg-secondary">
                     </div>
                     <div class="col">
                        <a href="/profile/1" class="link-unstyled">
                           <h4 class="fs-5 fw-bold">dawg</h4>
                        </a>
                        <div class="text-secondary fw-bold" style="font-size: 12px;">
                           <i class="fad fa-clock"></i>
                           Posted:
                           12 minutes ago
                        </div>
                     </div>
                     <div class="col-1">
                        <div class="float-end">
                           <div class="dropdown">
                              <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="far fa-ellipsis-v text-muted"></i>
                              </a>
                              <ul class="dropdown-menu">
                                 <li>
                                    <a href="/report/wall/1 class=" dropdown-item">
                                       <div class="text-danger">
                                          <i class="fad fa-flag"></i>
                                          Report
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <p>
                     i have legitmately never played a worse game
                  </p>
               </div>
            </div>
            <div class="card mx-0">
               <div class="card-body">
                  <div class="row align-items-center mb-2">
                     <div class="col-3">
                        <img src="/cdn/img/avatar/thumbnail/1679091c5a880faf6fb5e6087eb1b2dc.png" class="img-fluid rounded bg-secondary">
                     </div>
                     <div class="col">
                        <a href="/profile/1" class="link-unstyled">
                           <h4 class="fs-5 fw-bold">eifo1</h4>
                        </a>
                        <div class="text-secondary fw-bold" style="font-size: 12px;">
                           <i class="fad fa-clock"></i>
                           Posted:
                           10 hours ago
                        </div>
                     </div>
                     <div class="col-1">
                        <div class="float-end">
                           <div class="dropdown">
                              <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="far fa-ellipsis-v text-muted"></i>
                              </a>
                              <ul class="dropdown-menu">
                                 <li>
                                    <a href="/report/wall/1 class=" dropdown-item">
                                       <div class="text-danger">
                                          <i class="fad fa-flag"></i>
                                          Report
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <p>
                     this game sucks donkey balls
                  </p>
               </div>
            </div>
            <div class="card mx-0">
               <div class="card-body">
                  <div class="row align-items-center mb-2">
                     <div class="col-3">
                        <img src="/cdn/img/avatar/thumbnail/1f0e3dad99908345f7439f8ffabdffc4.png" class="img-fluid rounded bg-secondary">
                     </div>
                     <div class="col">
                        <a href="/profile/1" class="link-unstyled">
                           <h4 class="fs-5 fw-bold">pixation</h4>
                        </a>
                        <div class="text-secondary fw-bold" style="font-size: 12px;">
                           <i class="fad fa-clock"></i>
                           Posted:
                           11 hours ago
                        </div>
                     </div>
                     <div class="col-1">
                        <div class="float-end">
                           <div class="dropdown">
                              <a class="link-unstyled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="far fa-ellipsis-v text-muted"></i>
                              </a>
                              <ul class="dropdown-menu">
                                 <li>
                                    <a href="/report/wall/1 class=" dropdown-item">
                                       <div class="text-danger">
                                          <i class="fad fa-flag"></i>
                                          Report
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <p>
                     wtf is wrong with this game bro
                  </p>
               </div>
            </div>
         </div>
         <div class="col-6">
            <h4 class="fw-bold">
               Recommended
            </h4>
            <div class="row">
               <?php
               for ($i = 1; $i < 5; $i++) {
               ?>
                  <div class="col-6">
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
   </div>
</div>

<?php

$cema->footer();

?>