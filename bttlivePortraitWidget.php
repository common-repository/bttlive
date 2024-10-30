<?php

/**
 * Stellt bttliveSpielplanWidget bereit
 */
class bttlivePortraitWidget extends WP_Widget {
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
        $mannschaftsname = apply_filters( 'widget_title', $instance['mannschaftsname'] );
        $spieler = apply_filters( 'widget_title', $instance['spieler'] );
        $portrait_anzeigen = apply_filters( 'widget_title', $instance['portrait_anzeigen'] );
        $action_anzeigen = apply_filters( 'widget_title', $instance['action_anzeigen'] );
        $gebjahr_anzeigen = apply_filters( 'widget_title', $instance['gebjahr_anzeigen'] );
        $verjahr_anzeigen = apply_filters( 'widget_title', $instance['verjahr_anzeigen']);
        $mfuehrer_anzeigen = apply_filters( 'widget_title', $instance['mfuehrer_anzeigen'] );
        $schlaghand_anzeigen = apply_filters( 'widget_title', $instance['schlaghand_anzeigen'] );
        $spieltyp_anzeigen = apply_filters( 'widget_title', $instance['spieltyp_anzeigen'] );
        $geschlecht_anzeigen = apply_filters( 'widget_title', $instance['geschlecht_anzeigen'] );
        $fragen_anzeigen = apply_filters( 'widget_title', $instance['fragen_anzeigen'] );
        $editor_anzeigen = apply_filters( 'widget_title', $instance['editor_anzeigen'] );
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        $arr = array(
            "elementname" => $this->getElementname(),
            "widget" => "1",
            'mannschaftsname' => $mannschaftsname, // name aus Mannschaften, eingetragen in Spielerportraits
            'spieler' => $spieler, // [0] =>'Timo Boll', alle Namen, die ausgegeben werden sollen (optional)(->Einzelportrait?)
            'portrait_anzeigen' => $portrait_anzeigen, // Soll Portraifoto angezeigt werden?
            'action_anzeigen' => $action_anzeigen, // Soll Actionfoto angezeigt werden?
            'gebjahr_anzeigen' => $gebjahr_anzeigen, // Soll Geburtsjahr angezeigt werden?
            'verjahr_anzeigen' => $verjahr_anzeigen, // Soll im Verein angezeigt werden?
            'mfuehrer_anzeigen' => $mfuehrer_anzeigen, // Soll Mannschaftsführer angezeigt werden
            'schlaghand_anzeigen' => $schlaghand_anzeigen, // Soll Schlaghand (links/rechts) angezeigt werden?
            'spieltyp_anzeigen' => $spieltyp_anzeigen, // Soll Spieltyp angezeigt werden?
            'geschlecht_anzeigen' => $geschlecht_anzeigen, // Soll Geschlecht angezeigt werden?
            'fragen_anzeigen' =>$fragen_anzeigen, // Sollen Spielerfragen angezeigt werden?
            'editor_anzeigen' =>$editor_anzeigen, // Sollen Spielerfragen angezeigt werden?
            );

