<?php
/**
 * Stellt bttliveVereinsplanWidget bereit
 */
class bttliveVereinsplanWidget extends bttliveSpielplanWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $arr = array(
            'basisid'       =>   'bttliveVereinsspielplanwidget',
            'name'          =>   'TTLive Vereinsplan',
            'description'   =>   'bttlive Vereinsplan Widget',
            'elementname'   =>   'Vereinsplan',
        );
        parent::__construct($arr);
    }

} // class bttliveVereinsplanwidget
// register bttliveVereinsplanwidget
if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
    add_action( 'widgets_init', create_function( '', 'register_widget( "bttliveVereinsplanWidget" );' ) );
}else{
    add_action('widgets_init',function() {
        return register_widget( "bttliveVereinsplanWidget" ); });
}
