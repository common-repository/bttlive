<?php

class bttliveSpielplan
{

    public static $noxml = 'Konnte TT-Live-XML nicht laden';
    public static $Spielplan = 'Spielplan';
    public static $SpielplanXMLContent = 'Spielplan';
    public static $Vereinsplan = 'Vereinsplan';
    public static $VereinsplanXMLContent = 'Vereinsspielplan';
    public static $Heimspielplan = 'Heimspielplan';
    public static $HeimspielplanXMLContent = 'Heimspielplan';
    public static $Klassenspielplan = 'Klassenspielplan';
    public static $KlassenspielplanXMLContent = 'Spielplan';
    public static $Plan14Tage="14Tage";
    public static $Plan14TageXMLContent = array('LetzteSpiele', 'NachsteSpiele');
    public static $TableClassName="TTLiveSpielplan";

    /**
     * gibt den Content Namen in der XML- Datei zurück
     */
private function getElementXMLContentName($params) {
    $retvalue="";
    switch ($params['elementname']) {
        case self::$Heimspielplan:
            $retvalue=self::$HeimspielplanXMLContent;
            break;
        case self::$Vereinsplan:
            $retvalue=self::$VereinsplanXMLContent;
            break;
        case self::$Klassenspielplan:
            $retvalue=self::$KlassenspielplanXMLContent;
            break;
        case self::$Spielplan:
            $retvalue=self::$SpielplanXMLContent;
            break;
        case self::$Plan14Tage:
            $retvalue=self::$Plan14TageXMLContent[$params['display_type']];
            break;
    }
    return $retvalue;
}
    /**
     * Formatiert die Daten für den Spielplan als HTML Ausgabe
     *
     * @param $params
     * @return string
     */
public function getbttliveSpielplan($params)
    {
        $_debug=0;
        /**
         * XML aus lokalem tmp Folder laden
         */
        $options = bpe_lib_tools::getInstance()->getOptions();
        $tableclassname = $options['bttlive_tableclassname_'. $params['elementname']];

        if ($params['tableclassname'] != '') {
            $tableclassname = $params['tableclassname'];
        }
        if ( $tableclassname == "" ) {
            $tableclassname = self::$TableClassName;
        }
        bttlive_tools::getInstance()->refreshbttliveData($params);
        $shortentext = new bttliveShortTxt();
        if($xml = simplexml_load_file($params['filename'], NULL, ($_debug==1)?LIBXML_NOERROR:NULL)) {
            //works only with php 5.3 and higher

            $heute = new DateTime(date("d.m.Y"));
            if ($params['elementname']==self::$Spielplan) {
                $nodestr = 'Content/' . $this->getElementXMLContentName($params) .'/Spiel';
            } else {
                $nodestr = '/' . $this->getElementXMLContentName($params) . '/Content/Spiel';
            }
            $nodes = $xml->xpath($nodestr);
            // Sort by date, descending?
            if (($params['elementname']==self::$Plan14Tage) && ($params['display_type']=="0")) {
                $nodes = bttlive_tools::getInstance()->xsort($nodes, 'Datum', SORT_DESC);
            }
            $plan = '<table class="' . $tableclassname . '">' . "\n";
            $plan .= "<tr><th></th><th>Datum</th>\n";
            $plan .= "<th>Zeit</th>\n";
            if ($params['spielklasse_anzeigen'] == true) {
                $plan .= "<th>Staffel</th>\n";
            }
            $plan .= "<th>Heimteam</th>\n";
            $plan .= "<th>&nbsp;</th>\n";
            $plan .= "<th>Gastteam</th>\n";
            if ($params['ergebnis_anzeigen'] == true) {
                $plan .= "<th>Erg.</th>\n";
            }

            $plan .= "</tr>\n";
            $zeile = 0;
            $printzeile = 0;
            $maxcount = 0;
            foreach ($nodes as $key => $attribute) {
                $zeile++;
                $time = $attribute->Zeit;
                if ($time == "00:00") {
                    $time = "";
                }
                $hide = false;

                $thedate = new Datetime(strftime('%d.%m.%Y', strtotime($attribute->Datum)));


                // Wenn aktuelles Datum berücksichtigt werden soll ...
                if ($params['aktuell'] == 1) {
                    if ($thedate < $heute) {
                        $hide = true;
                    } else {
                        if ($params['max'] > 0) {
                            if ($maxcount >= $params['max']) {
                                $hide = true;
                            } else {
                                $maxcount++;
                            }
                        }
                    }
                } else {
                    if ($params['max'] > 0) {
                        if ($zeile >= $params['max']) {
                            $hide = true;
                        }
                    }

                }
                // Nur Anzahl der Zeilen ausgeben ...
                if (!$hide) {
                    $printzeile++;
                    $plan .= "<tr";
                    $oddclass = "";
                    if ($printzeile % 2 != 0) {
                        //ungerade
                        $oddclass .= " class='even'>";
                    } else {
                        $oddclass .= " class='odd'>";
                        //gerade
                    }
                    $plan .= $oddclass;
                    $plan .= '<td>' . $attribute->Tag . '</td><td>' . $thedate->format('d.m.Y') . '</td>' . "\n";
                    $plan .= "<td>$time Uhr</td>\n";
                    if ($params['spielklasse_anzeigen'] == true) {
                        $newstaffel = $shortentext->getShortenText($attribute->Staffelname);
                        $plan .= "<td><em>$newstaffel</em></td>\n";
                    }
                    $plan .= "<td" . $oddclass . "<em>" . $attribute->Heimmannschaft . "</em></td>\n";
                    $plan .= "<td> -</td><td>";
                    $plan .= $attribute->Gastmannschaft . "<br />\n";
                    $plan .= "</td>\n";
                    $array = explode(':', $attribute->Ergebnis);
                    $sieg = "";
                    $isHeimteam = false;
                    $isAuswaertsteam = false;
                    if (preg_match($options['bttlive_ownteam'], $attribute->Heimmannschaft)) {
                        $isHeimteam = true;
                    }
                    if (preg_match($options['bttlive_ownteam'], $attribute->Gastmannschaft)) {
                        $isAuswaertsteam = true;
                    }
                    if ((strstr($attribute->Heimmannschaft, "Herren")) or (strstr($attribute->Heimmannschaft, "Jungen")) or(strstr($attribute->Heimmannschaft, "Mini")) or (strstr($attribute->Heimmannschaft, "Schüler")) or (strstr($attribute->Heimmannschaft, "Damen")) or (strstr($attribute->Heimmannschaft, "Senioren"))) {
                        $isHeimteam = true;
                    }
                    if ((strstr($attribute->Gastmannschaft, "Herren")) or (strstr($attribute->Gastmannschaft, "Jungen")) or(strstr($attribute->Gastmannschaft, "Mini")) or (strstr($attribute->Gastmannschaft, "Schüler")) or (strstr($attribute->Gastmannschaft, "Damen")) or (strstr($attribute->Gastmannschaft, "Senioren"))) {
                        $isAuswaertsteam = true;
                    }

                    if ($params['ergebnis_anzeigen'] == true) {

                        if ((intval($array[0])) == (intval($array[1]))&& ($array[0] != "Vorbericht") ) {
                            $sieg = "unentschieden";
                        }
                        if ((intval($array[0])) > (intval($array[1])) && ($isHeimteam == true)) {
                            $sieg = "sieg";
                        }
                        if ((intval($array[0])) < (intval($array[1])) && ($isHeimteam == true) ) {
                            $sieg = "niederlage";
                        }
                        if ((intval($array[0])) < (intval($array[1])) && ($isAuswaertsteam == true)) {
                            $sieg = "sieg";
                        }
                        if ((intval($array[0])) > (intval($array[1])) && ($isAuswaertsteam == true) ) {
                            $sieg = "niederlage";
                        }
                        if ($printzeile % 2 != 0 && ( ($isAuswaertsteam == true) || ($isHeimteam == true) )) {
                            //ungerade
                            $oddclass = " class='even cOwnTeam " . $sieg . "' >";

                        } elseif ($printzeile % 2 == 0 && ( ($isAuswaertsteam == true) || ($isHeimteam == true) )) {
                            $oddclass = " class='odd cOwnTeam ". $sieg . "' >";
                        } else {
                            if ($printzeile % 2 != 0) {
                                //ungerade
                                $oddclass = " class='even " . $sieg .  "' >";
                            } else {
                                $oddclass = " class='odd " . $sieg . "' >";
                                //gerade
                            }
                        }

                        $plan .= "<td" . $oddclass . "\n";
                        if ($attribute->Link) {
                            $plan .= "<a href=\"" . htmlentities($attribute->Link) . "\" target=\"_blank\">";
                        }
                        $plan .= $attribute->Ergebnis;

                        if ($attribute->Link) {
                            $plan .= "</a>";
                        }
                        if (!empty($sieg)&& ($isHeimteam || $isAuswaertsteam )){
                            $plan.= '<img style="float:right;" src="' . plugins_url( 'images/' , __FILE__ ) . $sieg . '-128.png" width="32" alt="' . $sieg . '">';
                        }

                    }
                    $plan .= "</td>\n";
                    $plan .= "</tr>\n";
                }
            };
            $plan .= "</table>\n";
            return $plan;
        } else {
            bpe_lib_tools::getInstance()->log(self::$noxml, __METHOD__ . ":" . __LINE__);
            return self::$noxml. "->" . __METHOD__ . ":" . __LINE__;
        }
    }

