<?php
if (!session_id()) {
	session_start();
}

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://support.catsplugins.com
 * @since             1.0.0
 * @package           Acf_Form_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       User Frontend Submit PRO
 * Plugin URI:        acf-form-builder
 * Description:       Create new posts on frontend with guest or paying feautres
 * Version:           3.5
 * Author:            Cat's Plugins
 * Author URI:        http://support.catsplugins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-form-builder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('ACF_FORM_BUILDER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ACF_FORM_BUILDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ACF_FORM_BUILDER_TEXTDOMAIN', 'acf-form-builder');

define("ACF_FORM_PLUGIN_URL", plugin_dir_url(__FILE__));
/**
 * Include Codestar Framework
 *
 * @since 1.0
 */
require_once ACF_FORM_BUILDER_PLUGIN_PATH . 'includes/codestar-framework-1.0.1/cs-framework.php';
require_once ACF_FORM_BUILDER_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acf-form-builder-activator.php
 */
function activate_acf_form_builder() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-acf-form-builder-activator.php';
	Acf_Form_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acf-form-builder-deactivator.php
 */
function deactivate_acf_form_builder() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-acf-form-builder-deactivator.php';
	Acf_Form_Builder_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_acf_form_builder');
register_deactivation_hook(__FILE__, 'deactivate_acf_form_builder');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-acf-form-builder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acf_form_builder() {

	$dir = plugin_dir_path(__FILE__);
	include_once $dir . 'includes/acf-form-builder-helper-functions.php';

	$plugin = new Acf_Form_Builder();
	$plugin->run();

}
run_acf_form_builder();




function acfWooRunCore()
{
    require_once __DIR__ . "/vendor/autoload.php";

    add_action('admin_init', function (){
        add_action('admin_head', function (){
            if (isset($_GET['page']) && $_GET['page'] == 'acfform-merlin') {
                echo "<link rel=\"stylesheet\" href=\"". plugin_dir_url(__FILE__) ."admin/css/acf-form-builder-admin.css\">";
            }
        });
    });

    $cpCoreDemo = new CastPlugin\CpCore('ACF Form Builder', [
        'plugin_path' => __DIR__,
        'plugin_id' => "",
        'force_active' => false
    ]);

    $cpCoreDemo->createPageSetting([
        'file' => __DIR__ . '/config/admin_setting.json'
    ]);

    $cpCoreDemo->merlin([
        'merlin_url' => 'acfform-merlin',
        'edd_theme_slug' => 'acfform-merlin',
        'license_step' => false,
        'ready_big_button_url' => admin_url('/admin.php?page=acf-form-builder-dashboard')
    ], [
        'import-header' => esc_html__('Import ACF Demo Forms', 'acfwoo'),
        'import' => esc_html__('If you want to have the sample demo, please check the demo content below to have a faster kickstart', 'acfwoo'),

        'welcome-header%s' => esc_html__('Auto Installation %s', 'catsplugin'),
        'welcome-header-success%s' => esc_html__('Auto Installation', 'catsplugin'),
        'welcome%s' => esc_html__('You may have already run this plugin setup wizard. If you would like to proceed anyway, click on the "Start" button below. If this is the first time, running through this wizard is required.!', 'catsplugin'),
        'welcome-success%s' => esc_html__('You may have already run this plugin setup wizard. If you would like to proceed anyway, click on the "Start" button below. If this is the first time, running through this wizard is required.!', 'catsplugin'),

        'admin-menu' => esc_html(__('ACF for Woocommerce Import content')),
        'license%s' => "Enter your license key and Email",
        'plugins' => esc_html(__('User Frontend Submit PRO require either ACF PRO or FREE installed to run.', 'acfwoo')),

        'ready%s' => esc_html__('User Frontend Submit PRO have been set up. Enjoy your new form builder plugin!', 'acfwoo'),
        'ready-action-link' => esc_html__('Extras', 'acfwoo'),
        'ready-big-button' => esc_html__('Wellcome.', 'acfwoo'),
        'ready-link-1' => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://catsplugins.com/knowledge-base/frontend-submit-pro/user-frontend-submit-pro-full-guide/', esc_html__('Document', 'acfwoo')),
        'ready-link-2' => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://catsplugins.com/knowledge-base/frontend-submit-pro/user-frontend-submit-pro-full-guide/', esc_html__('Get Help', 'acfwoo')),
        'ready-link-3' => sprintf('<a href="%1$s">%2$s</a>', 'https://catsplugins.com/faq', esc_html__('Community Support', 'acfwoo')),
    ], array(
        array(
            'import_file_name' => 'Import ACF Demo',
            'import_file_url' => ACF_FORM_PLUGIN_URL . '/data-sample/acf-export-2018-12-11.json',
        )
    ));

    $plugins = array();


    if (is_file(plugin_dir_path( __DIR__ ) . "advanced-custom-fields-pro-master/acf.php")) {
        $plugins[] = array(
            'name' => 'Advanced Custom Fields Pro',
            'slug' => 'advanced-custom-fields-pro-master',
            'required' => true,
            'force_activation' => true
        );
    } else if (is_file(plugin_dir_path( __DIR__ ) . "advanced-custom-fields-pro/acf.php")) {
        $plugins[] = array(
            'name' => 'Advanced Custom Fields Pro',
            'slug' => 'advanced-custom-fields-pro',
            'required' => true,
            'force_activation' => true
        );
    } else if (is_file(plugin_dir_path( __DIR__ ) . "advanced-custom-fields/acf.php")) {
        $plugins[] = array(
            'name' => 'Advanced Custom Fields',
            'slug' => 'advanced-custom-fields',
            'required' => true,
            'force_activation' => true
        );
    }

    $cpCoreDemo->tgm($plugins);
}

acfWooRunCore();