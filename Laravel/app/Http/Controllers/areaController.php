<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\area;
use App\User;
use App\usuContrato;

class areaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('viewAdm.FormRegistroArea');
    }
    public function create(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = Request()->all();
        $area = new area;
        $area->nombre = $data["nombre"];
        $area->descripcion = $data["descripcion"];
        $area->tipo = $data["area"];

        $resul = $area->save();
        if ($resul) {
            \Session::flash('flash_message_correcto', 'Area creada exitosamente.');
            //return view("mensajes.msj_correcto")->with("msj","Usuario Registrado Correctamente");   
        } else {
            \Session::flash('flash_message_rechazado', 'Huvo un error al crear el Area vuelva a intentarlo');
            // return view("mensajes.msj_rechazado")->with("msj","hubo un error vuelva a intentarlo");  

        }
        //event(new Registered($user = $this->create($request->all())));
        //ingreso luego del registro  $this->guard()->login($user);      
        return redirect()->route('formNewArea');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [

            'nombre' => ' required|string|max:50|unique:area',
            'descripcion' => 'string|max:200|nullable',
            'area' => 'required|string|max:20',

        ]);
    }

    public function homeArea()
    {
        return view('viewRRHH.viewAreas.homeAreas');
    }

    public function list()
    {
        $list = area::join('users as us', 'us.id', 'area.ar_id_encargado')
            ->select('area.*')
            ->addSelect('us.usu_appaterno', 'us.usu_nombre')
            ->get();
        foreach ($list as $l) {
            $cont = usuContrato::where('uc_area', $l->nombre)->where('uc_estado', 1)->count();
            array_add($l, 'cant_usuarios', $cont);
        }
        return $list;
    }
    public function show(Request $request)
    {
        $area = area::where('id', $request->input('id'))->first();
        $user = User::where('id', $area->ar_id_encargado)->first();
        $cantPersonal= usuContrato::where('uc_area',$area->nombre)->where('uc_estado',1)->count();
        $personal= usuContrato::where('uc_estado',1)->where('uc_area',$area->nombre)
        ->select('uc_tipoContrato','cod_usu')
        ->join('users as u','u.id','usu_Contratos.cod_usu')
        ->addSelect('u.usu_nombre','u.usu_appaterno')
        ->get();
        $per=usuContrato::where('uc_estado',1)->where('uc_area',$area->nombre)->groupBy('uc_tipoContrato')->select('uc_tipoContrato', \DB::raw('count(*) as total'))->get();
        array_add($area, 'area_encargado', "$user->usu_appaterno  $user->usu_nombre");
        array_add($area, 'cantidaPersonal', $cantPersonal);
        array_add($area, 'contratos', $per);
        array_add($area, 'personal', $personal);
        return $area;
    }
}