    /**
     * Formatiert die Daten für die nächsten 14Tage als HTML Ausgabe im Widget
     *
     * @param $params
     * @return string
     */
public function getbttliveSpielplanDataForWidget($params)
    {
        /**
         * XML aus lokalem tmp Folder laden
         */
        $_debug=0;
        $options = get_option('bttlive_opt');
        $tableclassname = $options['bttlive_tableclassname_'. $params['elementname']];

        if ($params['tableclassname'] != '') {
            $tableclassname = $params['tableclassname'];
        }
        if ( $tableclassname == "" ) {
            $tableclassname = self::$TableClassName;
        }

        bttlive_tools::getInstance()->refreshbttliveData($params);
        $shortentext = new bttliveShortTxt();
        if ($xml = simplexml_load_file($params['filename'], NULL, ($_debug == 1) ? LIBXML_NOERROR : NULL)) {
            //works only with php 5.3 and higher

            $heute = new DateTime(date("d.m.Y"));
            if ($params['elementname']==self::$Spielplan) {
                $nodestr = 'Content/' . $this->getElementXMLContentName($params) .'/Spiel';
            } else {
                $nodestr = '/' . $this->getElementXMLContentName($params) . '/Content/Spiel';
            }
            $nodes = $xml->xpath($nodestr);

            // Sort by date, descending?
            if (($params['elementname']==self::$Plan14Tage) && ($params['display_type']=="0")) {
                $nodes = bttlive_tools::getInstance()->xsort($nodes, 'Datum', SORT_DESC);
            }
            if ($nodes) {
                $plan = '<div class="'. $tableclassname .'">' . "\n";
            } else {
                $plan = "";
            }
            $zeile = 0;
            $printzeile = 0;
            $maxcount = 0;
            foreach ($nodes as $key => $attribute) {
                $zeile++;
                $time = $attribute->Zeit;
                if ($time == "00:00") {
                    $time = "";
                }
                $hide = false;
                if ( $params['tage_zurueck']) {

                    $heute = new Datetime(strftime('%d.%m.%Y',strtotime("-" . $params['tage_zurueck'] . "day")));
                }
                $thedate = new Datetime(strftime('%d.%m.%Y', strtotime($attribute->Datum)));


                // Wenn aktuelles Datum berücksichtigt werden soll ...
                if ($params['aktuell'] == 1) {
                    if ($thedate < $heute) {
                        $hide = true;
                    } else {
                        if ($params['max'] > 0) {
                            if ($maxcount >= $params['max']) {
                                $hide = true;
                            } else {
                                $maxcount++;
                            }
                        }
                    }
                } else {
                    if ($params['max'] > 0) {
                        if ($zeile >= $params['max']) {
                            $hide = true;
                        }
                    }

                }
                // Nur Anzahl der Zeilen ausgeben ...
                if (!$hide) {
                    $printzeile++;

                    $plan .= "<dl>\n";
                    $plan .= "<dt";
                    $oddclass = "";
                    if ($printzeile % 2 != 0) {
                        //ungerade
                        $oddclass .= ' class="even">';
                    } else {
                        $oddclass .= ' class="odd">';
                    }
                    $plan .= $oddclass;
                    $plan .= $attribute->Tag . ', ' . $thedate->format('d.m.Y');
                    $plan .= " - $time<br /></dt>\n";
                    if ($params['spielklasse_anzeigen'] == true) {
                        $newstaffel = $shortentext->getShortenText($attribute->Staffelname);
                        $plan .= "<dt><em>$newstaffel</em></dt>\n";
                    }
                    $plan .= "<dd" . $oddclass . "<em>" . $attribute->Heimmannschaft . "</em>\n";
                    $plan .= " - ";
                    $plan .= $attribute->Gastmannschaft . "<br />\n";
                    $plan .= "</dd>\n";
                    $array = explode(':', $attribute->Ergebnis);
                    $sieg = "";
                    $isHeimteam = false;
                    $isAuswaertsteam = false;
                    if (preg_match($options['bttlive_ownteam'], $attribute->Heimmannschaft)) {
                        $isHeimteam = true;
                        }
                    if (preg_match($options['bttlive_ownteam'], $attribute->Gastmannschaft)) {
                        $isAuswaertsteam = true;
                    }
                    if ((strstr($attribute->Heimmannschaft, "Herren")) or (strstr($attribute->Heimmannschaft, "Jungen")) or(strstr($attribute->Heimmannschaft, "Mini")) or (strstr($attribute->Heimmannschaft, "Schüler")) or (strstr($attribute->Heimmannschaft, "Damen")) or (strstr($attribute->Heimmannschaft, "Senioren"))) {
                        $isHeimteam = true;
                    }
                    if ((strstr($attribute->Gastmannschaft, "Herren")) or (strstr($attribute->Gastmannschaft, "Jungen")) or(strstr($attribute->Gastmannschaft, "Mini")) or (strstr($attribute->Gastmannschaft, "Schüler")) or (strstr($attribute->Gastmannschaft, "Damen")) or (strstr($attribute->Gastmannschaft, "Senioren"))) {
                        $isAuswaertsteam = true;
                    }

                    if ($params['ergebnis_anzeigen'] == true) {

                        if ((intval($array[0])) == (intval($array[1]))&& ($array[0] != "Vorbericht") ) {
                            $sieg = "unentschieden";
                        }
                        if ((intval($array[0])) > (intval($array[1])) && ($isHeimteam == true)) {
                           $sieg = "sieg";
                        }
                        if ((intval($array[0])) < (intval($array[1])) && ($isHeimteam == true) ) {
                            $sieg = "niederlage";
                        }
                        if ((intval($array[0])) < (intval($array[1])) && ($isAuswaertsteam == true)) {
                            $sieg = "sieg";
                        }
                            if ((intval($array[0])) > (intval($array[1])) && ($isAuswaertsteam == true) ) {
                            $sieg = "niederlage";
                        }
                        if ($printzeile % 2 != 0 && ( ($isAuswaertsteam == true) || ($isHeimteam == true) )) {
                            //ungerade
                            $oddclass = ' class="even cOwnTeam ' . $sieg . '" >';

                        } elseif ($printzeile % 2 == 0 && ( ($isAuswaertsteam == true) || ($isHeimteam == true) )) {
                            $oddclass = ' class="odd cOwnTeam '. $sieg . '" >';
                        } else {
                            if ($printzeile % 2 != 0) {
                                //ungerade
                                $oddclass = ' class="even ' . $sieg .  '" >';
                            } else {
                                $oddclass = ' class="odd ' . $sieg . '" >';
                                //gerade
                            }
                        }

                        $plan .= "<dd" . $oddclass . "\n";
                        if ($attribute->Link) {
                            $plan .= '<a href="' . htmlentities($attribute->Link) . '" target="_blank">';
                        }
                        $plan .= $attribute->Ergebnis;

                        if ($attribute->Link) {
                            $plan .= "</a>";
                        }
                        if (!empty($sieg)&& ($isHeimteam || $isAuswaertsteam )){
                            $plan.= '<img style="float:right;" src="' . plugins_url( 'images/' , __FILE__ ) . $sieg . '-128.png" width="32" alt="' . $sieg . '">';
                        }
                        $plan .= "</dd>\n";
                    }
                    $plan .= "</dl>\n";
                }
            }
            if ($params['linkanzeigen'] == true) {
                switch ($params['elementname']) {
                    case self::$Heimspielplan:
                        if ($options['hallenplan_seite'] !== false) {
                            $plan .= '<a href="' . get_permalink($options['hallenplan_seite']) . '" >';
                            $plan .= "Hallenplan Gesamt";
                        }
                        break;
                    case self::$Vereinsplan:
                        if ($options['vereinsplan_seite'] !== false) {
                            $plan .= '<a href="' . get_permalink($options['vereinsplan_seite']) . '" >';
                            $plan .= "Vereinsplan Gesamt";
                        }
                        break;
                }
                $plan .= "</a>";
            }

            if (!empty($params['link'])) {
                $linktext = $params['linktext'];
                $plan .= '<a href="' . $params['link'] . '" >';
                if (!empty($linktext)) {
                    $plan .= "$linktext";
                } else {
                    $plan .= "Mehr";
                }
                $plan .= "<a>\n";
            }
            if ($nodes) {
                $plan .= "</div>\n";
            }
            return $plan;
        } else {
            bpe_lib_tools::getInstance()->log(self::$noxml, __METHOD__ . ":" . __LINE__);
            return self::$noxml. "->" . __METHOD__ . ":" . __LINE__;
        }
    }

}