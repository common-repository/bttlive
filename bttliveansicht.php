<?php

add_action('the_content','bttlive_ansicht');

/**
 * Gibt alle vereinbarten TTLive - Elemente in den Beiträge(posts) aus
 * @param $content
 * @return string
 */
function bttlive_ansicht( $content) {

    $options=bpe_lib_tools::getInstance()->getOptions();

    if ( get_post_type() != 'bttlive') return($content);


    $customs = get_post_custom(get_the_ID());

    if (isset($customs['_mannschaftsname'])) {
        $mannschaftsname = $customs['_mannschaftsname'][0];
    } else {
        $mannschaftsname = '';
    }
    // Staffel und Mannschaftsid aus Optionen
    $taxonomies = array(
        'mannschaften',
    );

    $args = array(
        'slug'              => $mannschaftsname,
    );
    $terms = get_terms($taxonomies, $args);
    if (!$terms) {
        $message= 'Konnte Mannschaften mit slug="' . $mannschaftsname . '"nicht laden';
        echo $message;
        return;
    }
    foreach ($terms as $term) {
        $term_meta = get_option( "bttlive_mannschaften_" .$term->term_id );
        $staffelid =$term_meta['staffel_id'];
        $mannschaftsid = $term_meta['mannschafts_id'];
    }

    // Ende
    $tage_anzahl = $customs['_tage_anzahl'][0];
    $tabelle_anzeigen = $customs['_tabelle_anzeigen'][0];
    $aufstellung_anzeigen = $customs['_aufstellung_anzeigen'][0];
    $nspiele_anzeigen = $customs['_nspiele_anzeigen'][0];
    $aspiele_anzeigen = $customs['_aspiele_anzeigen'][0];
    $heimspielplan_anzeigen = $customs['_heimspielplan_anzeigen'][0];
    $vereinsplan_anzeigen = $customs['_vereinsplan_anzeigen'][0];
    $spielerportrait_anzeigen = $customs['_spielerportrait_anzeigen'][0];
    $klassenspielplan_anzeigen = $customs['_klassenspielplan_anzeigen'][0];

    $layout = $customs['_layout'][0];
    if ($layout == '') { $layout = 1;}
    $saison = $customs['_saison'][0];
    $runde = $customs['_runde'][0];
    if ($saison == '') {
        $saison = $options['bttlive_saison'];
    }
    if ($runde == '') {
        $runde = $options['bttlive_runde'];
    }
    $atts = array(
        'elementname' => '', 		// Rueckgabe-Element "Vereinsplan", "Heimspielplan", "Spielplan", "Tabelle" oder "14Tage"
        'mannschaftsname' => $mannschaftsname,
        'mannschaft_id' => $mannschaftsid, 		// TTLive Mannschaft ID
        'staffel_id' => $staffelid, 		// TTLive Staffel ID
        'tableclassname' => '', 	// Klassenname der Tabelle
        'runde' => $runde,				// Vorrunde = 1 (default), Rückrunde = 2
        'showxdays' => 0,			// 14Tage: Anzahl der Tage die dargestellt werden sollen
        'max' => 0,					// 14Tage: Anzahl der Tage die maximal dargestell werden sollen
        'aktuell' => 0,             // Bei Heim und Vereinsplan -> nur die nächsten x Spiele ...
        'saison' => $saison,           // gibt die Saison an, die für den Heim- und Auswärtsspielplan ausschlaggebend ist.
        //todo aktuell und saison für nutzer dokumentieren!
        'widget' => 0,				// Für die Darstellung in einem Widget, aktiv bei: 14Tage
        'teamalias' => '',			// Teamname:Alias;Teamname2:Alias2;
        'showleague' => true,		// Ueberschrift-Anzeige der Liga
        'showmatchecount' => true,	// Anzahl der gemachten Spiele
        'showsets' => true,			// Anzahl der gewonnenen/verlorenen Saetze
        'showgames' => true,		// Anzahl der gewonnenen/verlorenen Spiele
        'aufstiegsplatz' => 1,		// Aufstiegsplaetze bis
        'abstiegsplatz' => 9,		// Abstiegsplaetze ab
        'relegation' => '2',			// Relegationsplätze
        'display_type' => 0,	// die letzten 14Tage (0 - default) oder die naechsten 14Tage (1)
        'own_team' => $options['bttlive_ownteam'], //'/VfB L.*beck/', 			Name des eigenen Teams
        'refresh' => $options['bttlive_refreshHours'],				// Anzahl Stunden bis die Daten erneut vom Live-System aktualisiert werden sollen
        // Spielerportrait
        'portrait_anzeigen' => true,
        'gebjahr_anzeigen' => true,
        'verjahr_anzeigen' => true,
        'schlaghand_anzeigen' => true,
        'spieltyp_anzeigen' => true,
        'geschlecht_anzeigen' => true,
        'action_anzeigen' => true,
        'editor_anzeigen' => true,
        'mfuehrer_anzeigen' => true,
        'fragen_anzeigen' => true,
    );


    if ( $tage_anzahl || $tage_anzahl != '0' ) {
        $atts['showxdays'] = $tage_anzahl;
    }
    $contentcount=0;
    if ( $aufstellung_anzeigen == '1' ) {$contentcount++;}
    if ( $tabelle_anzeigen == '1' ) {$contentcount++;}
    if ( $aspiele_anzeigen == '1' ) {$contentcount++;}
    if ( $spielerportrait_anzeigen == '1' ) {$contentcount++;}
    if ( $klassenspielplan_anzeigen == '1' ) {$contentcount++;}
    if ( $nspiele_anzeigen == '1' ) {$contentcount++;}
    if ( $vereinsplan_anzeigen == '1' ) {$contentcount++;}
    if ( $heimspielplan_anzeigen == '1' ) {$contentcount++;}
    if ( $content ) {$contentcount++;}
    bttlive_tools::getInstance()->ansichts_init();
    $tmpcontent = '<div class="bttlive_ansicht" >' . "\n";
    if ($content) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $tmpcontent .= $tmp . $content;
    }
    //     Aufstellung am Ende des Contents anfügen
    if ( $aufstellung_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);

        $atts['elementname'] = 'Mannschaft';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    //     Tabelle am Ende des Contents anfügen
    if ( $tabelle_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'Tabelle';
        $tmpcontent .= $tmp .  bttlivecontrol($atts);
    }
    if ( $aspiele_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'Spielplan';
        $tmpcontent .= $tmp .  bttlivecontrol($atts);
    }
    if ( $klassenspielplan_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'Klassenspielplan';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    if ( $spielerportrait_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'SpielerPortrait';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    //     Spielplan am Ende des Contents anfügen
    if ( $nspiele_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = '14Tage';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    if ( $vereinsplan_anzeigen == '1' ) {
        $tmp = bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'Vereinsplan';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    if ( $heimspielplan_anzeigen == '1' ) {
        $tmp .= bttlive_tools::getInstance()->ansichts_layout($contentcount, $layout);
        $atts['elementname'] = 'Heimspielplan';
        $tmpcontent .= $tmp . bttlivecontrol($atts);
    }
    $tmpcontent .= '</div>' . "\n";
    $tmpcontent .= '</div>' . "\n";
    $content = $tmpcontent;
    return $content;

}

