<?php

/**
 * Class bttliveHandbuch
 * Stellt Handbuch für das Plugin bereit
 */
class bttliveHandbuch {

    function __construct()
    {
        add_action('admin_menu',array( $this, 'handbuch_register'));

        function register_my_custom_submenu_page() {
        }
    }

    public function handbuch_register(){

        add_submenu_page(
            'edit.php?post_type=bttlive_spieler',
            'TTLive Handbuch',
            'TTLive Handbuch',
            'manage_options',
            'bttlivehandbuch', // nicht mehr nötig ab 3.0 ???
            array(&$this, 'handbuch')
            );
        add_submenu_page(
            'edit.php?post_type=bttlive',
            'TTLive Handbuch',
            'TTLive Handbuch',
            'manage_options',
            'bttlivehandbuch', // nicht mehr nötig ab 3.0 ???
            array(&$this, 'handbuch')
        );
    }
    public function handbuch(){
        ?>

        <div class="wrap">
            <h2>Handbuch für bttlive</h2>
            <div class="PluginDescription" >
            <p itemprop="description" class="shortdesc">
                Ein Wordpress-Plugin um die Daten aus dem ttlive-system zu holen und in den Beiträgen oder in den Seiten anzuzeigen.
                Es ist sind bttlive-Daten realisiert,bei dem Sie dies Daten zusammen mit eigenen Inhalten anzeigen können.
                Sie können, nach Erfassung von Mannschaftsdaten, Spielerporträts pro Mannschaft präsentieren.

                <br>
                Weitere Infos zur Verwendung unter <a href="http://www.vfb-luebeck-tt.de">VfB Lübeck</a> </p>
            </div>
            <div class="block-content">
                <ol>
                    <li><code>bttlive</code>-zip-file Herunterladen und unzippen</li>
                    <li><code>bttlive</code>-directory in das <code>/wp-content/plugins/</code> directory hochladen</li>
                    <li>Das plugin über das 'Plugins' Menü in WordPress aktivieren</li>
                    <li>Setzen der base url und der VereinsID in den admin settings/options.</li>
                    <li>Setze <code>[bttlive]</code> shortcode in Ihre posts oder pages. Benutze die Parameter, um auszuwählen welche Daten wie angezeigt werden sollen.</li>
                    <li>Erfasse alle Mannschaften des Vereins und Spielerporträts, um diese als Code oder Widget zu nutzen</li>
                    <li>Nutze auch alle anderen Widgets, um TTLive - Daten entweder für den gesamten Verein oder für die Mannschaften anzuzeigen</li>
                </ol>

                <p>Parameter:</p>

                <pre><code>elementname</code></pre>

                <ul>
                    <li>Rueckgabe-Element - mögliche Werte: Spielerporträt, Mannschaft, Spielplan, Tabelle, 14Tage oder Rangliste</li>
                </ul>


                <pre><code>mannschaft_id</code></pre>

                <ul>
                    <li>TTLive Mannschaft ID</li>
                </ul>

                <pre><code>staffel_id</code></pre>

                <ul>
                    <li>TTLive Staffel ID</li>
                </ul>

                <pre><code>tableclassname</code></pre>

                <ul>
                    <li>css-Klassenname der Tabelle</li>
                </ul>

                <pre><code>own_team</code></pre>

                <ul>
                    <li>Name des eigenen Teams</li>
                </ul>

                <pre><code>runde</code></pre>

                <ul>
                    <li>Vorrunde = [bttlive runde=1] (default), Rückrunde = [bttlive runde=2]</li>
                </ul>

                <pre><code>showxdays</code></pre>

                <ul>
                    <li>14Tage: Anzahl der Tage die dargestellt werden sollen (default = 14)</li>
                </ul>

                <pre><code>max</code></pre>

                <ul>
                    <li>14Tage: Anzahl der Tage die maximal dargestellt werden sollen (default = 0)</li>
                </ul>

                <pre><code>widget</code></pre>

                <ul>
                    <li>14Tage: Für die Darstellung in einem Widget - default = 0 --&gt; legt man den Schalter auf 1, wird eine Darstellung optimiert für ein Sidebar-Widget verwendet </li>
                </ul>

                <pre><code>teamalias</code></pre>

                <ul>
                    <li>Nur für die Tabelle: "Teamname:Alias;Teamname2:Alias2;..."</li>
                </ul>

                <pre><code>showleague</code></pre>

                <ul>
                    <li>Nur für die Tabelle: Ueberschrift-Anzeige der Liga (default: 1)</li>
                </ul>

                <pre><code>showmatchecount</code></pre>

                <ul>
                    <li>Nur für die Tabelle: Anzahl der gemachten Spiele (default: 1)</li>
                </ul>

                <pre><code>showsets</code></pre>

                <ul>
                    <li>Nur für die Tabelle:  Anzahl der gewonnenen/verlorenen Saetze (default: 1)</li>
                </ul>

                <pre><code>showgames</code></pre>

                <ul>
                    <li>Nur für die Tabelle:  Anzahl der gewonnenen/verlorenen Spiele (default: 1)</li>
                </ul>

                <pre><code>aufstiegsplatz</code></pre>

                <ul>
                    <li>Nur für die Tabelle:  Aufstiegsplaetze bis (default: 2)</li>
                </ul>

                <pre><code>abstiegsplatz</code></pre>

                <ul>
                    <li>Nur für die Tabelle:  Abstiegsplaetze ab (default: 9)</li>
                </ul>

                <pre><code>relegation</code></pre>

                <ul>
                    <li>Nur für die Tabelle:  Relegationsplätze (default: '') Beispiel: relegation="2,8" -&gt; 2 für die Relegation Aufstieg, und 8 für Abstieg</li>
                </ul>

                <pre><code>saison</code></pre>

                <ul>
                    <li>Nur für Hallenplan: (default: '') Wenn '', dann wird kein Hallenplan angezeigt. Das erste Jahr der Saison muss hier gesetzt werden.</li>
                </ul>

                <pre><code>display_type</code></pre>

                <ul>
                    <li>Nur für Rangliste: (default: 1) Wenn 0, dann werden nur die Spieler angezeigt die eine gültige LivePZ haben</li>
                </ul>

                <pre><code>display_type</code></pre>

                <ul>
                    <li>Nur für die 14Tage: die letzten 14Tage (0 - default) oder die naechsten 14Tage (1)     </li>
                </ul>

                <pre><code>refresh</code></pre>

                <ul>
                    <li>Anzahl Stunden bis die Daten erneut vom Live-System aktualisiert werden sollen</li>
                </ul>
                <hr>
                <h4>Spielerporträts</h4>
                <p itemprop="description" class="shortdesc">
                    Um Spielerporträts erfassen zu können, müssen zunächst alle Mannschaften des Vereins angelegt werden.
                    Das geschieht im Backend unter dem Menü Spielerporträts<br>
                    In einer Auswahl müssen diesen Mannschaften die Staffel- und MannschaftsID zugeordnet werden.
                    Über das sogenannte <strong>slug</strong> in den Mannschaftsdaten wird die Mannschaft später referenziert.<br>
                    Dann werden die Spielerdaten eingegeben. Vergessen Sie nicht die Mannschaften bei den Spielern zuzuordnen.<br>

                    Sie können die Spielerporträts entweder über einen Code in den Inhalten ausgeben (siehe unten) oder über die<
                    Eingabe der sogenannten Widgets. Dort stehen Ihnen noch zusätzliche Features bereit.
                    Sie können zusätzlich Trainer, Mannschaftsführer und Betreuer erfassen, die offiziell nicht in der
                    Mannschaft spielen, indem Sie dem (auch schon erfassten Spieler) in dem Feld <strong>Position</strong> z.B.
                    das Wort <pre><code>Trainer:</code></pre> zuordnen. Die Positionen werden alphabetisch sortiert. Ist das Mitglied
                    in der Mannschaft gemeldet, oder hat es schon einmal Ersatz gespielt, so kommt diese Position nicht zum tragen,
                    sondern es wird die Mannschaftsposition eingeordnet.<br>

                    Jeder Spieler hat auch eine Einzelansicht, die als Link vom Spielerporträt (beim erfassen des Porträts)
                    referenziert werden kann.<br>

                    In der LivePZ - Rangliste werden zugeordnete Spieler mit Portraitbild und Actionbild präsentiert.<br>

                    Verwendung unter <a href="http://www.vfb-luebeck-tt.de">VfB Lübeck</a>. Und dort bei der Rangliste und den Mannschaftsdaten
                </p>
                <p itemprop="description" class="shortdesc">
                    Beispiele mit der base url (<a href="http://ttvsh.tischtennislive.de" rel="nofollow">http://ttvsh.tischtennislive.de</a>):</p>

                    <pre><code>[bttlive elementname=&quot;SpielerPortrait&quot;
                         mannschaftsname=&quot;1-herren&quot;
                         portrait_anzeigen=&quot;1&quot;
                         gebjahr_anzeigen=&quot;1&quot;
                         verjahr_anzeigen=&quot;1&quot;
                         schlaghand_anzeigen=&quot;1&quot;
                         spieltyp_anzeigen= &quot;1&quot;
                         geschlecht_anzeigen=&quot;0&quot;
                         action_anzeigen=&quot;1&quot;
                         editor_anzeigen=&quot;1&quot;
                    ]</code></pre>


                <pre><code>[bttlive elementname=&quot;Mannschaft&quot; mannschaft_id=&quot;34435&quot; staffel_id=&quot;5311&quot;]</code></pre>

                <pre><code>[bttlive elementname=&quot;Spielplan&quot; own_team=&quot;SV Berliner Brauerei&quot; mannschaft_id=&quot;34435&quot; staffel_id=&quot;5311&quot; tableclassname=&quot;TTLiveSpielplan&quot;]</code></pre>

                <pre><code>[bttlive elementname=&quot;Tabelle&quot; mannschaft_id=&quot;34435&quot; staffel_id=&quot;5311&quot; tableclassname=&quot;TTLiveTabelle&quot; aufstiegsplatz=2 abstiegsplatz=9 relegation=&quot;2,8&quot; teamalias=&quot;SV Berliner Brauereien e.V.:SVBB1; SV Lichtenberg 47:Lichtenberg&quot;]</code></pre>

                <pre><code>[bttlive elementname=&quot;14Tage&quot; tableclassname=&quot;TTLive14Tage&quot; display_type=0]</code></pre>

                <pre><code>[bttlive elementname=&quot;14Tage&quot; tableclassname=&quot;TTLive14Tage&quot; display_type=1]</code></pre>

                <pre><code>[bttlive elementname=&quot;Rangliste&quot; tableclassname=&quot;TTLiveRangliste&quot; display_all=0]</code></pre>

                <pre><code>[bttlive elementname=&quot;Heimspielplan&quot; saison=&quot;2014&quot; runde=&quot;1&quot;]</code></pre>

                <hr>
                <h4>bttlive Einstellungen</h4>
                <p itemprop="description" class="shortdesc">

                <pre><code>Base Url</code></pre>  URL-Basis zum TTLive-System (z.B. http://ttvsh.tischtennislive.de)<br>
                <pre><code>Stunden Aktualisierung 	Stunden</code></pre>Stunden bis die Daten vom TT-live-system aktualisiert werden (z.B. 1)<br>
                <pre><code>Abteilungs/SpartenID 	VfB Lübeck - Tischtennis</code></pre>Sie bekommen diese und andere ID´s im TischtennisLive-System aus der URL der XML-Dateien unter Verwaltung --> Statistiken
                <pre><code>Suchstring eigene Mannschaft</code></pre>Eigenes Team Suchstring bei VfB Lübeck z.B. /VfB.*beck/. Wichtig für Suche nach den Mannschaften. Unbedingt setzen!
                <pre><code>Saison Heim- und Vereinsplan</code></pre>Gegenwärtige Saison für Heimspiel- und Vereinsplan als vierstellige Jahreszahl(JJJJ) bsp: 2014. Nach dem Abschluß der Saison und dem Bereitstehen neuer Informationen umstellen.</code></pre>Vor- oder Rückrunde 	Vorrunde Rückrunde
                Gegenwärtige Runde für Heimspiel- und Vereinsplan.
                <pre><code>Spielklasse in Spielplänen anzeigen</code></pre>Spielklasse im Vereins- und Heimspielplan anzeigen (Voreinstellung kann geändert werden)
                <pre><code>Ergebnisse in Spielplänen anzeigen</code></pre>Ergebnisse anzeigen ja/nein (Voreinstellung kann geändert werden)
                Spielerportraits:
                <pre><code>Anzahl Fragen Spielerportrait</code></pre>Die Anzahl der Fragen und Antworten, die in der Erfassung des Spielerportraits auftauchen
                <pre><code>Fragex Spielerportrait</code></pre>Fragen, die in ein neues Spielerportrait übernommen wird
                <pre><code>CSS-Class TeamSpielplan</code></pre>Klassenname Teamspielplan in css-Datei
                <pre><code>CSS-Class Tabelle</code></pre>Klassenname Tabelle in css-Datei
                <pre><code>CSS-Class 14Tage</code></pre>Klassenname 14 Tage - Abfrage in css-Datei
                <pre><code>CSS-Class Heimspielplan</code></pre>Klassenname Heimspielplan - Abfrage in css-Datei
                <pre><code>CSS-Class Vereinsplan</code></pre>Klassenname Vereinsplan - Abfrage in css-Datei
                <pre><code>CSS-Class Klassenspielplan</code></pre>Klassenname Klassenspielplan - Abfrage in css-Datei
                <pre><code>CSS-Class LivePZ - Rangliste</code></pre>Klassenname LivePZ Rangliste - Abfrage in css-Datei
                <pre><code>CSS-Class Mannschaft</code></pre>Klassenname Mannschaftsaufstellung in css-Datei
                <hr>
                Individuelle css-Daten
                Das css ist vorbereitet, um Ihre Daten anzuzeigen, kann aber natürlich auch gerne verändert werden. Nach Speicherung der  Daten braucht
                nur die Seite neu geladen zu werden.
                <hr>
                Style Spielerporträt:
                <pre><code>CSS-Class Portrait</code></pre>Klassenname Portrait - Abfrage in css-Datei
                <pre><code>CSS-Class Portraitbild</code></pre>Klassenname Portraitbild in css-Datei
                <pre><code>CSS-Class Actionbild</code></pre>Klassenname Actionbild in css-Datei
                <pre><code>CSS-Class Spielerbilanz</code></pre>Klassenname Spielerbilanz in css-Datei
                <pre><code>CSS-Class Spieler-Positionsüberschrift</code></pre>Klassenname Spielerposueberschrift in css-Datei
                <pre><code>CSS-Class Spielerposition</code></pre>Klassenname Spielerposition in css-Datei
                <pre><code>CSS-Class AnzahlSpiele</code></pre>Klassenname AnzahlSpiele in css-Datei
                <pre><code>CSS-Class LivePZ</code></pre>Klassenname LivePZ in css-Datei
                <pre><code>CSS-Class Spieler_frage</code></pre>Klassenname Spieler_frage in css-Datei
                <pre><code>CSS-Class Geburtsjahr</code></pre>Klassenname Geburtsjahr in css-Datei
                <pre><code>CSS-Class Vereinsjahr</code></pre>Klassenname Vereinsjahr in css-Datei
                <pre><code>CSS-Class Schlaghand</code></pre>Klassenname Schlaghand in css-Datei
                <pre><code>CSS-Class Spieltyp</code></pre>Klassenname Spieltyp in css-Datei
                <pre><code>CSS-Class Geschlecht</code></pre>Klassenname Geschlecht in css-Datei
                <hr>
                Planseiten:
                <pre><code>Seiten Id Hallenplan</code></pre>Seiten ID, die beim Link angezeigt wird
                <pre><code>Seiten Id Vereinsplan</code></pre>Seiten ID, die beim Link angezeigt wird
                <hr>
                Debugging Options:
                <pre><code>Debug Tracing aktivieren</code></pre>Erzeugt Trace - Infos in debug.log bei angeschaltetem WP_DEBUG zusätzlich. Erzeugt sehr viel Output, nur für Kenner geeignet
                <pre><code>Applikation Debugging aktivieren</code></pre>Erzeugt Infos über Aktionen in dem bttlive Plugin in debug.log. Erzeugt sehr viel Output, nur für Kenner geeignet

            </p>

        </div>

    <?php
    }
}


?>
