<?php
/* Default pos-type */
add_action('init','post_type_bttlive');
add_action('init','taxonomies_bttlive', 0 );

/**
 * creates Post-Type bttlive
 */
function post_type_bttlive() {
    register_post_type(
        'bttlive',
        array(
            'labels' => array (
                'name' => 'bttlive Inhalte',
                'description' => 'TTlive Inhalte im Beiträge ausgeben.',
                'singular_name' => 'TTlive Inhalt',
                'add_new_item' => 'Neuen TTlive Inhalt anlegen',

            ),
            'menu_icon' => plugins_url( 'images/tt_16.png' , __FILE__ ),
            'public' => true,
            'show_ui' => true,
            'supports' =>array(
                'title',
                'excerpt', 'editor' , 'thumbnail', 'custom-fields',
            )
        )

    );
}

/**
 * creates taxonomies bttlive
 */
function taxonomies_bttlive() {
    // Add new "Kategorie" taxonomy to Posts
    $posttype = 'bttlive';
    $taxonomy = 'kategorien';
    $labels = array(
        'name'                  => _x( 'Kategorien', 'Kategorien der Inhalte' ),
        'singular_name'         => _x( 'Kategorie', 'Kategorie des Inhalts' ),
        'search_items'          =>  __( 'Kategoriensuche' ),
        'all_items'             => __( 'Alle Kategorien' ),
        'parent_item'           => __( 'Eltern Kategorie' ),
        'parent_item_colon'     => __( 'Eltern Kategorie:' ),
        'edit_item'             => __( 'Bearbeite Kategorie' ),
        'update_item'           => __( 'Ändern Kategorie' ),
        'add_new_item'          => __( 'Neue Kategorie' ),
        'new_item_name'         => __( 'Neuer Kategorie Name' ),
        'menu_name'             => __( 'Kategorien' ),
    );
    $args = array(
        // Hierarchical taxonomy (like categories)
        'hierarchical'          => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        // Control the slugs used for this taxonomy
        'rewrite'               => array(
            'slug' => 'kategorien', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/kategorien/"
            'hierarchical' => true // This will allow URL's like "/kategorien/Damen/1. Damen/"
        ),
    );
    register_taxonomy($taxonomy, $posttype, $args);

    $taxonomy = 'schlagworte';
    $labels = array(
        'name'                          => _x( 'Schlagworte', 'Schlagworte der Inhalte' ),
        'singular_name'                 => _x( 'Schlagwort', 'Schlagwort des Inhalts' ),
        'search_items'                  =>  __( 'Schlagwortsuche' ),
        'popular_items'                 => __( 'Populäre Schlagwörter' ),
        'parent_item'                   => null,
        'parent_item_colon'             => null,

        'all_items'                     => __( 'Alle Schlagworte' ),
        'edit_item'                     => __( 'Bearbeite Schlagwort' ),
        'update_item'                   => __( 'Ändern Schlagwort' ),
        'add_new_item'                  => __( 'Neues Schlagwort' ),
        'new_item_name'                 => __( 'Neues Schlagwort' ),
        'separate_items_with_commas'    => __( 'Trenne Schlagwörter mit Kommas' ),
        'add_or_remove_items'           => __( 'Hinzufügen oder Löschen von Schlagworten' ),
        'choose_from_most_used'         => __( 'Wähle von den am meisten benutzten Schlagworten' ),
        'not_found'                     => __( 'Keine Schlagwort gefunden.' ),
        'menu_name'                     => __( 'Schlagworte' ),
    );
    $args = array(
        // Hierarchical taxonomy (like categories)
        'hierarchical'                  => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels'                        => $labels,
        'show_ui'                       => true,
        'show_admin_column'             => true,
        'query_var'                     => 'schlagworte',
        'update_count_callback' => '_update_post_term_count',
        // Control the slugs used for this taxonomy
        'rewrite'                       => array(
            'slug' => 'schlagworte', // This controls the base slug that will display before each term
        ),
    );
    register_taxonomy($taxonomy, $posttype, $args);

}
/* Spieler Post-Type */
add_action('init','post_type_spieler');
add_action('init','taxonomies_mannschaften', 0 );

/**
 * creates Post-Type bttlive Spieler
 */
function post_type_spieler() {
    register_post_type(
        'bttlive_spieler',
        array(
            'labels' => array (
                'name' => 'bttlive Spielerporträts',
                'description' => 'TTlive Spieler Porträts verwalten.',
                'singular_name' => 'TTlive Spieler Porträt',
                'add_new_item' => 'Neues TTlive Spieler Porträt anlegen',

            ),
            'menu_icon' => plugins_url( 'images/tt_16.png' , __FILE__ ),
            'public' => true,
            'show_ui' => true,
            //"show_in_admin_bar" => true,
            //"show_in_menu" => "admin.php?page=cms", // => true : works as main menu item
            'supports' =>array(
                'title',
                'excerpt', 'editor' , 'thumbnail', 'custom-fields',
            )
        )

    );
}

