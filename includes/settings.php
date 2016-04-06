<?php
/**
 * Settings
 * @since 0.1.0
**/
/* Do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }


/**
 * Create Settings Page
 * @since 0.1.0
 */
class fx_Private_Site_Settings{

	/**
	 * Settings Slug
	 * @since 0.1.0
	 */
	public $settings_slug = 'reading';

	/**
	 * Options Group
	 * @since 0.1.0
	 */
	public $options_group = 'reading';

	/**
	 * Option Name
	 * @since 0.1.0
	 */
	public $option_name = 'fx-private-site';

	/**
	 * Construct
	 * @since 0.1.0
	 */
	public function __construct(){

		/* Load the Script needed for the settings screen. */
		add_action( 'admin_enqueue_scripts', array( $this, 'settings_scripts' ) );

		/* Register Settings and Fields */
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Settings Scripts
	 * @since 0.1.0
	 */
	public function settings_scripts( $hook_suffix ){
		if ( 'options-reading.php' == $hook_suffix ){
			wp_enqueue_style( 'fx-private-site-admin', FX_PRIVATE_SITE_URI . 'css/settings.css', array(), FX_PRIVATE_SITE_VERSION );
		}
	}

	/**
	 * Sanitize Options
	 * @since 0.1.0
	 */
	public function sanitize( $data ){

		/* New Data */
		$new_data = array();

		/* Enable/Disable Private Site Feature */
		$new_data['enable'] = isset( $data['enable'] ) ? true : false ;

		/* RSS Feed Error Message */
		$new_data['rss_error'] = fx_private_site_sanitize_rss_error( $data['rss_error'] );

		return $new_data;
	}

	/**
	 * Register Settings
	 * @since 0.1.0
	 */
	public function register_settings(){

		/* Register settings */
		register_setting(
			$this->options_group, // options group
			$this->option_name, // option name/database
			array( $this, 'sanitize' ) // sanitize callback function
		);

		/* Create settings section */
		add_settings_section(
			'fx_private_site_section', // section ID
			'<span id="fx-private-site">' . _x( 'Private Site', 'settings page', 'fx-private-site' ) . '</span>', // section title
			array( $this, 'settings_section' ), // section callback function
			$this->settings_slug // settings page slug
		);

		/* Field: Front Page Title */
		add_settings_field(
			'fx_private_site_enable', // field ID
			_x( 'Enable Private Site', 'settings page', 'fx-private-site' ), // field title 
			array( $this, 'settings_field_enable_private_site' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_private_site_section' // section ID
		);

		/* Field: Front Page Title */
		add_settings_field(
			'fx_private_site_rss_error', // field ID
			_x( 'RSS Feed Error Message', 'settings page', 'fx-private-site' ), // field title 
			array( $this, 'settings_field_rss_feed_error_msg' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_private_site_section' // section ID
		);

	}

	/**
	 * Settings Section
	 * @since 0.1.0
	 */
	public function settings_section(){
		echo wpautop( _x( 'Set site to private. Only registered user can view this site.', 'settings page', 'fx-private-site' ) );
	}

	/**
	 * Enable Private Site
	 * @since 0.1.0
	 */
	public function settings_field_enable_private_site(){
	?>
		<label for="fx_private_site_enable"><input type="checkbox" value="1" id="fx_private_site_enable" name="<?php echo esc_attr( $this->option_name . '[enable]' );?>" <?php checked( fx_private_site_get_option( 'enable', false ) ); ?>> <?php _ex( 'Redirect all logged-out users to the login page before allowing them to view the site. ', 'settings page', 'fx-private-site' );?></label>
	<?php
	}

	/**
	 * Enable Private Site
	 * @since 0.1.0
	 */
	public function settings_field_rss_feed_error_msg(){
	?>
		<textarea name="<?php echo esc_attr( $this->option_name . '[rss_error]' );?>" class="regular-text"><?php echo esc_textarea( fx_private_site_sanitize_rss_error( fx_private_site_get_option( 'rss_error', _x( 'You must be logged into the site to view this content.', 'default RSS Feed error message', 'fx-private-site' ) ) ) ); ?></textarea>
		<p class="description"><?php _ex( 'If site is set to private, this error message will replace RSS feed content.', 'settings page', 'fx-private-site' );?></p>
	<?php
	}

}
