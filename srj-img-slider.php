<?php
/*
 * Plugin Name: Image Slider
 * Description: Image Slider is an Slider of images uploaded in its settings page.
 * Author: Suraj
 * Version: 0.1
 */

/*
 * Initial Actions
 */
add_action( 'admin_menu', 'slideshow_admin_end' );
register_activation_hook( __FILE__, 'slideshow_set_defaults' );
register_deactivation_hook( __FILE__, 'slideshow_reset_defaults' );

/*
 * Settings Page
 */
function slideshow_admin_end() {
    //Add settings page
    $hook = add_options_page( 'Slideshow page', 'Slideshow options', 'manage_options', 'slideshow-options', 'slideshow_admin_fn' );
    
    //Enqueue CSS and JS for the options page
    add_action('admin_print_scripts-'.$hook, 'slideshow_assets');
}

function slideshow_admin_fn() { ?>
    <div class="wrap">
        <h2><?php _e( 'Slideshow Options' ); ?></h2>
        <p class="clear"></p>
        <div id="content_block" class="align_left">
            <form action="options.php" method="post" ><?php
                settings_fields( 'slideshow_plugin_options' );
                do_settings_sections( __FILE__ );
                $slideshow_plugin_options = get_option( 'slideshow_plugin_options' ); ?>
                <div class="metabox-holder align_left" id="">
                    <div class="postbox-container">
                        <div class="meta-box-sortables">
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br /></div>
                                <h3 class="hndle">Slideshow Settings</h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tbody id="sortable">
                                            <?php 
                                            if (isset($slideshow_plugin_options['upload_image_id'])) {
                                                for ($i=0;$i<count($slideshow_plugin_options['upload_image_id']);$i++) { ?>
                                                    <tr class="clone-div">
                                                        <td>
                                                            <input type='button' class="upload_image_button" value='upload' /><br/><br/>
                                                            <?php $image_src = wp_get_attachment_image_src($slideshow_plugin_options['upload_image_id'][$i]); ?>
                                                            <input type="button" class="clone-btn" value="Clone" onclick="makeClone();" /><br/>
                                                            <input type='button' id="remove-btn" class="remove-btn" value='remove' <?php echo ($i==0)? 'style="display: none;"' : ''; ?> />
                                                        </td>
                                                        <td>
                                                            <input type='hidden' name="slideshow_plugin_options[upload_image_id][]" class="upload_image_id" value='<?php echo $slideshow_plugin_options['upload_image_id'][$i]; ?>' />
                                                            <img id="upload_image" width="300" height="180" src="<?php echo $image_src[0]; ?>" class="upload_image_src" name='upload' />
                                                        </td>
                                                    </tr>
                                                <?php } 
                                            } else { ?>
                                                <tr class="clone-div">
                                                    <td>
                                                        <input type='button' class="upload_image_button" value='upload' /><br/><br/>
                                                        <input type="button" class="clone-btn" value="Clone" onclick="makeClone();" /><br/>
                                                        <input type='button' id="remove-btn" class="remove-btn" value='remove' style="display: none;" />
                                                    </td>
                                                    <td>
                                                        <input type='hidden' name="slideshow_plugin_options[upload_image_id][]" class="upload_image_id" value='' />
                                                        <img id="upload_image" src="" width="300" height="180" name='upload' />
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <p class="submit"><input type="submit" name="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
}

/*
 * Add the options variable
 */
add_action( 'admin_init', 'slideswhow_options_init_fn' );
function slideswhow_options_init_fn() {
    register_setting( 'slideshow_plugin_options', 'slideshow_plugin_options' );
}

/*
 * Slide show output on front-end
 */
add_shortcode('slideshow_plugin', 'slideswhow_feeds');
function slideswhow_feeds() {
    $result = '';
    $slideshow_plugin_options = get_option ( 'slideshow_plugin_options' );
    $result = '<div class="slider-wrapper theme-default">';
    $result .= '<div id="slider" class="nivoSlider">';

    //the loop  
    if(isset($slideshow_plugin_options['upload_image_id']) && !empty($slideshow_plugin_options['upload_image_id'])) {
        for($i=0;$i<count($slideshow_plugin_options['upload_image_id']);$i++) {
            $the_url = wp_get_attachment_image_src($slideshow_plugin_options['upload_image_id'][$i], 'full');
            $result .='<img title="' . get_the_title() . '" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/>';
        }
    }
    $result .= '</div>';
    $result .='</div>';
    $result .='<script>';
    $result .='jQuery("#slider").nivoSlider();';
    $result .='</script>';
    return $result;
}

/*
 * Function for setting default values
 */
function slideshow_set_defaults() {
    $defaults = array(
        'upload_image_id'   => null,
    );
    if ( !get_option( 'slideshow_plugin_options' ) ) {
        update_option( 'slideshow_plugin_options', $defaults);
    }
}

/*
 * Delete plugin options
 */
function slideshow_reset_defaults() {
    delete_option( 'slideshow_plugin_options' );
}

/*
 * Enqueue scripts and styles
 */
//The similar action for the admin page is on line no.26 above!
add_action('wp_enqueue_scripts', 'slideshow_assets');
function slideshow_assets() {
    //Dashboard JS and CSS for admin side only
    if(is_admin()){
        wp_enqueue_script( 'dashboard' );
        wp_enqueue_style( 'dashboard' );
        wp_register_script('np_script', plugins_url('js/script.js', __FILE__));   
        wp_enqueue_media('media-uploader');
        wp_enqueue_script('np_script');
        }

    //Plugin CSS
        wp_enqueue_style( 'np-styleSheet', plugins_url('styles/nivo-slider.css', __FILE__));
    wp_enqueue_style( 'np-theme-styles', plugins_url('styles/default.css', __FILE__));
    wp_enqueue_style( 'custom-styleSheet', plugins_url('styles/style.css', __FILE__));
    //Register Plugin JS
    wp_register_script('np_nivo-script', plugins_url('js/jquery.nivo.slider.pack.js', __FILE__), array('jquery'), true);
   

    // enqueue
    wp_enqueue_script('np_nivo-script');
    
    wp_enqueue_script('jquery-ui');
    
}

/*
 * Place in Option List on Settings -> Plugins page
 */
add_filter( 'plugin_action_links', 'slideshow_actlinks', 10, 2 );
function slideshow_actlinks( $links, $file ) {
    static $this_plugin;
    if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
    }
    if ( $file == $this_plugin ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=slideshow-options' ) . '">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link ); // before other links
    }
    return $links;
}