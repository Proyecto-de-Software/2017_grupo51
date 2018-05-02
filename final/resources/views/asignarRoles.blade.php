@extends('layout')

@section('content')
    @foreach ($roles as $rol)
        <button>{{ $rol['nombre'] }}</button>
    @endforeach
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection