<?php
// starships-class.php

class Starships {

    // Initialize
    public function init() {
        //hooks
        add_action('admin_menu', [$this, 'starships_options_page']); // Register admin menu
        add_action('admin_init', [$this, 'starships_settings_init']); // Register settings and fields
        add_filter('the_content', [$this, 'display_starships_content']); // Display starships content
        add_action('wp_enqueue_scripts', [$this, 'enqueue_starships_css']); // Enqueue CSS file when plugin is active
    }

    // Create Admin Page
    public function starships_options_page() {
        add_menu_page(
            __('Starships Settings', 'starships-textdomain'),
            __('Starships', 'starships-textdomain'),
            'manage_options',
            'starships-settings',
            [$this, 'starships_settings_page']
        );
    }

    // Add Submenu Page
    public function starships_settings_page() {
        ?>
        <div class="wrap">
            <h2><?php _e('Starships Settings', 'starships-textdomain'); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('starships_settings_group');
                do_settings_sections('starships-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // Register Settings and Fields
    public function starships_settings_init() {
        register_setting('starships_settings_group', 'starships_selected_page');
        register_setting('starships_settings_group', 'starships_display_position', [
            'type' => 'string',
            'default' => 'before', // Default value is 'before'
        ]);

        add_settings_section(
            'starships_settings_section',
            __('Select Page for Starships', 'starships-textdomain'),
            [$this, 'starships_settings_section_callback'],
            'starships-settings'
        );

        add_settings_field(
            'starships_selected_page',
            __('Selected Page', 'starships-textdomain'),
            [$this, 'starships_selected_page_callback'],
            'starships-settings',
            'starships_settings_section'
        );

        add_settings_field(
            'starships_display_position',
            __('Display Starships', 'starships-textdomain'),
            [$this, 'starships_display_position_callback'],
            'starships-settings',
            'starships_settings_section'
        );
    }

    public function starships_settings_section_callback() {
        echo '<p>' . __('Select the page where you want to display Star Wars starships.', 'starships-textdomain') . '</p>';
    }

    public function starships_selected_page_callback() {
        $selected_page_id = get_option('starships_selected_page');
        $pages = get_pages();

        echo '<select name="starships_selected_page">';
        foreach ($pages as $page) {
            echo '<option value="' . $page->ID . '" ' . selected($selected_page_id, $page->ID, false) . '>';
            echo esc_html($page->post_title);
            echo '</option>';
        }
        echo '</select>';
    }

    public function starships_display_position_callback() {
        $display_position = get_option('starships_display_position');
        ?>
        <select name="starships_display_position">
            <option value="before" <?php selected($display_position, 'before'); ?>><?php _e('Before Content', 'starships-textdomain'); ?></option>
            <option value="after" <?php selected($display_position, 'after'); ?>><?php _e('After Content', 'starships-textdomain'); ?></option>
        </select>
        <?php
    }

    // Display Starships on Selected Page
    public function display_starships_content($content) {
        $selected_page_id = get_option('starships_selected_page');
        $display_position = get_option('starships_display_position');

        if (is_page($selected_page_id)) {
            // Fetch and display starships content before or after the page content
            $api_url = STARSHIPS_API_URL;
            $response = wp_remote_get($api_url);

            if (is_array($response) && !is_wp_error($response)) {
                $starships = json_decode($response['body']);

                // Start output buffering
                ob_start(); 

                // Table view 
                include(plugin_dir_path(__FILE__) . 'view/starships-table.php');

                // Get the contents of the buffer and clean the buffer
                $starships_html = ob_get_clean(); 

                if ($display_position === 'before') {
                    $content = $starships_html . $content;
                } elseif ($display_position === 'after') {
                    $content .= $starships_html;
                }
            }
        }

        return $content;
    }

    // Enqueue CSS file when plugin is active
    public function enqueue_starships_css() {
        $selected_page_id = get_option('starships_selected_page');
        if (is_page($selected_page_id)) {
            wp_enqueue_style('starships-style', plugins_url('/css/starships.css', __FILE__), array(), STARSHIPS_VERSION);
        }
    }
}
