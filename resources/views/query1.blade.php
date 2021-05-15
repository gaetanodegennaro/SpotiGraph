@extends('app')

@section('content')
    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent" style="text-align: left; padding-left: 20px;">

            <h3 class=" fadeIn first" style="margin-top:2%;">Query 1 - Scopri i 10 artisti più popolari</h3>
            <br />
            <form method="POST" action="{{Route('exec-query1')}}">
                @csrf
                <div class="form-group">
                    <p class=" fadeIn first">Seleziona un genere</p>
                    <select class="form-control fadeIn first" id="genere" name="genere" style="width:80%; float:left; margin-top: 10px;">
                        @foreach($generi as $g)
                            <option value="{{$g}}"
                            @if(isset($genereSelezionato) && $genereSelezionato==$g)
                                selected
                            @endif>{{$g}}</option>
                        @endforeach
                    </select>
                    <input type="submit" class="fadeIn second" value="Vai">
                </div>
            </form>

            <br />

        </div>
    </div>

    @if(isset($artisti))
        <div class="wrapper fadeInDown" style="height: 100%;">
            <div id="formContent" style="text-align: left; padding-left: 20px;">

                <div class="fadeIn third" style="margin-top: 2%;">
                    @foreach($artisti as $a)
                        <a href="https://open.spotify.com/artist/{{$a['artist_id']}}">
                            <h3 style="color:white;">{{$a['artista']}}</h3>
                        </a>
                        <br />
                    @endforeach
                </div>


                <br />

            </div>
        </div>
    @endif

@endsection