/**
 * creates bttlive Mannschaften
 */
function taxonomies_mannschaften() {
    // Add new "Kategorie" taxonomy to Posts
    $posttype = 'bttlive_spieler';
    $taxonomy = 'mannschaften';
    $labels = array(
        'name'                  => _x( 'Mannschaften', 'Spieler der Inhalte' ),
        'singular_name'         => _x( 'Mannschaft', 'Mannschaft des Inhalts' ),
        'search_items'          =>  __( 'Mannschaftssuche' ),
        'all_items'             => __( 'Alle Mannschaften' ),
        'parent_item'           => __( 'Eltern Mannschaft' ),
        'parent_item_colon'     => __( 'Eltern Mannschaft:' ),
        'edit_item'             => __( 'Bearbeite Mannschaft' ),
        'update_item'           => __( 'Ändern Mannschaft' ),
        'add_new_item'          => __( 'Neue Mannschaft' ),
        'new_item_name'         => __( 'Neuer Mannschaftsname' ),
        'menu_name'             => __( 'Mannschaften' ),
    );
    $args = array(
        // Hierarchical taxonomy (like categories)
        'hierarchical'          => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        // Control the slugs used for this taxonomy
        'rewrite'               => array(
            'slug' => 'mannschaften', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/mannschaften/"
            'hierarchical' => true // This will allow URL's like "/mannschaften/Damen/1. Damen/"
        ),
    );
    register_taxonomy($taxonomy, $posttype, $args);

    $taxonomy = 'schlagworte';
    $labels = array(
        'name'                          => _x( 'Schlagworte', 'Schlagworte der Inhalte' ),
        'singular_name'                 => _x( 'Schlagwort', 'Schlagwort des Inhalts' ),
        'search_items'                  =>  __( 'Schlagwortsuche' ),
        'popular_items'                 => __( 'Populäre Schlagwörter' ),
        'parent_item'                   => null,
        'parent_item_colon'             => null,

        'all_items'                     => __( 'Alle Schlagworte' ),
        'edit_item'                     => __( 'Bearbeite Schlagwort' ),
        'update_item'                   => __( 'Ändern Schlagwort' ),
        'add_new_item'                  => __( 'Neues Schlagwort' ),
        'new_item_name'                 => __( 'Neues Schlagwort' ),
        'separate_items_with_commas'    => __( 'Trenne Schlagwörter mit Kommas' ),
        'add_or_remove_items'           => __( 'Hinzufügen oder Löschen von Schlagworten' ),
        'choose_from_most_used'         => __( 'Wähle von den am meisten benutzten Schlagworten' ),
        'not_found'                     => __( 'Keine Schlagwort gefunden.' ),
        'menu_name'                     => __( 'Schlagworte' ),
    );
    $args = array(
        // Hierarchical taxonomy (like categories)
        'hierarchical'                  => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels'                        => $labels,
        'show_ui'                       => true,
        'show_admin_column'             => true,
        'query_var'                     => 'schlagworte',
        'update_count_callback' => '_update_post_term_count',
        // Control the slugs used for this taxonomy
        'rewrite'                       => array(
            'slug' => 'schlagworte', // This controls the base slug that will display before each term
        ),
    );
    register_taxonomy($taxonomy, $posttype, $args);

}


function create_mannschaften_custom_fields($term_id) {
        $t_id = $term_id;
        $term_meta=array();
        //default options
        //save the option array
        bpe_lib_tools::getInstance()->log($t_id, __FUNCTION__ . ":" . __LINE__ );
        add_option( "bttlive_mannschaften_$t_id", $term_meta );
}

/**
 * @param $tag
 * callback function to add a custom fields to "mannschaften" taxonomy
 * Bearbeite Mannschaften - Tag
 */
function mannschaften_edit_custom_fields($tag)
{
    // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = get_option("bttlive_mannschaften_$t_id"); // Do the check
    mannschaften_view($term_meta);
}

/**
 * @param $tag
 * callback function to add a custom fields to "mannschaften" taxonomy
 * Create Mannschaften - Tag *
 */
function mannschaften_add_custom_fields($tag)
{
    // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = array(
        //'staffel_id' => '5311',
        //'mannschafts_id' => '35482',
    );
    mannschaften_view($term_meta);
}


/**
 * @param $term_meta
 * Input Form der Custom fields von taxonomy mannschaften
 */
