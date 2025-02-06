<?php
/**
 * Plugin Name: Roofing Design Tool
 * Description: A tool for roofing contractors that lets users design their roofs by selecting a style and material. The design summary and a contact form (via a shortcode) are then displayed on a separate page.
 * Version: 1.0
 * Author: Hans Steffens
 * Text Domain: roofing-design-tool
 * License: GPL2
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin directory and URL constants.
if ( ! defined( 'RDT_PLUGIN_DIR' ) ) {
	define( 'RDT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'RDT_PLUGIN_URL' ) ) {
	define( 'RDT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* ==========================================================================
   1. Register Custom Post Types (Roofing Styles & Roofing Materials)
   ========================================================================== */
function rdt_register_post_types() {
	// Roofing Styles
	$labels = array(
		'name'               => _x( 'Roofing Styles', 'Post Type General Name', 'roofing-design-tool' ),
		'singular_name'      => _x( 'Roofing Style', 'Post Type Singular Name', 'roofing-design-tool' ),
		'menu_name'          => __( 'Roofing Styles', 'roofing-design-tool' ),
		'name_admin_bar'     => __( 'Roofing Style', 'roofing-design-tool' ),
		'all_items'          => __( 'All Roofing Styles', 'roofing-design-tool' ),
		'add_new_item'       => __( 'Add New Roofing Style', 'roofing-design-tool' ),
		'edit_item'          => __( 'Edit Roofing Style', 'roofing-design-tool' ),
		'new_item'           => __( 'New Roofing Style', 'roofing-design-tool' ),
		'view_item'          => __( 'View Roofing Style', 'roofing-design-tool' ),
		'search_items'       => __( 'Search Roofing Styles', 'roofing-design-tool' ),
	);
	$args = array(
		'label'           => __( 'Roofing Style', 'roofing-design-tool' ),
		'description'     => __( 'Roofing Style Post Type', 'roofing-design-tool' ),
		'labels'          => $labels,
		'supports'        => array( 'title', 'editor', 'thumbnail' ),
		'public'          => true,
		'show_in_menu'    => true,
		'menu_position'   => 5,
		'menu_icon'       => 'dashicons-admin-customizer',
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'roofing-style' ),
		'show_in_rest'    => true,
	);
	register_post_type( 'roofing_style', $args );

	// Roofing Materials
	$labels = array(
		'name'               => _x( 'Roofing Materials', 'Post Type General Name', 'roofing-design-tool' ),
		'singular_name'      => _x( 'Roofing Material', 'Post Type Singular Name', 'roofing-design-tool' ),
		'menu_name'          => __( 'Roofing Materials', 'roofing-design-tool' ),
		'name_admin_bar'     => __( 'Roofing Material', 'roofing-design-tool' ),
		'all_items'          => __( 'All Roofing Materials', 'roofing-design-tool' ),
		'add_new_item'       => __( 'Add New Roofing Material', 'roofing-design-tool' ),
		'edit_item'          => __( 'Edit Roofing Material', 'roofing-design-tool' ),
		'new_item'           => __( 'New Roofing Material', 'roofing-design-tool' ),
		'view_item'          => __( 'View Roofing Material', 'roofing-design-tool' ),
		'search_items'       => __( 'Search Roofing Materials', 'roofing-design-tool' ),
	);
	$args = array(
		'label'           => __( 'Roofing Material', 'roofing-design-tool' ),
		'description'     => __( 'Roofing Material Post Type', 'roofing-design-tool' ),
		'labels'          => $labels,
		'supports'        => array( 'title', 'editor', 'thumbnail' ),
		'public'          => true,
		'show_in_menu'    => true,
		'menu_position'   => 6,
		'menu_icon'       => 'dashicons-admin-page',
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'roofing-material' ),
		'show_in_rest'    => true,
	);
	register_post_type( 'roofing_material', $args );
}
add_action( 'init', 'rdt_register_post_types' );

/* ==========================================================================
   2. Enqueue Front-end Scripts and Styles & Pass Settings to JS
   ========================================================================== */
