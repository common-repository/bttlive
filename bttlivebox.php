<?php


add_action('add_meta_boxes','bttlive_addmetaboxes');
add_action('save_post', 'bttlive_savedata');

add_action('add_meta_boxes','bttlive_addmetaboxes_spieler');

// scripts zur Spieler - Portrait - Anwendung
add_action('admin_enqueue_scripts', 'bttlive_scripts');

function bttlive_scripts() {
        wp_enqueue_media();
        wp_register_script('bttlive-js', plugins_url( 'js/bttlive-js.js', __FILE__ ) , array('jquery'));
        wp_enqueue_script('bttlive-js');
}
/**
 * Generiert die Metabox in dem post_type bttlive Mannschaft
 *
 */
function bttlive_addmetaboxes() {
add_meta_box(
'bttlive_metabox',
'Einstellungen bttlive Inhalte',
'bttlive_box',
'bttlive',
'side',
'high'
);
}

/**
 * Generiert die Metabox in dem post_type bttlive Spieler
 */
function bttlive_addmetaboxes_spieler() {
    add_meta_box(
        'bttlive_metabox_spieler',
        'Einstellungen Spieler',
        'bttlive_spieler_box',
        'bttlive_spieler',
        'normal',
        'high'
    );
}

/**
 * Gibt die Daten in der Metabox in html aus
 *
 */
