<?php

/**
 * Class bttliveRangliste
 * Zeigt die LivePZ - Ragliste aus den TTLive - Daten an
 */
class bttliveRangliste {
    public static $noxml = 'Konnte TT-Live-XML nicht laden';

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
    public function getbttliveRangliste($params)
    {
        $this->setParams($params);
        /**
         * XML aus lokalem tmp Folder laden
         */
        $options = get_option('bttlive_opt');
        $tableclassname = $options['bttlive_tableclassname_Rangliste'];
        if ($params['tableclassname'] != '') {
            $tableclassname = $params['tableclassname'];
        }

        refreshbttliveData($params);
        if ($xml = simplexml_load_file($params['filename'], NULL, ($this->getDebug() == 1) ? LIBXML_NOERROR : NULL)) {
            //works only with php 5.3 and higher

            $nodes = $xml->xpath('/LivePZ/Content/Spieler');
            $plan = "<table class='" . $tableclassname . "'>\n";
            $plan .= "<tr><th>Nr.</th><th>Spieler</th>\n";
            $plan .= "<th>Geb.jahr</th>\n";
            $plan .= "<th>LivePZ</th>\n";
            $plan .= "<th colspan=2>Porträt</th>\n";
            $plan .= "<th colspan=2>Actionbild</th>\n";
            $plan .= "</tr>\n";
            $zeile = 0;

            foreach($nodes as $key => $attribute)
            {
                $showSpieler = true;
                if (!$params['display_all']) {
                    if ($attribute->LivePZ == "k.A."){
                        $showSpieler = false;
                    }
                }
                if ($showSpieler){
                    $zeile++;


                    $plan .= "<tr";
                    if ($zeile % 2 !=0) {
                        //ungerade
                        $plan .=  " class='even'>";
                    } else {
                        //gerade
                        $plan .=  " class='odd'>";
                    }
                    $plan .= "<td>$zeile</td>\n";
                    $plan .= "<td>$attribute->Spielername</td>\n";
                    $plan .= "<td>$attribute->Gebdatum</td>\n";
                    $plan .= "<td>$attribute->LivePZ</td>\n";

                    $spielername=str_replace(",", "", (strstr((string)$attribute->Spielername,",") . " " .strstr((string)$attribute->Spielername,",", true)) );
                    // Suche den Spieler raus
                    $posts=get_posts(
                        array('post_type'=>'bttlive_spieler',
                            'meta_key' => '_spieler_name',
                            'meta_query' => array(
                                array(
                                    'key' => '_spieler_name',
                                    'value' => $spielername,
                                    'compare' => '='
                                )
                            )
                        )
                    );
                    global $post;
                    if( ! $posts ) {
                        $plan .= '<td colspan=2></td>' . "\n";
                        $plan .= '<td colspan=2></td>' . "\n";
                    } else {
                        foreach ($posts AS $post) {
                            setup_postdata($post);
                            $custom = get_post_custom(get_the_ID());
                            if ($custom['_portrait_bild'][0] != '') {
                                $plan .= '
                <td colspan=2>
                <figure class="LivePZRangliste ' . $options['cssPortraitbild'] . '">
                   <a href="' . get_the_permalink() . '"><img class="bttliveImg" src="' . $custom['_portrait_bild'][0] . '" alt="Portrait von ' . $spielername . '"></a>
                <figcaption class="' . $options['cssSpielerposueberschrift'] . '">' . $spielername . '</figcaption>
                </figure>
                </td>' . "\n";

                            } else {
                                $plan .= '
                <td colspan=2>
                </td>' . "\n";

                            }
                            if ($custom['_action_bild'][0] != '') {
                                $plan .= '
                <td colspan=2>
                <figure class="LivePZRangliste ' . $options['cssActionbild'] . '">
                   <a href="' . get_the_permalink() . '"><img class="bttliveImg" src="' . $custom['_action_bild'][0] . '" alt="Actionbild von ' . $spielername . '"></a>
                <figcaption class="' . $options['cssSpielerposueberschrift'] . '">' . $spielername . '</figcaption>
                </figure>
                </td>' . "\n";

                            } else {
                                $plan .= '
                <td colspan=2>
                </td>' . "\n";

                            }
                        }
                    }

                    $plan .= "</tr>\n";
                }
            }
            $plan .= "</table>\n";
            $plan .= "<br/>\n";
            $this->setContent($plan);
            return $this->getContent();
        } else {
            return self::$noxml . "->" . __METHOD__ . ":" . __LINE__;
            bpe_lib_tools::getInstance()->log(self::$noxml, __METHOD__ . ":" . __LINE__);
        }
    }

}
