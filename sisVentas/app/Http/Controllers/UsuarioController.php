<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use sisVentas\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\UsuarioFormRequest;
use Illuminate\Database\QueryException;
use DB;


class UsuarioController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$usuarios=DB::table('users')
    		->where('name','LIKE','%'.$query.'%')
    		->orderBy('id','desc')
    		->paginate(7);
    		return view('seguridad.usuario.index', ["usuarios"=>$usuarios,"searchText"=>$query]);
    	}
    }
    public function create(){
    	return view('seguridad.usuario.create');
    }
    public function store(UsuarioFormRequest $request){
        $usuario=new User;
        $usuario->name=$request->get('name');
        $usuario->email=$request->get('email');
        $usuario->password=bcrypt($request->get('password'));
        $usuario->save();
        return Redirect::to('seguridad/usuario');
    }
    public function edit($id){ 
		return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
    }
    public function update(UsuarioFormRequest $request,$id){ //PATCH
        try {            
            $usuario=User::findOrFail($id);     
            $usuario->name=$request->get('name');
            $usuario->email=$request->get('email');
            $usuario->password=bcrypt($request->get('password'));
            $usuario->update();
            return Redirect::to('seguridad/usuario');
        } catch (QueryException $ex) {
            echo '<script>history.back(alert("El valor del campo de correo electrónico ya está en uso"))</script>';
        }
    }
    public function destroy($id){ //DELETE
    	$usuario=DB::table('users')->where('id','=',$id)->delete();
    	return Redirect::to('seguridad/usuario');
    }
}
