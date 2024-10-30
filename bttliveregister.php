<?php

add_action('admin_init','bttlive_opt_init');
register_activation_hook(realpath(dirname(__FILE__)).'/bttlive.php','bttlive_install');
register_deactivation_hook(realpath(dirname(__FILE__)).'/bttlive.php', 'bttlive_remove' );

/**
 * Registriert die Optionen als array bttlive_opt in admin_menu
 *
 */
function bttlive_opt_init() {

    register_setting(
        bpe_lib_tools::getInstance()->getOptionName() . '_options',
        bpe_lib_tools::getInstance()->getOptionName(),
        'bttlive_opt_validate');
}


/**
 * Validiert die eingegeben Werte
 *
 * @param $input
 * @return mixed
 */
function bttlive_opt_validate($input) {
    // Todo validieren!!!!
    $input['mannschaften'] = bpe_lib_tools::getInstance()->get_option("mannschaften");
    $input['bttlive_css'] = wp_filter_kses($input['bttlive_css']);
    if(empty($input['bttlive_s_anz'])) { $input['bttlive_s_anz']='0'; }
    if(empty($input['bttlive_e_anz'])) { $input['bttlive_e_anz']='0'; }
    if(empty($input['debug_trace'])) { $input['debug_trace']='0'; }
    if(empty($input['debug_application'])) { $input['debug_application']='0'; }
    return $input;
}



/**
 * Wird bei der Installation aufgerufen und registriert und erzeugt alle Optionen mit Default - Werten
 *
 */