        $tmp = bttlivecontrol($arr,null);
        echo $tmp;
        echo $after_widget;
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
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['mannschaftsname'] = strip_tags( $new_instance['mannschaftsname'] );
        $instance['spieler'] = strip_tags( $new_instance['spieler'] );
        $instance['portrait_anzeigen'] = strip_tags( $new_instance['portrait_anzeigen'] );
        $instance['action_anzeigen'] = strip_tags( $new_instance['action_anzeigen'] );
        $instance['gebjahr_anzeigen'] = strip_tags( $new_instance['gebjahr_anzeigen'] );
        $instance['verjahr_anzeigen'] = strip_tags( $new_instance['verjahr_anzeigen'] );
        $instance['mfuehrer_anzeigen'] = strip_tags( $new_instance['mfuehrer_anzeigen'] );
        $instance['schlaghand_anzeigen'] = strip_tags( $new_instance['schlaghand_anzeigen'] );
        $instance['spieltyp_anzeigen'] = strip_tags( $new_instance['spieltyp_anzeigen'] );
        $instance['geschlecht_anzeigen'] = strip_tags( $new_instance['geschlecht_anzeigen'] );
        $instance['fragen_anzeigen'] = strip_tags( $new_instance['fragen_anzeigen'] );
        $instance['editor_anzeigen'] = strip_tags( $new_instance['editor_anzeigen'] );
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
        $term_list = get_terms('mannschaften');
        if ( empty( $term_list ) || is_wp_error( $term_list ) ){
            echo "<div>Es sind keine Mannschaften angelegt!</div>";
            return;
        }
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Neuer Titel', 'text_domain' );
        }
        if ( isset( $instance[ 'mannschaftsname' ] ) ) {
            $mannschaftsname = $instance[ 'mannschaftsname' ];
        } else {
            $mannschaftsname = __( '', 'text_domain' );
        }

        if ( isset( $instance[ 'spieler' ] ) ) {
            $spieler = $instance[ 'spieler' ];
        } else {
            $spieler = array();
        }

        if ( isset( $instance[ 'portrait_anzeigen' ] ) ) {
            $portrait_anzeigen = $instance[ 'portrait_anzeigen' ];
        } else {
            $portrait_anzeigen = true;
        }
        if ( true == $portrait_anzeigen ) {
            $portrait_anzeigen_checked = 'checked="checked"';
        } else {
            $portrait_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'action_anzeigen' ] ) ) {
            $action_anzeigen = $instance[ 'action_anzeigen' ];
        } else {
            $action_anzeigen = true;
        }
        if ( true == $action_anzeigen ) {
            $action_anzeigen_checked = 'checked="checked"';
        } else {
            $action_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'gebjahr_anzeigen' ] ) ) {
            $gebjahr_anzeigen = $instance[ 'gebjahr_anzeigen' ];
        } else {
            $gebjahr_anzeigen =true;
        }
        if ( true == $gebjahr_anzeigen ) {
            $gebjahr_anzeigen_checked = 'checked="checked"';
        } else {
            $gebjahr_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'verjahr_anzeigen' ] ) ) {
            $verjahr_anzeigen = $instance[ 'verjahr_anzeigen' ];
        } else {
            $verjahr_anzeigen = true;
        }
        if ( true == $verjahr_anzeigen ) {
            $verjahr_anzeigen_checked = 'checked="checked"';
        } else {
            $verjahr_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'mfuehrer_anzeigen' ] ) ) {
            $mfuehrer_anzeigen = $instance[ 'mfuehrer_anzeigen' ];
        } else {
            $mfuehrer_anzeigen = true;
        }
        if ( true == $mfuehrer_anzeigen ) {
            $mfuehrer_anzeigen_checked = 'checked="checked"';
        } else {
            $mfuehrer_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'schlaghand_anzeigen' ] ) ) {
            $schlaghand_anzeigen = $instance[ 'schlaghand_anzeigen' ];
        } else {
            $schlaghand_anzeigen = true;
        }
        if ( true == $schlaghand_anzeigen ) {
            $schlaghand_anzeigen_checked = 'checked="checked"';
        } else {
            $schlaghand_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'spieltyp_anzeigen' ] ) ) {
            $spieltyp_anzeigen = $instance[ 'spieltyp_anzeigen' ];
        } else {
            $spieltyp_anzeigen = true;
        }
        if ( true == $spieltyp_anzeigen ) {
            $spieltyp_anzeigen_checked = 'checked="checked"';
        } else {
            $spieltyp_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'geschlecht_anzeigen' ] ) ) {
            $geschlecht_anzeigen = $instance[ 'geschlecht_anzeigen' ];
        } else {
            $geschlecht_anzeigen = true;
        }
        if ( true == $geschlecht_anzeigen ) {
            $geschlecht_anzeigen_checked = 'checked="checked"';
        } else {
            $geschlecht_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'fragen_anzeigen' ] ) ) {
            $fragen_anzeigen = $instance[ 'fragen_anzeigen' ];
        } else {
            $fragen_anzeigen = true;
        }
        if ( true == $fragen_anzeigen ) {
            $fragen_anzeigen_checked = 'checked="checked"';
        } else {
            $fragen_anzeigen_checked = "";
        }
        if ( isset( $instance[ 'editor_anzeigen' ] ) ) {
            $editor_anzeigen = $instance[ 'editor_anzeigen' ];
        } else {
            $editor_anzeigen = true;
        }
        if ( true == $editor_anzeigen ) {
            $editor_anzeigen_checked = 'checked="checked"';
        } else {
            $editor_anzeigen_checked = "";
        }

?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p> <?php
            echo '<label for="'. $this->get_field_id( 'mannschaftsname' ) .'">' ._e( 'Mannschaftsname' ). '</label> ';echo "\n";

            echo'<select id="'. $this->get_field_id( 'mannschaftsname' ) .'" name="'.$this->get_field_name( 'mannschaftsname' ) .'" size="1">';
            foreach ($term_list as $term) {
                echo '<option';
                if ( $mannschaftsname == $term->slug ) echo ' selected="selected"';
                echo ' value="' . $term->slug . '" ';
                echo'>'. $term->name . '</option>';echo "\n";
            }
            echo '</select>';
            ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'portrait_anzeigen' ); ?>"><?php _e( 'Portrait anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'portrait_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'portrait_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $portrait_anzeigen_checked ); ?>" />

        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'action_anzeigen' ); ?>"><?php _e( 'Actions - Bild anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'action_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'action_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $action_anzeigen_checked ); ?>" />

        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'gebjahr_anzeigen' ); ?>"><?php _e( 'Geburtsjahr anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'gebjahr_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'gebjahr_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $gebjahr_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'verjahr_anzeigen' ); ?>"><?php _e( 'Im Verein seit anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'verjahr_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'verjahr_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $verjahr_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'mfuehrer_anzeigen' ); ?>"><?php _e( 'Mannschaftsführer anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'mfuehrer_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'mfuehrer_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $mfuehrer_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'schlaghand_anzeigen' ); ?>"><?php _e( 'Schlaghand anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'schlaghand_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'schlaghand_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $schlaghand_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'spieltyp_anzeigen' ); ?>"><?php _e( 'Spieltyp anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'spieltyp_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'spieltyp_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $spieltyp_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'geschlecht_anzeigen' ); ?>"><?php _e( 'Geschlecht anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'geschlecht_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'geschlecht_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $geschlecht_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'fragen_anzeigen' ); ?>"><?php _e( 'Fragen und Antworten anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'fragen_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'fragen_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $fragen_anzeigen_checked ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'editor_anzeigen' ); ?>"><?php _e( 'Freien Text anzeigen:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'editor_anzeigen' ); ?>" name="<?php echo $this->get_field_name( 'editor_anzeigen' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $editor_anzeigen_checked ); ?>" />
        </p>
    <?php
    }

} // class bttlivePortraitwidget

