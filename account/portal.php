<?php
$name = 'Portal';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireAuth();
?>

<h4 class="fw-bold">
   Developer Portal
</h4>
<div class="row">
   <div class="col-3">
      <div class="card">
         <ul class="nav flex-column nav-pills px-4 py-3">
            <li class="nav-item">
               <label for="create">
                  Create
               </label>
               <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#clothes">
                  <i class="fa fa-tshirt"></i>
                  Clothes
               </button>
            </li>
            <?php
            if ($cema->isAdmin()) {
            ?>
               <label for="admin">
                  Admin
               </label>
               <li class="nav-item">
                  <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#hat">
                     <i class="fa fa-hard-hat"></i>
                     Hat/Tool
                  </button>
               </li>
               <li class="nav-item">
                  <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#face">
                     <i class="fa fa-smile-wink"></i>
                     Face
                  </button>
               </li>
            <?php
            }
            ?>
         </ul>
      </div>
   </div>
   <div class="col-9">
      <div class="card">
         <div class="tab-content">
            <div class="tab-pane fade show active" id="clothes" role="tabpanel">
               <div class="card-body">
                  <div class="row">
                     <div class="col-8">
                        <h4 class="fw-bold text-center">
                           Create Shirt/Pants
                        </h4>
                        <form action="/api/portal/clothing.php" enctype="multipart/form-data" method="post">
                           <label for="name">
                              Name
                           </label>
                           <div class="input-group mb-2">
                              <input type="text" name="name" class="form-control" placeholder="Item Name">
                           </div>
                           <label for="name">
                              Price
                           </label>
                           <div class="input-group mb-2">
                              <input type="text" name="price" class="form-control" placeholder="Item Price (0 is free)">
                           </div>
                           <label for="name">
                              Type
                           </label>
                           <div class="input-group">
                              <select name="type" id="type" class="form-select">
                                 <option value="shirt" selected>
                                    Shirt
                                 </option>
                                 <option value="pants">
                                    Pants
                                 </option>
                              </select>
                           </div>
                           <label for="name">
                              Status
                           </label>
                           <div class="input-group">
                              <select name="status" id="type" class="form-select">
                                 <option value="onsale" selected>
                                    Onsale
                                 </option>
                                 <option value="offsale">
                                    Offsale
                                 </option>
                              </select>
                           </div>
                           <div class="mb-1"></div>
                           <label for="image">
                              Texture (.png)
                           </label>
                           <input type="file" name="image" id="image">
                           <button type="submit" name="submit" class="btn btn-theme btn-sm mt-1">
                              Submit Item for Review
                           </button>
                        </form>
                     </div>
                     <div class="col">
                        <h4 class="fw-bold text-center">
                           Shirt/Pants Template
                        </h4>
                        <img src="/cdn/img/template.png" alt="ShirtTemplate" class="img-fluid rounded">
                     </div>
                  </div>
               </div>
            </div>
            <?php
            if ($cema->isAdmin()) {
            ?>
               <div class="tab-pane fade" id="hat" role="tabpanel">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                           <h4 class="fw-bold text-center">
                              Create Hat or Tool
                           </h4>
                           <form action="/api/portal/clothing.php" enctype="multipart/form-data">
                              <label for="name">
                                 Name
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Name">
                              </div>
                              <label for="name">
                                 Price
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Price (0 is free)">
                              </div>
                              <label for="name">
                                 Stock
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Stock (0 for normal item)">
                              </div>
                              <label for="name">
                                 Type
                              </label>
                              <div class="input-group">
                                 <select name="type" id="type" class="form-select">
                                    <option value="hat" selected>
                                       Hat
                                    </option>
                                    <option value="tool">
                                       Tool
                                    </option>
                                 </select>
                              </div>
                              <label for="name">
                                 Status
                              </label>
                              <div class="input-group">
                                 <select name="status" id="type" class="form-select">
                                    <option value="onsale" selected>
                                       Onsale
                                    </option>
                                    <option value="offsale">
                                       Offsale
                                    </option>
                                 </select>
                              </div>
                              <div class="mb-1"></div>
                              <label for="image">
                                 Texture (.png)
                              </label>
                              <input type="file" name="image" id="image">
                              <label for="obj">
                                 Object (.obj)
                              </label>
                              <input type="file" name="obj" id="obj">
                              <button type="submit" name="submit" class="btn btn-theme btn-sm mt-1">
                                 Submit Item
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="face" role="tabpanel">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                           <h4 class="fw-bold text-center">
                              Create Face
                           </h4>
                           <form action="/api/portal/clothing.php" enctype="multipart/form-data">
                              <label for="name">
                                 Name
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Name">
                              </div>
                              <label for="name">
                                 Price
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Price (0 is free)">
                              </div>
                              <label for="name">
                                 Stock
                              </label>
                              <div class="input-group mb-2">
                                 <input type="text" name="name" class="form-control" placeholder="Item Stock (0 for normal item)">
                              </div>
                              <label for="name">
                                 Status
                              </label>
                              <div class="input-group">
                                 <select name="status" id="type" class="form-select">
                                    <option value="onsale" selected>
                                       Onsale
                                    </option>
                                    <option value="offsale">
                                       Offsale
                                    </option>
                                 </select>
                              </div>
                              <div class="mb-1"></div>
                              <label for="image">
                                 Texture (.png)
                              </label>
                              <input type="file" name="image" id="image">
                              <button type="submit" name="submit" class="btn btn-theme btn-sm mt-1">
                                 Submit Item
                              </button>
                           </form>
                        </div>
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