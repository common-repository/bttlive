=bttlive==
Contributors: berntp66
Donate link:
Tags: 
Requires at least: 3.7
Tested up to: 5.4.2
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin can XML and HTML data page tischtennislive.de accumulate with your own pictures and information, so that by simple means a colorful club world arises.
== Description ==


The plugin can XML and HTML data page tischtennislive.de accumulate with your own pictures and information, so that by simple means a colorful club world arises. Only useful in Germany, because the home of the plattform is only in Germany.

bttlive shows team results on your wp-page or within the wp-post.

Das Plugin kann die XML- und HTML-Daten der Plattform tischtennislive.de mit den eigenen Bildern und Informationen in dem WP-Blog kombinieren, so dass eine bute Vereinswelt entsteht.

Dieses Plugin kann folgende Funktionen abdecken:

    Spielerporträts
    Standardseiten für Mannschaften
    Live-PZ-Rangliste mit Bildern aus den Spielerporträts
    Klassenspielpläne
    Mannschaftsaufstellungen
    Heimnspielpläne
    Vereinsspielpläne
    

== Installation ==


English:
1. Download and unzip `bttlive`-zip-file or upload to `bttlive`-directory in `/wp-content/plugins/`
2. Activate plugin in 'Plugins' menu in WordPress
3. Change the fields 'Basis url', 'Abteilungs/SpartenID' and 'Suchstring' in bttlive settings. More Infos about 'SpartenID' in Manual
4. place the `[bttlive]` Shortcode in your posts or pages. Use the parameters to select data, as you need.

Deutsch:
1. `bttlive`-zip-file hochladen und den Befehl 'unzip' ausführen lassen or in  das `bttlive`-directory in `/wp-content/plugins/` hochladen
2. Das Plugin im 'Plugins' menu in WordPress aktivieren
3. Die Felder 'Basis url', 'Abteilungs/SpartenID' und 'Suchstring' in bttlive Einstellungen ändern. Mehr Informationen über die 'SpartenID' im Handbuch
4. Platziere den `[bttlive]` Shortcode in deinen Beiträgen oder Seiten. Nutze die Parameter um die Daten zu selektieren, so wie sie gebraucht werden.


Parameter:
`elementname` - Rueckgabe-Element - mögliche Werte: Mannschaft, Spielplan, Tabelle, 14Tage oder Rangliste
`mannschaft_id` - TTLive Mannschaft ID
`staffel_id` - TTLive Staffel ID
`mannschaft_name` - Feld Slug in der taxonomie "mannschaften"
`tableclassname` - css-Klassenname der Tabelle
`runde` -  Vorrunde = [bttlive runde=1] (default), Rückrunde = [bttlive runde=2]
`showxdays` -  14Tage: Anzahl der Tage die dargestellt werden sollen (default = 14)
`widget` -  14Tage: Für die Darstellung in einem Widget - default = 0 --> legt man den Schalter auf 1, wird eine Darstellung optimiert für ein Sidebar-Widget verwendet 
`display_type` - Nur für Rangliste: (default: 1) Wenn 0, dann werden nur die Spieler angezeigt die eine gültige LivePZ haben
`display_type` - Nur für die 14Tage: die letzten 14Tage (0 - default) oder die naechsten 14Tage (1)		
`refresh` - Anzahl Stunden bis die Daten erneut vom Live-System aktualisiert werden sollen

Beispiele mit der url (http://ttvsh.tischtennislive.de) im Handbuch

== Frequently Asked Questions ==

= Where can I find this plugin in action? =

Check the [plugin homepage] (http://www.preetz-dragons.de//).

== Screenshots ==

no screenshots available

== Changelog ==
= 1.5 =
* Anpassung auf Wordpress > 5.4.2
Änderung auf PHP > 5.3
Änderung auf Wordpress > 5.4.2

= 1.2 =
* Initial version
Spielerporträts
    Standardseiten für Mannschaften
    Live-PZ-Rangliste mit Bildern aus den Spielerporträts
    Klassenspielpläne
    Mannschaftsaufstellungen
    Heimnspielpläne
    Vereinsspielpläne
    Letzte 14Tage - Spiele
