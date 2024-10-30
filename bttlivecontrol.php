<?php


/**
 * Je nach Element-Name(Mannschaft, Tabelle, ...) wird die entsprechende Funktion aufgerufen
 *
 * @param $atts
 * @param null $content
 * @return bool|string
 */
function bttlivecontrol( $atts, $content = null ) {
    $options=bpe_lib_tools::getInstance()->getOptions();
    $a = shortcode_atts( array(
        'elementname' => '', 		// Rueckgabe-Element "Spielerportrait", "Vereinsplan", "Heimspielplan", "Rangliste", "Spielplan", "Tabelle" oder "14Tage"
        'mannschaft_id' => get_post_meta(get_the_ID(), "mannschaft_id", true), 		// TTLive Mannschaft ID
        'staffel_id' => get_post_meta(get_the_ID(), "staffel_id", true), 		// TTLive Staffel ID
        'tableclassname' => '', 	// Klassenname der Tabelle
        'own_team' => $options['bttlive_ownteam'], //'/VfB L.*beck/', 			Name des eigenen Teams
        'runde' => $options['bttlive_runde'],				// Vorrunde = 1 (default), Rückrunde = 2
        'spielklasse_anzeigen' => $options['bttlive_s_anz'],// Spielklasse im Heim oder Vereinsplan anzeigen
        'showxdays' => 0,			// 14Tage: Anzahl der Tage die dargestellt werden sollen
        'max' => 0,					// 14Tage: Anzahl der Tage die maximal dargestell werden sollen
        'aktuell' => 0,             // Bei Heim und Vereinsplan -> nur die nächsten x Spiele ...
        'saison' => $options['bttlive_saison'],           // gibt die Saison an, die für den Heim- und Auswärtsspielplan ausschlaggebend ist.
        'widget' => 0,				// 14Tage: Für die Darstellung in einem Widget
        'link' => '',               // Für 14Tage, Heimspiel- und Vereinsplamwidget
        'linktext' => '',           // Für 14Tage, Heimspiel- und Vereinsplamwidget
        'linkanzeigen' => true,     // Für 14Tage, Heimspiel- und Vereinsplamwidget anzeigen des Links zur Post-Seite des Hallen-/Vereinsplans aus dem Widget heraus
        'ergebnis_anzeigen' => true, // Für 14Tage, Heimspiel- und Vereinsplamwidget anzeigen des Links zur Post-Seite des Hallen-/Vereinsplans aus dem Widget heraus
        //START nur für die Tabelle
        'teamalias' => '',			// Teamname:Alias;Teamname2:Alias2;
        'showleague' => true,		// Ueberschrift-Anzeige der Liga
        'showmatchecount' => true,	// Anzahl der gemachten Spiele
        'showsets' => true,			// Anzahl der gewonnenen/verlorenen Saetze
        'showgames' => true,		// Anzahl der gewonnenen/verlorenen Spiele
        'aufstiegsplatz' => 1,		// Aufstiegsplaetze bis
        'abstiegsplatz' => 9,		// Abstiegsplaetze ab
        'relegation' => '2',			// Relegationsplätze
        //ENDE nur für Tabelle
        //Start nur für Portrait
        'mannschaftsname' => '', // name aus Mannschaften, eingetragen in Spielerportraits -> slug !!!!
        'spieler' => array(), // [0] =>'Timo Boll', alle Namen, die ausgegeben werden sollen (optional)(->Einzelportrait)
        'portrait_anzeigen' => true, // Soll Portraifoto angezeigt werden?
        'action_anzeigen' => true, // Soll Actionfoto angezeigt werden?
        'gebjahr_anzeigen' => true, // Soll Geburtsjahr angezeigt werden?
        'verjahr_anzeigen' => true, // Soll im Verein angezeigt werden?
        'mfuehrer_anzeigen' => true, // Soll Mannschaftsführer angezeigt werden
        'schlaghand_anzeigen' => true, // Soll Schlaghand (links/rechts) angezeigt werden?
        'spieltyp_anzeigen' => true, // Soll Spieltyp angezeigt werden?
        'geschlecht_anzeigen' => true, // Soll Geschlecht angezeigt werden?
        'fragen_anzeigen' =>true, // Sollen Spielerfragen angezeigt werden?
        'editor_anzeigen' => true, // Soll freier Text angezeigt werden?
        //Ende  nur für Portrait
        'tage_zurueck'  => '0',             // wirkt bei den Spielplänen
        'display_type' => false,	// die letzten 14Tage (0 - default) oder die naechsten 14Tage (1)
        'display_all' => false,     //Rangliste: zeigt alle oder nur Spieler mit gültiger LivePZ
        'refresh' => $options['bttlive_refreshHours'],				// Anzahl Stunden bis die Daten erneut vom Live-System aktualisiert werden sollen
    ), $atts );

    $a = bttlive_tools::getInstance()->ttliveurl($a); // setzt filename und url in a
    bpe_lib_tools::getInstance()->log($a, __FUNCTION__ .":" . __LINE__, 2);
    switch (strtolower($a['elementname'])) {
        case "mannschaft":
            $retval = getbttliveMannschaft($a);
            break;
        case "tabelle":
            $retval = getbttliveTabelle($a);
            break;
        case "rangliste":
            $rangliste = new bttliveRangliste();
            /*$a['filename'] = ABSPATH . "wp-content/plugins/bttlive/bttlive-files/ttliveRangliste.xml";
            $a['url'] = $a['baseurl']."/Export/default.aspx?SpartenID=".$options['bttlive_divisionID']."&Format=XML&SportArt=96&Area=VereinLivePZ";
            */
            $retval = $rangliste->getbttliveRangliste($a);
            break;
        case "spielerportrait":
            $portrait = new bttliveSpielerPortrait();
            if ( $a['widget'] == 1 ) {
                $retval = $portrait->getbttliveSpielerPortraitDataForWidget($a);
            } else {
                $retval = $portrait->getbttliveSpielerPortrait($a);
            }
            break;
        case "klassenspielplan":
            $spielplan = new bttliveSpielplan();
            /*
            $runde="";
            if ( $a['runde'] <= 1  ):
                $runde .= "&Runde=1";
            else:
                $runde .= "&Runde=2";
            endif;
            $a['filename'] = ABSPATH . 'wp-content/plugins/bttlive/bttlive-files/bttlive' . DS . $a['elementname'] .$runde . "Staffel=" . $a['staffel_id'] .".xml";
            $a['url'] = $a['baseurl']."/Export/default.aspx?LigaID=".$a['staffel_id']."&Format=XML&SportArt=96&Area=Spielplan" . $runde ;
            */
            if ( $a['widget'] == 1 ) {
                $retval = $spielplan->getbttliveSpielplanDataForWidget($a);
            } else {
                $retval = $spielplan->getbttliveSpielplan($a);
            }
            break;
        case "14tage":
            $spielplan = new bttliveSpielplan();

            if ( $a['widget'] == 1 ) {
                $retval = $spielplan->getbttliveSpielplanDataForWidget($a);
            } else {
                $retval = $spielplan->getbttliveSpielplan($a);
            }
            break;
        case "spielplan":
            $spielplan = new bttliveSpielplan();
            if ( $a['widget'] == 1 ) {
                $retval = $spielplan->getbttliveSpielplanDataForWidget($a);
            } else {
                $retval = $spielplan->getbttliveSpielplan($a);
            }
            break;
        case "heimspielplan":
        case "vereinsplan":
            $spielplan = new bttliveSpielplan();
            /*
            $a = bttlive_tools::getInstance()->ttliveplanurl($a);*/

            if ( $a['widget'] == 1 ) {
                $retval = $spielplan->getbttliveSpielplanDataForWidget($a);
            } else {
                $retval = $spielplan->getbttliveSpielplan($a);
            }
            break;
        default:
            $retval = "Konnte Elementname: ". $a['elementname'] . "nicht auswerten";
    }
    return $retval;
}


add_shortcode( 'bttlive', 'bttlivecontrol');