function bttlive_install() {

    $options = array(
        // -- Einstellungen
        'bttlive_baseurl'                       => 'http://ttvsh.tischtennislive.de',
        'bttlive_refreshHours'                  => '2',
        'bttlive_divisionID'                    => '34',
        'mannschaften'                          => array(),
        'bttlive_ownteam'                       => '/FT Preetz/',
        'bttlive_saison'                        => '2020', // gegenwärtige Saison für Heimspiel- und Vereinsplan
        'bttlive_runde'                         => '1', // 1= Vorrunde, 2 = Rückrunde
        'bttlive_s_anz'                         => '0', // Spielklasse im Vereins- und Heimspielplan ja/nein
        'bttlive_e_anz'                         => '1', // Ergebnisse anzeigen ja/nein (Voreinstellung kann geändert werden)
        // -- Style
        'bttlive_tableclassname_TeamSpielplan'  => 'TTLiveSpielplan',
        'bttlive_tableclassname_Aufstellung'    => 'TTLiveAufstellung',
        'bttlive_tableclassname_Tabelle'        => 'TTLiveTabelle',
        'bttlive_tableclassname_14Tage'         => 'TTLive14Tage',
        'bttlive_tableclassname_Mannschaft'     => 'TTLiveMannschaft',
        'bttlive_tableclassname_Rangliste'      => 'TTLiveRangliste',
        'bttlive_tableclassname_Heimspielplan'  => 'TTLiveHeimspielplan',
        'bttlive_tableclassname_Vereinsplan'    => 'TTLiveVereinsplan',

        'bttlive_tableclassname_Klassenspielplan'  => 'TTLiveKlassenspielplan',
        'bttlive_css'                           => '
@media (max-width: 50em) { /* Breite beträgt höchstens 60em */
    .TTLiveTabelle, .TTLiveSpielplan, .TTLiveAufstellung, .TTLiveKlassenspielplan {
        font-size:70%;
        width:100%;
    }
    .TTLiveTabelle td,
    .TTLiveTabelle th,
    .TTLiveSpielplan td,
    .TTLiveSpielplan th,
    .TTLiveAufstellung td,
    .TTLiveAufstellung th,
    .TTLiveKlassenspielplan td,
    .TTLiveKlassenspielplan th
    {
        font-size:70%;
    }

}
@media (min-width: 50em) { /* Breite beträgt mindestens 60em */
    .TTLiveTabelle,
    .TTLiveSpielplan,
    .TTLiveAufstellung,
    .TTLiveKlassenspielplan
    {
        font-size:90%;
        width:100%;
    }

   .TTLiveTabelle td,
   .TTLiveTabelle th,
   .TTLiveSpielplan td,
   .TTLiveSpielplan th,
   .TTLiveAufstellung td,
   .TTLiveAufstellung th,
   .TTLiveKlassenspielplan td,
   .TTLiveKlassenspielplan th
   {
        font-size:90%;
    }
}

.TTLiveTabelle,
.TTLiveSpielplan,
.TTLiveAufstellung,
.TTLiveRangliste,
.TTLiveKlassenspielplan
{
    margin-bottom:0.5em;
    padding: 2px;
    border-radius: 10px;
    box-shadow: 0 0 15px #333;
    color: #666;
    margin:auto;
    border-spacing: 0;
    border-collapse: separate;
}


.TTLiveTabelle td,
.TTLiveTabelle th,
.TTLiveSpielplan td,
.TTLiveSpielplan th,
.TTLiveRangliste td,
.TTLiveRangliste th
{
    padding: 2px 2px;
    border-bottom: 1px solid green;
    border-right: 1px solid green;
}

.TTLiveAufstellung td,
.TTLiveAufstellung th,
.TTLiveKlassenspielplan td,
.TTLiveKlassenspielplan th
{
    padding: 1px 1px;
    border-bottom: 1px solid green;
    border-right: 1px solid green;
}

.TTLiveTabelle th,
.TTLiveSpielplan th,
.TTLiveRangliste th,
.TTLiveAufstellung th,
.TTLiveKlassenspielplan th
{
    background: black;
    color: white;
}

.TTLiveTabelle tr:last-child td:first-child,
.TTLiveSpielplan tr:last-child td:first-child,
.TTLiveRangliste tr:last-child td:first-child,
.TTLiveAufstellung tr:last-child td:first-child,
.TTLiveKlassenspielplan tr:last-child td:first-child
{
    border-bottom-left-radius:10px;
}

.TTLiveTabelle tr:last-child td:last-child,
.TTLiveSpielplan tr:last-child td:last-child,
.TTLiveRangliste tr:last-child td:last-child,
.TTLiveAufstellung tr:last-child td:last-child,
.TTLiveKlassenspielplan tr:last-child td:last-child
{
    border-bottom-right-radius:10px;
}

.TTLiveTabelle tr th:first-child,
.TTLiveTabelle tr td:first-child,
.TTLiveRangliste tr th:first-child,
.TTLiveRangliste tr td:first-child,
.TTLiveSpielplan tr th:first-child,
.TTLiveSpielplan tr td:first-child,
.TTLiveAufstellung tr td:first-child,
.TTLiveAufstellung tr th:first-child,
.TTLiveKlassenspielplan tr th:first-child,
.TTLiveKlassenspielplan tr td:first-child
{
    border-left: 1px solid black;
}
.TTLiveTabelle tr:first-child th,
.TTLiveTabelle tr:first-child td,
.TTLiveRangliste tr:first-child th,
.TTLiveRangliste tr:first-child td,
.TTLiveSpielplan tr:first-child th,
.TTLiveSpielplan tr:first-child td,
.TTLiveAufstellung tr:first-child th,
.TTLiveAufstellung tr:first-child td,
.TTLiveKlassenspielplan tr:first-child th,
.TTLiveKlassenspielplan tr:first-child th
{
    border-top: 1px solid black;
}

.TTLiveTabelle tr:first-child th:first-child,
.TTLiveRangliste tr:first-child th:first-child,
.TTLiveSpielplan tr:first-child th:first-child,
.TTLiveAufstellung tr:first-child th:first-child,
.TTLiveKlassenspielplan tr:first-child th:first-child
{
    border-top-left-radius:10px
}

.TTLiveTabelle tr:first-child th:last-child,
.TTLiveRangliste tr:first-child th:last-child,
.TTLiveSpielplan tr:first-child th:last-child,
.TTLiveAufstellung tr:first-child th:last-child,
.TTLiveKlassenspielplan tr:first-child th:last-child
{
    border-top-right-radius:10px
}

.TTLiveTabelle tr td:nth-child(1),
.TTLiveTabelle tr td:nth-child(3),
.TTLiveTabelle tr td:nth-child(4),
.TTLiveTabelle tr td:nth-child(5),
.TTLiveTabelle tr td:nth-child(6)
{
    text-align: center;
}
.TTLiveTabelle tr td:nth-child(2) {
    max-width:110px;
    font-size:80%;
    overflow:hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
/* Aufstellung mittig */
.TTLiveAufstellung tr td:nth-child(1),
.TTLiveAufstellung tr td:nth-child(4),
.TTLiveAufstellung tr td:nth-child(5),
.TTLiveAufstellung tr td:nth-child(6),
.TTLiveAufstellung tr td:nth-child(7)
{
text-align: center;
    /*width:100%;*/
}
.TTLiveSpielplan tr td:nth-child(4),
.TTLiveSpielplan tr td:nth-child(6)

{
     font-size:60%;
     max-width:100px;
     overflow:hidden;
     text-overflow:ellipsis;
     white-space:nowrap;
 }
.TTLiveSpielplan tr td:nth-child(7) {
    font-size:60%;
}
.TTLiveSpielpan tr td:last-child {
    max-width:70px;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
}




.even { background-color: whitesmoke; }

.cAufstieg{ background-color:gold;color:black; }
.cAbstieg{ background-color:red; color:black;}
.cRelegation{ background-color:DarkOrange; color:black; }
.cOwnTeam td { background-color:green;color:white;font-weight: bold; }
.cOwnTeam:hover{
    background-color: #ddd;
    cursor:pointer;
}
.cAbstieg.cOwnTeam td { background-color:green;color:red;font-weight: bold; }

.Portraitbild img, .Actionbild img {
    margin-left: auto;
    margin-right: auto;
    padding:15px;
    border: 1px double #DDD;
    border-radius: 18px;
    box-shadow: 0 0 15px #333;
    color: #666;
    outline: none;
}

figcaption.TTLiveSpielplan,
figcaption.Spielerposueberschrift
{
    background-color: #ffffff;
    color:black;
    text-align: center;
    border: 2px double #DDD;
    border-radius: 20px;
    font-size:100%;
}
body{
    font-size:0.75em;
}

.bttliveImg {
    border: 2px double #DDD;
    border-radius: 15px;
    width: 100%;
}
.TTLiveSpielerPortrait figure
{
    margin:20px;
    border: 2px double #DDD;
    border-radius: 20px;
    box-shadow: 0 0 10px #333;
    color: #666;
    outline: none;
}
.TTLiveSpielerPortrait
{
    border: 1px double #DDD;
    border-radius: 5px;
    box-shadow: 0 0 15px #333;
    color: #666;
    outline: none;
}

.LivePZRangliste.Portraitbild img ,
.LivePZRangliste.Actionbild img
{
    width:100%;
    padding: 1px;
    border-radius: 5px;
    box-shadow: 0 0 5px #333;
}

.TTLiveSpielerPortrait img { width:100%; }



.widget_bttliveklassenspielplanwidget
{
    margin-left:auto;
    margin-right:auto;
    border: 2px double #DDD;
    border-radius: 5px;
    box-shadow: 0 0 5px #333;
    color: #666;
    outline: none;
}


.PortraitSpieler td,
.TTLiveTabelle th
{
    text-align:center;
    font-weight:bold;
}

.bttlive_splayout1{
    width:100%;
}
@media (max-width: 40em) {
    /* Breite beträgt höchstens 50em */
    .bttlive_splayout2 {
        -webkit-column-count: 1; /* Chrome, Safari, Opera */
        -moz-column-count: 1; /* Firefox */
        column-count: 1;
        -webkit-column-gap: 20px; /* Chrome, Safari, Opera */
        -moz-column-gap: 20px; /* Firefox */
        column-gap: 20px;
        -webkit-column-rule: 1px dotted #000000; /* Chrome, Safari, Opera */
        -moz-column-rule: 1px dotted #000000; /* Firefox */
        column-rule: 1px dotted #000000;
    }
    .bttlive_splayout2 .TTLiveTabelle,
    .bttlive_splayout2 .TTLiveSpielplan,
    .bttlive_splayout2 .TTLiveAufstellung,
    .bttlive_splayout2 .TTLiveKlassenspielplan
    {
        font-size:90%;
    }
    .bttlive_splayout2 .TTLiveTabelle td,
    .bttlive_splayout2 .TTLiveTabelle th,
    .bttlive_splayout2 .TTLiveSpielplan td,
    .bttlive_splayout2 .TTLiveSpielplan th,
    .bttlive_splayout2 .TTLiveAufstellung td,
    .bttlive_splayout2 .TTLiveAufstellung th,
    .bttlive_splayout2 .TTLiveKlassenspielplan th
    {
        font-size:90%;
    }
}
@media (min-width: 40em) and (max-width: 70em) {


    /* Breite beträgt mindestens 60em */
    .bttlive_splayout2 {
        -webkit-column-count: 2; /* Chrome, Safari, Opera */
        -moz-column-count: 2; /* Firefox */
        column-count: 2;
        -webkit-column-gap: 30px; /* Chrome, Safari, Opera */
        -moz-column-gap: 30px; /* Firefox */
        column-gap: 30px;
        -webkit-column-rule: 2px dotted #000000; /* Chrome, Safari, Opera */
        -moz-column-rule: 2px dotted #000000; /* Firefox */
        column-rule: 2px dotted #000000;
    }

}
@media (min-width: 70em) {
    .bttlive_splayout2 {
        -webkit-column-count: 3; /* Chrome, Safari, Opera */
        -moz-column-count: 3; /* Firefox */
        column-count: 3;
        -webkit-column-gap: 40px; /* Chrome, Safari, Opera */
        -moz-column-gap: 40px; /* Firefox */
        column-gap: 40px;
        -webkit-column-rule: 2px dotted #000000; /* Chrome, Safari, Opera */
        -moz-column-rule: 2px dotted #000000; /* Firefox */
        column-rule: 2px dotted #000000;
    }
}


.Spielerbilanz td {font-weight:bold;}
.Spielerbilanz.plus{ background-color:green;color:white;}
.Spielerbilanz.minus{ background-color:red;color:white;}
.Spielerbilanz.ausgeglichen{ background-color:orange;color:white;}
.LivePZ td, .Spielerposueberschrift  {font-weight:bold}
.widget_bttlivespielerportraitwidget { text-align:center}



        ',  // default - css zur Auslieferung
        // Default-Seiten
        'vereinsplan_seite'                     => '',
        'hallenplan_seite'                      => '',
        // Spieler - Portraits
        'anzahl_spieler_fragen'                 => '5',
        'spieler_frage1'                        => "Saisonziel",
        'spieler_frage2'                        => "Laufbahn",
        'spieler_frage3'                        => "Größte Erfolge",
        'spieler_frage4'                        => "Hobbies",
        'spieler_frage5'                        => "Spieleridol",
        'spieler_frage6'                        => "frei",
        'spieler_frage7'                        => "frei",
        'spieler_frage8'                        => "frei",
        'spieler_frage9'                        => "frei",
        'spieler_frage10'                        => "frei",
        // css Spielerportraits
        'bttlive_tableclassname_SpielerPortrait'=> 'TTLiveSpielerPortrait',
        'cssPortraitbild'                       => 'Portraitbild',
        'cssActionbild'                         => 'Actionbild',
        'cssSpielerbilanz'                      => 'Spielerbilanz',
        'cssSpielerposueberschrift'             => 'Spielerposueberschrift',
        'cssSpielerposition'                    => 'Spielerposition',
        'cssAnzahlSpiele'                       => 'AnzahlSpiele',
        'cssLivePZ'                             => 'LivePZ',
        'cssSpieler_frage'                      => 'Spieler_frage',
        'cssGeburtsjahr'                        => 'Geburtsjahr',
        'cssVereinsjahr'                        => 'Vereinsjahr',
        'cssSchlaghand'                         => 'Schlaghand',
        'cssSpieltyp'                           => 'Spieltyp',
        'cssGeschlecht'                         => 'Geschlecht',
        // Debug
        'debug_trace'                           => '0',
        'debug_application'                     => '0',
        'debug_stage'                           => '1', // 1=default, 2=Applicationdebug+, 3=debug++, 4=Action_hooks
    );
    bpe_lib_tools::getInstance()->add_options($options);
    bpe_lib_tools::getInstance()->log($options,__FUNCTION__ . ":" . __LINE__);
    $opt=get_option(bpe_lib_tools::getInstance()->getOptionName());
    bpe_lib_tools::getInstance()->log($opt,__FUNCTION__ . ":" . __LINE__);
    //print_r(get_option(bpe_lib_tools::getInstance()->getOptionName()));
    $error="";
    $my_post = array(
        'post_title'    => 'TTLive Hallenplan',
        'post_content'  => "<p class='bttlive_v_hallenplan'><h2>Hallenplan Vorrunde</h2>\n\n[bttlive elementname=Heimspielplan runde=1]</p>\n
                            <p class='bttlive_r_hallenplan'><h2>Hallenplan Rückrunde</h2>\n\n[bttlive elementname=Heimspielplan runde=2]</p>\n",
        'post_status'   => 'publish',
        'post_type'		=> 'page'
    );
    // Insert the post into the database und
    // Speichere die ID dieser Seite
    $id=wp_insert_post( $my_post,$error);
    bpe_lib_tools::getInstance()->put_option('hallenplan_seite',$id,false);
    // Erzeuge Seite des Vereinsplan
    $error="";
    $my_post = array(
        'post_title'    => 'TTLive Vereinsplan',
        'post_content'  => "<p class='bttlive_v_vereinsplan'><h2>Vereinsplan Vorrunde</h2>\n\n[bttlive elementname=Vereinsplan runde=1]</p>
                          \n<p class='bttlive_r_vereinsplan'><h2>Vereinsplan Rückrunde</h2>\n\n[bttlive elementname=Vereinsplan runde=2]</p>\n",
        'post_status'   => 'publish',
        'post_type'		=> 'page'
    );
    // Insert the post into the database
    // Speichere die ID dieser Seite
    $id=wp_insert_post( $my_post,$error);
    bpe_lib_tools::getInstance()->put_option('vereinsplan_seite',$id,true);

}

/**
 * Wird beim deinstallieren aufgerufen und löscht alle erzeugt und veränderten Werte
 * Lösche die Seiten heraus, der erzeugt wurden und lösche auch alle Optionen
 *
 */
function bttlive_remove() {
    wp_delete_post(bpe_lib_tools::getInstance()->get_option('vereinsplan_seite'), false);
    wp_delete_post(bpe_lib_tools::getInstance()->get_option('hallenplan_seite'), false);
    bpe_lib_tools::getInstance()->remove_options();
}

/**
 * Erzeugt die Seite bttlive - Einstellungen in html-Code
 * <input type="submit" value="<?php _e('Save Changes') ?>" />
 */

function bttlive_do_page() {
 ?>
 <div class="wrap">
     <h2>bttlive Settings</h2>
     <form method="post" action="options.php">
         <?php settings_fields(bpe_lib_tools::getInstance()->getOptionName() . '_options'); ?>
         <?php $options=bpe_lib_tools::getInstance()->getOptions(); ?>
         <?php $checked = 'checked="checked"'; ?>
         <p>
             <?php submit_button(); ?>
         </p>
         <table width="800" class="form-table">
             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Optionen</h2></td></tr>
             <tr>
                 <td>
                     <?php /*
                     foreach ($options['mannschaften'] as $key => $value) {
                         foreach ($value as $k => $v) {
                             echo '<input type = "hidden" name = bttlive_opt[mannschaften][' . $key . ']['.$k.'] value = "' . $v . '" />' . "\n";
                         }
                    }
 */
                     ?>

             <input type="hidden" name=bttlive_opt[verein] value="<?php echo $options['verein']; ?>" />
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Base Url</th>
                 <td><input type="text" name=bttlive_opt[bttlive_baseurl] class="wide_fat"  value="<?php echo $options['bttlive_baseurl']; ?>" />
                     <br /><em>URL-Basis zum TTLive-System (z.B. http://ttvsh.tischtennislive.de)</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Stunden Aktualisierung</th>
                 <td><input type="number" step="1" min="1" max="72" name=bttlive_opt[bttlive_refreshHours] style="width: 50px;"  value="<?php echo $options['bttlive_refreshHours']; ?>" />Stunden
                     <br /><em> Stunden bis die Daten vom TT-live-system aktualisiert werden (z.B. 1)</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Abteilungs/SpartenID</th>
                 <td><input type="number" step="1" min="1" max="999999" name=bttlive_opt[bttlive_divisionID] style="width: 50px;"  value="<?php echo $options['bttlive_divisionID']; ?>" /><?php echo " " . $options['verein']; ?>
                     <br /><em>Sie bekommen diese und andere ID´s im TischtennisLive-System aus der URL der XML-Dateien unter Verwaltung --> Statistiken</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Suchstring eigene Mannschaft</th>
             <td><input type="text" name=bttlive_opt[bttlive_ownteam] style="width: 150px;"  value="<?php echo $options['bttlive_ownteam']; ?>" />
             <br /><em>Eigenes Team Suchstring bei VfB Lübeck z.B. /VfB.*beck/</em>
             </td>
             </tr>
             <tr valign="top"><th scope="row">Saison Heim- und Vereinsplan</th>
             <td><input type="number" step="1" min="2000" max="2999" name=bttlive_opt[bttlive_saison] style="width: 80px;"  value="<?php echo $options['bttlive_saison']; ?>" />
             <br /><em>Gegenwärtige Saison für Heimspiel- und Vereinsplan als vierstellige Jahreszahl(JJJJ) bsp: 2014. </em>
             <br /><em>Nach dem Abschluß der Saison und dem Bereitstehen neuer Informationen umstellen.</em>
                 
             </td>
             <tr valign="top"><th scope="row">Vor- oder Rückrunde</th>
                 <td>
                 <input name=bttlive_opt[bttlive_runde] type="radio" value="1" <?php if ( 1 == $options['bttlive_runde'] ) echo esc_attr( $checked ); ?> > Vorrunde
                 <input name=bttlive_opt[bttlive_runde] type="radio" value="2" <?php if ( 2 == $options['bttlive_runde'] ) echo esc_attr( $checked ); ?> > Rückrunde
                     <br /><em>Gegenwärtige Runde für Heimspiel- und Vereinsplan.</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Spielklasse in Spielplänen anzeigen</th>
                 <td><input type="checkbox" name=bttlive_opt[bttlive_s_anz] style="width: 50px;"  value="1" <?php if ($options['bttlive_s_anz'] == 1) echo 'checked="checked"'; ?> />
                     <br /><em>Spielklasse im Vereins- und Heimspielplan anzeigen (Voreinstellung kann geändert werden)</em>
                 </td>
             </tr>

             <tr valign="top"><th scope="row">Ergebnisse in Spielplänen anzeigen</th>
                 <td><input type="checkbox" name=bttlive_opt[bttlive_e_anz] style="width: 50px;"  value="1" <?php if ($options['bttlive_e_anz'] == 1) echo 'checked="checked"'; ?> />
                     <br /><em>Ergebnisse anzeigen ja/nein (Voreinstellung kann geändert werden)</em>
                 </td>
             </tr>

             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Spielerportraits:</h2></td></tr>
             <tr valign="top"><th scope="row">Anzahl Fragen Spielerportrait</th>
                 <td><input type="number" step="1" min="3" max="10" name=bttlive_opt[anzahl_spieler_fragen] style="width: 50px;"  value="<?php echo $options['anzahl_spieler_fragen']; ?>" />Fragen
                     <br /><em> Die Anzahl der Fragen und Antworten, die in der Erfassung des Spielerportraits auftauchen</em>
                 </td>
             </tr>
             <?php
             $j=$options['anzahl_spieler_fragen'];
             if ($j<=3 || $j>=10) $j=3;
             for ($i = 1; $i <= 10; $i++) {
                 if ($i >= $j) {
                     $class="bttlive_spieler_frage hidden";
                 } else {
                     $class="bttlive_spieler_frage";
                 }

                echo '<tr class=$class valign="top"><th scope="row">Frage' . $i . ' Spielerportrait</th>';
                $wertfrage=$options['spieler_frage' . $i];
                echo '<td>';echo "\n";
                echo '<textarea rows="2" cols="26"  name="bttlive_opt[spieler_frage' . $i  . ']" >' . $wertfrage . '</textarea></p>';echo "\n";
                echo '<br /><em> Die '. $i . '. Frage, die im Spielerportrait übernommen wird</em>';
                echo '</td>';echo "\n";
             }
             ?>
             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Style:</h2></td></tr>

             <tr valign="top"><th scope="row">CSS-Class TeamSpielplan</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_TeamSpielplan] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_TeamSpielplan']; ?>" />
                     <br /><em>Klassenname Teamspielplan in css-Datei</em>
                 </td>
             </tr>

             <tr valign="top"><th scope="row">CSS-Class Aufstellung</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Aufstellung] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Aufstellung']; ?>" />
                     <br /><em>Klassenname Aufstellung in css-Datei</em>
                 </td>
             </tr>

             <tr valign="top"><th scope="row">CSS-Class Tabelle</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Tabelle] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Tabelle']; ?>" />
                     <br /><em>Klassenname Tabelle in css-Datei</em>
                 </td>
             </tr>

             <tr valign="top"><th scope="row">CSS-Class 14Tage</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_14Tage] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_14Tage']; ?>" />
                     <br /><em>Klassenname 14 Tage - Abfrage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Heimspielplan</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Heimspielplan] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Heimspielplan']; ?>" />
                     <br /><em>Klassenname Heimspielplan - Abfrage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Vereinsplan</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Vereinsplan] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Vereinsplan']; ?>" />
                     <br /><em>Klassenname Vereinsplan - Abfrage in css-Datei</em>
                 </td>
             </tr>

             <tr valign="top"><th scope="row">CSS-Class Klassenspielplan</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Klassenspielplan] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Klassenspielplan']; ?>" />
                     <br /><em>Klassenname Klassenspielplan - Abfrage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class LivePZ - Rangliste</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Rangliste] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Rangliste']; ?>" />
                     <br /><em>Klassenname LivePZ Rangliste - Abfrage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Mannschaft</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_Mannschaft] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_Mannschaft']; ?>" />
                     <br /><em>Klassenname Mannschaftsaufstellung in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Custom CSS</th>
                 <td><textarea rows="7" cols="80"  name=bttlive_opt[bttlive_css] ><?php echo $options['bttlive_css']; ?></textarea>
                     <br /><em>Individuelle css-Daten</em>
                 </td>
             </tr>
             <tr>
                 <td><?php submit_button(); ?></td>
             </tr>
             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Style Spielerporträt:</h2></td></tr>
             <tr valign="top"><th scope="row">CSS-Class Portrait</th>
                 <td><input type="text" name=bttlive_opt[bttlive_tableclassname_SpielerPortrait] class="wide_fat"  value="<?php echo $options['bttlive_tableclassname_SpielerPortrait']; ?>" />
                     <br /><em>Klassenname Portrait - Abfrage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Portraitbild</th>
                 <td><input type="text" name=bttlive_opt[cssPortraitbild] class="wide_fat"  value="<?php echo $options['cssPortraitbild']; ?>" />
                     <br /><em>Klassenname Portraitbild in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Actionbild</th>
                 <td><input type="text" name=bttlive_opt[cssActionbild] class="wide_fat"  value="<?php echo $options['cssActionbild']; ?>" />
                     <br /><em>Klassenname Actionbild in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Spielerbilanz</th>
                 <td><input type="text" name=bttlive_opt[cssSpielerbilanz] class="wide_fat"  value="<?php echo $options['cssSpielerbilanz']; ?>" />
                     <br /><em>Klassenname Spielerbilanz in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Spieler-Positionsüberschrift</th>
                 <td><input type="text" name=bttlive_opt[cssSpielerposueberschrift] class="wide_fat"  value="<?php echo $options['cssSpielerposueberschrift']; ?>" />
                     <br /><em>Klassenname Spielerposueberschrift in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Spielerposition</th>
                 <td><input type="text" name=bttlive_opt[cssSpielerposition] class="wide_fat"  value="<?php echo $options['cssSpielerposition']; ?>" />
                     <br /><em>Klassenname Spielerposition in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class AnzahlSpiele</th>
                 <td><input type="text" name=bttlive_opt[cssAnzahlSpiele] class="wide_fat"  value="<?php echo $options['cssAnzahlSpiele']; ?>" />
                     <br /><em>Klassenname AnzahlSpiele in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class LivePZ</th>
                 <td><input type="text" name=bttlive_opt[cssLivePZ] class="wide_fat"  value="<?php echo $options['cssLivePZ']; ?>" />
                     <br /><em>Klassenname LivePZ in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Spieler_frage</th>
                 <td><input type="text" name=bttlive_opt[cssSpieler_frage] class="wide_fat"  value="<?php echo $options['cssSpieler_frage']; ?>" />
                     <br /><em>Klassenname Spieler_frage in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Geburtsjahr</th>
                 <td><input type="text" name=bttlive_opt[cssGeburtsjahr] class="wide_fat"  value="<?php echo $options['cssGeburtsjahr']; ?>" />
                     <br /><em>Klassenname Geburtsjahr in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Vereinsjahr</th>
                 <td><input type="text" name=bttlive_opt[cssVereinsjahr] class="wide_fat"  value="<?php echo $options['cssVereinsjahr']; ?>" />
                     <br /><em>Klassenname Vereinsjahr in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Schlaghand</th>
                 <td><input type="text" name=bttlive_opt[cssSchlaghand] class="wide_fat"  value="<?php echo $options['cssSchlaghand']; ?>" />
                     <br /><em>Klassenname Schlaghand in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Spieltyp</th>
                 <td><input type="text" name=bttlive_opt[cssSpieltyp] class="wide_fat"  value="<?php echo $options['cssSpieltyp']; ?>" />
                     <br /><em>Klassenname Spieltyp in css-Datei</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">CSS-Class Geschlecht</th>
                 <td><input type="text" name=bttlive_opt[cssGeschlecht] class="wide_fat"  value="<?php echo $options['cssGeschlecht']; ?>" />
                     <br /><em>Klassenname Geschlecht in css-Datei</em>
                 </td>
             </tr>

             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Planseiten:</h2></td></tr>
             <tr valign="top"><th scope="row">Seiten Id Hallenplan</th>
                 <td><input type="number" step="1" min="1" max="999999" name=bttlive_opt[hallenplan_seite] style="width: 50px;"  value="<?php echo $options['hallenplan_seite']; ?>" />ID
                     <br /><em> Seiten ID, die beim Link angezeigt wird</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Seiten Id Vereinsplan</th>
                 <td><input type="number" step="1" min="1" max="999999" name=bttlive_opt[vereinsplan_seite] style="width: 50px;"  value="<?php echo $options['vereinsplan_seite']; ?>" />ID
                     <br /><em> Seiten ID, die beim Link angezeigt wird</em>
                 </td>
             </tr>
             <tr><td colspan=2 style="border-top:solid 1px lightgrey;"><h2>Debugging Options:</h2></td></tr>
             <tr valign="top"><th scope="row">Debug Tracing aktivieren</th>
                 <td><input type="checkbox" name=bttlive_opt[debug_trace] style="width: 50px;"  value="1" <?php if ($options['debug_trace'] == '1') echo 'checked="checked"'; ?> />
                     <br /><em>Erzeugt Trace - Infos in debug.log bei angeschaltetem WP_DEBUG zusätzlich. Erzeugt sehr viel Output, nur für Kenner geeignet</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Applikation Debugging aktivieren</th>
                 <td><input type="checkbox" name=bttlive_opt[debug_application] style="width: 50px;"  value="1" <?php if ($options['debug_application'] == '1') echo 'checked="checked"'; ?> />
                     <br /><em>Erzeugt Infos über Aktionen in dem bttlive Plugin in debug.log. Erzeugt sehr viel Output, nur für Kenner geeignet</em>
                 </td>
             </tr>
             <tr valign="top"><th scope="row">Debug - Level</th>
                 <td><input type="number" step="1" min="1" max="3" name=bttlive_opt[debug_stage] style="width: 80px;"  value="<?php echo $options['debug_stage']; ?>" />
                     <br /><em>Debug-Level für Applikationsmeldungen. Standard-Wert = 1, Höchstwert = 3  </em>
                 </td>
             </tr>

         </table>

         <p>
             <?php submit_button(); ?>
         </p>

     </form>


 </div>
<?php

}
add_action('wp_head','bttlive_css');

/**
 * bindet css-Daten aus Optionen in Header ein!
 */
function bttlive_css()
{
    $options=bpe_lib_tools::getInstance()->getOptions();
    if ($options['bttlive_css'] != '') {
        $output = "<style>" . $options['bttlive_css'] . "</style>";
    }
    echo $output;

}
