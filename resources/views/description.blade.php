@extends('app')

@section('content')
    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent" style="text-align: left; padding-left: 20px;">

            <div class="fadeIn first" style="text-align:center; margin-top: 2%;">
                <h3>COSA PUOI FARE</h3>
            </div>

            <div class="fadeIn second">
                <h3 style="margin-top:20px;">1. Scopri i 10 artisti più popolari</h3>
                <p>Fissato un genere, scopri quali sono i 10 artisti più popolari per quel genere.</p>
            </div>

            <div class="fadeIn second">
                <h3 style="margin-top:50px;">2. Scopri l'età media delle persone che ascoltano un determinato genere</h3>
                <p>Fissato un genere, scopri qual è l'età media che lo ascolta.</p>
            </div>

            <div class="fadeIn third">
                <h3 style="margin-top:50px;">3. Suggerisci una traccia per una tua playlist</h3>
                <p>Fissata una playlist che ami ascoltare, suggerisce una traccia da ascoltare e aggiungere.</p>
            </div>

            <div class="fadeIn third">
                <h3 style="margin-top:50px;">4. Suggerisci una traccia per un utente</h3>
                <p>Fissato un utente, suggerisce una traccia in base ai generi e alle playlist che ascolta.</p>
            </div>

            <div class="fadeIn fourth">
                <h3 style="margin-top:50px;">5. Suggerisci in base ai gusti dell'utente, cinque tracce di un artista che non conosce</h3>
                <p>Fissato un utente, suggerisce cinque tracce di un artista che l'utente ancora non conosce in base ai suoi gusti.</p>
            </div>

            <div class="fadeIn fourth">
                <h3 style="margin-top:50px;">6. Suggerisci delle tracce per una playlist, in base alle tracce presenti in tutte le altre playlist</h3>
                <p>Fissata una playlist di un utente, suggerisce delle tracce che l'utente ancora non conosce in base al contenuto della sua e delle altre playlist.</p>
            </div>

            <br />

        </div>
    </div>

@endsection