function bttlive_box(){
wp_nonce_field('bttlive_action','bttlive_name');

$term_list = get_terms('mannschaften');
if ( empty( $term_list ) || is_wp_error( $term_list ) ){
   echo "<div>Es sind keine Mannschaften angelegt!</div>";
   return;
}
$mannschaftsname=get_post_meta(get_the_ID(), '_mannschaftsname',true);

            echo '<p><label for="myplugin_fieldm">' ._e( 'Mannschaft' ). '</label> ';echo "\n";

            echo'<select id="myplugin_fieldm" name="myplugin_fieldm" size="1">';
            foreach ($term_list as $term) {
                echo '<option';
                if ( $mannschaftsname == $term->slug ) echo ' selected="selected"';
                echo ' value="' . $term->slug . '" ';
                echo'>'. $term->name . '</option>';echo "\n";
            }
            echo '</select></p>';
            echo '<p><br/><label for="spaltenlayout">Layout: </label> ';echo "\n";
            $wlayout=get_post_meta(get_the_ID(), '_layout',true);
    $swerte = array(
        '1' => '1-Spaltig',
        '2' => '2-Spaltig',

    );
echo'<select id="spaltenlayout" name="spaltenlayout" size="1">';
    for ($i = 1; $i <= 3; $i++) {
        echo '<option value="' . $i . '"';
        if ( $i == $wlayout ) echo ' selected="selected"';
        echo'>'. $swerte["$i"] . '</option>';echo "\n";

    }
echo '</select>';echo "</p>\n";
echo '<p><br/><label for="myplugin_field4">Tabelle anzeigen: </label> ';
    $wert4=get_post_meta(get_the_ID(), '_tabelle_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field4" name="myplugin_field4" value="1" ';
    if ( 1 == $wert4 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';

echo '<p><br/><label for="myplugin_field5">Aufstellung anzeigen: </label> ';
    $wert5=get_post_meta(get_the_ID(), '_aufstellung_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field5" name="myplugin_field5" value="1" ';
    if ( 1 == $wert5 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';
echo '<p><br/><label for="myplugin_field9a">Spielerporträt anzeigen: </label> ';
    $wert9=get_post_meta(get_the_ID(), '_spielerportrait_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field9a" name="myplugin_field9a" value="1" ';
    if ( 1 == $wert9 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';
echo '<p><br/><label for="myplugin_field9b">Klassenspielplan anzeigen: </label> ';
    $wert9b=get_post_meta(get_the_ID(), '_klassenspielplan_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field9b" name="myplugin_field9b" value="1" ';
    if ( 1 == $wert9b ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';


echo '<p><br/><label for="myplugin_field6">Nächste Spiele anzeigen: </label> ';
    $wert6=get_post_meta(get_the_ID(), '_nspiele_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field6" name="myplugin_field6" value="1" ';
    if ( 1 == $wert6 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';

echo '<p><br/><label for="myplugin_field7">Alle Spiele anzeigen: </label> ';
    $wert7=get_post_meta(get_the_ID(), '_aspiele_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field7" name="myplugin_field7" value="1" ';
    if ( 1 == $wert7 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';

echo '<p><br/><label for="myplugin_field8">Heimspielplan anzeigen: </label> ';
    $wert8=get_post_meta(get_the_ID(), '_heimspielplan_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field8" name="myplugin_field8" value="1" ';
    if ( 1 == $wert8 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';
echo '<p><br/><label for="myplugin_field9">Vereinsplan anzeigen: </label> ';
    $wert9=get_post_meta(get_the_ID(), '_vereinsplan_anzeigen',true);
    echo '<input type="checkbox" id="myplugin_new_field9" name="myplugin_field9" value="1" ';
    if ( 1 == $wert9 ) echo 'checked="checked"';
    echo ' style="width:4em"/></p>';

}

/**
 * Gibt die Daten in der Metabox in html aus
 *
 */
function bttlive_spieler_box(){
    wp_nonce_field('bttlive_action','bttlive_spieler');

    echo '<p><label for="spieler_field1">TTLive Spielername:</label>';
    $wert1=get_post_meta(get_the_ID(), '_spieler_name',true);
    echo '<input type="text" id="spieler_field1" name="spieler_field1" value="'.$wert1.'" class="large-text"/>';echo "</p>\n";

    echo '<p><label for="spieler_field1_a">Position: </label>';
    $wertpos=get_post_meta(get_the_ID(), '_position',true);
    echo '<input type="text" id="spieler_field1_a" name="spieler_field1_a" value="'.$wertpos.'" style="width:5em" />';echo "</p>\n";
    echo '<br />Auf welcher Position soll der Verantwortliche erscheinen (z.B. T01 für Trainer erscheint am Ende)?';echo "</p>\n";

    echo '<p><br/><label for="spieler_field1_b">Portraittyp </label> ';echo "\n";
    $wertpt=get_post_meta(get_the_ID(), '_portraittyp',true);
    $swerte = array(
        '1' => 'Spieler',
        '2' => 'Trainer',
        '3' => 'Betreuer',
    );
    echo'<select id="spieler_field1_b" name="spieler_field1_b" size="1">';
    for ($i = 1; $i <= 3; $i++) {
        echo '<option';
        if ( $swerte["$i"] == $wertpt ) echo ' selected="selected"';
        echo'>'. $swerte["$i"] . '</option>';echo "\n";

    }
    echo '</select>';echo "</p>\n";

    $wertimg1=get_post_meta(get_the_ID(), '_portrait_bild',true);
    echo '<p  ><img class="bttliveImg" src="'.$wertimg1 .'" id="spieler_img1"  alt="Portrait '.$wert1.'" width="250px"></p>';
    echo '<p><label for="spieler_bild1">Portrait-Bild</label>';
    echo '<input id="portrait_bild" type="text" class="large-text" name="spieler_bild1" value="'.$wertimg1 .'"/> ';
    echo '<input id="portrait_bild_button" class="button" type="button" value="Portrait einstellen" />';
    echo '<br />Eine URL eingeben oder ein Bild einstellen';echo "</p>\n";

    $wertimg2=get_post_meta(get_the_ID(), '_action_bild',true);
    echo '<p  ><img class="bttliveImg" src="'.$wertimg2 .'" id="spieler_img2" alt="Actionfoto '.$wert1.'"  width="250px"></p>';
    echo '<p><label for="spieler_bild2">Aktions-Bild</label>';
    echo '<input id="action_bild" type="text" class="large-text" name="spieler_bild2" value="'.$wertimg2 .'" />';
    echo '<input id="action_bild_button" class="button" type="button" value="Action-Bild einstellen" />';
    echo '<br />Eine URL eingeben oder ein Bild einstellen';echo "</p>\n";

    echo '<p><label for="spieler_field1_1">Vorname des Spielers:</label>';
    $wert1_1=get_post_meta(get_the_ID(), '_vorname',true);
    echo '<input type="text" id="spieler_field1_1" name="spieler_field1_1" value="'.$wert1_1.'" class="large-text" /> </p>';echo "</p>\n";

    echo '<p><label for="spieler_field1_2">Nachname des Spielers:</label>';
    $wert1_2=get_post_meta(get_the_ID(), '_nachname',true);
    echo '<input type="text" id="spieler_field1_2" name="spieler_field1_2" value="'.$wert1_2.'" class="large-text" /> </p>';echo "</p>\n";

    echo '<p><label for="spieler_field2">Geburtsjahr: </label>';
    $wert2=get_post_meta(get_the_ID(), '_gebjahr',true);
    echo '<input type="number" step="1" min="1900" max="9999" id="spieler_field2" name="spieler_field2" value="'.$wert2.'" style="width:5em" />';echo "</p>\n";

    echo '<p><label for="spieler_field3">Im Verein seit: </label>';
    $wert3=get_post_meta(get_the_ID(), '_verjahr',true);
    echo '<input type="number" step="1" min="1900" max="9999" id="spieler_field3" name="spieler_field3" value="'.$wert3.'" style="width:5em" />';echo "</p>\n";

    echo '<p><br/><label for="spieler_field4">Mannschaftsführer?: </label> ';
    $wert4=get_post_meta(get_the_ID(), '_mannschafts_fuehrer',true);
    echo '<input type="checkbox" id="spieler_field4" name="spieler_field4" value="1" ';
    if ( 1 == $wert4 ) echo 'checked="checked"';
    echo ' style="width:4em"/>';echo "</p>\n";

    echo '<p><br/><label for="spieler_field5">Schlaghand: </label> ';echo "\n";
    $wert5=get_post_meta(get_the_ID(), '_schlaghand',true);
    $swerte = array(
        '1' => 'Rechts',
        '2' => 'Links',
    );
    echo'<select id="spieler_field5" name="spieler_field5" size="1">';
    for ($i = 1; $i <= 2; $i++) {
        echo '<option';
        if ( $swerte["$i"] == $wert5 ) echo ' selected="selected"';
        echo'>'. $swerte["$i"] . '</option>';echo "\n";

    }
    echo '</select>';echo "</p>\n";

    echo '<p><br/><label for="spieler_field6">Spieltyp: </label> ';echo "\n";
    $wert6=get_post_meta(get_the_ID(), '_spieltyp',true);
    $swerte = array(
        '1' => 'Offensiv',
        '2' => 'Defensiv',
        '3' => 'Allround',
    );
    echo'<select id="spieler_field6" name="spieler_field6" size="1">';
    for ($i = 1; $i <= 3; $i++) {
        echo '<option';
        if ( $swerte["$i"] == $wert6 ) echo ' selected="selected"';
        echo'>'. $swerte["$i"] . '</option>';echo "\n";

    }
    echo '</select>';echo "</p>\n";


    echo '<p><br/><label for="spieler_field7">Geschlecht: </label> ';echo "\n";
    $wert7=get_post_meta(get_the_ID(), '_geschlecht',true);
    $swerte = array(
        '1' => 'm',
        '2' => 'w',
        '3' => 'o', // other ;-)
    );
    for ($i = 1; $i <= 3; $i++) {
        echo'<input type="radio" id="spieler_field7" name="spieler_field7" value="' . $swerte["$i"] . '"';
        if ( $swerte["$i"] == $wert7 ) echo ' checked="checked"';
        echo ' >';
        echo $swerte["$i"];

    }
    echo '</select>';echo "</p>\n";
    $options=bpe_lib_tools::getInstance()->getOptions();

    for ($i = 1; $i <= $options['anzahl_spieler_fragen']; $i++) {
        echo '<p class="bttlive_spieler_frage"><br/><label for="spieler_field_frage' . $i . '" >Frage ' . $i . ':</label> ';
        $wertfrage=get_post_meta(get_the_ID(), '_frage' . $i ,true);
        if (empty($wertfrage)) {
            $wertfrage=$options['spieler_frage' . $i];
        }
        echo '<textarea class="large-text" rows="2" cols="26"  name="spieler_field_frage' . $i  . '" >' . $wertfrage . '</textarea>';echo "</p>\n";

        echo '<p class="bttlive_spieler_antwort"><br/><label for="spieler_field_antwort' . $i . '" >Antwort ' . $i . ':</label> ';
        $wertantwort=get_post_meta(get_the_ID(), '_antwort' . $i ,true);
        echo '<textarea class="large-text" rows="2" cols="26"  name="spieler_field_antwort' . $i  . '" >' . $wertantwort . '</textarea>';echo "</p>\n";
    }




}


/**
 * Speichert die Daten in der Datenbank, die in der Metabox Mannschaft eingegeben wurden
 *
 * @param $post_id
 */
function bttlive_savedata($post_id){
    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return false;
    if( !current_user_can('edit_post',$post_id)) return false;
    if ( ! isset( $_POST['post_type'] )) return false;

    switch ($_POST['post_type']) {
    case 'bttlive':
        if ( !wp_verify_nonce($_POST['bttlive_name'],'bttlive_action')) return false;
        update_post_meta($_POST['post_ID'], '_mannschaftsname', $_POST['myplugin_fieldm'], false);
        update_post_meta($_POST['post_ID'], '_layout', $_POST['spaltenlayout'], false);
        update_post_meta($_POST['post_ID'], '_tage_anzahl', $_POST['myplugin_field3'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_tabelle_anzeigen', $_POST['myplugin_field4'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_aufstellung_anzeigen', $_POST['myplugin_field5'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_nspiele_anzeigen', $_POST['myplugin_field6'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_aspiele_anzeigen', $_POST['myplugin_field7'], false); // das _ verhindert, dass es zu sehen istupdate_post_meta($_POST['post_ID'], '_tabelle_anzeigen', $_POST['myplugin_field4'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_heimspielplan_anzeigen', $_POST['myplugin_field8'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_vereinsplan_anzeigen', $_POST['myplugin_field9'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_saison', $_POST['myplugin_field10'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_spielerportrait_anzeigen', $_POST['myplugin_field9a'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_klassenspielplan_anzeigen', $_POST['myplugin_field9b'], false); // das _ verhindert, dass es zu sehen ist
        update_post_meta($_POST['post_ID'], '_runde', $_POST['myplugin_field11'], false); // das _ verhindert, dass es zu sehen ist
        break;
    case 'bttlive_spieler':
        if ( !wp_verify_nonce($_POST['bttlive_spieler'],'bttlive_action')) return false;
        update_post_meta($_POST['post_ID'], '_spieler_name', $_POST['spieler_field1'], false);
        update_post_meta($_POST['post_ID'], '_position', $_POST['spieler_field1_a'], false);
        update_post_meta($_POST['post_ID'], '_portraittyp', $_POST['spieler_field1_b'], false);
        update_post_meta($_POST['post_ID'], '_portrait_bild', $_POST['spieler_bild1'], false);
        update_post_meta($_POST['post_ID'], '_action_bild', $_POST['spieler_bild2'], false);
        update_post_meta($_POST['post_ID'], '_vorname', $_POST['spieler_field1_1'], false);
        update_post_meta($_POST['post_ID'], '_nachname', $_POST['spieler_field1_2'], false);
        update_post_meta($_POST['post_ID'], '_gebjahr', $_POST['spieler_field2'], false);
        update_post_meta($_POST['post_ID'], '_verjahr', $_POST['spieler_field3'], false);
        update_post_meta($_POST['post_ID'], '_mannschafts_fuehrer', $_POST['spieler_field4'], false);
        update_post_meta($_POST['post_ID'], '_schlaghand', $_POST['spieler_field5'], false);
        update_post_meta($_POST['post_ID'], '_spieltyp', $_POST['spieler_field6'], false);
        update_post_meta($_POST['post_ID'], '_geschlecht', $_POST['spieler_field7'], false);
        for ($i = 1; $i <= 10; $i++) {
            update_post_meta($_POST['post_ID'], '_frage' . $i, $_POST['spieler_field_frage' . $i], false);
            update_post_meta($_POST['post_ID'], '_antwort' . $i, $_POST['spieler_field_antwort' . $i], false);
        }
        break;
    default:
    return false;
}
    bpe_lib_tools::getInstance()->log($_POST,
        __FUNCTION__ . ":" . __LINE__ . ": Save custom post_type: "
        . $_POST['post_type']. " ID:" . $_POST['post_ID'] );

}

