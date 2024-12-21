<?php 
$availableTabs = array(
	'settings' => __('Settings', 'disable-email-notification-for-auto-updates'),
	'disable_plugin_updates' => __('Block Plugin Updates', 'disable-email-notification-for-auto-updates'),
	'youtube' => __('YouTube', 'disable-email-notification-for-auto-updates'),
	'other_plugins' => __('Other Plugins', 'disable-email-notification-for-auto-updates'),
	'about' => __('About', 'disable-email-notification-for-auto-updates'),
);
$active_tab = __( 'settings', 'disable-email-notification-for-auto-updates' ); // default tab
$plugin_slug_name = $this->get_plugin_slug();

if (isset($_GET['tab'])) {
    // Ensure the nonce is unslashed, sanitized and verified before processing the tab
    if (isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce'])); // Unsashing and sanitizing the nonce
        if (wp_verify_nonce($nonce, 'nonce_idc_action')) {
            // Sanitize the 'tab' input
            $input_tab = sanitize_key($_GET['tab']); 
            if (!empty($input_tab) && in_array($input_tab, array_keys($availableTabs))) {
                $active_tab = $input_tab;
            }
        } else {
            // Nonce is invalid, handle the error
            die('Nonce verification failed.');
        }
    }
}
?>

<div class="wrap">

<h1 id="wp_security_plugin_itc_title" data-purl="<?php echo esc_url( ITC_DISABLE_UPDATE_NOTIFICATIONS_PLUGIN_DIR_URL ); ?>"><?php echo esc_html( get_admin_page_title() ); ?></h1>

<nav class="nav-tab-wrapper wp-clearfix">
    <?php 
    foreach ($availableTabs as $tabIndex => $tab) {
        // Escaping the $tabIndex when used in HTML attributes (like href and class)
    ?>
        <a href="?page=<?php echo esc_attr($plugin_slug_name); ?>&tab=<?php echo esc_attr($tabIndex); ?>&_wpnonce=<?php echo esc_attr(wp_create_nonce('nonce_idc_action')); ?>" class="nav-tab <?php echo esc_attr($active_tab == $tabIndex ? 'nav-tab-active' : ''); ?>" title="<?php echo esc_attr($tab); ?>"><?php echo esc_html($tab); ?></a>
    <?php } ?>
</nav>

<?php 
foreach ($availableTabs as $tabIndex => $tab) {
    if ($active_tab === $tabIndex) {
        include_once '_'.esc_attr($tabIndex).'.php';  // Ensure $tabIndex is escaped here as well
    }
}
?>
</div>