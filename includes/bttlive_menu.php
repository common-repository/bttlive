<?php
/**
 * Project: bttlive
 * File: bttlive_menu.php
 * Version: 1.0
 * Author: Bernt Penderak
 * Author URI: http://bepe.penderak.net
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Date: 06.10.14

 */

class bttlive_menu {
    function __construct()
    {
       add_action('admin_menu',array( $this, 'menu_register'));
    }
    public function menu_register(){
        // Einstellungen unter post?
        add_submenu_page(
            'edit.php?post_type=bttlive_spieler',
            'bttlive Einstellungen',
            'bttlive Einstellungen',
            'manage_options',
            'bttlive_do_page', // nicht mehr nötig ab 3.0 ???
            'bttlive_do_page'
        );
        add_submenu_page(
            'edit.php?post_type=bttlive',
            'bttlive Einstellungen',
            'bttlive Einstellungen',
            'manage_options',
            'bttlive_do_page', // nicht mehr nötig ab 3.0 ???
            'bttlive_do_page'
        );
    }

} 