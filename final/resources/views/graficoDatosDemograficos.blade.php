@extends('layout')

@section('javascript')
    <script src="{{ asset('js/graphic/code/highcharts.js') }}"></script>
    <script src="{{ asset('js/graphic/code/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/dibujaGrafico.js') }}"></script>
@endsection

@section('atributosBody')
    onload="dibujar({{ $datosGrafico }});"
@endsection

@section('content')
    <div id="container"></div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection