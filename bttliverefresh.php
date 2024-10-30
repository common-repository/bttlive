<?php


/**
 * Importiert die Daten aus TTLive und schreibt sie als xml-File in das Plugin-Verzeichnis
 *
 * @param $params
 */
function refreshbttliveData(&$params) {
    /***
     *   Cache XML-File to reduce traffic
     *   IF XML is older than x hour -> renew (store XML in TMP folder)
     */

    $secondsToRefresh = $params['refresh'] * 3600;
//    if(!file_exists($params['filename']) || mktime()-$secondsToRefresh > filemtime($params['filename']))

    if(!file_exists($params['filename']) || time()-$secondsToRefresh > filemtime($params['filename']))
    {

        $html = wp_remote_retrieve_body( wp_remote_get($params['url']) );

        if ($html != '') {
            $myhandle = fopen($params['filename'], "w");
            if (!fwrite($myhandle, $html)) {
                print "<br />Kann in die Datei". $params['filename'] ."nicht schreiben.<br />";
                exit; // @todo: normaler Exit

            }
        }
    }
}
