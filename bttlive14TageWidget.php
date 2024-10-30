<?php

/**
 * Stellt bttlive14TageWidget bereit
 */
class bttlive14TageWidget extends bttliveSpielplanWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttlive14Tagewidget',
            'name'          =>   'TTLive 14Tage-Plan',
            'description'   =>   'TTlive 14Tage-Plan Widget',
            'elementname'   =>   '14Tage',
        );
        parent::__construct($arr);
    }
} // class bttlive14Tagewidget
// register bttlive14Tagewidget

if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttlive14TageWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttlive14TageWidget" ); });
}

