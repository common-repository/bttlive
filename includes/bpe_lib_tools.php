<?php
/**
 * Tools for wordpress
 * File: bpe_lib_tools.php
 * Author: Bernt Penderak
 * Author URI: http://bepe.penderak.net
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Singleton Pattern!!!!!
 */
class bpe_lib_tools {

    private static $INSTANCE = NULL;

    private function __construct()  {}
    private function __clone()      {}
    private function __wakeup()     {}

    public static function getInstance() {
        if(!self::$INSTANCE) {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    protected $_multisite = false;

    /**
     * @return string
     */
    public function getPluginName()
    {
        return $this->_plugin_name;
    }

    /**
     * @param string $plugin_name
     */
    public function setPluginName($plugin_name)
    {
        $this->_plugin_name = $plugin_name;
        return $this;
    }
    protected $_plugin_name = '';

    /**
     * @return boolean
     */
    public function isMultisite()
    {
        return $this->_multisite;
    }

    /**
     * @param boolean $multisite
     */
    public function setMultisite($multisite)
    {
        $this->_multisite = $multisite;
        return $this;
    }

    protected $_options=array();

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }


    protected $_option_name;

    /**
     * @return mixed
     */
    public function getOptionName()
    {
        return $this->_option_name;
    }

    /**
     * @param mixed $option_name
     */
    public function setOptionName($option_name)
    {
        $this->_option_name = $option_name;
        return $this;
    }

    protected $_action_hook_list = array();

    /**
     * @return array
     */
    public function getActionHookList()
    {
        return $this->_action_hook_list;
    }

    /**
     * @param array $action_hook_list
     */
    public function setActionHookList($action_hook_list)
    {
        $this->_action_hook_list = $action_hook_list;
    }

    protected $_user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }


    /**
     * class constructor
     * @param string $option_name   identifire to save/retrieve plugin options to/from wordpress
     */
    public function construct($plugin_name) {
        if ((WP_DEBUG === true) && ($this->get_option('debug_stage') >= 4)) {
            add_action('all',array( $this, 'log_action_hooks' ), 99999, 99);
        }

        // no generator in the head!!!
        add_filter('the_generator',array( $this, 'wp_remove_version' ));
        if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
            add_filter('login_errors',create_function('$a', "return 'Benutzerdaten sind falsch! ;-(';") );
        }else{
            add_filter('login_errors',function() { return 'Benutzerdaten sind falsch! ;-(';} );
        }

