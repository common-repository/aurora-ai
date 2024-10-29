<?php
// Add a new top-level menu to the WordPress admin dashboard
add_action('admin_menu', 'aurora_ai_add_menu_page');

function aurora_ai_add_menu_page() {
    add_menu_page(
        esc_html__('Aurora AI Settings', 'aurora-ai'),
        esc_html__('Aurora AI', 'aurora-ai'),
        'manage_options',
        'aurora-ai-settings',
        'aurora_ai_render_settings_page',
        'dashicons-format-chat',
        6
    );
}

// Render the settings page
function aurora_ai_render_settings_page() {
    $chatbot_id = get_option('aurora_ai_chatbot_id', '');
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Aurora AI Settings', 'aurora-ai'); ?></h1>

        <?php if (!empty($chatbot_id)): ?>
            <div id="aurora-live-status" style="display: flex; align-items: center; background-color: #e6ffe6; padding: 10px; border-radius: 10px; width: fit-content; margin-bottom: 20px;">
                <span style="display: inline-block; width: 12px; height: 12px; background-color: #28a745; border-radius: 50%; margin-right: 8px;"></span>
                <span style="color: #28a745; font-weight: bold;"><?php esc_html_e('Live', 'aurora-ai'); ?></span>
            </div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered setting
            settings_fields('aurora_ai_settings_group');
            // Output setting sections and their fields
            do_settings_sections('aurora-ai-settings');
            ?>

            <!-- Add the new button with black background and white text -->
            <a href="https://www.itsaurora.ai/login" target="_blank" style="display: inline-block; background-color: #000000; color: #ffffff; font-weight: bold; padding: 10px 20px; border-radius: 20px; border: 1px solid #cbd5e0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); text-decoration: none; margin-bottom: 20px;">
                <?php esc_html_e('Open Aurora AI Dashboard', 'aurora-ai'); ?>
            </a>
            <p class="description"><?php esc_html_e('Open the Aurora AI Dashboard to configure your chatbot settings and manage interactions.', 'aurora-ai'); ?></p>

            <?php
            // Output the save settings button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register the chatbot ID setting and sanitize the input
add_action('admin_init', 'aurora_ai_register_settings');

function aurora_ai_register_settings() {
    register_setting(
        'aurora_ai_settings_group', 
        'aurora_ai_chatbot_id', 
        'sanitize_text_field' // Sanitize the Chatbot ID input
    );

    add_settings_section(
        'aurora_ai_main_section', // Section ID
        '', // Title (empty since we don't need a title here)
        null, // Callback (not needed)
        'aurora-ai-settings' // Page slug
    );

    add_settings_field(
        'aurora_ai_chatbot_id_field', // Field ID
        __('Chatbot ID', 'aurora-ai'), // Field Title
        'aurora_ai_chatbot_id_input_callback', // Callback to render the input
        'aurora-ai-settings', // Page slug
        'aurora_ai_main_section' // Section ID
    );
}

// Callback function to render the input field for chatbot ID
function aurora_ai_chatbot_id_input_callback() {
    $chatbot_id = get_option('aurora_ai_chatbot_id', '');
    ?>
    <input type="text" name="aurora_ai_chatbot_id" value="<?php echo esc_attr($chatbot_id); ?>" class="regular-text">
    <p class="description"><?php esc_html_e('Enter your Aurora AI Chatbot ID here (e.g., cm153fp4s000an8oychij82q3). You can find this ID in the Aurora AI Dashboard after configuring your chatbot.', 'aurora-ai'); ?></p>
    <?php
}
