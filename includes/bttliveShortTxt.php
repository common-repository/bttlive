<?php


/**
 * Class bttliveShortTxt
 *
 * Sucht Texte heraus und ersetzt sie mit Kürzeln
 *
 * Project: bttlive
 * File: bttliveShortTxt.php
 * Version: 1.0
 * Author: Bernt Penderak
 * Author URI: http://bepe.penderak.net
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Date: 25.08.14
 * Time: 20:34
 */
class bttliveShortTxt {

    protected $_search_text;

    /**
    * @param mixed $search_text
    */
    public function setSearchText(array $search_text)
    {
        $this->_search_text = $search_text;
    }
    /**
     * @return mixed
    */

    public function getSearchText()
    {
    return $this->_search_text;
    }
    protected $_replace_text = array();

    /**
    * @return mixed
     */
    public function getReplaceText()
    {
      return $this->_replace_text;
    }
    /**
     * @param mixed $replace_text
     */
    public function setReplaceText(array $replace_text)
    {
      $this->_replace_text = $replace_text;
    }

    protected  $_shorten_array = array();

    /**
     * @param $shorten_text
     * @return mixed
     *
     * Sucht im Staffelnamen die Texte heraus und ersetzt sie mit Kurznamen
     */
    public function getShortenText($shorten_text)
    {
        //
        $this->setShortenArray(explode(" ", $shorten_text));
        $retvalue = str_replace($this->getSearchText(), $this->getReplaceText(), $this->_shorten_array);
        $this->setShortenArray(implode(" ", $retvalue));
        return $this->_shorten_array;
    }

    /**
     * @param mixed $shorten_array
     */
    private function setShortenArray($shorten_array)
    {
        $this->_shorten_array = $shorten_array;
    }

    public function getShortenArray()
    {
        return $this->_shorten_array;
    }

    /**
     * Erzeugt die Texte zum Ersetzen
     */
    function __construct()
    {
        $this->setSearchText(
            array(
                "Verbandsliga",
                "Bezirksklasse",
                "Bezirksliga",
                "Kreisliga",
                "Kreisklasse",
                "Landesliga",
                "Nord",
                "Süd",
                "Ost",
                "West",
                "Mitte",
                "Bezirk",
                "Lübeck/",
                "Lauenburg",
                "Herren",
                "Damen",
                "Jugend",
                "Schüler"
        ));

        $this->setReplaceText(
            array(
                "VL",
                "BK",
                "BL",
                "KL",
                "KK",
                "LL",
                "N",
                "S",
                "O",
                "W",
                "M",
                "BZ",
                "Lü/",
                "Lb",
                "He",
                "Da",
                "Jg",
                "Schü"
            ));

    }



}