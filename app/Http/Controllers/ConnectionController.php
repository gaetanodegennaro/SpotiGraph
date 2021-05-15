<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ConnectionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public static function readGenres()
    {
        $client = Config::get("client");

        $result = $client->run(
            " MATCH (a:Artista)-[ass:ASSOCIATO]-(g:Genere)
                    WITH g.descrizione AS genere, COUNT(ass) AS nCollegamenti
                    RETURN genere, nCollegamenti
                    ORDER BY nCollegamenti DESC LIMIT 15"
        );

        $generi = array();

        for($i=0; $i<count($result); $i++)
        {
            array_push($generi, $result[$i]['genere']);
        }

        return $generi;
    }

    public static function readPlaylists()
    {
        $client = Config::get("client");

        $result = $client->run(
            " MATCH (t:Traccia)-[c:CONTIENE]-(p:Playlist)
                WITH COUNT(c) as collegamenti, p
               WHERE collegamenti>100
               RETURN p.nomePlaylist AS nomePlaylist
               ORDER BY p.nomePlaylist"
        );

        $playlists = array();

        foreach($result as $r)
        {
            array_push($playlists, $r->get('nomePlaylist'));
        }

        /*shuffle($playlists);
        $playlists = array_slice($playlists, 0, 15);*/

        return $playlists;
    }

    public function query1(Request $request)
    {
        $client = Config::get("client");

        $result = $client->run(
            " MATCH (t:Traccia)-[]-(a:Artista)-[]-(g:Genere{descrizione:'".$request->input('genere')."'})
                    WITH a, AVG(t.popularity) AS popolarità
                    RETURN a AS artista, ROUND(popolarità, 2) AS popolarità
                    ORDER BY popolarità DESC LIMIT 10"
        );

        $artisti = array();

        for($i=0; $i<count($result); $i++)
        {
            array_push($artisti, $result[$i]['artista']);
        }

        return View("query1", ['generi' => $this->readGenres(), 'genereSelezionato' => $request->input('genere'),'artisti' => $artisti]);
    }

    public function query2(Request $request)
    {
        $client = Config::get("client");

        $result = $client->run(
            " MATCH (u:Utente)-[]->(p:Playlist)-[]->(t:Traccia)<-[]-(a:Artista)-[]->(g:Genere{descrizione:'".$request->input("genere")."'})
                    WITH DISTINCT u.NomeUtente as nomeUtente, g.descrizione as genere, duration.between(u.DataNascita, date()).years AS anni
                    RETURN round(avg(anni)*100)/100 AS mediaAnni"
        );

        return View("query2", ['generi' => $this->readGenres(), 'genereSelezionato' => $request->input('genere'),'etaMedia' => $result[0]->get('mediaAnni')]);
    }

    public function query3(Request $request)
    {
        $client = Config::get("client");

        $sensibilità = $request->input("sensibilità")/100;

        $result = $client->run(
            "MATCH(p:Playlist{nomePlaylist:\"".$request->input('playlist')."\"})-[]-(t:Traccia)
            WITH AVG(t.danceability) as danceability, AVG(t.instrumentalness) as instrumentalness,AVG(t.energy) as energy,AVG(t.liveness) as liveness,AVG(t.loudness) as loudness,AVG(t.speechiness) as speechiness,AVG(t.valence) as valence,AVG(t.acousticness) as acousticness
            MATCH(p:Playlist)-[]-(t1:Traccia)-[]-(a:Artista)
            WHERE NOT (p:Playlist{nomePlaylist:\"".$request->input('playlist')."\"})-[]-(t1:Traccia)
            AND danceability-".$sensibilità."<=t1.danceability<=".$sensibilità."+danceability
            AND instrumentalness-".$sensibilità."<=t1.instrumentalness<=".$sensibilità."+instrumentalness
            AND energy-".$sensibilità."<=t1.energy<=".$sensibilità."+energy
            AND liveness-".$sensibilità."<=t1.liveness<=".$sensibilità."+liveness
            AND loudness-".$sensibilità."<=t1.loudness<=".$sensibilità."+loudness
            AND speechiness-".$sensibilità."<=t1.speechiness<=".$sensibilità."+speechiness
            AND valence-".$sensibilità."<=t1.valence<=".$sensibilità."+valence
            AND acousticness-".$sensibilità."<=t1.acousticness<=".$sensibilità."+acousticness
            WITH ABS(t1.danceability-danceability) AS diff_danceability, ABS(t1.instrumentalness-instrumentalness) AS diff_instrumentalness, ABS(t1.energy-energy) AS diff_energy, ABS(t1.liveness-liveness) AS diff_liveness, ABS(t1.loudness-loudness) AS diff_loudness, ABS(t1.speechiness-speechiness) AS diff_speechiness, ABS(t1.valence-valence) AS diff_valence, ABS(t1.acousticness-acousticness) AS diff_acousticness, t1,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, a.artista as artista
            WITH diff_danceability+diff_instrumentalness+diff_energy+diff_liveness+diff_loudness+diff_speechiness+diff_valence+diff_acousticness AS diff, t1,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, artista
            RETURN t1, ROUND(danceability, 3) AS danceability, ROUND(instrumentalness, 3) AS instrumentalness, ROUND(energy,3) AS energy, ROUND(liveness,3) AS liveness,
            ROUND(loudness,3) AS loudness, ROUND(speechiness,3) AS speechiness, ROUND(valence,3) AS valence, ROUND(acousticness,3) AS acousticness, artista ORDER BY diff LIMIT 1"
        );

        //dd($result[0]['t1']['release_date']);



        if(count($result)!=0)
            return View("query3", ['playlists' => $this->readPlaylists(),'traccia' => $result[0]['t1'], 'playlistSelezionata' => $request->input('playlist'), 'sensSelezionata' => $request->input('sensibilità'),
                'acousticnessAvg' => $result[0]['acousticness'], 'danceabilityAvg' => $result[0]['danceability'], 'instrumentalnessAvg' => $result[0]['instrumentalness'], 'energyAvg' => $result[0]['energy'],
                'livenessAvg' => $result[0]['liveness'], 'loudnessAvg' => $result[0]['loudness'], 'speechinessAvg' => $result[0]['speechiness'], 'valenceAvg' => $result[0]['valence'],
                'artista' => $result[0]['artista']]);
        else
            return View("query3", ['playlists' => $this->readPlaylists(),'error' => True, 'playlistSelezionata' => $request->input('playlist'), 'sensSelezionata' => $request->input('sensibilità')]);
    }

    public function query4(Request $request)
    {
        $client = Config::get("client");

        $sensibilità = $request->input("sensibilità")/100;

        $result = $client->run(
            "MATCH (u:Utente{NomeUtente:\"".$request->input('utente')."\"})-[]-(p:Playlist)-[]-(t:Traccia)-[]-(a:Artista)-[]-(g:Genere)
            WITH COLLECT(DISTINCT g) AS generiAscoltati, COLLECT(DISTINCT (t)) AS tracceAscoltate, AVG(t.danceability) as danceability, AVG(t.instrumentalness) as instrumentalness,AVG(t.energy) as energy,AVG(t.liveness) as liveness,AVG(t.loudness) as loudness,AVG(t.speechiness) as speechiness,AVG(t.valence) as valence,AVG(t.acousticness) as acousticness
            MATCH (t:Traccia)-[]-(a:Artista)-[]-(g:Genere)
            WHERE NOT t IN tracceAscoltate
            AND g IN generiAscoltati
            AND danceability-".$sensibilità."<=t.danceability<=".$sensibilità."+danceability
            AND instrumentalness-".$sensibilità."<=t.instrumentalness<=".$sensibilità."+instrumentalness
            AND energy-".$sensibilità."<=t.energy<=".$sensibilità."+energy
            AND liveness-".$sensibilità."<=t.liveness<=".$sensibilità."+liveness
            AND loudness-".$sensibilità."<=t.loudness<=".$sensibilità."+loudness
            AND speechiness-".$sensibilità."<=t.speechiness<=".$sensibilità."+speechiness
            AND valence-".$sensibilità."<=t.valence<=".$sensibilità."+valence
            AND acousticness-".$sensibilità."<=t.acousticness<=".$sensibilità."+acousticness
            WITH ABS(t.danceability-danceability) AS diff_danceability, ABS(t.instrumentalness-instrumentalness) AS diff_instrumentalness, ABS(t.energy-energy) AS diff_energy, ABS(t.liveness-liveness) AS diff_liveness, ABS(t.loudness-loudness) AS diff_loudness, ABS(t.speechiness-speechiness) AS diff_speechiness, ABS(t.valence-valence) AS diff_valence, ABS(t.acousticness-acousticness) AS diff_acousticness,t,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, a.artista as artista
            WITH diff_danceability+diff_instrumentalness+diff_energy+diff_liveness+diff_loudness+diff_speechiness+diff_valence+diff_acousticness AS diff, t,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, artista
            RETURN t, ROUND(danceability, 3) AS danceability, ROUND(instrumentalness, 3) AS instrumentalness, ROUND(energy,3) AS energy, ROUND(liveness,3) AS liveness,
            ROUND(loudness,3) AS loudness, ROUND(speechiness,3) AS speechiness, ROUND(valence,3) AS valence, ROUND(acousticness,3) AS acousticness, artista ORDER BY diff LIMIT 1"
        );

        if(count($result)!=0)
            return View("query4", ['traccia' => $result[0]['t'], 'utenteSelezionato' => $request->input('utente'), 'sensSelezionata' => $request->input('sensibilità'),
                'acousticnessAvg' => $result[0]['acousticness'], 'danceabilityAvg' => $result[0]['danceability'], 'instrumentalnessAvg' => $result[0]['instrumentalness'], 'energyAvg' => $result[0]['energy'],
                'livenessAvg' => $result[0]['liveness'], 'loudnessAvg' => $result[0]['loudness'], 'speechinessAvg' => $result[0]['speechiness'], 'valenceAvg' => $result[0]['valence'],
                'artista' => $result[0]['artista']]);
        else
            return View("query4", ['playlists' => $this->readPlaylists(),'error' => True, 'utenteSelezionato' => $request->input('utente'), 'sensSelezionata' => $request->input('sensibilità')]);
    }

    public function query5(Request $request)
    {
        $client = Config::get("client");

        $sensibilità = $request->input("sensibilità")/100;

        $result = $client->run(
            "MATCH (u:Utente{NomeUtente:\"".$request->input('utente')."\"})-[]-(p:Playlist)-[]-(t:Traccia)-[]-(a:Artista)-[]-(g:Genere)
            WITH COLLECT(DISTINCT g) AS generiAscoltati, COLLECT(DISTINCT (t)) AS tracceAscoltate, COLLECT(DISTINCT a) AS artistiAscoltati, AVG(t.danceability) as danceability, AVG(t.instrumentalness) as instrumentalness,AVG(t.energy) as energy,AVG(t.liveness) as liveness,AVG(t.loudness) as loudness,AVG(t.speechiness) as speechiness,AVG(t.valence) as valence, AVG(t.acousticness) AS acousticness
            MATCH (t:Traccia)-[]-(a:Artista)-[]-(g:Genere)
            WHERE NOT t IN tracceAscoltate
            AND NOT a in artistiAscoltati
            AND g IN generiAscoltati
            AND duration.inDays(t.release_date, Date()).days < 365*4
            AND danceability-".$sensibilità."<=t.danceability<=".$sensibilità."+danceability
            AND instrumentalness-".$sensibilità."<=t.instrumentalness<=".$sensibilità."+instrumentalness
            AND energy-".$sensibilità."<=t.energy<=".$sensibilità."+energy
            AND liveness-".$sensibilità."<=t.liveness<=".$sensibilità."+liveness
            AND loudness-".$sensibilità."<=t.loudness<=".$sensibilità."+loudness
            AND speechiness-".$sensibilità."<=t.speechiness<=".$sensibilità."+speechiness
            AND valence-".$sensibilità."<=t.valence<=".$sensibilità."+valence
            AND acousticness-".$sensibilità."<=t.acousticness<=".$sensibilità."+acousticness
            WITH DISTINCT ABS(t.danceability-danceability) AS diff_danceability, ABS(t.instrumentalness-instrumentalness) AS diff_instrumentalness,
            ABS(t.energy-energy) AS diff_energy, ABS(t.liveness-liveness) AS diff_liveness, ABS(t.loudness-loudness) AS diff_loudness,
            ABS(t.speechiness-speechiness) AS diff_speechiness, ABS(t.valence-valence) AS diff_valence, ABS(t.acousticness-acousticness) AS diff_acousticness, t,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, a.artista as artista
            WITH DISTINCT diff_danceability+diff_instrumentalness+diff_energy+diff_liveness+diff_loudness+diff_speechiness+diff_valence+diff_acousticness AS diff, t,
            danceability, instrumentalness, energy, liveness, loudness, speechiness, valence, acousticness, artista
            RETURN t, ROUND(danceability, 3) AS danceability, ROUND(instrumentalness, 3) AS instrumentalness, ROUND(energy,3) AS energy, ROUND(liveness,3) AS liveness,
            ROUND(loudness,3) AS loudness, ROUND(speechiness,3) AS speechiness, ROUND(valence,3) AS valence, ROUND(acousticness,3) AS acousticness, artista ORDER BY diff LIMIT 5"
        );

        if(count($result)!=0)
            return View("query5", ['tracce' => $result, 'utenteSelezionato' => $request->input('utente'), 'sensSelezionata' => $request->input('sensibilità'),
                'acousticnessAvg' => $result[0]['acousticness'], 'danceabilityAvg' => $result[0]['danceability'], 'instrumentalnessAvg' => $result[0]['instrumentalness'], 'energyAvg' => $result[0]['energy'],
                'livenessAvg' => $result[0]['liveness'], 'loudnessAvg' => $result[0]['loudness'], 'speechinessAvg' => $result[0]['speechiness'], 'valenceAvg' => $result[0]['valence'],
                'artista' => $result[0]['artista']]);
        else
            return View("query5", ['playlists' => $this->readPlaylists(),'error' => True, 'utenteSelezionato' => $request->input('utente'), 'sensSelezionata' => $request->input('sensibilità')]);
    }


    public function query6(Request $request)
    {
        $client = Config::get("client");

        $sensibilità = $request->input("sensibilità")/100;

        $result = $client->run(
            "MATCH(p:Playlist{nomePlaylist:\"".$request->input('playlist')."\"})-[:CONTIENE]->(t:Traccia)<-[:CONTIENE]-(p2:Playlist)
            WITH  p2, COUNT(t) as nTracceInComune ORDER BY nTracceInComune DESC LIMIT 1
            MATCH(p:Playlist{nomePlaylist:\"".$request->input('playlist')."\"})-[:CONTIENE]->(t1:Traccia)
            WHERE NOT (t1)<--(p2)
            RETURN t1 ORDER BY t1.popularity DESC"
        );

        if(count($result)!=0)
            return View("query6", ['tracce' => $result, 'playlists' => $this->readPlaylists(), 'playlistSelezionata' => $request->input('playlist')]);
        else
            return View("query6", ['playlists' => $this->readPlaylists(),'error' => True, 'playlistSelezionata' => $request->input('playlist')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
