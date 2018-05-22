<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Models\Configuracion;
use App\Models\Rol;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($condition = NULL, $username = NULL)
    {
        $config = Configuracion::all();
        if($condition == NULL){
            $usuarios = User::Usuarios([['id','!=',Auth::id()]])->orderBy('id', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);
        }else{
            switch ($condition) {
                case 'username':
                    $newCondition = ['username','LIKE','%'.$username.'%'];
                    break;
                case 'active':
                    $newCondition = ['active','=',1];
                    break;
                case 'blocked':
                    $newCondition = ['active','=',0];
                    break;
                default:
                    return redirect('usuarios/index');
            }
            $usuarios = User::Usuarios([$newCondition,['id','!=',Auth::id()]])->orderBy('id', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);
        }
        return view('paginaPrincipalUsuarios')->with(['config' => $config, 'usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = Configuracion::all();
        $roles = Rol::all();
        return view('formularioUsuario')->with(['config' => $config,'accion' => 'Crear', 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->all());
        $user->save();
        if(isset($request->all()['check'])){
            $rolesAAsignar = $request->all()['check'];
            foreach ($rolesAAsignar as $rol){
                $user->roles()->attach($rol);
            }
        }
        flash('Usuario creado exitosamente.');
        return redirect('usuarios/'.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $config = Configuracion::all();
        $usuario = User::find($id);
        $roles = $usuario->roles->toArray();
        return view('detalleUsuario')->with(['config' => $config, 'usuario' => $usuario, 'roles' => $roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $config = Configuracion::all();
        $usuario = User::find($id);
        return view('formularioUsuario')->with(['config' => $config,'accion' => 'Editar','usuario' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        
        $user = User::find($id);
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();
        flash('La información del usuario se ha modificado exitosamente.')->info();
        return $this->show($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        flash('Se ha eliminado el usuario.')->important();
        return redirect()->route('usuarios');
    }
    
    //Bloque o desbloque un usuario
    public function lockOrUnlock($id){
        $user = User::find($id);
        $user->active = !$user->active;
        $user->save();
        return redirect()->route('usuarios');
    }

    public function assignOrUnassignRol($id){
        //Obtencion de todos los roles y los asignados al usuario.
        //Construccion de un arreglo de roles que posee o no posee el usuario para enviar a la vista
        $user = User::find($id);
        $userRol = $user->roles->toArray();
        $config = Configuracion::all();
        $roles = Rol::all()->toArray();
        $arrayRoles = $this->rolArrayBuilder($roles,$userRol);
        return view('roles')->with(['config' => $config, 'roles' => $arrayRoles, 'usuario' => $user]);
    }
    
    public function rolArrayBuilder($roles,$userRol){
        //Arma el arreglo indicando cual rol posee el usuario, asignandole true,
        // y asignandole false caso contrario
        $arrayRoles = [];
        $pos = 0;
        foreach ($roles as $rol){
            $usuarioPoseeRol = false;
            foreach ($userRol as $rolUsuario){
                if($rolUsuario['id'] == $rol['id']){
                    $usuarioPoseeRol = true;
                    $arrayRoles[$pos] = ['poseeRol' => true,'id' => $rol['id'],'nombre' => $rol['nombre']];
                    break;
                }
            }
            if(!$usuarioPoseeRol){
                $arrayRoles[$pos] = ['poseeRol' => false,'id' => $rol['id'],'nombre' => $rol['nombre']];
            }
            $pos++;
        }
        return $arrayRoles;
    }
    
    public function assignRol($userId,$rolId){
        //Asgina el rol rolId al usuario userId
        $user = User::find($userId);
        $user->roles()->attach($rolId);
        return redirect('/usuarios/'.$userId.'/assignOrUnassignRol');
    }
    
    public function unassignRol($userId,$rolId){
        //Dessgina el rol rolId al usuario userId
        $user = User::find($userId);
        $user->roles()->detach($rolId);
        return redirect('/usuarios/'.$userId.'/assignOrUnassignRol');
    }
    
    public function filter(Request $request){
        //dd($request->request->all());
        $search = $request->request->all();
        switch ($search['busquedaUsuario']) {
            case 'nombreUsuario':
                return redirect('usuarios/index/username/'.$search['nombreUsuario']);
            case 'activos':
                return redirect('usuarios/index/active');
            case 'bloqueados':
                return redirect('usuarios/index/blocked');
            default:
                return redirect('usuarios/index/');
        }
    }
}