function rdt_enqueue_assets() {
	if ( ! is_admin() ) {
		wp_enqueue_style( 'rdt-style', RDT_PLUGIN_URL . 'assets/css/roof-design.css', array(), '1.0' );
		wp_enqueue_script( 'rdt-script', RDT_PLUGIN_URL . 'assets/js/roof-design.js', array( 'jquery' ), '1.1', true );

		// Get settings options.
		$design_form_page_id = get_option( 'rdt_design_form_page', '' );
		$design_form_url     = $design_form_page_id ? get_permalink( $design_form_page_id ) : '';
		$hidden_field_style  = get_option( 'rdt_hidden_field_style', 'roof_style' );
		$hidden_field_material = get_option( 'rdt_hidden_field_material', 'roof_material' );

		// Pass settings to JavaScript.
		wp_localize_script( 'rdt-script', 'rdt_vars', array(
			'designFormUrl'     => esc_url( $design_form_url ),
			'hiddenStyleKey'    => esc_attr( $hidden_field_style ),
			'hiddenMaterialKey' => esc_attr( $hidden_field_material ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'rdt_enqueue_assets' );

/* ==========================================================================
   3. Shortcode: [roof_design_tool] – Displays the Interactive Design Tool
   ========================================================================== */
function rdt_display_design_tool() {
	ob_start();
	?>
	<div class="rdt-container">
		<h2><?php esc_html_e( 'Select Roof Style:', 'roofing-design-tool' ); ?></h2>
		<div class="rdt-styles">
			<?php
			$args = array(
				'post_type'      => 'roofing_style',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);
			$styles = new WP_Query( $args );
			if ( $styles->have_posts() ) :
				while ( $styles->have_posts() ) : $styles->the_post();
					$style_title   = get_the_title();
					$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					?>
					<div class="rdt-card" data-style="<?php echo esc_attr( $style_title ); ?>">
						<?php if ( $thumbnail_url ) : ?>
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $style_title ); ?>">
						<?php endif; ?>
						<p><?php echo esc_html( $style_title ); ?></p>
						<button type="button" class="rdt-select-style"><?php esc_html_e( 'Choose The Roof Style', 'roofing-design-tool' ); ?></button>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p>' . esc_html__( 'No roofing styles found.', 'roofing-design-tool' ) . '</p>';
			endif;
			?>
		</div>

		<h2><?php esc_html_e( 'Select Roof Material:', 'roofing-design-tool' ); ?></h2>
		<div class="rdt-materials">
			<?php
			$args = array(
				'post_type'      => 'roofing_material',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);
			$materials = new WP_Query( $args );
			if ( $materials->have_posts() ) :
				while ( $materials->have_posts() ) : $materials->the_post();
					$material_title   = get_the_title();
					$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					?>
					<div class="rdt-card" data-material="<?php echo esc_attr( $material_title ); ?>">
						<?php if ( $thumbnail_url ) : ?>
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $material_title ); ?>">
						<?php endif; ?>
						<p><?php echo esc_html( $material_title ); ?></p>
						<button type="button" class="rdt-select-material"><?php esc_html_e( 'Choose The Roof Material', 'roofing-design-tool' ); ?></button>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p>' . esc_html__( 'No roofing materials found.', 'roofing-design-tool' ) . '</p>';
			endif;
			?>
		</div>

		<div class="rdt-summary">
			<p><?php esc_html_e( 'Selected Style:', 'roofing-design-tool' ); ?> <span id="rdt-selected-style"></span></p>
			<p><?php esc_html_e( 'Selected Material:', 'roofing-design-tool' ); ?> <span id="rdt-selected-material"></span></p>
			<button type="button" id="rdt-submit-design"><?php esc_html_e( 'Submit Design', 'roofing-design-tool' ); ?></button>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'roof_design_tool', 'rdt_display_design_tool' );

/* ==========================================================================
   4. Shortcode: [roof_design_form] – Displays the Design Summary and the Contact Form
   ========================================================================== */
function rdt_display_design_form() {
	ob_start();

	// Get the hidden field names from settings.
	$hidden_field_style   = get_option( 'rdt_hidden_field_style', 'roof_style' );
	$hidden_field_material = get_option( 'rdt_hidden_field_material', 'roof_material' );

	// Get and sanitize URL parameters using the keys defined in settings.
	$selected_style    = isset( $_GET[ $hidden_field_style ] ) ? sanitize_text_field( wp_unslash( $_GET[ $hidden_field_style ] ) ) : '';
	$selected_material = isset( $_GET[ $hidden_field_material ] ) ? sanitize_text_field( wp_unslash( $_GET[ $hidden_field_material ] ) ) : '';
	?>
	<div class="rdt-form-container">
		<h2><?php esc_html_e( 'Your Roof Design Summary', 'roofing-design-tool' ); ?></h2>
		<div class="rdt-design-summary">
			<p><?php esc_html_e( 'Roof Style:', 'roofing-design-tool' ); ?> <strong><?php echo esc_html( $selected_style ); ?></strong></p>
			<p><?php esc_html_e( 'Roof Material:', 'roofing-design-tool' ); ?> <strong><?php echo esc_html( $selected_material ); ?></strong></p>
		</div>
		<div class="rdt-contact-form">
			<?php
			// Get the form shortcode from the plugin settings.
			$form_shortcode = get_option( 'rdt_form_shortcode', '' );
			if ( ! empty( $form_shortcode ) ) {
				echo do_shortcode( $form_shortcode );
			} else {
				echo '<p>' . esc_html__( 'No form shortcode has been set in the plugin settings.', 'roofing-design-tool' ) . '</p>';
			}
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'roof_design_form', 'rdt_display_design_form' );

/* ==========================================================================
   5. Plugin Settings Page – Form Shortcode, Design Form Page & Hidden Field Names
   ========================================================================== */
function rdt_add_settings_page() {
	add_options_page(
		__( 'Roof Design Tool Settings', 'roofing-design-tool' ),
		__( 'Roof Design Tool', 'roofing-design-tool' ),
		'manage_options',
		'rdt-settings',
		'rdt_render_settings_page'
	);
}
add_action( 'admin_menu', 'rdt_add_settings_page' );

function rdt_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Process form submission.
	if ( isset( $_POST['rdt_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdt_settings_nonce'] ) ), 'rdt_settings_save' ) ) {
		if ( isset( $_POST['rdt_form_shortcode'] ) ) {
			$form_shortcode = sanitize_text_field( wp_unslash( $_POST['rdt_form_shortcode'] ) );
			update_option( 'rdt_form_shortcode', $form_shortcode );
		}
		if ( isset( $_POST['rdt_design_form_page'] ) ) {
			$design_form_page = absint( $_POST['rdt_design_form_page'] );
			update_option( 'rdt_design_form_page', $design_form_page );
		}
		if ( isset( $_POST['rdt_hidden_field_style'] ) ) {
			$hidden_field_style = sanitize_text_field( wp_unslash( $_POST['rdt_hidden_field_style'] ) );
			update_option( 'rdt_hidden_field_style', $hidden_field_style );
		}
		if ( isset( $_POST['rdt_hidden_field_material'] ) ) {
			$hidden_field_material = sanitize_text_field( wp_unslash( $_POST['rdt_hidden_field_material'] ) );
			update_option( 'rdt_hidden_field_material', $hidden_field_material );
		}
		echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'roofing-design-tool' ) . '</p></div>';
	}

	$current_shortcode          = get_option( 'rdt_form_shortcode', '' );
	$current_design_form_page   = get_option( 'rdt_design_form_page', '' );
	$current_hidden_field_style = get_option( 'rdt_hidden_field_style', 'roof_style' );
	$current_hidden_field_material = get_option( 'rdt_hidden_field_material', 'roof_material' );

	// Get published pages for a dropdown.
	$pages = get_pages( array(
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
	) );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Roof Design Tool Settings', 'roofing-design-tool' ); ?></h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'rdt_settings_save', 'rdt_settings_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="rdt_form_shortcode"><?php esc_html_e( 'Contact Form Shortcode', 'roofing-design-tool' ); ?></label>
					</th>
					<td>
						<input name="rdt_form_shortcode" type="text" id="rdt_form_shortcode" value="<?php echo esc_attr( $current_shortcode ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'Enter the form shortcode from Fluent Forms Pro (or any other form plugin).', 'roofing-design-tool' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="rdt_design_form_page"><?php esc_html_e( 'Design Form Page', 'roofing-design-tool' ); ?></label>
					</th>
					<td>
						<select name="rdt_design_form_page" id="rdt_design_form_page">
							<option value=""><?php esc_html_e( '-- Select Page --', 'roofing-design-tool' ); ?></option>
							<?php
							foreach ( $pages as $page ) {
								printf(
									'<option value="%d" %s>%s</option>',
									absint( $page->ID ),
									selected( $current_design_form_page, $page->ID, false ),
									esc_html( $page->post_title )
								);
							}
							?>
						</select>
						<p class="description"><?php esc_html_e( 'Select the page that contains the [roof_design_form] shortcode.', 'roofing-design-tool' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="rdt_hidden_field_style"><?php esc_html_e( 'Hidden Field Name for Roof Style', 'roofing-design-tool' ); ?></label>
					</th>
					<td>
						<input name="rdt_hidden_field_style" type="text" id="rdt_hidden_field_style" value="<?php echo esc_attr( $current_hidden_field_style ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'Enter the hidden field name that will be used for the roof style value (this must match your Fluent Forms hidden field).', 'roofing-design-tool' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="rdt_hidden_field_material"><?php esc_html_e( 'Hidden Field Name for Roof Material', 'roofing-design-tool' ); ?></label>
					</th>
					<td>
						<input name="rdt_hidden_field_material" type="text" id="rdt_hidden_field_material" value="<?php echo esc_attr( $current_hidden_field_material ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'Enter the hidden field name that will be used for the roof material value (this must match your Fluent Forms hidden field).', 'roofing-design-tool' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/* ==========================================================================
   6. Flush Rewrite Rules on Activation/Deactivation
   ========================================================================== */
function rdt_activate_plugin() {
	rdt_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'rdt_activate_plugin' );

function rdt_deactivate_plugin() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'rdt_deactivate_plugin' );
