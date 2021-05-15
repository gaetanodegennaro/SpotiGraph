@extends('app')

@section('content')

    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent" style="text-align: left; padding-left: 20px;">

            <h3 class=" fadeIn first" style="margin-top:2%;">Query 5 - Suggerisci in base ai gusti dell'utente, cinque tracce di un artista che non conosce</h3>
            <br />
            <form method="POST" action="{{Route('exec-query5')}}">
                @csrf
                <div class="form-group">
                    <p class=" fadeIn first">Inserisci un utente e seleziona una sensibilità</p>
                    <input class="form-control" id="utente" name="utente" style="width:40%; float:left; margin-top: 10px;" placeholder="Anthony Lynn"
                           @if(isset($utenteSelezionato)) value="{{$utenteSelezionato}}" @endif required\>
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

    @if(isset($tracce) || isset($error))

        <div class="wrapper fadeInDown" style="height: 100%;">
            <div id="formContent" style="text-align: left; padding-left: 20px;">

                <div style="margin-top: 2%;">

                    @if(!isset($error))
                        <div align="center">
                            <h3>Tracce suggerite per l'utente: {{$utenteSelezionato}}</h3>
                            <br />
                        </div>

                        <div clas="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div align="right">
                                        <div style="width: 60%;">
                                            <div  style="margin-top:-20px;">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

                        <div class="container" style="max-width: 100%;">
                            <div class="row">
                                <div class="col-xl-1">
                                    <h3>Traccia</h3>
                                    <p><b>Album</b>: </p>
                                    <p style="margin-top: -10px;"><b>Acoutsicness</b>: </p>
                                    <p style="margin-top: -10px;"><b>Loudness</b>: </p>
                                    <p style="margin-top: -10px;"><b>Danceability</b>: </p>
                                    <p style="margin-top: -10px;"><b>Speechiness</b>: </p>
                                    <p style="margin-top: -10px;"><b>Valence</b>: </p>
                                    <p style="margin-top: -10px;"><b>Instrumentalness</b>: </p>
                                    <p style="margin-top: -10px;"><b>Energy</b>: </p>
                                    <p style="margin-top: -10px;"><b>Popularity</b>: </p>
                                    <p style="margin-top: -10px;"><b>Time sign.</b>: </p>
                                    <p style="margin-top: -10px;"><b>Length</b>: </p>
                                    <p style="margin-top: -10px;"><b>Tempo</b>: </p>
                                </div>
                            @foreach($tracce as $r)
                                <?php $traccia = $r['t']; ?>

                                    <div class="col-xl-2">
                                        <?php
                                            if(strlen($traccia['traccia'])>15)
                                                $nomeTraccia = substr($traccia['traccia'], 0, 12)."...";
                                            else $nomeTraccia = $traccia['traccia'];

                                            if(strlen($traccia['album'])>28)
                                                $nomeAlbum = substr($traccia['album'], 0, 25)."...";
                                            else $nomeAlbum = $traccia['album'];
                                        ?>
                                        <div title="{{$traccia['traccia']}}">
                                            <a id="load" href="{{$traccia['audio_demo']}}.mp3" data-turntable="turntable-large" data-artwork="{{$traccia['album_cover']}}">
                                                <h3>{{$nomeTraccia}}</h3>
                                            </a>
                                        </div>
                                        <div title="{{$traccia['album']}}">
                                            <p>{{$nomeAlbum}}</p>
                                        </div>

                                        <p style="margin-top: -10px;">{{$traccia['acousticness']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['loudness']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['danceability']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['speechiness']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['valence']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['instrumentalness']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['energy']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['popularity']}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['time_signature']}}</p>
                                        <p style="margin-top: -10px;">{{round($traccia['length']/3600, 3)}}</p>
                                        <p style="margin-top: -10px;">{{$traccia['tempo']}} BPM</p>
                                    </div>
                            @endforeach
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
