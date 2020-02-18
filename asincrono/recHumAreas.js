$('#form-createArea').on('submit', function (e) {
  e.preventDefault();
  createArea();
});
function ShowInfArea(id) {
  var data={id:id};
  $('#id_inf_area').val(id);
  $.get("/C.S.J.O.bo/adm/area/show", data,
    function (data) {
     console.log(data);
     var tipEmp=data.contratos.map(function (e) { return `
     Personal - ${e.uc_tipoContrato} : <strong> ${e.total} </strong><br>
     ` }).join(" ");
     var datosEMP = `Nomnbre del area: <strong>${data.nombre}</strong><br>
     Encargado: <strong>${data.area_encargado}</strong><br>
     # personal en el area: <strong>${data.cantidaPersonal}</strong> <hr>
     ${tipEmp}
     `;
     document.getElementById("datosEmp").innerHTML = datosEMP;

     var html2 = data.personal.map(function (e) { 
     return `
      * ${e.usu_nombre} ${e.usu_appaterno}. Contrato: ${e.uc_tipoContrato}<br>
     `;
      }).join(" ") 
  document.getElementById("datosInst").innerHTML = html2;
    }
  );
 
  var html2 = `   Nombre de personal en el area<br>
                  1 personal<br>
                  2 personal<br>
                  3 personal<br>
                  4 personal<br>
                  5 personal<br>
                  `;
  document.getElementById("datosInst").innerHTML = html2;

  $("#md-InfArea")
    .modal("show");
}
function listAreas() {
$.get("/C.S.J.O.bo/adm/area/list",
  function (data) {
   console.log(data) 
   list=data.map(function (e) { 
     return `
     <tr>
        <td>COD-${e.id}</td>
        <td valign="middle">${e.nombre}</td>
        <td align="left"> <button onClick="areaActualizarUsuEncargado(${e.id})" class="btn btn-theme-inverse btn-transparent btn-sm title="Asignar nuevo encargado" ><i class="fa fa-user"></i></button> ${e.usu_appaterno} ${e.usu_nombre} </td>
        <td><span class="label label-success">${e.cant_usuarios} </span></td>
        <td>
            <span class="tooltip-area">
            <button onclick="ShowInfArea(${e.id})" class="btn btn-default btn-sm" title="Edit"><i class="fa fa-bullseye"></i></button>
            <button class="btn btn-default btn-sm" onClick="deleteArea(${e.id})" title="Delete"><i class="fa fa-trash-o"></i></button>
            </span>
        </td>
      </tr>
     `;
    }).join(" ");
    $('#tableListAreas').html(list);
  }
);  
  }

  function formCreateArea() {
    $('#form-createArea').trigger('reset');
    $.get("Areas/listUsuarios",
      function (data, textStatus, jqXHR) {
       console.log(data)
       var listUsu=data.map(function (e) {
         return `
         <option value="${e.id}">${e.usu_nombre} ${e.usu_appaterno} -  ${e.di_profecion}</option>
         `;
         }).join(' ');
         $('#area_usuEncargado').html(listUsu); 
      }
    );
    $('#md-createArea').modal('show');

    }
  function createArea() {
   var dat={
			_token: $('meta[name=csrf-token]').attr('content'),
     nombre:$('#area_nombre').val(),
     descripcion:$('#area_descripcion').val(),
     usuEncargado:$('#area_usuEncargado').val(),
     area_tipo:$('#area_tipo').val(),
   }
   $.post("Areas/create", dat,
     function (data) {
       console.log(data);
       if (data) {
         $('#btn_formCreate_close').click();
         listAreas();
         notif('1','Area registrada');
        } else {
          notif('2','Error de registro, Vuelva a intentarlo!');
       }
     }
   );
  }
  function showModalAreaAgreUsu() {
    var data={id:$('#id_inf_area').val()}
    $.get("Areas/listUsuAreaAgregar",data, 
      function (data) {
       console.log(data); 
        var listHtml=data.map(function (e) {
          return`
          <tr>
              <td>${e.usu_nombre} ${e.usu_appaterno} </td>
              <td>${e.uc_area} </td>
              <td><button class="btn btn-theme-inverse btn-sm " onClick="cambioAreaUsuario(${e.cod_usu})">Agregar </button></td>
          </tr>
          `;
          }).join(' ');
          $('#area_list_pers_agregar').html(listHtml);
      }
    );
    $('#md_area_argregarPersonal').modal('show');
    }
    function cambioAreaUsuario(id) {
      console.log(id);
      var data={
			  _token: $('meta[name=csrf-token]').attr('content'),
        id:id,area:$('#id_inf_area').val()}
      $.post("Areas/usuAreaCambio", data,
        function (data) {
          if (data==1) {
            notif('1','Usuario Agregado');
            showModalAreaAgreUsu();
            listAreas();
            ShowInfArea($('#id_inf_area').val());
          } else {
            notif('2','Error, Vueva a intentarlo!')
          }
        }
      );
      }
    function areaActualizarUsuEncargado(id) {
      console.log(id);
      var data={id:id};
      $.getJSON("Areas/listUsuAreaCambUsu", data,
        function (data) {
          console.log(data);
          var html=data['usu'].map(function (e) { 
            return `
            <tr>
                <td>${e.usu_nombre} ${e.usu_appaterno} </td>
                <td>${e.uc_tipoContrato} </td>
                <td><button class="btn btn-theme-inverse btn-sm " onClick="areaActUsuEncargado(${e.id})">Agregar </button></td>
            </tr>
            `;
           }).join(" ");
           $('#id_area_actualizarEncargado').val("");
           $('#id_area_actualizarEncargado').val(data.area);
           $('#md_area_asignarUsuEncargado').modal('show');
           $('#list_asignarUsuEncargado').html(html);
        }
      );
    }

    function areaActUsuEncargado(usu) {
      var id_usu=usu;
      var id_area=$('#id_area_actualizarEncargado').val();
      if (id_usu > 0 && id_area > 0) {
      var data={_token: $('meta[name=csrf-token]').attr('content'),id_usu:id_usu,id_area:id_area};
        $.post("Areas/updateUsuEncargado", data,
          function (data) {
           console.log(data);
           if (data==1) {
             notif('1','Encaragado actualizdo!');
             $('#btn-close_md_area_asignarUsuEncargado').click();
             listAreas();
           } else {
             
           } 
          }
        );
      } else {
        console.log('fail')
      }
      }

      function deleteArea(id_area) {
        console.log(id_area)
        }
