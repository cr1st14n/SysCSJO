//var frase = "Son tres mil trescientos treinta y tres con nueve";
//frase3 = frase.replace(/[ ]/gi,'.');
//alert(frase3);
$(function(){
	$('#HCLpaciente').on('keyup',buscarHCLpaciente);
	$('#NOMBRESpaciente').on('keyup',buscarNOMBRESpaciente);
});
function listPacientes(data) {
    var html=data.map(function (elem,index) {
        var pre1="";
        if (elem.hclEst >0 ){
            pre1= `<button class="btn btn-danger" onclick="showEstPres(${elem.hclEst})"><i class="fa fa-ban"></i></button>`;
        } else {
            pre1= `<button class="btn btn-default" ><i class="fa fa-check"></i></button>`;
        }
        return(`<tr>
                      <td>${elem.pa_hcl}</td>
                      <td>${elem.pa_ci}</td>
                      <td>${elem.pa_nombre}</td>
                      <td>${elem.pa_appaterno} / ${elem.pa_apmaterno}</td>
                      <td>${pre1}</td>
                     <td>
                        <span class="tooltip-area">
                        <button name="${elem.pa_id}" onclick="rutaAtender(this.name)" class="btn btn-default btn-sm" title="Atender"><i class="fa  fa-plus-square"></i></button>
                        <a name="${elem.pa_id}" onclick="rutaprintHCL(this.name)" class="btn btn-default btn-sm" target="_blank" title="Inprimir"><i class="glyphicon glyphicon-print"></i></a>
                        <button name="${elem.pa_id}" onclick="rutaEditPaciente(this.name)" class="btn btn-default btn-sm" title="Editar"><i class="fa fa-pencil"></i></i></button>
                        <button name="${elem.pa_id}" onclick="rutaDestroyPaHcl(this.name)" class="btn btn-default btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                        <button name="${elem.pa_id}" onclick="rutaPrestarHCL(${elem.pa_id},${elem.pa_hcl})" class="btn btn-default btn-sm" title="Presatar HCl"><i class="fa fa-puzzle-piece"></i></button>
                        </span>
                    </td>  
                </tr>`);
    }).join(" ");
    document.getElementById('resulBusqPacientes').innerHTML=html;

}
function rutaPrestarHCL(id, hcl) {
// ----funcion para modal presatamos de hcl
    $("#md-HCLprestamo").attr('class','modal fade').addClass('md-flipHor').modal('show');
    document.getElementById('codHCL').innerHTML= hcl;
    document.getElementById('presIDHCL').innerHTML= id;
    document.getElementById('areaEntrega').selectedIndex="Contabilidad";

}
function registrarPrestamo() {
    var id = document.getElementById('presIDHCL').innerHTML;
    var usuEntrega = document.getElementById('usuEntrega').value;
    var area = document.getElementById('areaEntrega').value;
    if (usuEntrega == null || usuEntrega.length == 0 || /^\s+$/.test(usuEntrega)){
        var data = new Array();
        data.verticalEdge='right';
        data.horizontalEdge='top';
        data.theme = "danger";
        setTimeout(function () { $.notific8('Error, Complete el formulario.',data) });
    }else{

        var url = "/C.S.J.O.bo/Recepcion/PresHCL/create";
        var data= { '_token': $('meta[name=csrf-token]').attr('content'),
                    'id': id,
                    'usuEntrega': usuEntrega ,
                    'areaPrestamo':area};
        $.post(url,data).done(function (prestamo) {
            console.log(prestamo);
            if (prestamo == "1"){
                notif('1','Prestamo Registrado');
                $("#md-HCLprestamo").modal('hide');
                document.getElementById('usuEntrega').value = "";
                document.getElementById('presIDHCL').innerHTML = "";
                document.getElementById('resulBusqPacientes').innerHTML = "";

            }else{
                var data = new Array();
                data.verticalEdge='right';
                data.horizontalEdge='top';
                data.theme = "danger";
                setTimeout(function () { $.notific8('Error, vuelva a intentarlo.',data) });
            }
        }).fail(function () {
            var data = new Array();
            data.verticalEdge='right';
            data.horizontalEdge='top';
            data.theme = "danger";
            setTimeout(function () { $.notific8('Error en el servidor, vuelva a intentarlo.',data) });
        });
       /* var data = new Array();
        data.verticalEdge='right';
        data.horizontalEdge='top';
        data.theme = "success";
        setTimeout(function () { $.notific8('Exito, Prestamos registrado.',data) });*/
    }

    // $("#md-HCLprestamo").attr('class','modal fade').addClass('md-flipHor').modal('show');


}
function rutaAtender(x)
{
    //window.alert(x);
    document.location.href="/C.S.J.O.bo/Recepcion/atencion/index/"+x+"";
}
function rutaprintHCL(x)
{
    //window.alert(x);
    var ventana = window.open('', 'PRINT', 'height=700,width=700');
       ventana.location.href="/C.S.J.O.bo/Recepcion/paciente/PrintHCL1/"+x+"";
    //document.location.href="/C.S.J.O.bo/Recepcion/paciente/PrintHCL1/"+x+"";
}
function rutaEditPaciente(x)
{
    //indow.alert(x);
    document.location.href="/C.S.J.O.bo/Recepcion/paciente/edit/"+x+"";
}
function rutaDestroyPaHcl(x)
{
    //window.alert(x);
    document.location.href="/C.S.J.O.bo/Recepcion/paciente/delete/"+x+"";
}
function buscarHCLpaciente()
{
    var hcl = $(this).val();
    if (hcl == "") {
        console.log("sin nuemro");
            $('#resulBusqPacientes').html("");

    }else{
        $.get('/C.S.J.O.bo/api/buscarPacienteHCL/'+hcl+'',function(paciente){
            listPacientes(paciente);
            /*$('#resulBusqPacientes').html("");
            for (var i = 0; i <= paciente.length - 1; i++) {
                console.log(paciente[i]);
                var tr = `<tr>
                      <td>`+paciente[i].pa_hcl+`</td>
                      <td>`+paciente[i].pa_ci+`</td>
                      <td>`+paciente[i].pa_nombre+`</td>
                      <td>`+paciente[i].pa_appaterno+` / `+paciente[i].pa_apmaterno+`</td>
                     <td>
                        <span class="tooltip-area">
                        <button name="`+paciente[i].pa_id+`" onclick="rutaAtender(this.name)" class="btn btn-default btn-sm" title="Atender"><i class="fa  fa-plus-square"></i></button>
                        <a name="`+paciente[i].pa_id+`" onclick="rutaprintHCL(this.name)" class="btn btn-default btn-sm" target="_blank" title="Inprimir"><i class="glyphicon glyphicon-print"></i></a>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaEditPaciente(this.name)" class="btn btn-default btn-sm" title="Editar"><i class="fa fa-pencil"></i></i></button>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaDestroyPaHcl(this.name)" class="btn btn-default btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaPrestarHCL(`+paciente[i].pa_id+`,`+paciente[i].pa_hcl+`)" class="btn btn-default btn-sm" title="Presatar HCl"><i class="fa fa-puzzle-piece"></i></button>
                        </span>
                    </td>
                </tr>`;

                $("#resulBusqPacientes").append(tr)
            }*/
        });
    }
}
function buscarNOMBRESpaciente()
{
    var nombres = $(this).val();
    if (nombres == "") {
        console.log("sin letras");
            $('#resulBusqPacientes').html("");

    }else{

        nombres = nombres.replace(/[ ]/gi,'-');
        $.get('/C.S.J.O.bo/api/buscarPacienteNombre/'+nombres+'',function(paciente){
            listPacientes(paciente);
            /*$('#resulBusqPacientes').html("");
            for (var i = paciente.length - 1; i >= 0; i--) {
                console.log(paciente[i]);
                var tr = `<tr>
                      <td>`+paciente[i].pa_hcl+`</td>
                      <td>`+paciente[i].pa_ci+`</td>
                      <td>`+paciente[i].pa_nombre+`</td>
                      <td>`+paciente[i].pa_appaterno+` / `+paciente[i].pa_apmaterno+`</td>
                     <td>
                        <span class="tooltip-area">
                        <button name="`+paciente[i].pa_id+`" onclick="rutaAtender(this.name)" class="btn btn-default btn-sm" title="Atender"><i class="fa  fa-plus-square"></i></button>
                        <a name="`+paciente[i].pa_id+`" onclick="rutaprintHCL(this.name)" class="btn btn-default btn-sm" target="_blank" title="Inprimir"><i class="glyphicon glyphicon-print"></i></a>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaEditPaciente(this.name)" class="btn btn-default btn-sm" title="Editar"><i class="fa fa-pencil"></i></i></button>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaDestroyPaHcl(this.name)" class="btn btn-default btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                        <button name="`+paciente[i].pa_id+`" onclick="rutaPrestarHCL(`+paciente[i].pa_id+`)" class="btn btn-default btn-sm" title="Prestar HCL"><i class="fa fa-puzzle-piece"></i></button>
                        </span>
                    </td>
                </tr>`;

                $("#resulBusqPacientes").append(tr)
            }*/
        });
    }
}
function showEstPres(prest) {
    console.log(prest);
    $.get('/C.S.J.O.bo/Recepcion/PresHCL/show/'+prest+'',function (prest) {
        var usu =prest.prest_area;
        $("#area_pres_update").val(usu);
        document.getElementById('personal_pres_update').value=(prest.prest_usuEntrega);
        document.getElementById('idPresCerrar').innerHTML=prest.id;
        document.getElementById('fechaPrestamo').innerText=moment(prest.created_at , "YYYY/MM/DD HH:mm:ss").format('DD/MM/YYYY HH:mm');
        $("#md-editPres").attr('class','modal fade').addClass('md-stickTop').modal('show');
    }).fail(function () {
        notif("2","ERROR SERVER BUSCAR PREST");
    });
}
function updatePrestamo() {
    var id  = document.getElementById('idPresCerrar').innerHTML;
    var area =  document.getElementById('area_pres_update').value;
    var usuentrega = document.getElementById('personal_pres_update').value;
    var url = "/C.S.J.O.bo/Recepcion/PresHCL/update";
    var data= {'_token': $('meta[name=csrf-token]').attr('content'),
        'id':id,
        'area':area,
        'usuentrega':usuentrega};
    $.post(url,data).done(function (prest) {
        if (prest == 1){
            document.getElementById('resulBusqPacientes').innerHTML="";
            $("#md-editPres").modal('hide');
            notif("1",'Prestamo actualizado');
        }else{
            notif('3','Error !. Vuelva a interntarlo');
        }
    }).fail();
}
function cerrarPrestamo() {
    var id  = document.getElementById('idPresCerrar').innerText;
    $.get('/C.S.J.O.bo/Recepcion/PresHCL/cerrarPrestamo/'+id+'',function (result) {
        console.log(result);
        if (result == 1){
            $("#md-editPres").modal('hide');
            notif('1','Prestamo concluido');
            document.getElementById('resulBusqPacientes').innerHTML="";
        }else{
            notif('3','Error!. velva a intentarlo');
        }

    }).fail(function () {
        notif('2','Error SERVER');
    });
}