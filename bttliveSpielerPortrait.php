<?php

/**
 * Class bttliveSpielerPortrait
 * View des Spielerportraits
 */
class bttliveSpielerPortrait
{
    public static $noxml = 'Konnte Live-XML nicht laden';
    public static $noposts = "Konnte Mannschaft in Spielern nicht finden!";
    public static $noterms = "Konnte Mannschaft nicht finden!";
    public static $notermsinoptions = "Konnte Mannschaftsdaten nicht finden!";
    public static $SpielerPortrait = 'SpielerPortrait';

    protected $_content;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }


    protected $_debug = 0;

    /**
     * @return int
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param int $debug
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    protected $_params = array();

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }


    /**
     * Spielerporträtansicht!!!
     */
    public function construct() {
           add_action('the_content', array($this, "portrait_ansicht"));
    }

    public function portrait_ansicht($content) {

        $term_list = wp_get_post_terms(get_the_ID(), 'mannschaften', array("fields" => "all"));
        if (empty($term_list)){
            return($content);
        }
        foreach ($term_list[0] as $k => $v) {
            if ($k == 'slug' ) {
                $params['mannschaftsname'] = $v;
                $params['SpielerPostID']=get_the_ID();
                $spdata = get_post_custom(get_the_ID()); // Spielerdaten
                $params['Spielername']=$spdata['_spieler_name'][0];
                $params['portrait_anzeigen'] = true;
                $params['gebjahr_anzeigen'] = true;
                $params['verjahr_anzeigen'] = true;
                $params['schlaghand_anzeigen'] = true;
                $params['spieltyp_anzeigen'] = true;
                $params['geschlecht_anzeigen'] = true;
                $params['action_anzeigen'] = true;
                $params['editor_anzeigen'] = true;
                $params['mfuehrer_anzeigen'] = true;
                $params['fragen_anzeigen'] = true;
            }
        }

        $params = bttlive_tools::getInstance()->ttliveurl($params); // Url für Spielerportrait festlegen
        $this->getbttliveSpielerPortraitDataForWidget($params);
        $this->setContent($content);
        return($content);
    }


    /**
     * Formatiert die Daten für den Spielplan als HTML Ausgabe
     *
     * @param $params
     * @return string
     */
    public function getbttliveSpielerPortrait(&$params)
    {

        $this->getbttliveSpielerPortraitDataForWidget($params);
        return $this->getContent();
    }



    /**
     * Formatiert die Daten für die nächsten 14Tage als HTML Ausgabe im Widget
     *
     * @param $params
     * @return string
     */
    public function getbttliveSpielerPortraitDataForWidget(&$params)
    {
        $options = bpe_lib_tools::getInstance()->getOptions();
        $taxonomy = 'mannschaften';
        $terms = get_terms( $taxonomy, array(
            'slug' => $params['mannschaftsname'],
        ) );

        if ( empty( $terms ) || is_wp_error( $terms ) ){
            bpe_lib_tools::getInstance()->log(self::$noterms, __METHOD__ . ":" . __LINE__);
            return self::$noterms;
        }
        foreach ( $terms as $term ) {
            $t_id = $term->term_id;
        }
        $term_meta = get_option("bttlive_mannschaften_$t_id"); // in Options
        if ( ($term_meta['mannschafts_id'] == '') || ($term_meta['staffel_id'] == '') ){
            bpe_lib_tools::getInstance()->log(self::$notermsinoptions, __METHOD__ . ":" . __LINE__);
            return self::$notermsinoptions;
        }

        $args = array(
            'post_type' => 'bttlive_spieler',
            'tax_query' => array(
                array(
                    'taxonomy' => 'mannschaften',
                    'field' => 'slug',
                    'terms' => array($params['mannschaftsname']),
                ),
            ),
        );
        $query = new WP_Query($args);
        if (!$query->have_posts()) {
            bpe_lib_tools::getInstance()->log(self::$noposts, __METHOD__ . ":" . __LINE__);
            return self::$noposts;
        }


        $params['mannschaft_id'] = $term_meta['mannschafts_id'];
        $params['staffel_id'] = $term_meta['staffel_id'];
        // -> Filename und url ttlive
        /*$params['filename'] = ABSPATH . "wp-content/plugins/bttlive/bttlive-files/bttlive" . $params['staffel_id'] . "-" . $params['mannschaft_id'] . "_". $options['bttlive_runde'] .".xml";
        $params['url'] = $params['baseurl']."/Export/default.aspx?TeamID=".$params['mannschaft_id']."&WettID=". $params['staffel_id'] . "&Format=XML&Runde=". $options['bttlive_runde'] ."&SportArt=96&Area=TeamReport";*/
        $params = bttlive_tools::getInstance()->ttliveurl($params);
        $this->setParams($params);


        refreshbttliveData($params);
        if (! $xml = simplexml_load_file($params['filename'], NULL, ($this->getDebug() == 1) ? LIBXML_NOERROR : NULL)) {
            bpe_lib_tools::getInstance()->log(self::$noxml, __METHOD__ . ":" . __LINE__);
            return self::$noxml. "->" . __METHOD__ . ":" . __LINE__;
        }



        $hasPK1 = false;
        $hasPK2 = false;
        $hasPK3 = false;
        $hasPK4 = false;
        $spieler = array();
        foreach ($xml->Content->Bilanz->Spieler as $key => $attribute) {

            if (($params['SpielerPostID']== '') || (($params['SpielerPostID']!= '')
                    && ((string)$attribute->Spielername)== $params['Spielername'])) {
                $spieler[] =
                    array(
                        'Position' => (string)$attribute->Position,
                        'Spielername' => (string)$attribute->Spielername,
                        'Spielertyp' => 'Spieler',
                        'PK1' => (string)$attribute->PK1,
                        'PK2' => (string)$attribute->PK2,
                        'PK3' => (string)$attribute->PK3,
                        'PK4' => (string)$attribute->PK4,
                        'GesamtPlus' => (string)$attribute->GesamtPlus,
                        'GesamtMinus' => (string)$attribute->GesamtMinus,
                        'Teilnahme' => (string)$attribute->Teilnahme,
                        'LivePZ' => (string)$attribute->LivePZ,
                        'title' => '',
                        'vorname' => '',
                        'nachname' => '',
                        'portrait_bild' => '',
                        'action_bild' => '',

                    );
                if ($attribute->PK1) {
                    $hasPK1 = true;
                }
                if ($attribute->PK2) {
                    $hasPK2 = true;
                }
                if ($attribute->PK3) {
                    $hasPK3 = true;
                }
                if ($attribute->PK4) {
                    $hasPK4 = true;
                }
            }
        }
        while ($query->have_posts()) {
            $query->the_post(); // -> Zugriff auf the_title etc.
            $spdata = get_post_custom(get_the_ID()); // Spielerdaten
            if (($params['SpielerPostID']== '') ||
                ( ($params['SpielerPostID']!= '') && ($spdata['_spieler_name'][0]==$params['Spielername']))
            ) {
                foreach ($spieler as $pos => &$spielerdaten) {
                    if ($spielerdaten['Spielername'] == $spdata['_spieler_name'][0]) {

                            $spielerdaten['title'] = get_the_title();
                            $spielerdaten['vorname'] = $spdata['_vorname'][0];
                            $spielerdaten['nachname'] = $spdata['_nachname'][0];
                            $spielerdaten['portrait_bild'] = $spdata['_portrait_bild'][0];
                            $spielerdaten['action_bild'] = $spdata['_action_bild'][0];
                            $spielerdaten['gebjahr'] = $spdata['_gebjahr'][0];
                            $spielerdaten['verjahr'] = $spdata['_verjahr'][0];
                            $spielerdaten['schlaghand'] = $spdata['_schlaghand'][0];
                            $spielerdaten['spieltyp'] = $spdata['_spieltyp'][0];
                            $spielerdaten['geschlecht'] = $spdata['_geschlecht'][0];
                            $spielerdaten['mannschafts_fuehrer'] = $spdata['_mannschafts_fuehrer'][0];
                            $spielerdaten['link'] = get_the_permalink();
                            for ($i = 1; $i <= $options['anzahl_spieler_fragen']; $i++) {
                                if ($spdata['_antwort' . $i][0]) {
                                    $spielerdaten['antwort' . $i] = $spdata['_antwort' . $i][0];
                                    $spielerdaten['frage' . $i] = $spdata['_frage' . $i][0];
                                }
                            }
                            $spielerdaten['content'] = get_the_content();
                        }
                }
                if ( ! bpe_lib_tools::getInstance()->recursiveFind($spieler,$spdata['_spieler_name'][0]) ) {

                    // Wenn Spieler aus Porträtpost nicht gefunden wird, so bitte anlegen
                    $spieler[] =
                        array(
                            'Position'              => $spdata['_position'][0],
                            'Spielername'           => $spdata['_spieler_name'][0],
                            'Spielertyp'            => $spdata['_portraittyp'][0],
                            // keine Daten bei Trainern oder Betreuern
                            'PK1'                   => '',
                            'PK2'                   => '',
                            'PK3'                   => '',
                            'PK4'                   => '',
                            'GesamtPlus'            => '',
                            'GesamtMinus'           => '',
                            'Teilnahme'             => '',
                            'LivePZ'                => '',
                            'title'                 => get_the_title(),
                            'vorname'               => $spdata['_vorname'][0],
                            'nachname'              => $spdata['_nachname'][0],
                            'portrait_bild'         => $spdata['_portrait_bild'][0],
                            'action_bild'           => $spdata['_action_bild'][0],
                            'gebjahr'               => $spdata['_gebjahr'][0],
                            'verjahr'               => $spdata['_verjahr'][0],
                            'schlaghand'            => $spdata['_schlaghand'][0],
                            'spieltyp'              => $spdata['_spieltyp'][0],
                            'geschlecht'            => $spdata['_geschlecht'][0],
                            'mannschafts_fuehrer'   => $spdata['_mannschafts_fuehrer'][0],
                            'link'                  =>get_the_permalink(),
                            'frage1'                => $spdata['_frage1'][0],
                            'frage2'                => $spdata['_frage2'][0],
                            'frage3'                => $spdata['_frage3'][0],
                            'frage4'                => $spdata['_frage4'][0],
                            'frage5'                => $spdata['_frage5'][0],
                            'frage6'                => $spdata['_frage6'][0],
                            'frage7'                => $spdata['_frage7'][0],
                            'frage8'                => $spdata['_frage8'][0],
                            'frage9'                => $spdata['_frage9'][0],
                            'frage10'                => $spdata['_frage10'][0],
                            'antwort1'                => $spdata['_antwort1'][0],
                            'antwort2'                => $spdata['_antwort2'][0],
                            'antwort3'                => $spdata['_antwort3'][0],
                            'antwort4'                => $spdata['_antwort4'][0],
                            'antwort5'                => $spdata['_antwort5'][0],
                            'antwort6'                => $spdata['_antwort6'][0],
                            'antwort7'                => $spdata['_antwort7'][0],
                            'antwort8'                => $spdata['_antwort8'][0],
                            'antwort9'                => $spdata['_antwort9'][0],
                            'antwort10'                => $spdata['_antwort10'][0],
                            'content'                   => get_the_content(),
                        );
                }
            }
        }
        bpe_lib_tools::getInstance()->log($spieler,__METHOD__ . ":" . __LINE__, 3);
        bpe_lib_tools::getInstance()->log($params, __METHOD__ . ":" . __LINE__, 3);

        $tableclassname = $options['bttlive_tableclassname_' . $params['elementname']];
        if ($params['tableclassname'] != '') {
            $tableclassname = $params['tableclassname'];
        }
        $plan = "<table class='" . $tableclassname . "'>\n";
        $plan .= "<tbody>\n";

        //ksort($spieler);
        foreach ($spieler as $key => $spielerdaten2) {
            // Alle Spieler einer Mannschaft
            $stern="";
            if ($spielerdaten2['_mannschafts_fuehrer']== true) {
                $stern="*";
            }
            $spielername = $spielerdaten2['Spielername'];
            if ((! $spielerdaten2['portrait_bild'])&& (! $spielerdaten2['action_bild'])) {
                $plan .= '
                <tr class="' .$options['cssSpielerposueberschrift'] .'"><td colspan="2">
                ' . $spielerdaten2['Position'] ." " . $spielername . $stern .'
                </td></tr>';
                echo "\n";
            }
            if (($params['portrait_anzeigen'] == true ) && ($spielerdaten2['portrait_bild'])){
                $plan .= '
                <tr ><td colspan="2">
                <figure class="' .$options['cssPortraitbild'] .'">
                   <a href="'. $spielerdaten2['link'] . '"><img class="bttliveImg" src="' . $spielerdaten2['portrait_bild'] . '" alt="Portrait von ' . $spielerdaten2['vorname'] . " " . $spielerdaten2['nachname'] . '"></a>
                <figcaption class="' .$options['cssSpielerposueberschrift'] . '">' . $spielerdaten2['Position'] ." " . $spielername . $stern .'</figcaption>
                </figure>
                </td></tr>' . "\n";
            }

            $bilanzClass = "";
            if ($spielerdaten2['GesamtPlus'] != '') {
               $bilanz = $spielerdaten2['GesamtPlus'] . ":" . $spielerdaten2['GesamtMinus'];
               if ($spielerdaten2['GesamtPlus'] > $spielerdaten2['GesamtMinus'] ) {
                  $bilanzClass="plus";
               }
               if ($spielerdaten2['GesamtPlus'] == $spielerdaten2['GesamtMinus'] ) {
                  $bilanzClass="ausgeglichen";
               }
               if ($spielerdaten2['GesamtPlus'] < $spielerdaten2['GesamtMinus'] ) {
                  $bilanzClass="minus";
               }
               $plan .= '<tr class="' .$options['cssSpielerbilanz'] . " ". $bilanzClass . '"><td>Bilanz</td><td>' . $bilanz . '</td></tr>';
               echo "\n";
               $plan .= '<tr class="' .$options['cssSpielerposition'] .'"><td>Mannschafts-Position</td><td>' . $spielerdaten2['Position'] . '</td></tr>';
               echo "\n";
               $plan .= '<tr class="' .$options['cssAnzahlSpiele'] .'"><td>Anzahl Spiele</td><td>' . $spielerdaten2['Teilnahme'] . '</td></tr>';
               echo "\n";
            }
            $plan .= '<tr class="' .$options['cssLivePZ'] .'"><td>LivePZ</td><td>' . $spielerdaten2['LivePZ'] . '</td></tr>';
            echo "\n";
            for ($i = 1; $i <= $options['anzahl_spieler_fragen']; $i++) {
                if ($spielerdaten2['antwort' . $i]) {
                    $plan .= '<tr class="' .$options['cssSpieler_frage'] .'"><td>' . $spielerdaten2['frage' . $i] . '</td><td>' . $spielerdaten2['antwort' . $i] . '</td></tr>';
                    echo "\n";
                }
            }

            if (($spielerdaten2['gebjahr']) && ($params['gebjahr_anzeigen'] == true )) {
                $plan .= '<tr class="' .$options['cssGeburtsjahr'] .'"><td>Geburtsjahr</td><td>' . $spielerdaten2['gebjahr'] . '</td></tr>';
                echo "\n";
            }
            if (($spielerdaten2['verjahr']) && ($params['verjahr_anzeigen'] == true )) {
                $plan .= '<tr class="' .$options['cssVereinsjahr'] .'"><td>Im Verein seit</td><td>' . $spielerdaten2['verjahr'] . '</td></tr>';
                echo "\n";
            }
            if ($spielerdaten2['Spielertyp'] == "Spieler") {
               if (($spielerdaten2['schlaghand']) && ($params['schlaghand_anzeigen'] == true )) {
                   $plan .= '<tr class="' .$options['cssSchlaghand'] .'"><td>Schlaghand</td><td>' . $spielerdaten2['schlaghand'] . '</td></tr>';
                   echo "\n";
               }
               if (($spielerdaten2['spieltyp'])&& ($params['spieltyp_anzeigen'] == true )) {
                   $plan .= '<tr class="' .$options['cssSpieltyp'] .'"><td>Spieltyp</td><td>' . $spielerdaten2['spieltyp'] . '</td></tr>';
                   echo "\n";
               }
            }
            if (($spielerdaten2['geschlecht'])&& ($params['geschlecht_anzeigen'] == true )) {
                $plan .= '<tr class="' .$options['cssGeschlecht'] .'"><td >Geschlecht</td><td>' . $spielerdaten2['geschlecht'] . '</td></tr>';
                echo "\n";
            }
            if (($params['action_anzeigen'] == true ) && ($spielerdaten2['action_bild'])){
                $plan .= '
                <tr ><td colspan="2">
                <figure class="' .$options['cssActionbild'] .'">
                   <a href="'. $spielerdaten2['link'] . '"><img class="bttliveImg" src="' . $spielerdaten2['action_bild'] . '" alt="Actionbild von ' . $spielerdaten2['vorname'] . " " . $spielerdaten2['nachname'] . '"></a>
                <figcaption class="' .$options['cssSpielerposueberschrift'] .'">'. $spielername . $stern . ' in Aktion</figcaption>
                </figure>
                </td></tr>';
                echo "\n";
            }
            if (($params['editor_anzeigen'] == true) && ($spielerdaten2['content']!='')) {
                $plan .= '
                <tr ><td colspan="2">'.
                $spielerdaten2['content']
                .'</td></tr>';
                echo "\n";
            }
            $plan .=$this->getContent();

        }
        $plan .= "</tbody>\n";
        $plan .= "</table>\n";

        $this->setContent($plan);
        return $plan;

    }
}
