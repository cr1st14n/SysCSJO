@extends('layouts.admLay2')
@section('head')
@endsection
@section('refUbi')
<ol class="breadcrumb">
    <li><a href="#">Administracion</a></li>
    <li class="active">Estado Recepcion</li>
</ol>

@endsection
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="well bg-inverse">
            <div class="widget-tile">
                <section>
                    <h5><strong>Clientes </strong> Registrados </h5>
                    <h2>{{$total}}</h2>
                    <div class="progress progress-xs progress-white progress-over-tile">
                        <div class="progress-bar  progress-bar-white" aria-valuetransitiongoal="8590" aria-valuemax="10000"></div>
                    </div>
                </section>
                <div class="hold-icon"><i class="fa fa-bar-chart-o"></i></div>
                <div class=" ">
                    <button class="btn btn-transparent btn-theme-inverse " data-toggle="modal" data-target="#md-informePacientes" onclick="informe1()"><i class="glyphicon glyphicon-signal"></i></button>
                    <button class="btn btn-transparent btn-theme-inverse " data-toggle="modal" data-target="#md-full-width"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="well bg-inverse">
            <div class="widget-tile">
                <section>
                    <h5><strong>citas </strong>medicas </h5>
                    <h2>478</h2>
                    <div class="progress progress-xs progress-white progress-over-tile">
                        <div class="progress-bar  progress-bar-white" aria-valuetransitiongoal="478" aria-valuemax="1000"></div>
                    </div>
                    <button class="btn btn-transparent btn-theme-inverse " data-toggle="modal" data-target="#md-infoCaja"><i class="glyphicon glyphicon-signal"></i></button>
                </section>
                <div class="hold-icon"><i class="fa fa-shopping-cart"></i></div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-8">
        <section class="panel corner-flip">
            <div class="widget-chart bg-lightseagreen bg-gradient-green" onclick="cuadroEstadistico()">
                <h2>Estado anual registro de pacientes</h2>
                <table class="flot-chart" data-type="lines" data-tick-color="rgba(255,255,255,0.2)" data-width="100%" data-height="220px">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="color : #FFF;">Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>ENE</th>
                            <td>{{$enero}}</td>
                        </tr>
                        <tr>
                            <th>FEB</th>
                            <td>{{$febrero}}</td>
                        </tr>
                        <tr>
                            <th>MAR</th>
                            <td>{{$marzo}}</td>
                        </tr>
                        <tr>
                            <th>ABR</th>
                            <td>{{$abril}}</td>
                        </tr>
                        <tr>
                            <th>MAY</th>
                            <td>{{$mayo}}</td>
                        </tr>
                        <tr>
                            <th>JUN</th>
                            <td>{{$junio}}</td>
                        </tr>
                        <tr>
                            <th>JUL</th>
                            <td>{{$julio}}</td>
                        </tr>
                        <tr>
                            <th>AGO</th>
                            <td>{{$agosto}}</td>
                        </tr>
                        <tr>
                            <th>SEP</th>
                            <td>{{$septiembre}}</td>
                        </tr>
                        <tr>
                            <th>OCT</th>
                            <td>{{$octubre}}</td>
                        </tr>
                        <tr>
                            <th>NOV</th>
                            <td>{{$noviembre}}</td>
                        </tr>
                        <tr>
                            <th>DIC</th>
                            <td>{{$diciembre}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-body">
                <h3>Estado mes actual</h3>
                <div class="row align-lg-center">
                    <div class="col-sm-6">
                        <div class="showcase showcase-pie-easy clearfix">
                            <span class="easy-chart pull-left" data-percent="75" data-color="purple" data-track-color="#EDEDED" data-line-width="15" data-size="140">
                                <span class="percent"></span>
                            </span>
                            <ul>
                                <li>548<small>Pacientes registrados</small></li>
                                <li>3,984<small>Pacientes atendidos</small></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </section>

    </div>
    <div class="col-lg-4">
        <section class="panel">
            <header class="panel-heading">
                <h2><strong>Usuarios </strong> en el area</h2>
            </header>
            <ul class="list-group">
                <li class="list-group-item">
                    Elena
                </li>
                <li class="list-group-item">
                    Monica
                </li>
                <li class="list-group-item">
                    --
                </li>
                <li class="list-group-item">
                    --
                </li>
            </ul>
        </section>
    </div>
</div>


//? Modal para filtrar
<div id="md-informePacientes" class="modal fade md-stickTop">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title">Informe de registro de pacientes</h4>
    </div>
    <!-- //modal-header-->
    <div class="modal-body">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-7" id="porcentajeRegistro">

                </div>

                <div class="col-md-5" id="totalRegistros">

                </div>
            </div>
        </div>
    </div>
    <!-- //modal-body-->
</div>

<div id="md-full-width" class="modal fade md-stickTop">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title">Pacientes registrados</h4>
    </div>
    <!-- //modal-header-->
    <div class="modal-body">

        <div class="col-lg-3">
            <input type="number" class="form-control" placeholder="CI / HCL" id="buscNumHCL" oninput="buscarCiHCL(this.value,1)" onkeyup="validar('buscNumHCL')" pattern="[0-9]+">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control" placeholder="Nombre apellico" oninput="buscarCiHCL(this.value,2)">
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th># HCL</th>
                        <th>CI</th>
                        <th>Nombre </th>
                        <th>apellico</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody align="center" id="listPacientes">
                    <tr>
                        <td>
                            Ingrese datos para buscar!
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- //modal-body-->
</div>

<div id="md-infoCaja" class="modal fade md-stickTop">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title">Informe de caja</h4>
    </div>
    <!-- //modal-header-->
    <div class="modal-body">
        <div class="panel-body">
            <div class="row">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-theme-inverse btn-sm btn-block" onclick="InfoCajaList()">filtrar</button>
                    </div>
                    <div class="col-lg-6">
                        <div class="align-lg-center ">
                            <select name="" id="infCajaMez" class="form-control">
                                <option value="Anual">Anual</option>
                                <option value="01">enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="align-lg-center ">
                            <input class="form-control" type="number" name="" id="infoCajaAño" placeholder="Año" value="2019">
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <h3><strong>Total</strong>.- </h3>
                    <br>
                    <ol class="rectangle-list" id="listReporteCaja">
                        <li><a href="#"> ** ** <span class="pull-right">## ##</span></a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- //modal-body-->
</div>
<div id="md-DetalleCajaEsp" class="modal fade container md-stickTop">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title">estado </h4>
    </div>
    <!-- //modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <input type="number" class="form-control" placeholder="Año">
            </div>
            <div class="col-sm-8">
                <button class="btn  btn-theme-inverse btn-block" onclick="showDataEstEsp()">Filtrar</button>
            </div>
        </div>
        <div id="estadoAnualEst">

        </div>
    </div>
    <!-- //modal-body-->
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('asincrono/admRecepHome.js') }}"></script>
<script type="text/javascript" src="{{ asset('asincrono/homejs.js') }}"></script>
@endsection