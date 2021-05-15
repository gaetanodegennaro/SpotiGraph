@extends('app')

@section('content')


    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent" style="text-align: left; padding-left: 20px;">

                <h3 class=" fadeIn first" style="margin-top:2%;">Query 3 - Suggerisci una traccia per una tua playlist</h3>
                <br />
                <form method="POST" action="{{Route('exec-query3')}}">
                    @csrf
                    <div class="form-group">
                        <p class=" fadeIn first">Seleziona una playlist e una sensibilità</p>
                        <select class="form-control fadeIn first" id="playlist" name="playlist" style="width:40%; float:left; margin-top: 10px;">
                            @foreach($playlists as $p)
                                <option value="{{$p}}"
                                        @if(isset($playlistSelezionata) && $playlistSelezionata==$p)
                                        selected
                                    @endif>{{$p}}</option>
                            @endforeach
                        </select>
                        <select class="form-control fadeIn first" id="sensibilità" name="sensibilità" style="width:40%; float:left; margin-top: 10px; margin-left: 10px;">
                            <option value="5" @if(isset($sensSelezionata) && $sensSelezionata==5) selected @endif>5 %</option>
                            <option value="10" @if(isset($sensSelezionata) && $sensSelezionata==10) selected @endif>10 %</option>
                            <option value="15" @if(isset($sensSelezionata) && $sensSelezionata==15) selected @endif>15 %</option>
                            <option value="20" @if(isset($sensSelezionata) && $sensSelezionata==20) selected @endif>20 %</option>
                            <option value="25" @if(isset($sensSelezionata) && $sensSelezionata==25) selected @endif>25 %</option>
                        </select>
                        <input type="submit" class="fadeIn second" value="Vai">
                    </div>
                </form>

        </div>
    </div>

    @if(isset($traccia) || isset($error))

        <div class="wrapper fadeInDown" style="height: 100%;">
            <div id="formContent" style="text-align: left; padding-left: 20px;">

                <div style="margin-top: 2%;">
                    @if(!isset($error))
                        <h3>Traccia suggerita per la playlist: {{$playlistSelezionata}}</h3>
                        <br />

                            <div class="row">
                                <div class="col-md-3">
                                    <div  style="margin-top:-20px;">
                                        <div style="position:relative">
                                            <div style="position:relative;text-align:center">
                                                <div id="turntable-large" class="turntable has-slipmat" style="display:inline-block">
                                                    <div class="frame"></div>
                                                    <div class="table-bg"></div>
                                                    <!-- image that sets the base dimensions-->
                                                    <img src="css/player-img/tt_case_and_lighting.png" alt="" class="stub" />
                                                    <div class="bd">
                                                        <div class="platter"></div>
                                                        <div class="slipmat-holder">
                                                            <div class="slipmat"></div>
                                                        </div>
                                                        <div class="record-holder">
                                                            <div class="record"></div>
                                                            <div class="record-grooves"></div>
                                                            <div class="label"></div>
                                                        </div>
                                                        <div class="spindle"></div>
                                                        <div class="power-light"></div>
                                                        <a href="#" class="power-dial" data-method="powerToggle"></a>
                                                        <a href="#" class="button start-stop" data-method="toggle"></a>
                                                        <a href="#" class="button speed-33 on"></a>
                                                        <a href="#" class="button speed-45"></a>
                                                        <div class="light light-on"></div>
                                                        <a href="#" class="button light"></a>
                                                        <div class="tonearm-holder">
                                                            <div class="tonearm"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a id="load" href="{{$traccia['audio_demo']}}.mp3" data-turntable="turntable-large" data-artwork="{{$traccia['album_cover']}}">Clicca qui per caricare la traccia</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h3>{{$traccia['traccia']}}</h3>
                                    <p><b>Album</b>: {{$traccia['album']}}</p>
                                    <p style="margin-top: -10px;"><b>Acoutsicness</b>: {{$traccia['acousticness']}}</p>
                                    <p style="margin-top: -10px;"><b>Loudness</b>: {{$traccia['loudness']}}</p>
                                    <p style="margin-top: -10px;"><b>Danceability</b>: {{$traccia['danceability']}}</p>
                                    <p style="margin-top: -10px;"><b>Speechiness</b>: {{$traccia['speechiness']}}</p>
                                    <p style="margin-top: -10px;"><b>Valence</b>: {{$traccia['valence']}}</p>
                                    <p style="margin-top: -10px;"><b>Instrumentalness</b>: {{$traccia['instrumentalness']}}</p>
                                    <p style="margin-top: -10px;"><b>Energy</b>: {{$traccia['energy']}}</p>
                                    <p style="margin-top: -10px;"><b>Popularity</b>: {{$traccia['popularity']}}</p>
                                    <p style="margin-top: -10px;"><b>Time signature</b>: {{$traccia['time_signature']}}</p>
                                    <p style="margin-top: -10px;"><b>Length</b>: {{round($traccia['length']/3600, 3)}}</p>
                                    <p style="margin-top: -10px;"><b>Tempo</b>: {{$traccia['tempo']}} BPM</p>

                                </div>
                                <div class="col-md-6">
                                    <h3>Informazioni aggregate della playlist:</h3>
                                    <br />
                                    <p style="margin-top: 5px;"><b>Acouticness:</b> {{$acousticnessAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Loudness:</b> {{$loudnessAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Danceability:</b> {{$danceabilityAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Speechiness:</b> {{$speechinessAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Valence:</b> {{$valenceAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Instrumentalness:</b> {{$instrumentalnessAvg}}</p>
                                    <p style="margin-top: -10px;"><b>Energy:</b> {{$energyAvg}}</p>

                                </div>
                            </div>
                        </div>

                    @else
                        <h3>Non è stata rilevata nessuna traccia come suggerimento. Prova ad aumentare la sensibilità.</h3>
                        </div>
                        <br />
                    @endif
                <br />
            </div>
        </div>
    @endif
    <script type="text/javascript" src="{{asset('js/soundmanager2.js')}}"></script>
    <script src="{{asset('js/turntable.js')}}"></script>
    <script src="{{asset('js/turntable-app.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/turntable.css')}}" />

@endsection
