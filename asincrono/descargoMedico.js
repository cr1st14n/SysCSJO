$("#btn-md_agregar_item").click(function (e) {
  e.preventDefault();
  $("#md-create_item_descargoMed").modal("show");
});
$("#form-createItemDesMed").submit(function (e) {
  e.preventDefault();
  console.log();
  $.ajax({
    type: "post",
    url: "descargosMedicos/desMed",
    data: $("#form-createItemDesMed").serialize(),
    // dataType: "",
    success: function (response) {
      if (response) {
        $("#md-create_item_descargoMed").modal("hide");
        $("#form-createItemDesMed").trigger("reset");
        listItemsDesMed();
      } else {
        notif("2", "Error. intentelo nuevamente");
      }
    },
  });
});
function listItemsDesMed() {
    $.ajax({
        type: "get",
        url: "descargosMedicos/desMed",
        // data: "data",
        // dataType: "dataType",
        success: function (response) {
            var html=response.map(function (e) { 
                return a=`
                <tr>
                    <td>${e.id}</td>
                    <td valign="middle">${e.dmi_nombre}</td>
                    <td><span class="label label-success">${e.dmi_tipo}</span></td>
                    <td>
                        <span class="tooltip-area">
                            <a href="javascript:void(0)" class="btn btn-default btn-sm" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-default btn-sm" title="" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                        </span>
                    </td>
                </tr>`;
             }).join(' ');
             $('#list-items1').html(html);
        }
    });
  }