<?php
if ( ! is_admin() ) {
    return;
}

class ITC_Disable_Update_Notifications_Admin extends ITC_Disable_Update_Notifications_BaseController {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_notices', array( $this, 'general_admin_notice' ) );
        add_action( 'admin_init', array( $this, 'handle_dismiss_notice' ) );
    }

    public function enqueue_styles() {
        wp_enqueue_style( 'itc-disable_update_notifications-admin-css', plugin_dir_url( __FILE__ ) . 'css/itc-admin.css', array(), $this->get_version(), 'all' );
    }

    public function enqueue_scripts() {
        wp_register_script( 'itc-disable_update_notifications-admin-js', plugin_dir_url( __FILE__ ) . 'js/itc-admin.js', array( 'jquery' ), $this->get_version(), false );
        wp_localize_script( 'itc-disable_update_notifications-admin-js', 'ITC_Disable_Update_Notifications_Admin', array(
            'ajaxurl' => get_admin_url() . 'admin-ajax.php',
        ));
        wp_enqueue_script( 'itc-disable_update_notifications-admin-js' );
    }

    public function itc_disable_update_notifications_action_links( $links ) {
        $plugin_slug_name = $this->get_plugin_slug();
        $links1 = '<a href="https://buymeacoffee.com/ideastocode" target="_blank" style="font-weight:bold;">' . esc_html__( 'Donate', 'disable-email-notification-for-auto-updates' ) . '</a>';
        $links2 = '<a href="' . esc_url( menu_page_url( $plugin_slug_name, false ) ) . '">' . esc_html__( 'Settings', 'disable-email-notification-for-auto-updates' ) . '</a>';
        array_unshift( $links, $links1, $links2 );
        return $links;
    }

    public function add_options_page() {
        global $admin_page_hooks;
        $plugin_slug_name = $this->get_plugin_slug();
        $plugin_title = $this->get_plugin_title();

        if ( ! isset( $admin_page_hooks[$plugin_slug_name] ) ) {
            add_options_page(
                $plugin_title,
                $plugin_title,
                'manage_options',
                $plugin_slug_name,
                array( $this, 'itc_disable_update_notifications_option_page' )
            );
        }
    }

    public function itc_disable_update_notifications_option_page() {
        include_once 'partials/admin-display.php';
    }

    public function register_setting() {
        $setting_slug = $this->get_settings();

        register_setting( $setting_slug . "_option_group", $setting_slug, array(
            'sanitize_callback' => array( $this, 'form_submit_sanitize' )
        ));

        // Register settings for blocking plugin updates
        $disable_plugin_updates_slug = $setting_slug . '_disable_plugin_updates';
        register_setting( $disable_plugin_updates_slug . "_option_group", $disable_plugin_updates_slug, array(
            'sanitize_callback' => array( $this, 'form_submit_sanitize_disable_plugin_updates' )
        ));
    }

    public function form_submit_sanitize( $settings ) {
        $finalSettings = $this->get_option_default();
        $finalSettings['plugin'] = $this->form_submit_sanitize_bool($settings, 'plugin');
        $finalSettings['themes'] = $this->form_submit_sanitize_bool($settings, 'themes');
        $finalSettings['wordpress'] = $this->form_submit_sanitize_bool($settings, 'wordpress');
        $finalSettings['wp_update_button'] = $this->form_submit_sanitize_bool($settings, 'wp_update_button');
        $finalSettings['wp_core'] = $this->form_submit_sanitize_bool($settings, 'wp_core');
        $finalSettings['wp_themes'] = $this->form_submit_sanitize_bool($settings, 'wp_themes');

        $setting_slug = $this->get_settings();
        if ( ! get_option( $setting_slug ) ) {
            add_settings_error(
                $setting_slug . "_option_group",
                $setting_slug . "_option_name",
                "Setting successfully updated.",
                "updated"
            );
        }

        return $finalSettings;
    }

    public function form_submit_sanitize_disable_plugin_updates( $settings ) {
        $finalSettings = [];
        if ( is_array( $settings ) ) {
            foreach ( $settings as $key => $value ) {
                $finalSettings[$key] = $this->form_submit_sanitize_bool( $settings, $key );
            }
        }
        return $finalSettings;
    }

    private function form_submit_sanitize_bool( $settings, $key ) { 
        return isset( $settings[$key] ) && $settings[$key] == "1" ? 1 : 0;
    }

    public function general_admin_notice() {
        $plugin_slug_name = $this->get_plugin_slug();

        // Check if the user has dismissed the notice
        $notice_dismissed = get_option( 'itc-disable_update_notifications_notice_dismissed', false );

        if ( $notice_dismissed ) {
            return; // Don't show the notice if dismissed
        }

        // Show the notice
        ?>
        <div class="notice notice-info itc-svg-upload-notice">
            <div class="notice-content">
                <p class="notice-text">
                    <strong><?php esc_html_e( 'New Features Added: ', 'disable-email-notification-for-auto-updates' ); ?></strong>
                    <?php esc_html_e( ' Block specific plugin updates, block WordPress core and theme updates, or remove the update button under the Dashboard menu.', 'disable-email-notification-for-auto-updates' ); ?>
                </p>
                <div class="notice-buttons">
                    <a href="<?php echo esc_url( menu_page_url( $plugin_slug_name, false ) ); ?>" class="button button-primary">
                        <?php esc_html_e( 'Check Features', 'disable-email-notification-for-auto-updates' ); ?>
                    </a>
                    <a href="<?php echo esc_url( add_query_arg( 'dismiss_notice', 'true' ) ); ?>" class="button button-secondary">
                        <?php esc_html_e( 'Dismiss Notice', 'disable-email-notification-for-auto-updates' ); ?>
                    </a>
                </div>
            </div>
        </div>
        <style>
            .itc-svg-upload-notice .notice-dismiss {
                display: none !important;
            }
            .itc-svg-upload-notice .notice-content {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                padding: 5px;
            }
            .itc-svg-upload-notice .notice-text {
                margin-right: 10px;
                flex-grow: 1;
            }
            .itc-svg-upload-notice .notice-buttons {
                display: flex;
                justify-content: flex-end;
            }
            .itc-svg-upload-notice .button {
                margin-left: 10px;
            }
        </style>
        <?php
    }

    public function handle_dismiss_notice() {
        if ( isset( $_GET['dismiss_notice'] ) && $_GET['dismiss_notice'] === 'true' ) {
            update_option( 'itc-disable_update_notifications_notice_dismissed', true );
        }
    }
}

