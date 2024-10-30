<?php


/**
 * Liefert die Ausgabe der Mannschaftsaufstellung anhand der XML - Daten aus TT-Live
 *
 * @param $params
 * @return string
 */
function getbttliveMannschaft(&$params) {
    $debug = 0;
    /**
     * XML aus lokalem tmp Folder laden
     */
    $options=get_option('bttlive_opt');
    $tableclassname = $params['tableclassname'];
    if ($tableclassname == '') { $tableclassname = $options['bttlive_tableclassname_Aufstellung'];}
    if ($tableclassname == '') { $tableclassname = $params['bttlive_tableclassname_TeamSpielplan']; }

    refreshbttliveData($params);
    bpe_lib_tools::getInstance()->log($params, __FUNCTION__ . ":" . __LINE__, "2");
    if($xml = simplexml_load_file($params['filename'], NULL, ($debug==1)?LIBXML_NOERROR:NULL))
    {
        $hasPK1 = false;
        $hasPK2 = false;
        $hasPK3 = false;
        $hasPK4 = false;
        $colspan = 6;
        foreach($xml->Content->Bilanz->Spieler as $key => $attribute)
        {
            if ($attribute->PK1) { $hasPK1 = true; }
            if ($attribute->PK2) { $hasPK2 = true; }
            if ($attribute->PK3) { $hasPK3 = true; }
            if ($attribute->PK4) { $hasPK4 = true; }
        }
        if ($hasPK1) { $colspan++;}
        if ($hasPK2) { $colspan++;}
        if ($hasPK3) { $colspan++;}
        if ($hasPK4) { $colspan++;}
        // neu
        $plan = '<h4>Aufstellung ' . $xml->Liga . '</h4>' . "\n";
        $plan .= '<table class="'.$tableclassname.'">';

        // Mannschaftsbild
        if ($params['mannschaftsname'] != '') {
            // Staffel und Mannschaftsid aus Optionen, wenn mannschaftsname gesetzt
            $taxonomies = array(
                'mannschaften',
            );

            $args = array(
                'slug'              => $params['mannschaftsname'],
            );
            $terms = get_terms($taxonomies, $args);

            foreach ($terms as $term) {
                bpe_lib_tools::getInstance()->log($term, __FUNCTION__ . ":" . __LINE__, "2");
                $term_meta = get_option( "bttlive_mannschaften_" .$term->term_id );
                $mannschaftsbild=$term_meta['mannschafts_bild'];
                $mannschaftsname=$term->name;
            }
            if ($mannschaftsbild != '') {
                $plan .= "<tr>\n";
                $plan .= '<th colspan="'. $colspan .'">';
                $plan .= '<figure class="'. $tableclassname . '">' . "\n";
                $plan .= '<img class="bttliveImg" src="' . $mannschaftsbild .
                    '" alt="Portrait von ' . $mannschaftsname .
                    '">';
                $plan .= '<figcaption class="' . $tableclassname .
                    '">' . $mannschaftsname . "\n" .
                    '</figcaption>' . "\n";
                $plan .= "</figure>";

                //$plan .= '<img src="'.$mannschaftsbild .'" >';

                $plan .= '</th>';
                $plan .= "</tr>\n";
            }
        }
        // Mannschaftsbild
        $plan .= "<tr>\n";
        $plan .= "<th>Pos</th>\n";
        $plan .= "<th>Spieler</th>\n";
        $plan .= "<th>Bem.</th>\n";
        $plan .= "<th>ST</th>\n";


        if ($hasPK1) { $plan .= "<th>PK1</th>"; }
        if ($hasPK2) { $plan .= "<th>PK2</th>"; }
        if ($hasPK3) { $plan .= "<th>PK3</th>"; }
        if ($hasPK4) { $plan .= "<th>PK4</th>"; }

        $plan .= "<th>Gesamt</th>\n";
        $plan .= "<th>LPZ</th>\n";
        $plan .= "</tr>\n";

        $zeile = 0;

        foreach($xml->Content->Bilanz->Spieler as $key => $attribute)
        {
            $zeile++;
            $plan .= "<tr";
            if ($zeile % 2 !=0) {
                //ungerade
                $plan .=  " class='even'>";
            } else {
                //gerade
                $plan .=  " class='odd'>";
            }
            $plan .= "<td>".$attribute->Position ."</td>\n";
            $plan .= '<td><a href="'.$params['baseurl'].'/default.aspx?L1=Ergebnisse&L2=TTStaffeln&L2P='.$params['staffel_id'].'&L3=Spieler&L3P='.$attribute->ID.'" target="_blank">'.$attribute->Spielername ."</a></td>\n";
            $plan .= "<td>".trim($attribute->Attribute) ."</td>\n";
            $plan .= "<td>".$attribute->Teilnahme ."</td>\n";
            if ($hasPK1) { $plan .= "<td>".$attribute->PK1 ."</td>\n"; }
            if ($hasPK2) { $plan .= "<td>".$attribute->PK2 ."</td>\n"; }
            if ($hasPK3) { $plan .= "<td>".$attribute->PK3 ."</td>\n"; }
            if ($hasPK4) { $plan .= "<td>".$attribute->PK4 ."</td>\n"; }
            $bilanz = "";
            if ($attribute->GesamtPlus) {
                $bilanz = $attribute->GesamtPlus .":".$attribute->GesamtMinus;
            }
            $plan .= "<td style='text-align:center;'>".$bilanz."</td>\n";
            $plan .= "<td>".$attribute->LivePZ ."</td>\n";
            $plan .= "</tr>\n";
        }
        $plan .= "</table>";
        return $plan;
    }
    else
    {
        return 'Konnte TT-Live-XML nicht laden';
    }
}
