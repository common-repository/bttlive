<?php

/**
 * Stellt bttliveHeimspielplanWidget bereit
 */
class bttliveHeimspielplanWidget extends bttliveSpielplanWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttliveHeimspielplanwidget',
            'name'          =>   'TTLive Hallenplan',
            'description'   =>   'TTlive Heimspielplan Widget',
            'elementname'   =>   'Heimspielplan',
        );
        parent::__construct($arr);
    }

} // class bttliveHeimspielplanwidget
// register bttliveHeimspielplanwidget

if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttliveHeimspielplanWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttliveHeimspielplanWidget" ); });
}