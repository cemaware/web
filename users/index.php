<?php
$name = 'Users';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
?>
<input type="text" class="search" placeholder="Search...">
<div class="row" id="results">

</div>
<div id="pageButtons">
  <button class="btn btn-theme btn-sm" style="float:left;display:inline;width:auto;display:none;" id="previousPage" class="shop-search-button"><i class="fas fa-chevron-left"></i> Previous Page</button>
  <button class="btn btn-theme btn-sm" style="float: right; width: auto;" id="nextPage" class="shop-search-button">Next Page <i class="fas fa-chevron-right"></i></button>
</div>

<script>
  $(function() {

    currentPage = 1;
    globalPerPage = 20;
    $(".search").keypress(function(trigger) {
      if (trigger.which == 13) {
        console.log($(".search").val());
        search($(".search").val(), 1);
      }
    });

    $("#nextPage").click(function() {
      search(globalQuery, parseInt(currentPage) + 1)
    });
    $("#previousPage").click(function() {
      search(globalQuery, parseInt(currentPage) - 1)
    });


    search("", 1);
  });

  function pageButtons(currentPage, total, status) {
    if (status == "ok") {
      totalPages = Math.ceil(total / globalPerPage);
      console.log(totalPages);
      console.log(currentPage + "/" + totalPages);
      if (currentPage == 1 && currentPage == totalPages) {
        $("#nextPage").hide();
        $("#previousPage").hide();
      } else if (currentPage == 1) {
        $("#nextPage").show();
        $("#previousPage").hide();
      } else if (currentPage == totalPages) {
        $("#previousPage").show();
        $("#nextPage").hide();
      } else {
        $("#previousPage").show();
        $("#nextPage").show();
      }
      $("#page").html(currentPage);
      $("#totalPages").html(totalPages);
    } else {
      $("#nextPage").hide();
      $("#previousPage").hide();
      $("#page").html("0");
      $("#totalPages").html("0");
    }
    $("#total").html(total);
  }

  function search(query, page) {
    currentPage = page;
    globalQuery = query;
    response("/api/users/users.php?query=" + query + "&page=" + page).success(function(data) {
      $("#results").html("");
      $.each(data.data, function(index, user) {
        appendUser(user);
      });
      pageButtons(currentPage, data.total, data.status);
    }).fail(function(error) {
      $("#results").html("<div class='centered' style='font-weight:600;color:#888;'>" + error + "</div>");
      pageButtons(currentPage, 0, "error");
    }).get();
  }

  function appendUser(user) {
    var online;
    console.log(user);
    if (user.online != false) {
      online = "<span class='online'>Online</span>";
    } else {
      online = "<span class='offline''>Offline</span>";
    }
    $("#results").append(
      "\
      <div class='col-lg-3 col-md p-0'>\
        <div class='card'>\
          <div class='card-body'>\
            <div class='row'>\
              <div class='col-4'>\
                <a class='users-link' href='/profile/" + user.id + "'>\
                  <img src='/cdn/img/avatar/thumbnail/" + user.avatar_link + ".png' alt='Avatar' class='img-fluid bg-secondary rounded'>\
                </a>\
              </div>\
              <div class='col-8 users'>\
                <a class='users-link' href='/profile/" + user.id + "'>" + user.name + "</a>\
                " + online + "\
                <p class='users-label'>\
                " + user.bio + "\
                </p>\
              </div>\
            </div>\
          </div>\
        </div>\
      </div>\
      "
    )
  }
</script>
<?php



$cema->footer();

?>