        add_action( 'plugins_loaded', array( $this, 'check_if_user_is_logged_in' ) );
        $this->setMultisite(function_exists('is_multisite') && is_multisite());
        $this->setOptionName($plugin_name . "_opt");
        $this->init_options($plugin_name);
        add_action('admin_notices', array($this, 'show_message'));
    }

    /**
     * Checks if user is logged in and calls setUser
     */
    public function check_if_user_is_logged_in() {
        $current_user = wp_get_current_user();

        if ( !($current_user instanceof WP_User) )
            return;

        $this->setUser($current_user->user_login);
    }

    /**
     * @param $message
     * @param string $logtype
     * @param string $DS
     * Logging in wordpress (maybe with more comfort in future time)
     * be careful of using debug_trace, there will be much more output in debug.log
     * only used if WP_DEBUG == true
     * log_stage > 1 is useful for more Application debugging logging!!!
     * Logstage setting in the options settings of bttlive
     */
    public function log($message, $aktion="" ,$log_stage="1", $logtype="APP", $DS=";") {

            $datum = date("d.m.Y H:m:s");
            $stamp=$datum . $DS . $this->getUser() . $DS . $logtype . $DS;
            if ($aktion) {
                $stamp = $aktion . $DS . $stamp;
            }
            if (is_array($message) || is_object($message)) {
                $nachricht = print_r($message,true);
            } else {
                $nachricht = $message;
            }
            if ($this->get_option('debug_stage')) {
                $debug_stage = $this->get_option('debug_stage');
            } else {
                $debug_stage = "1";
            }
            switch ($debug_stage) {
                default:
                case "1":
                    if ($this->get_option('debug_application',true)){
                        error_log($stamp . $nachricht);
                    }
                break;
                case "2":
                case "3":
                case "4":
                    if ($log_stage >= $debug_stage) {
                        error_log($stamp . $nachricht);
                    }
                break;
            }
            if (WP_DEBUG === true) {
                // zusätzliche Absicherung!!
                if ($this->get_option('debug_trace',false)){
                    $nachricht = print_r(debug_backtrace(),true);
                    str_replace($logtype,'TRC',$stamp);
                    error_log($stamp . $nachricht);
                }
            }
    }
    public function wp_remove_version () {
        return "";
    }
    public function log_action_hooks()
    {
        if (WP_HOOK_LOG === true) {
            static $list = array ();
            $exclude = array ( 'gettext', 'gettext_with_context' );

            $action = current_filter();
            $args = func_get_args();

            if ( ! in_array( $action, $exclude ) )
            {
                $list[] = $action;
            }
            $this->setActionHookList($list);
            // shutdown is the last action
            if ( 'shutdown' == $action )
            {
                $this->log($this->getActionHookList(),"", 3, "HOOK");
            }
        }
    }

    /**
     * get current options for this plugin
     * z.B. bttlive_opt
     */
    protected function init_options($plugin_name) {
        $this->setPluginName($plugin_name);
        $this->getInstance()->setOptions(get_option($this->getOptionName()));
    }

    public function add_options(array $options) {
        add_option($this->getOptionName(), $options);
        $this->getInstance()->setOptions($options);
        $this->log($this->getOptions(), __METHOD__ . ":" . __LINE__);
    }

    /**
     * Return HTML formatted message
     *
     * @param string $message   message text
     * @param string $error_style message div CSS style
     */
    public function show_message($message, $error_style=false) {

        if ($message) {
            if ($error_style) {
                echo '<div id="message" class="error" >';
            } else {
                echo '<div id="message" class="updated fade">';
            }
            echo $message . '</div>';
        }

    }


    /**
     * @param $option_name
     * @param bool $default
     * @return bool
     *      * returns option value for option with name in $option_name
     */
    public function get_option($option_name, $default = false) {
        $options=$this->getOptions();
        if ( isset( $options[ $option_name ] ) ) {
            return $options[$option_name];
        } else {
            return $default;
        }
    }


    /**
     * @param $option_name
     * @param $option_value
     * @param bool $flush_options
     * puts option value according to $option_name option name into options array property
     */
    public function put_option($option_name, $option_value, $flush_options=true) {
        $options=$this->getOptions();
        $options[$option_name] = $option_value;
        $this->getInstance()->setOptions($options);
        if ($flush_options) {
            $this->flush_options();
        }
    }


    /**
     * Delete array option with name option_name
     * @param string $option_name
     * @param bool $flush_options
     */
    public function delete_option($option_name, $flush_options=false) {
        $options=$this->getOptions();
        if (array_key_exists($option_name, $options)) {
            unset($options[$option_name]);
            $this->getInstance()->setOptions($options);
            if ($flush_options) {
                $this->flush_options();
            }
        }

    }

    public function remove_options() {
        delete_option($this->getOptionName());
    }

    /**
     * saves options array into WordPress database wp_options table
     */
    public function flush_options() {
        $this->log($this->getOptions(), __METHOD__ . ":" . __LINE__);
        update_option($this->getOptionName(), $this->getOptions());

    }

    /**
     * Check product version and stop execution if product version is not compatible
     * @param type $must_have_version
     * @param type $version_to_check
     * @param type $error_message
     * @return type
     */
    public static function check_version($must_have_version, $version_to_check, $error_message, $plugin_file_name) {

        if (version_compare( $must_have_version, $version_to_check, '<' ) ) {
            if ( is_admin() && ( !defined('DOING_AJAX') || !DOING_AJAX ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
                deactivate_plugins( $plugin_file_name );
                wp_die( $error_message );
            } else {
                return;
            }
        }
    }


    /**
     * returns 'selected' HTML cluster if $value matches to $etalon
     *
     * @param string $value
     * @param string $etalon
     * @return string
     */
    public function option_selected($value, $etalon) {
        $selected = '';
        if (strcasecmp($value, $etalon) == 0) {
            $selected = 'selected="selected"';
        }

        return $selected;
    }


    public function get_current_url() {
        global $wp;
        $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

        return $current_url;
    }

    public function recursiveFind(array $array, $needle)
    {
        $iterator  = new RecursiveArrayIterator($array);
        $recursive = new RecursiveIteratorIterator($iterator,
            RecursiveIteratorIterator::SELF_FIRST);
        foreach ($recursive as $key => $value) {
            if ($value == $needle) {
                return $value;
            }
        }
    }
}
// end of bpetools class

