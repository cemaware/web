    <!-- end container -->
    </div>
    </div>
    </div>
    </div>
    </div>
    <br>
    <footer>
       <!--
      <div class="row">
        <div class="col-12">
          <div class="text-center">
            <?php
            $quoteList = array(
               "'you only learn when you do it yourself' -dawg",
               "powering your mother",
               "'I know not with what weapons World War III will be fought, but World War IV will be fought with sticks and stones' -Albert Einstein",
               "cemaware is not affiliated with roblox",
               "'its not a crime if you loved doing it' -GRIZLAR"
            );
            ?>
            <img src="/cdn/img/logo.png" alt="logo" height="23.5">
            <p>
              <?= $quoteList[mt_rand(0, count($quoteList) - 1)]; ?>
            </p>
          </div>
        </div>
      </div>
          -->
       <div class="container">
          <div class="row">
             <div class="col-2"></div>
             <div class="col-4">
                <div class="row">
                   <div class="col-6 p-0">
                      <span class="d-block">
                         Community
                      </span>
                      <a href="/forum" class="fw-light text-white d-block">
                         <i class="far fa-question text-theme"></i>
                         Forum
                      </a>
                      <a href="/forum" class="fw-light text-white d-block">
                         <i class="far fa-rss text-theme"></i>
                         Blog
                      </a>
                      <a href="/forum" class="fw-light text-white d-block">
                         <i class="far fa-envelope text-theme"></i>
                         Messages
                      </a>
                      <a href="/discord" class="fw-light text-white d-block">
                         <i class="fab fa-discord text-theme"></i>
                         Discord
                      </a>
                   </div>
                   <div class="col-6 p-0">
                      <span class="d-block">
                         Extra
                      </span>
                      <a href="/tos" class="fw-light text-white d-block">
                         <i class="fas fa-book text-theme"></i>
                         Terms of Service
                      </a>
                      <a href="/privacy" class="fw-light text-white d-block">
                         <i class="fas fa-print text-theme"></i>
                         Privacy Policy
                      </a>
                      <a href="/events" class="fw-light text-white d-block">
                         <i class="far fa-users text-theme"></i>
                         Events
                      </a>
                      <a href="/api/documentation" class="fw-light text-white d-block">
                         <i class="fa fa-code text-theme"></i>
                         API Docs
                      </a>
                   </div>
                </div>
             </div>
             <div class="col-4 align-self-center">
                <div class="">
                   Copyright &copy;
                   Cemaware 2022
                   <br>
                   All Rights Reserved
                </div>
             </div>
          </div>
       </div>
    </footer>
    </div>
    <script>
       $(function() {
          $('.hover-dropdown').hover(function() {
                $('.dropdown-menu', this).addClass('show');
             },
             function() {
                $('.dropdown-menu', this).removeClass('show');
             });
       });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    </body>

    </html>