function mannschaften_view(&$term_meta) {
    $mimage=$term_meta['mannschafts_bild'];
    echo "<table>\n";
    echo "<tbody>\n";
    $options=bpe_lib_tools::getInstance()->getOptions();
    if (! empty($options['mannschaften'])) {
        echo '<tr class="form-field" valign="top">' .
            '<th scope="row">' . "\n" .
            '<label for="term_meta[mannschaftsname]">Mannschaft </label></th> ' . "\n";
        echo'<td><select id="term_meta[mannschaftsname]" name="term_meta[mannschaftsname]" class="postform" >' . "\n";
        foreach ($options['mannschaften'] as $mannschaft) {
            echo '<option';
            echo ' '. bpe_lib_tools::getInstance()->option_selected($mannschaft['Name'],$term_meta['mannschaftsname']);
            echo' value="' . $mannschaft['Name']
                . '">' . $mannschaft['Name']
                . " " . $mannschaft['Staffelname']
                . " St.ID: " . $mannschaft['StaffelID']
                . " MID: " . $mannschaft['MannschaftsID'] . '</option>' . "\n";
        }
        echo '</select>' . "\n";
        echo "</td>\n</tr>\n";

    } else {

        ?>

        <tr class="form-field" valign="top">
            <th scope="row"><label for="staffel_id"><?php _e('TTLive Staffel Id'); ?></label></th>
            <td>
                <input type="number" step="1" min="1" max="999999" name="term_meta[staffel_id]"
                       id="term_meta[staffel_id]"
                       style="width: 100px;"
                       value="<?php echo $term_meta['staffel_id'] ? $term_meta['staffel_id'] : ''; ?>"><br/>
                <span class="description"><?php _e('Die Staffel Id der Mannschaft aus den TTLive Daten'); ?></span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" valign="top">
                <label for="mannschafts_id"><?php _e('TTLive Mannschafts Id'); ?></label>
            </th>
            <td>

                <input type="number" step="1" min="1" max="999999" name="term_meta[mannschafts_id]"
                       id="term_meta[mannschafts_id]"
                       style="width: 100px;"
                       value="<?php echo $term_meta['mannschafts_id'] ? $term_meta['mannschafts_id'] : ''; ?>"><br/>
                <span class="description"><?php _e('Die Mannschafts Id der Mannschaft aus den TTLive Daten'); ?></span>
            </td>

    <?php


    }
    ?>
    <tr valign="top">
            <th scope="row" valign="top">
                <label for="mannschafts_bild"><?php _e('Mannschafts Bild'); ?></label>
    </th>
    <td>
        <input class="large_text" type="text" name="term_meta[mannschafts_bild]" id="mannschafts_bild_id"
           value="<?php echo $term_meta['mannschafts_bild'] ? $term_meta['mannschafts_bild'] : ''; ?>">
        <input id="mannschafts_bild_button" class="button" type="button" value="Mannschaftsbild einstellen" /><br/>
        <br />Eine URL eingeben oder ein Bild einstellen<br />
        <span class="description"><?php _e('Das Mannschafts-Bild zur zugeordneten Mannschaft'); ?></span>

    </td>
    </tr>

    </tr>
    <tr><th scope="row" valign="top">
            <label for="mannschafts_img_id"><?php _e('Bild'); ?></label>
        </th>
        <td><img src="<?php echo $term_meta['mannschafts_bild'] ?>" class="bttliveImg" id="mannschafts_img_id"  alt="Mannschaftsbild" width="250px">
    </td>
    </tr>

    </tbody>
    </table>
<?php


}
    // A callback function to save our extra taxonomy field(s)
/**
 * @param $term_id
 * Save Custom fields von taxonomy mannschaften
 */
function save_mannschaften( $term_id  ) {
        $options=bpe_lib_tools::getInstance()->getOptions();
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "bttlive_mannschaften_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ){
                if ( isset( $_POST['term_meta'][$key] ) ){
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            if ( isset( $term_meta['mannschaftsname']) && isset ($options['mannschaften'])) {
                foreach ($options['mannschaften'] as $mannschaft) {
                    bpe_lib_tools::getInstance()->log($mannschaft, __FUNCTION__ . ":" . __LINE__ , 3);
                    if ($mannschaft['Name'] == $term_meta['mannschaftsname']) {
                        $term_meta['staffel_id'] = $mannschaft['StaffelID'];
                        $term_meta['mannschafts_id'] = $mannschaft['MannschaftsID'];
                    }
                }
            }
            bpe_lib_tools::getInstance()->log($term_meta,  __FUNCTION__ . ":" . __LINE__ );
            //save the option array
            update_option( "bttlive_mannschaften_$t_id", $term_meta );
        }
}

// Add the fields to the "Mannschaften" taxonomy, using our callback function
add_action( 'mannschaften_edit_form_fields', 'mannschaften_edit_custom_fields', 10, 2 );
add_action( 'mannschaften_add_form_fields', 'mannschaften_add_custom_fields', 10, 2 );

// Save the changes made on the "Mannschaften" taxonomy, using our callback function
add_action( 'edited_mannschaften', 'save_mannschaften', 10, 2);

add_action( 'created_mannschaften', 'create_mannschaften_custom_fields', 10, 2 );
?>