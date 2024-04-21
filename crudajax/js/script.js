//get pagination
function pagination(totalpages, currentpage) {
  var pagelist = "";
  if (totalpages > 1) {
    currentpage = parseInt(currentpage);
    pagelist += `<ul class="pagination justify-content-center">`;
    const prevClass = currentpage == 1 ? "disabled" : "";
    pagelist += `<li class="page-item ${prevClass}"><a class="page-link" href="#" data-page="${
      currentpage - 1
    }">Previous</a></li>`;
    for (let p = 1; p <= totalpages; p++) {
      const activeClass = currentpage == p ? "active" : "";
      pagelist += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
    }
    const nextClass = currentpage == totalpages ? "disabled" : "";
    pagelist += `<li class="page-item ${nextClass}"><a class="page-link" href="#" data-page="${
      currentpage + 1
    }" >Next</a></li>`;
    pagelist += `</ul>`;
  }
  $("#pagination").html(pagelist);
}

//get player row

function getplayersrow(player) {
  var playerRow = "";
  if (player) {
    playerRow = ` <tr>
        <td class="align-middle"><img src="uploads/${player.player_image}" class="img-thumbnail rounded float-left"></td>
        <td class="align-middle">${player.player_name}</td>
        <td class="align-middle">${player.player_email}</td>
        <td class="align-middle">${player.player_phone}</td>
        <td class="align-middle">
        <a href="#"  data-id="${player.id}" class="btn btn-success mr-3 profile" data-toggle="modal" data-target="#userViewModal" title="Prfile"><i class="fa fa-address-card-o" aria-hidden="true"></i></a>
        <a href="#" data-id="${player.id}"  class="btn btn-warning mr-3 edituser" data-toggle="modal" data-target="#userModal" title="Edit"><i class="fa fa-pencil-square-o fa-lg"></i></a>
        <a href="#" data-id="${player.id}" class="btn btn-danger deleteuser" data-userid="14" title="Delete"><i class="fa fa-trash-o fa-lg"></i></a>
        </td>
        </tr>`;
  }
  return playerRow;
}

//getplayer list

function getplayers() {
  var pageno = $("#currentpage").val();
  $.ajax({
    url: "ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageno, action: "getusers" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      if (rows.players) {
        var playerslist = "";
        $.each(rows.players, function (index, player) {
          playerslist += getplayersrow(player);
        });
        $("#userstable tbody").html(playerslist);
        let totalplayers = rows.count;
        let totalpages = Math.ceil(parseInt(totalplayers) / 4);
        const currentpage = $("#currentpage").val();
        pagination(totalpages, currentpage);
        $("#overlay").fadeOut();
      }
    },
    error: function () {
      console.log("something went wrong");
    },
  });
}

$(document).ready(function () {
  //add and edit user

  $(document).on("submit", "#addform", function (event) {
    event.preventDefault();
    $.ajax({
      url: "ajax.php",
      type: "post",
      dataType: "json",
      data: new FormData(this),
      processData: false, //kisi value ko as it is send krna hai tab iska use krte hai
      contentType: false, //mutipart form data ko kam krne ke liye is efalse krte hai
      beforeSend: function () {
        console.log("wait............");
        $("#overlay").fadeIn();
      },

      success: function (response) {
        console.log(response);
        if (response) {
          $("#userModal").modal("hide");
          $("#addform")[0].reset();
          getplayers();
          $("#overlay").fadeOut();
        }
      },
      error: function () {
        console.log("oops! something went wrong");
      },
    }); //ajax end here
  }); //document submit ed here

  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    var $this=$(this);
    const pagenum = $(this).data("page");
    $("#currentpage").val(pagenum);
    getplayers();
$this.parent().siblings().removeClass("active");
$this.parent().addClass("active");
  });

  //load players
  getplayers();
}); //document ready  end here
