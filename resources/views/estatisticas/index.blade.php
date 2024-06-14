@extends('layouts.main')

@section('header-title', 'Estatísticas')
@section('main')
<div class="container">

    <div class="card">
        <div class="card-header">Bilhetes</div>
        <br>
        <div class="card-body">
            <p>Mínimo preço: {{ $minimo }}</p>
            <p>Máximo preço: {{ $maximo }}</p>
            <p>Média preço: {{ $media }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Filmes</div>
        <br>
        <div class="card-body">
            <h3><b>Filmes Mais Vistos:</b></h3>
                <ul>
                    @foreach($idsMaisVistos as $id)
                        @if(isset($filmes[$id]))
                            <li>{{ $filmes[$id]->title }}</li>
                        @else
                            <li>Filme não encontrado para ID {{ $id }}</li>
                        @endif
                    @endforeach
                </ul>


            <h3><b>Filmes Menos Vistos:</b></h3>
            <ul>
                @foreach($idsMenosVistos as $id)
                    @if(isset($filmes[$id]))
                        <li>{{ $filmes[$id]->title }}</li>
                    @else
                        <li>Filme não encontrado para ID {{ $id }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Gêneros</div>
        <div class="card-body">
            <div id="genre-chart" style="width: 900px; height: 500px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable({!! $genre !!});

        var options = {
            title: 'Gêneros dos Filmes',
            pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('genre-chart'));
        chart.draw(data, options);
    }
</script>
@endsection
