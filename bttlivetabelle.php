<?php


/**
 *  Liefert die Ausgabe der Tabelle anhand der XML - Daten aus TT-Live
 *
 * @param $params
 * @return string
 */
function getbttliveTabelle(&$params){
    $debug = 0;
    $ladder='';
    /**
     * XML aus lokalem tmp Folder laden
     */
    $options=get_option('bttlive_opt');
    $tableclassname = $options['bttlive_tableclassname_Tabelle'];
    if ($params['tableclassname'] != '') { $tableclassname = $params['tableclassname']; }

    refreshbttliveData($params);

    if($xml = simplexml_load_file($params['filename'], NULL, ($debug==1)?LIBXML_NOERROR:NULL))
    {
        $params['own_team'] = utf8_encode($params['own_team']);

        $teamurl = $params['baseurl'].'/?L1=Ergebnisse&L2=TTStaffeln&L2P='.$params['staffel_id'].'&L3=Mannschaften&L3P='.$params['mannschaft_id'];

        // Ersetzungen aus Parameter in Array umwandeln
        if($params['teamalias'])
        {
            $teams = explode(';' , $params['teamalias']);
            foreach($teams as $team)
            {
                $tmpteam = explode(':' , $team);
                $aliases[$tmpteam[0]] = $tmpteam[1];
            }
        }
        // Split Relegationsplätze
        $relegations = array();
        if($params['relegation'])
        {
            $relegations = explode(",", $params['relegation']);
        }
        //START Tablehead

        $ladder .= "<table class='" . $tableclassname . "'>\n";
        $ladder .= "<thead>";
        if(! empty($params['showleague']))
        {
            $ladder .= '<tr><th colspan="6"><h4 style="text-align:center"><a href="'.$xml->Ligalink.'" target="_blank" >' . $xml->Liga. '</a></h4></th></tr>';
        }
        $ladder .= '<tr><th>Platz</th><th style="text-align:left;">Team</th>';
        if(! empty($params['showmatchecount']))
        {
            $ladder .= '<th style="text-align:center">Anz</th>';
        }
        if(! empty($params['showmatches']))
        {
            $ladder .= '<th style="text-align:center">S</th><th style="text-align:center">U</th><th style="text-align:center">N</th>';
        }
        if(! empty($params['showsets']))
        {
            $ladder .= '<th style="text-align:center;">SatzDif</th>';
        }
        if(! empty($params['showgames']))
        {
            $ladder .= '<th style="text-align:center">Spiele</th>';
        }
        $ladder .=  '<th style="text-align:center">Punkte</th></tr></thead>'."\n";
        //ENDE Tablehead
        $zeile=0;
        foreach($xml->Content->Tabelle->Mannschaft as $key => $attribute)
        {
            $zeile++;
            $teamname = (string) $attribute->Mannschaft;
            if(! empty($params['teamalias']))
            {
                if(array_key_exists((string) $attribute->Mannschaft , $aliases))
                {
                    $teamname = $aliases[(string) $attribute->Mannschaft];
                }
            }
            else
            {
                $teamname = (string) $attribute->Mannschaft;
            }


            //Tablerow --> even/odd and aufstieg/abstiegsplatz
            $ladder .= '<tr class="';

            if ($zeile <= $params['aufstiegsplatz'])
            {
                $ladder .= 'cAufstieg';
            }
            if ($zeile >= $params['abstiegsplatz'])
            {
                $ladder .= 'cAbstieg';
            }
            if (in_array(strval($zeile), $relegations))
            {
                $ladder .= 'cRelegation';
            }

            if ($zeile % 2 !=0) {
                //ungerade
                $ladder .=  ' even';
            } else {
                //gerade
                $ladder .=  ' odd';
            }

            if (preg_match($params['own_team'], $attribute->Mannschaft))
            {
                $ladder .= ' cOwnTeam" onclick="window.open(\''.$teamurl.'\', \'_blank\', \'\'); return false;">';
            } else {
                //$ladder .= '">';
                //$ladder .= '" /*' . $attribute->Mannschaft . '=' . $params['own_team'] . ' */ >';
                $ladder .= '"' . ">\n";
            }

            //Tabledata...
            $ladder .= "<td>".$attribute->Platz ."</td><td>". $teamname ."</td>\n";

            if(! empty($params['showmatchecount'] ))
            {
                $ladder .= '<td>'.  $attribute->Spiele ."</td>\n";
            }
            if(! empty($params['showmatches']))
            {
                $ladder .= '<td>'.  $attribute->Siege .'</td><td style="text-align:center;">'.  $attribute->Unentschieden .'</td><td style="width:25px;text-align:center;">'.  $attribute->Niederlagen ."</td>\n";
            }
            if(! empty($params['showsets']))
            {
                $ladder .= '<td>' .  $attribute->SaetzeDif . "</td>\n";
            }
            if(! empty($params['showgames'] ))
            {
                $ladder .= '<td>' .  $attribute->SpielePlus . ":" .$attribute->SpieleMinus . "</td>\n";                    }

            $ladder .=  "<td>".$attribute->PunktePlus . ":" .$attribute->PunkteMinus . "</td></tr>\n";
        }
        $ladder .=  "</table>\n";
        return $ladder;
    }
    else
    {
        return 'Konnte TT-Live-XML nicht laden';
    }
}
