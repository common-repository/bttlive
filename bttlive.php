<?php
/**
 * Project: bttlive
 * Plugin Name: bttlive
 * Version: 1.5
 * Description: ttlive - Zugriff für wordpress
 * Author: Bernt Penderak
 * Author URI: http://bepe.penderak.net
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Date: 30.06.20
 */


if (!class_exists('bpe_lib_tools')) {
    require_once('includes/bpe_lib_tools.php');
}

// check PHP version
$bpe_required_php_version = '5.3.1';
$exit_msg = sprintf( 'bttlive requires PHP %s or newer.', $bpe_required_php_version ) .
    '<a href="http://wordpress.org/about/requirements/"> ' . 'Please update!' . '</a>';
bpe_lib_tools::getInstance()->check_version( PHP_VERSION, $bpe_required_php_version, $exit_msg, __FILE__ );

// check WP version
$bpe_required_wp_version = '3.7';
$exit_msg = sprintf( 'bttlive requires WordPress %s or newer.', $bpe_required_wp_version ) .
    '<a href="http://codex.wordpress.org/Upgrading_WordPress"> ' . 'Please update!' . '</a>';
bpe_lib_tools::getInstance()->check_version(get_bloginfo('version'), $bpe_required_wp_version, $exit_msg, __FILE__ );

// Setzen Plugin - Name
bpe_lib_tools::getInstance()->construct('bttlive');

require_once('bttliveregister.php');

if (!class_exists('bttlive_tools')) {
    require_once('includes/bttlive_tools.php');
}
bttlive_tools::getInstance()->construct(bpe_lib_tools::getInstance()->getOptions());

if (!class_exists('bttliveShortTxt')) {
    require_once('includes/bttliveShortTxt.php');
}

if (!class_exists('bttlive_menu')) {
    require_once('includes/bttlive_menu.php');
    $m=new bttlive_menu();
}
if (!class_exists('bttliveHandbuch')) {
    require_once('includes/bttliveHandbuch.php');
    $handbuch=new bttliveHandbuch();
}


require_once('bttliveposttype.php');

require_once('bttlivebox.php');

require_once('bttliverefresh.php');

require_once('bttliveansicht.php');

require_once('bttliveTabelle.php');

require_once('bttliveRangliste.php');

require_once('bttliveMannschaft.php');

require_once('bttliveSpielplan.php');

if (!class_exists('bttliveSpielerPortrait')) {
    require_once('bttliveSpielerPortrait.php');
    $spielerportrait = new bttliveSpielerPortrait();
    $spielerportrait->construct();
}





require_once('bttlivecontrol.php');

require_once('bttliveSpielplanWidget.php');

require_once('bttlivePortraitWidget.php');

require_once('bttliveHeimspielplanWidget.php');

require_once('bttliveSpielerPortraitWidget.php');

require_once('bttliveKlassenspielplanWidget.php');

require_once('bttliveVereinsplanWidget.php');

require_once('bttliveTeamspielplanWidget.php');

require_once('bttlive14TageWidget.php');



?>