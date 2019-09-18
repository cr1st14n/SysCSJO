<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pacientes;
use App\especialidad;
use Illuminate\Support\Carbon;

class admRecepController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $total=pacientes::count();


  
        $year=2019;
        $enero=pacientes::whereYear('created_at',$year)->whereMonth('created_at',1)->count();
        $febrero=pacientes::whereYear('created_at',$year)->whereMonth('created_at',2)->count();
        $marzo=pacientes::whereYear('created_at',$year)->whereMonth('created_at',3)->count();
        $abril=pacientes::whereYear('created_at',$year)->whereMonth('created_at',4)->count();
        $mayo=pacientes::whereYear('created_at',$year)->whereMonth('created_at',5)->count();
        $junio=pacientes::whereYear('created_at',$year)->whereMonth('created_at',6)->count();
        $julio=pacientes::whereYear('created_at',$year)->whereMonth('created_at',7)->count();
        $agosto=pacientes::whereYear('created_at',$year)->whereMonth('created_at',8)->count();
        $septiembre=pacientes::whereYear('created_at',$year)->whereMonth('created_at',9)->count();
        $octubre=pacientes::whereYear('created_at',$year)->whereMonth('created_at',10)->count();
        $noviembre=pacientes::whereYear('created_at',$year)->whereMonth('created_at',11)->count();
        $diciembre=pacientes::whereYear('created_at',$year)->whereMonth('created_at',12)->count();

        return view('viewAdm.admRecepHome')
        ->with("total",$total)
        ->with("enero" ,$enero)
        ->with("febrero" ,$febrero)
        ->with("marzo" ,$marzo)
        ->with("abril" ,$abril)
        ->with("mayo" ,$mayo)
        ->with("junio" ,$junio)
        ->with("julio" ,$julio)
        ->with("agosto" ,$agosto)
        ->with("septiembre" ,$septiembre)
        ->with("octubre" ,$octubre)
        ->with("noviembre" ,$noviembre)
        ->with("diciembre" ,$diciembre);
    }

    public function uno(){
        $total=pacientes::count();
        $paHombre=pacientes::where('pa_sexo','M')->count();
        $paMujer=pacientes::where('pa_sexo','F')->count();
        $sinSexo=pacientes::count()-$paMujer-$paHombre;

        $paH=round (floatval(($paHombre*100)/$total),2);
        $paM=round (floatval(($paMujer*100)/$total),2);
        $paS=round (floatval(($sinSexo*100)/$total),2);

        $año=Carbon::now()->format('Y');
        $edad1=pacientes::whereYear('pa_fechnac','<',$año)->count();
        $edad2=pacientes::whereYear('pa_fechnac','<',$año-25)->count();
        $edad3=pacientes::whereYear('pa_fechnac','<',$año-50)->count();

        $edad1=($edad2+$edad3)-$edad1;
        $edad2=$edad2-$edad3;
        $edad3=$edad3;
        $edadTotal=$edad1+$edad2+$edad3;
        
        $edad1P=round (floatval($edad1*100/$edadTotal),2);
        $edad2P=round (floatval($edad2*100/$edadTotal),2);
        $edad3P=round (floatval($edad3*100/$edadTotal),2);


        $array=['Total'=>$total,
                'TotalHombre'=>$paHombre,
                'TotalMujer'=>$paMujer,    
                'TotalSinSexo'=>$sinSexo,
                'porcentajeHombre'=>$paH,   
                'porcentajeMujer'=>$paM,
                'porcentajeSinSexo'=>$paS,
                'edad1'=>$edad1,
                'edad2'=>$edad2,
                'edad3'=>$edad3,
                'edad1P'=>$edad1P,
                'edad2P'=>$edad2P,
                'edad3P'=>$edad3P];
        return $array;

    }

    public function buscasrHCL(Request $request){
        // return $request['dato'];
        $resultado='';   
        if ($request['tipo']==1) {
            $resultado=pacientes::where('pa_hcl','like',($request['dato']).'%')->orderBy('pa_hcl','asc')->limit(10)->get();

        }if($request['tipo']==2){
            $nombre=$request['dato'];
            $resultado= $this->buscarPacientesText($nombre);
        }
        if ($resultado->count()==0) {
            $resultado='vacio';
        }
        return $resultado;
    }

    public function buscarPacientesText($texto){
    	#return $texto;
        $var_Busqueda='';
        $apep='';
        $apem=''; 
        $apem2='';
        $a = ''; 
        $b = ''; 
        $c = ''; 
	        //fragmentar input por estacio
	        $trozo = preg_split("/[ ]+/", $texto);
	        for ($i=0; $i < count($trozo); $i++) { 
	          switch ($i) {
	            case '0':
	              $nom=$trozo[$i];
	              break;
	            case '1':
	              $apep=$trozo[$i];
	              break;
	            case '2':
	              $apem=$trozo[$i];
	              break;
	            case '3':
	              $apem2=$trozo[$i];
	              break;
	          }
	        }

	        if ($apem2 != '') {$apem="$apem $apem2";}
	        //generar valores de verdad
	        if ($nom == '0' || $nom == null) {$a='f';}else{$a='v';}
	        if ($apep == '0' || $apep == null) {$b='f';}else{$b='v';}
	        if ($apem == '0'|| $apem == null || $apem == ' ' ) {$c='f';}else{$c='v';}

	        $var_Busqueda="$nom $apep $apem";
	        $i="$a$b$c";
	        switch ($i) {
	          case 'vvv':
	            # code...
	          //echo "primera iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1'";
	            //$pacientes = pacientes::where(('pa_nombre','Like',$nom.'%') && 'pa_appaterno','Like',$apep.'%' && 'pa_apmaterno','Like',$apem.'%')->get();

	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_nombre','like','%'.$nom.'%')
	                                ->where('pa_appaterno','like',$apep.'%')
	                                ->Where('pa_apmaterno','like',$apem.'%'); })->limit(50)->get();
	            //$pacientes=pacientes::where([['pa_nombre','like','%'.$nom.'%'],['pa_appaterno','like',$apep.'%'],['pa_apmaterno','like',$apem.'%']])->get();
	            break;
	          case 'vvf':
	            # code...
	          //echo "segunda iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' " ;
	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_nombre','like','%'.$nom.'%')
	                                ->where('pa_appaterno','like',$apep.'%'); })->limit(50)->get();
	            break;
	          case 'vfv':
	            # code...
	          //echo "tercera iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_nombre','like','%'.$nom.'%')
	                                ->Where('pa_apmaterno','like',$apem.'%'); })->limit(50)->get();          
	            break;
	          case 'vff':
	            # code...
	          //echo "cuarta iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	             return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_nombre','like','%'.$nom.'%'); })->limit(50)->get();
	                               

	            break;
	          case 'fvv':
	            # code...
	          //echo "quinta iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_appaterno','like',$apep.'%')
	                                ->Where('pa_apmaterno','like',$apem.'%'); })->limit(50)->get();
	            break;
	          case 'fvf':
	            # code...
	          //echo "sexta iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->where('pa_appaterno','like',$apep.'%')
	                                ; })->limit(50)->get();
	            break;
	          case 'ffv':
	            # code...
	          //echo "septima iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	            return pacientes::Where(function($q) use ($nom,$apep,$apem){
	                              $q->Where('pa_apmaterno','like',$apem.'%'); })->limit(50)->get();
	            break;
	          case 'fff':
	            # code...
	          //echo "octava iteracion N==> '$nom' AP==> '$apep' AM==> '$apem' '$dato1' ";
	            $tabla='0';
	          // return view('viewRecepcion.formBuscarPaciente')->with("tabla",$tabla)->with("num",|$var_num)->with("Busqueda",$var_Busqueda);

	            break;

	          default:
	            # code...
	            break;
	        }
		  return "error revisar AjaxPacienteController";        
    }

    public function InfoCajaList(Request $request){
      $especialidades=especialidad::select('id','nombre')->get();
      $lista=array();
      
      foreach( $especialidades as $es){
        $var=array($es->nombre,$es->id);

        array_push($lista,$var);
      }


      return $lista;




    }
}
