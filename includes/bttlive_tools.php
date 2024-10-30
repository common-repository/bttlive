<?php

/**
 * Tools for wordpress
 * File: bttlive_tools.php
 * Author: Bernt Penderak
 * Author URI: http://bepe.penderak.net
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Singleton Pattern!!!!!
 */

class bttlive_tools {
    private static $noxml = "Keine XML - Daten gefunden!";
    private static $INSTANCE = NULL;

    private function __construct()  {}
    private function __clone()      {}
    private function __wakeup()     {}

    /**
     * @return bttlive_tools|null
     */
    public static function getInstance() {
        if(!self::$INSTANCE) {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    /**
     * @var array
     * Optionen aus wordpress
     */
    protected $_options=array();

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
    }



    /**
     * @var array
     * Enthälten Daten über alle Mannschaften des Vereins
     */
    protected $_mannschaften=array();

    /**
     * @return array
     */
    public function getMannschaften()
    {
        return $this->_mannschaften;
    }

    /**
     * @param array $mannschaften
     */
    public function setMannschaften($mannschaften)
    {
        $this->_mannschaften = $mannschaften;
    }


    protected $_debug;

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param mixed $debug
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    protected $_verein;

    /**
     * @return mixed
     */
    public function getVerein()
    {
        return $this->_verein;
    }

    /**
     * @param mixed $verein
     */
    public function setVerein($verein)
    {
        $this->_verein = $verein;
    }

    private $_umbruch;

    /**
     * @return mixed
     */
    private function getUmbruch()
    {
        return $this->_umbruch;
    }

    /**
     * @param mixed $umbruch
     */
    private function setUmbruch($umbruch)
    {
        $this->_umbruch = $umbruch;
    }

    private  $_anzahl_elemente;

    /**
     * @return mixed
     */
    private function getAnzahlElemente()
    {
        return $this->_anzahl_elemente;
    }

    /**
     * @param mixed $anzahl_elemente
     */
    private function setAnzahlElemente($anzahl_elemente)
    {
        $this->_anzahl_elemente = $anzahl_elemente;
    }


    /**
     * @param $options
     * @param $params
     * setzt default URL filename und url
     */
    public function ttliveurl(&$params) {
        $options=$this->getOptions();
        if ($params['runde'] <= 1) {
            $runde = "1";
        } else {
            $runde = "2";
        }
        $runde2="r" . $runde;
        $runde1 = "&Runde=" . $runde;
        $params['baseurl'] = $options['bttlive_baseurl'];
        $params['filename'] = ABSPATH . 'wp-content/plugins/'
            . "/bttlive/bttlive-files/bttlive";
        switch ($params['elementname']) {
            default:
            case "Mannschaft":
            case "Tabelle":
            case "SpielerPortrait":
            case "Spielplan":
                $params['filename'] .= $params['staffel_id']
                    . "-"
                    . $params['mannschaft_id']
                    . "saison=" .$params['saison']
                    . "_".$runde.".xml";
                $params['url'] = $options['bttlive_baseurl']
                    ."/Export/default.aspx?TeamID="
                    .$params['mannschaft_id']
                    ."&WettID="
                    .$params['staffel_id']
                    ."&Format=XML&Runde="
                    .$runde
                    ."&SportArt=96&Area=TeamReport";
            break;
            case "Klassenspielplan":
                $params['filename'] .= $params['elementname']
                    .$runde1 . "Staffel=" . $params['staffel_id'] .".xml";
                $params['url'] = $params['baseurl']
                    ."/Export/default.aspx?LigaID="
                    .$params['staffel_id']
                    ."&Format=XML&SportArt=96&Area=Spielplan"
                    .$runde1;
            break;
            case "14Tage":
                $area = "";
                if ( $params['display_type'] == 0 ):
                    $area .= "&Area=PlanLast";
                else:
                    $area .= "&Area=PlanNext";
                endif;
                $params['filename'] .= "14Days" .$area . ".xml";
                $params['url'] = $params['baseurl']
                    ."/Export/default.aspx?SpartenID="
                    .$options['bttlive_divisionID']
                    ."&Format=XML&SportArt=96&"
                    .$area;
            break;
            case "Heimspielplan":
            case "Vereinsplan":
                $params['filename'] .= $params['elementname']
                    . "saison=" .$params['saison']
                    . $options['bttlive_divisionID'] .$runde2 . ".xml";
                $params['url'] = $options['bttlive_baseurl']
                    . "/Export/default.aspx?SpartenID="
                    . $options['bttlive_divisionID']
                    . "&Format=XML&SportArt=96&Saison="
                    . $params['saison']
                    . $runde1;
                if ($params['elementname'] != "Vereinsplan") {
                    $params['url'] .= "&Area=Hallenplan";
                } else {
                    $params['url'] .= "&Area=" . $params['elementname'];
                }
            break;
            case "Rangliste":
                $params['filename'] .= $params['elementname'] . "ttliveRangliste.xml";
                $params['url'] = $params['baseurl'];
                $params['url'] .= '/Export/default.aspx?SpartenID='. $options['bttlive_divisionID']
                    . "&Format=XML&SportArt=96&Area=VereinLivePZ";
            break;

        }
        return($params);
    }

    /**
     * Sortiert Spielplan nach Datum
     *
     * @param $nodes
     * @param $child_name
     * @param int $order
     */
    public function xsort($nodes, $child_name, $order=SORT_ASC)
    {

        $sort_proxy = array();

        foreach ($nodes as $k => $node) {
            $sort_proxy[$k] = (string) $node->$child_name;
        }
        array_multisort($sort_proxy, $order, $nodes);
        return($nodes);
    }


    /**
     * @param $options
     * Mannschafts array erzeugen
     */
    public function construct($options) {
        $this->setOptions($options);
        bpe_lib_tools::getInstance()->log($options,__METHOD__. ":" . __LINE__);
        add_filter( 'pre_update_option_bttlive_opt', array($this, 'update_options'), 10, 2 );
        if (empty($options['mannschaften']) && ($options['bttlive_divisionID'])) {
            $this->write_options();
            $this->setMannschaften($options['mannschaften']);
        } elseif (! empty($options['mannschaften'])) {
            $this->setMannschaften($options['mannschaften']);
        }
    }

    /**
     * Schreiben der Mannschaften und des Vereins starten

     */
    public function write_options() {
        $options = $this->getOptions();
        $params = array(
            'saison' => $options['bttlive_saison'],
            'runde' => $options['bttlive_runde'],
            'elementname' => 'Vereinsplan',
        );
        $params = $this->ttliveurl($params);
        $this->refreshbttliveData($params);
        $this->getDataforVerein();
        $this->getDataforMannschaften($params);
    }

    /**
     * @param $old
     * @param $new
     * aufgerufen als Hook nach dem die Options bttlive gespeichert wurden
     */
    public function update_options($new, $old) {
        $this->setOptions($new);
        $this->write_options();
        $new['mannschaften']=$this->getMannschaften();
        $new['verein']=$this->getVerein();
        $this->setOptions($new);
        bpe_lib_tools::getInstance()->setOptions($this->getOptions());
        bpe_lib_tools::getInstance()->log($new, __METHOD__ . ":" . __LINE__);
        return $new;
    }

    /**
     * Importiert die Daten aus TTLive und schreibt sie als xml-File in das Plugin-Verzeichnis
     *
     * @param $params
     */
    public function refreshbttliveData($params) {
        /***
         *   Cache XML-File to reduce traffic
         *   IF XML is older than x hour -> renew (store XML in TMP folder)
         */
        $this->write_filename($params['filename'],$params['url']);

    }

    /**
     * @param $filename
     * @param $url
     * @param int $refresh
     * Schreibt die XML-Daten in eine Cache-Datei
     */
    public function write_filename($filename,$url) {
        $options= $this->getOptions();
        $secondsToRefresh = $options['bttlive_refreshHours'] * 3600;
        $fileurl = (string)$filename;
        if(!file_exists($fileurl) || time()-$secondsToRefresh > filemtime($fileurl))
        {
            $htmlurl = wp_remote_get($url);
            $html = wp_remote_retrieve_body( $htmlurl );
            if ($html != '') {
                $myhandle = fopen($fileurl, "w");
                if (!fwrite($myhandle, $html)) {
                    print "<br />Kann in die XML-Datei ". $fileurl ." nicht schreiben.<br />";
                    exit; // @todo: normaler Exit
                }
                fclose($myhandle);
            }
        }
    }

    public function getDataforVerein() {
        //http://ttvsh.tischtennislive.de/default.aspx?L1=Verwaltung&L2=Verein&L2P=204&L3=Tischtennis&L3P=408&L4=Statistiken&Action=Tabellen
        $options = $this->getOptions();
        $url=$options['bttlive_baseurl'] . "/default.aspx?L1=Verwaltung&L2=Verein&L2P=204&L3=Tischtennis&L3P=" . $options['bttlive_divisionID'] . "&L4=Statistiken&Action=Tabellen";
        $htmlurl = wp_remote_get($url);
        $html = wp_remote_retrieve_body( $htmlurl );
        if ($html != '') {

            // suche nach Verein</td><td>
            $suche = '/.*<td style="text-align:left">Abteilungsverwaltung: .*/';
            $verein='';
            if (preg_match_all($suche,$html, $ergebnis)) {

                foreach ($ergebnis as $zeile => $v ) {
                    foreach ( $v as $z => $value ) {
                        $strzw = strstr($value, '>Abteilungsverwaltung:');
                        $strzw = str_replace('>Abteilungsverwaltung:', "", $strzw);
                        $verein = strstr($strzw, '</td>', true);
                    }
                }

            }
            $this->setVerein($verein);
        }
    }
    public function getDataforMannschaften($params) {
        $mannschaften=array();
        bpe_lib_tools::getInstance()->log($params,__METHOD__ . ":" . __LINE__ );
        if ($xml = simplexml_load_file($params['filename'], NULL, ($this->getDebug() == 1) ? LIBXML_NOERROR : NULL)) {
            $nodes = $xml->xpath('/Vereinsspielplan/Content/Spiel');
            foreach ($nodes as $key => $attribute) {
                if ((strstr($attribute->Heimmannschaft, "Herren")) or (strstr($attribute->Heimmannschaft, "Jungen")) or(strstr($attribute->Heimmannschaft, "Mini")) or (strstr($attribute->Heimmannschaft, "Schüler")) or (strstr($attribute->Heimmannschaft, "Damen")) or (strstr($attribute->Heimmannschaft, "Senioren"))) {
                    $mannschaft = (string)$attribute->Heimmannschaft;
                }
                if ((strstr($attribute->Gastmannschaft, "Herren")) or (strstr($attribute->Gastmannschaft, "Jungen")) or(strstr($attribute->Gastmannschaft, "Mini")) or (strstr($attribute->Gastmannschaft, "Schüler")) or (strstr($attribute->Gastmannschaft, "Damen")) or (strstr($attribute->Gastmannschaft, "Senioren"))) {
                    $mannschaft = (string)$attribute->Gastmannschaft;
                }

                $mannschaften[$mannschaft]['Name']=$mannschaft;
                $mannschaften[$mannschaft]['Staffelname']=(string)$attribute->Staffelname;
                $link=(string)$attribute->Link;
                $linkzw=strstr($link,'&L3=Spielbericht&L3P=',true);$linkzw=str_replace('&L3=Spielbericht&L3P=',"", $linkzw);
                $mannschaften[$mannschaft]['StaffelID']=strstr($linkzw,'L1=Ergebnisse&L2=TTStaffeln&L2P=');
                $mannschaften[$mannschaft]['StaffelID']=str_replace('L1=Ergebnisse&L2=TTStaffeln&L2P=', "", $mannschaften[$mannschaft]['StaffelID']);
            }
            $mf=$mannschaften;
            foreach ($mf as $mannschaft ) {
                //http://ttvsh.tischtennislive.de/?L1=Ergebnisse&L2=TTStaffeln&L2P=5311
                $options = $this->getOptions();
                $url=$options['bttlive_baseurl'] . "/?L1=Ergebnisse&L2=TTStaffeln&L2P=" . $mannschaft['StaffelID'];
                $htmlurl = wp_remote_get($url);
                $html = wp_remote_retrieve_body( $htmlurl );
                if ($html != '') {
                    // /.*null.*VfB L.*beck.*/
                    $suche = str_replace("/", "" ,$options['bttlive_ownteam'] );
                    $suche = "/.*null.*" . $suche . ".*/";
                    if (preg_match_all($suche,$html, $ergebnis)) {
                        foreach ($ergebnis as $zeile => $v ) {
                            foreach ( $v as $z => $value ) {
                                $strzw = strstr($value, '&L3=Mannschaften&L3P=');
                                $strzw = str_replace('&L3=Mannschaften&L3P=', "", $strzw);
                                $strzw = str_replace('\'', "", $strzw);
                                $strzw = strstr($strzw, ',null', true);
                                $mannschaften[$mannschaft['Name']]['MannschaftsID'] = $strzw;
                            }
                        }

                    }
                }
            }
            asort($mannschaften);
            $this->setMannschaften($mannschaften);
        } else {
             echo self::$noxml . "->" . __METHOD__ . ":" . __LINE__;

            bpe_lib_tools::getInstance()->log(self::$noxml,__METHOD__ . ":" . __LINE__ );
            bpe_lib_tools::getInstance()->log(get_option(bpe_lib_tools::getInstance()->getOptionName()),__METHOD__ . ":" . __LINE__ );
        }
    }
    public function ansichts_init() {
        $this->setAnzahlElemente('');
        $this->setUmbruch('');
    }
    public function ansichts_layout($contentcount, $layout) {

        if ($this->getAnzahlElemente() == '') {
            $this->setAnzahlElemente("1");
            $anzahl_elemente = $this->getAnzahlElemente();
        } else {
            $anzahl_elemente = $this->getAnzahlElemente();
            $anzahl_elemente++;
            $this->setAnzahlElemente($anzahl_elemente);
        }

        if ($this->getUmbruch() == '' ) {
            $this->setUmbruch("1");
        }
        $umbruch=$this->getUmbruch();
        $retvalue="";
        switch ($layout) {
            default:
            case "1":
                if ($anzahl_elemente == "1") {
                    $retvalue='<div class="bttlive_splayout1">' . "\n";
                }
                break;
            case "2":
                if ($anzahl_elemente == "1") {
                    $retvalue='<div class="bttlive_splayout2">' . "\n";
                } elseif (($contentcount / $layout < $anzahl_elemente ) && ($umbruch =="1")) {
                    $umbruch++;
                }
                break;
            case "3":
                if ($anzahl_elemente <= $contentcount) {
                    if ($anzahl_elemente == "1") {
                        $retvalue = '<div class="bttlive_splayout3">' . "\n";
                    } elseif ((($contentcount / $layout) * $umbruch) < $anzahl_elemente) {
                        $umbruch++;
                    }
                }
                break;
        }
        $this->setUmbruch($umbruch);
        bpe_lib_tools::getInstance()->log(
            "Layout: " . $layout .
            "ContentCount: " . $contentcount .
            "Umbruch: " . $umbruch .
            "Anzahl Elemente: " . $anzahl_elemente .
            "Ret-Value: " . $retvalue
            ,__METHOD__ . ":" . __LINE__, 3);
        return $retvalue;
    }
}