<?php

/**
 * Stellt bttliveHeimspielplanWidget bereit
 */
class bttliveSpielerPortraitWidget extends bttlivePortraitWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttliveSpielerPortraitwidget',
            'name'          =>   'TTLive Spielerportraits',
            'description'   =>   'TTlive Spielerportraits Widget',
            'elementname'   =>   'SpielerPortrait',
        );
        parent::__construct($arr);
    }

} // class bttliveHeimspielplanwidget
// register bttliveHeimspielplanwidget

if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttliveSpielerPortraitWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttliveSpielerPortraitWidget" ); });
}