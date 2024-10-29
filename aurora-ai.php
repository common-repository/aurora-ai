<?php
/**
 * Plugin Name: Aurora AI
 * Description: Plugin to activate the Aurora AI virtual assistant on your website. Simple and efficient.
 * Version: 1.0
 * Author: <a href="https://www.itsaurora.ai">Aurora AI</a>
 * Text Domain: aurora-ai
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AURORA_AI_PLUGIN_VERSION', '1.0');

// Load plugin text domain for translations
function aurora_ai_load_textdomain() {
    load_plugin_textdomain('aurora-ai', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'aurora_ai_load_textdomain');

// Include the admin settings page
include_once(plugin_dir_path(__FILE__) . 'admin/settings-page.php');

// Enqueue scripts
function aurora_ai_enqueue_scripts() {
    $chatbot_id = get_option('aurora_ai_chatbot_id');

    if ($chatbot_id) {
        // Register a dummy script to attach inline script to
        wp_register_script('aurora-ai-script', '', array(), AURORA_AI_PLUGIN_VERSION, true);

        $inline_script = 'window.addEventListener("message", function(t) {
            var e = document.getElementById("itsaurora-chatbot-iframe"),
                s = document.getElementById("itsaurora-chatbot-button-iframe");
            "openChat" === t.data && (e && s ? (e.contentWindow.postMessage("openChat", "*"), s.contentWindow.postMessage("openChat", "*"), e.style.pointerEvents = "auto", e.style.display = "block", window.innerWidth < 640 ? (e.style.position = "fixed", e.style.width = "100%", e.style.height = "100%", e.style.top = "0", e.style.left = "0", e.style.zIndex = "9999") : (e.style.position = "fixed", e.style.width = "30rem", e.style.height = "65vh", e.style.bottom = "0", e.style.right = "0", e.style.top = "", e.style.left = "")) : console.error("iframe not found"));
            "closeChat" === t.data && e && s && (e.style.display = "none", e.style.pointerEvents = "none", e.contentWindow.postMessage("closeChat", "*"), s.contentWindow.postMessage("closeChat", "*"))
        });';

        wp_add_inline_script('aurora-ai-script', $inline_script);
        wp_enqueue_script('aurora-ai-script');
    }
}
add_action('wp_enqueue_scripts', 'aurora_ai_enqueue_scripts');

// Function to add code to the body
function aurora_ai_add_code_to_body() {
    $chatbot_id = get_option('aurora_ai_chatbot_id');

    if ($chatbot_id) {
        ?>
        <iframe src="https://www.itsaurora.ai/embed/<?php echo esc_attr($chatbot_id); ?>/button?chatbox=false"
            style="z-index: 50; margin-right: 1rem; margin-bottom: 1rem; position: fixed; right: 0; bottom: 0; width: 56px; height: 56px; border: 0 !important; border: 2px solid #e2e8f0; border-radius: 50%; color-scheme: none; background: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);"
            id="itsaurora-chatbot-button-iframe"></iframe>
        <iframe src="https://www.itsaurora.ai/embed/<?php echo esc_attr($chatbot_id); ?>/window?chatbox=false&withExitX=true"
            style="z-index: 50; margin-right: 1rem; margin-bottom: 6rem; display: none; position: fixed; right: 0; bottom: 0; pointer-events: none; overflow: hidden; height: 65vh; border: 2px solid #e2e8f0; border-radius: 0.375rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); width: 30rem;"
            allowfullscreen id="itsaurora-chatbot-iframe"></iframe>
        <?php
    }
}
add_action('wp_footer', 'aurora_ai_add_code_to_body');
