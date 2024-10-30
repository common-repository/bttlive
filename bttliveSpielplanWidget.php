<?php

/**
 * Stellt bttliveSpielplanWidget bereit
 */
class bttliveSpielplanWidget extends WP_Widget {
private $_elementname;

    /**
     * @return mixed
     */
    public function getElementname()
    {
        return $this->_elementname;
    }

    /**
     * @param mixed $elementname
     */
    public function setElementname($elementname)
    {
        $this->_elementname = $elementname;
    }
    /**
     * Register widget with WordPress.
     */
    public function __construct(array $arr) {
        $this->setElementname($arr['elementname']);
        parent::__construct(
            $arr['basisid'], // Base ID
            $arr['name'], // Name
            array( 'description' => __( $arr['description'], 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $maxnumber = apply_filters( 'widget_title', $instance['maxnumber'] );
        $aktuell = apply_filters( 'widget_title', $instance['aktuell'] );
        $saison = apply_filters( 'widget_title', $instance['saison'] );
        $runde = apply_filters( 'widget_title', $instance['runde'] );
        $ergebnis_anzeigen = apply_filters( 'widget_title', $instance['ergebnis_anzeigen'] );
        $linkanzeigen = apply_filters( 'widget_title', $instance['linkanzeigen'] );
        $link = apply_filters( 'widget_title', $instance['link'] );
        $linktext = apply_filters( 'widget_title', $instance['linktext'] );
        $spielklasse_anzeigen = apply_filters( 'widget_title', $instance['spielklasse_anzeigen'] );
        $tage_zurueck = apply_filters( 'widget_title', $instance['tage_zurueck'] );
        $staffel_id = apply_filters( 'widget_title', $instance['staffel_id'] );
        $mannschaft_id = apply_filters( 'widget_title', $instance['mannschaft_id'] );
        $mannschaft_name = apply_filters( 'widget_title', $instance['mannschaft_name'] );
        /*
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }*/
        $arr = array(
            "elementname" => $this->getElementname(),
            "staffel_id" => $staffel_id,
            "mannschaft_name" => $mannschaft_name,
            "mannschaft_id" => $mannschaft_id,
            "widget" => "1",
            "aktuell" => $aktuell,
            "max" => $maxnumber,
            "saison" => $saison,
            "runde" => $runde,
            "ergebnis_anzeigen" => $ergebnis_anzeigen,
            "spielklasse_anzeigen" => $spielklasse_anzeigen,
            "linkanzeigen" => $linkanzeigen,
            "tage_zurueck" => $tage_zurueck,
            "link" => $link,
            "linktext" => $linktext);

        if ( empty($maxnumber) ) unset($arr["max"]);
        if ( empty($aktuell) ) unset($arr["aktuell"]);
        if ( empty($saison) ) unset($arr["saison"]); // saison aus getoptions!
        if ( $runde == 0 ) unset($arr["runde"]); // Wert aus Einstellungen
        if ( empty($link) ) unset($arr["link"]); // Link für mehr am Ende
        if ( empty($linktext) ) unset($arr["linktext"]); // Link für mehr am Ende
        if ($spielklasse_anzeigen == 2) unset($arr["spielklasse_anzeigen"]); // Wert aus Einstellungen
        if ($ergebnis_anzeigen == 2) unset($arr["ergebnis_anzeigen"]); // Wert aus Einstellungen
        if ($tage_zurueck == 0) unset($arr['tage_zurueck']);
        if ( empty($staffel_id) ) unset($arr["staffel_id"]);
        $tmp = bttlivecontrol($arr,null);
        echo $tmp;
        /*
        echo $after_widget;*/
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $options=bpe_lib_tools::getInstance()->getOptions();
        $instance = array();
        $instance['mannschaft_name'] = strip_tags( $new_instance['mannschaft_name'] );
        if (!empty($instance['mannschaft_name'])) {
            $mannschaft = $options['mannschaften'][$instance['mannschaft_name']];
            $instance['staffel_id'] = $mannschaft['StaffelID'];
            $instance['mannschaft_id'] = $mannschaft['MannschaftsID'];
        }
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['aktuell'] = strip_tags( $new_instance['aktuell'] );
        $instance['maxnumber'] = strip_tags( $new_instance['maxnumber'] );
        $instance['saison'] = strip_tags( $new_instance['saison'] );
        $instance['runde'] = strip_tags( $new_instance['runde'] );
        $instance['link'] = strip_tags( $new_instance['link'] );
        $instance['linktext'] = strip_tags( $new_instance['linktext'] );
        $instance['linkanzeigen'] = strip_tags( $new_instance['linkanzeigen'] );
        $instance['ergebnis_anzeigen'] = strip_tags( $new_instance['ergebnis_anzeigen'] );
        $instance['spielklasse_anzeigen'] = strip_tags( $new_instance['spielklasse_anzeigen'] );
        $instance['tage_zurueck'] = strip_tags( $new_instance['tage_zurueck'] );
        bpe_lib_tools::getInstance()->log($instance, __METHOD__ . ":" . __LINE__);
        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $options=bpe_lib_tools::getInstance()->getOptions();
        $checked = 'checked="checked"';
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Neuer Titel', 'text_domain' );
        }
        if ( isset( $instance[ 'aktuell' ] ) ) {
            $aktuell = $instance[ 'aktuell' ];
        } else {
            $aktuell = true;
        }
        if ( isset( $instance[ 'maxnumber' ] ) ) {
            $maxnumber = $instance[ 'maxnumber' ];
        } else {
            $maxnumber = __( '', 'text_domain' );
        }
        if ( isset( $instance[ 'saison' ] ) ) {
            $saison = $instance[ 'saison' ];
        } else {
            $saison = __( "", 'text_domain' ); // wenn nicht gesetzt wird, dann wird default-Saison genommen
        }
        if ( isset( $instance[ 'tage_zurueck' ] ) ) {
            $tage_zurueck = $instance[ 'tage_zurueck' ];
        } else {
            $tage_zurueck = __( "", 'text_domain' );
        }
        if ( isset( $instance[ 'runde' ] ) ) {
            $runde = $instance[ 'runde' ];
        } else {
            $runde = __( '', 'text_domain' );
        }
        if ( isset( $instance[ 'link' ] ) ) {
            $link = $instance[ 'link' ];
        } else {
            $link = __( '', 'text_domain' );
        }
        if ( isset( $instance[ 'linktext' ] ) ) {
            $linktext = $instance[ 'linktext' ];
        } else {
            $linktext = __( '', 'text_domain' );
        }
        if ( isset( $instance[ 'linkanzeigen' ] ) ) {
            $linkanzeigen = $instance[ 'linkanzeigen' ];
        } else {
            $linkanzeigen = true;
        }
        if ( 1 == $linkanzeigen ) {
            $linkanzeigen_checked = 'checked="checked"';
        } else {
            $linkanzeigen_checked = "";
        }
        if ( isset( $instance[ 'ergebnis_anzeigen' ] ) ) {
            $ergebnis_anzeigen = $instance[ 'ergebnis_anzeigen' ];
        } else {
            $ergebnis_anzeigen = "2";
        }
        if ( isset( $instance[ 'spielklasse_anzeigen' ] ) ) {
            $spielklasse_anzeigen = $instance[ 'spielklasse_anzeigen' ];
        } else {
            $spielklasse_anzeigen = "2";
        }

?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php if (($this->getElementname() == "Klassenspielplan") || ($this->getElementname() == "Spielplan")) {
            echo "<p>\n" .
                '<label for="' . $this->get_field_id('mannschaft_name') . '"> Mannschaft </label>' . "\n" .
                 '<select id="'. $this->get_field_id('mannschaft_name') .'" name="'.$this->get_field_name('mannschaft_name') .'" class="postform" >' . "\n";
            foreach ($options['mannschaften'] as $mannschaft) {
                echo '<option';
                echo ' '. bpe_lib_tools::getInstance()->option_selected($mannschaft['Name'],$instance['mannschaft_name']);
                echo' value="' . $mannschaft['Name']
                    . '">' . $mannschaft['Name']
                    . " " . $mannschaft['Staffelname'] . '</option>' . "\n";
            }
            echo '</select>' . "\n";
            echo "</p>\n";


            /*<p>
            //    <label for="<?php echo $this->get_field_id( 'staffel_id' ); ?>"><?php _e( 'Staffel ID:' ); ?></label>
            //    <input id="<?php echo $this->get_field_id( 'staffel_id' ); ?>" name="<?php echo $this->get_field_name( 'staffel_id' ); ?>" type="number" step="1" min="1" max="999999" value="<?php echo esc_attr( $staffel_id ); ?>" />
            //    <br /><em>Staffel Id der Spielklasse, die angezeigt werden soll</em>
            </p>*/
         }?>
        <p>
            <label for="<?php echo $this->get_field_id( 'aktuell' ); ?>"><?php _e( 'Nur Positionen >= aktuelles Datum?:' ); ?></label><br>
            <input  id="<?php echo $this->get_field_id( 'aktuell' ); ?>1" name="<?php echo $this->get_field_name( 'aktuell' ); ?>" type="radio" value="1" <?php if ( 1 == $aktuell ) echo esc_attr( $checked ); ?> > Nur >=Datum
            <input  id="<?php echo $this->get_field_id( 'aktuell' ); ?>0" name="<?php echo $this->get_field_name( 'aktuell' ); ?>" type="radio" value="0" <?php if ( 1 != $aktuell ) echo esc_attr( $checked ); ?> > Alle
            <br /><em>Setzen Sie den Wert für Alle oder für ab aktuellem Datum</em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tage_zurueck' ); ?>"><?php _e( 'Tage zurueck:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tage_zurueck' ); ?>" name="<?php echo $this->get_field_name( 'tage_zurueck' ); ?>" type="number" step="1" min="0" max="99" value="<?php echo esc_attr( $tage_zurueck ); ?>" />
            <br /><em>Dieser Wert wird vom aktuellem Datum abgezogen, um zurückliegende Spiele anzuzeigen.</em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'saison' ); ?>"><?php _e( 'Saison:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'saison' ); ?>" name="<?php echo $this->get_field_name( 'saison' ); ?>" type="number" step="1" min="2000" max="2999" value="<?php echo esc_attr( $saison ); ?>" />
            <br /><em>Nur setzen(Format 'jjjj'),</em>  <br /><em>wenn bestimmte Saison genommen werden soll,</em><br /><em>sonst wird der Wert aus den Einstellungen übernommen</em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'runde' ); ?>"><?php _e( 'Runde:' ); ?></label><br>
            <input  id="<?php echo $this->get_field_id( 'runde' ); ?>0" name="<?php echo $this->get_field_name( 'runde' ); ?>" type="radio" value="0" <?php if ( 0 == $runde ) echo esc_attr( $checked ); ?> > Wert aus Einstellungen
            <input  id="<?php echo $this->get_field_id( 'runde' ); ?>1" name="<?php echo $this->get_field_name( 'runde' ); ?>" type="radio" value="1" <?php if ( 1 == $runde ) echo esc_attr( $checked ); ?> > Vorrunde
            <input  id="<?php echo $this->get_field_id( 'runde' ); ?>2" name="<?php echo $this->get_field_name( 'runde' ); ?>" type="radio" value="2" <?php if ( 2 == $runde ) echo esc_attr( $checked ); ?> > Rückrunde
            <br /><em>Setzt fest, ob Daten aus Vor- oder rückrunde genommen werden</em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'spielklasse_anzeigen' ); ?>"><?php _e( 'Spielklasse_anzeigen:' ); ?></label><br>
            <input  id="<?php echo $this->get_field_id( 'spielklasse_anzeigen' ); ?>2" name="<?php echo $this->get_field_name( 'spielklasse_anzeigen' ); ?>" type="radio" value="2" <?php if ( 2 == $spielklasse_anzeigen ) echo esc_attr( $checked ); ?> > Wert aus Einstellungen
            <input  id="<?php echo $this->get_field_id( 'spielklasse_anzeigen' ); ?>0" name="<?php echo $this->get_field_name( 'spielklasse_anzeigen' ); ?>" type="radio" value="0" <?php if ( 0 == $spielklasse_anzeigen ) echo esc_attr( $checked ); ?> > Nicht Anzeigen
            <input  id="<?php echo $this->get_field_id( 'spielklasse_anzeigen' ); ?>1" name="<?php echo $this->get_field_name( 'spielklasse_anzeigen' ); ?>" type="radio" value="1" <?php if ( 1 == $spielklasse_anzeigen ) echo esc_attr( $checked ); ?> > Wert aus Einstellungen
            <br /><em>Setzt fest, ob Spielklasse angezeigt wird</em>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'maxnumber' ); ?>"><?php _e( 'Max. Zeilen:' ); ?></label>
            <input  id="<?php echo $this->get_field_id( 'maxnumber' ); ?>" name="<?php echo $this->get_field_name( 'maxnumber' ); ?>" type="number" step="1" min="1" max="999" value="<?php echo esc_attr( $maxnumber ); ?>" />
            <br /><em>Maximale Anzahl Zeilen, die angezeigt werden sollen</em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'ergebnis_anzeigen' ); ?>"><?php _e( 'Ergebnis/Vorbericht anzeigen:' ); ?></label><br>
            <input  id="<?php echo $this->get_field_id( 'ergebnis_anzeigen' ); ?>2" name="<?php echo $this->get_field_name( 'ergebnis_anzeigen' ); ?>" type="radio" value="2" <?php if ( 2 == $ergebnis_anzeigen ) echo esc_attr( $checked ); ?> > Wert aus Einstellungen
            <input  id="<?php echo $this->get_field_id( 'ergebnis_anzeigen' ); ?>0" name="<?php echo $this->get_field_name( 'ergebnis_anzeigen' ); ?>" type="radio" value="0" <?php if ( 0 == $ergebnis_anzeigen ) echo esc_attr( $checked ); ?> > Nicht Anzeigen
            <input  id="<?php echo $this->get_field_id( 'ergebnis_anzeigen' ); ?>1" name="<?php echo $this->get_field_name( 'ergebnis_anzeigen' ); ?>" type="radio" value="1" <?php if ( 1 == $ergebnis_anzeigen ) echo esc_attr( $checked ); ?> > Anzeigen
            <br /><em>Setzt fest, ob Daten aus Vor- oder rückrunde genommen werden</em>
        </p>
         <p>
            <label for="<?php echo $this->get_field_id( 'linkanzeigen' ); ?>"><?php _e( 'Spielplan Link anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'linkanzeigen' ); ?>" name="<?php echo $this->get_field_name( 'linkanzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $linkanzeigen_checked ); ?>" />

        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link für mehr:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'linktext' ); ?>"><?php _e( 'Linktext:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'linktext' ); ?>" name="<?php echo $this->get_field_name( 'linktext' ); ?>" type="text" value="<?php echo esc_attr( $linktext ); ?>" />
        </p>
    <?php
    }

} // class bttliveSpielplanwidget

