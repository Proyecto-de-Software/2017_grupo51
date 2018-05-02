@extends('layout')

@section('javascript')
    <script src="{{ asset('js/validarIniciarSesion.js') }}"></script>
@endsection

@section('atributosBody')
    onload="validar()"
@endsection

@include('auth.login')

@section('atributosFooter')
    class="posicionFooter"
@endsection