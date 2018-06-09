@extends('layout')

@section('javascript')
    <script src="{{ asset('js/graphic/code/highcharts.js') }}"></script>
    <script src="{{ asset('js/graphic/code/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/dibujaGraficoTortas.js') }}"></script>
@endsection

@section('atributosBody')
    onload="dibujar({{$datosGrafico}});"
@endsection

@section('content')
    <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection