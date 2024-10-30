<?php

/**
 * Stellt bttliveTeamspielplanWidget bereit
 */
class bttliveTeamspielplanWidget extends bttliveSpielplanWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttliveTeamspielplanwidget',
            'name'          =>   'TTLive Teamspielplan',
            'description'   =>   'TTlive Teamspielplan Widget',
            'elementname'   =>   'Spielplan',
        );
        parent::__construct($arr);
    }

} // class bttliveTeamspielplanwidget
// register bttliveTeamspielplanwidget

if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttliveTeamspielplanWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttliveTeamspielplanWidget" ); });
}