<?php

/**
 * Stellt bttliveKlassenspielplanWidget bereit
 */
class bttliveKlassenspielplanWidget extends bttliveSpielplanWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttliveKlassenspielplanwidget',
            'name'          =>   'TTLive Klassenspielplan',
            'description'   =>   'TTlive Klassenspielplan Widget',
            'elementname'   =>   'Klassenspielplan',
        );
        parent::__construct($arr);
    }

} // class bttliveHeimspielplanwidget
// register bttliveHeimspielplanwidget
if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttliveKlassenspielplanWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttliveKlassenspielplanWidget" ); });
}

