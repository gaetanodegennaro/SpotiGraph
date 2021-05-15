@extends('app')

@section('content')
    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent" style="text-align: left; padding-left: 20px;">

            <h3 class=" fadeIn first" style="margin-top:2%;">Query 6 - Suggerisci delle tracce per una playlist, in base alle tracce presenti in tutte le altre playlist</h3>
            <br />
            <form method="POST" action="{{Route('exec-query6')}}">
                @csrf
                <div class="form-group">
                    <p class=" fadeIn first">Seleziona una playlist</p>
                    <select class="form-control fadeIn first" id="playlist" name="playlist" style="width:80%; float:left; margin-top: 10px;">
                        @foreach($playlists as $p)
                            <option value="{{$p}}"
                                    @if(isset($playlistSelezionata) && $playlistSelezionata==$p)
                                    selected
                                @endif>{{$p}}</option>
                        @endforeach
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
                            <h3>Tracce suggerite per la playlist: {{$playlistSelezionata}}</h3>
                            <br />
                        </div>

                        <div clas="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div align="center">
                                        <div style="width: 30%;">
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
                            </div>
                        </div>

                        <div class="container" style="max-width: 100%;">
                            <div class="row">
                                @foreach($tracce as $r)
                                    <?php $traccia = $r['t1']; ?>

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
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @else
                        <h3>Non è stata rilevata nessuna traccia come suggerimento. Prova un'altra playlist.</h3>
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
