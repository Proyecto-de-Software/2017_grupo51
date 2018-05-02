<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $config = Configuracion::all();
        $usuarios = User::orderBy('id', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);
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
        dd($user);
        $config = Configuracion::all();
        $roles = Rol::all();
        return view('asignarRoles')->with(['config' => $config, 'roles' => $roles]);
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
        //
    }

}
