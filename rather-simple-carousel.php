<?php
/**
 * Plugin Name: Rather Simple Carousel
 * Plugin URI:
 * Update URI: false
 * Version: 1.0
 * Requires at least: 6.3
 * Requires PHP: 7.4
 * Author: Oscar Ciutat
 * Author URI: http://oscarciutat.com/code/
 * Text Domain: rather-simple-carousel
 * Description: A really simple carousel
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package rather_simple_carousel
 */

/**
 * Core class used to implement the plugin.
 */
class Rather_Simple_Carousel {

	/**
	 * Plugin instance.
	 *
	 * @var object $instance
	 */
	protected static $instance = null;

	/**
	 * Access this plugin’s working instance
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Used for regular plugin work.
	 */
	public function plugin_setup() {

		$this->includes();

		add_action( 'init', array( $this, 'load_language' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_meta_fields' ) );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'save_post_carousel', array( $this, 'save_carousel' ) );
		add_action( 'media_buttons', array( $this, 'display_button' ) );
		add_filter( 'wp_editor_settings', array( $this, 'carousel_editor_settings' ) );

		// Columns.
		add_filter( 'manage_carousel_posts_columns', array( $this, 'carousel_columns' ) );
		add_action( 'manage_carousel_posts_custom_column', array( $this, 'carousel_custom_column' ), 5, 2 );

		// Enqueue the thickbox (required for button to work).
		add_action( 'admin_footer', array( $this, 'print_thickbox' ) );

		add_shortcode( 'carousel', array( $this, 'shortcode_carousel' ) );
	}

	/**
	 * Constructor. Intentionally left empty and public.
	 */
	public function __construct() {}

	/**
	 * Includes required core files used in admin and on the frontend.
	 */
	protected function includes() {}

	/**
	 * Loads language
	 */
	public function load_language() {
		load_plugin_textdomain( 'rather-simple-carousel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		// Enqueue styles.
		wp_enqueue_style(
			'rather-simple-carousel-css',
			plugins_url( '/style.css', __FILE__ ),
			array( 'dashicons' ),
			filemtime( plugin_dir_path( __FILE__ ) . '/style.css' )
		);
		// Enqueue scripts.
		wp_enqueue_script(
			'rather-simple-carousel-frontend',
			plugins_url( '/assets/js/frontend.js', __FILE__ ),
			array( 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/frontend.js' ),
			true
		);
	}

	/**
	 * Admin enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_style(
			'gallery-css',
			plugins_url( '/assets/css/carousel-gallery.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/css/carousel-gallery.css' )
		);
		wp_enqueue_script(
			'gallery-script',
			plugins_url( '/assets/js/carousel-gallery.js', __FILE__ ),
			array( 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/carousel-gallery.js' ),
			true
		);
	}

	/**
	 * Register post type
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Carousels', 'rather-simple-carousel' ),
			'singular_name'      => __( 'Carousel', 'rather-simple-carousel' ),
			'add_new'            => __( 'Add New Carousel', 'rather-simple-carousel' ),
			'add_new_item'       => __( 'Add New Carousel', 'rather-simple-carousel' ),
			'edit_item'          => __( 'Edit Carousel', 'rather-simple-carousel' ),
			'new_item'           => __( 'New Carousel', 'rather-simple-carousel' ),
			'view_item'          => __( 'View Carousel', 'rather-simple-carousel' ),
			'search_items'       => __( 'Search Carousels', 'rather-simple-carousel' ),
			'not_found'          => __( 'No Carousels found', 'rather-simple-carousel' ),
			'not_found_in_trash' => __( 'No Carousels found in Trash', 'rather-simple-carousel' ),
		);

		$args = array(
			'query_var'            => false,
			'rewrite'              => false,
			'public'               => true,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_in_nav_menus'    => false,
			'show_in_rest'         => true,
			'show_ui'              => true,
			'menu_position'        => 5,
			'menu_icon'            => 'dashicons-images-alt2',
			'supports'             => array( 'title', 'custom-fields' ),
			'labels'               => $labels,
			'register_meta_box_cb' => array( $this, 'add_carousel_meta_boxes' ),
		);

		register_post_type( 'carousel', $args );
	}

	/**
	 * Registers meta fields
	 */
	public function register_meta_fields() {
		// Register carousel meta fields.
		register_post_meta(
			'carousel',
			'_rsc_carousel_caption',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta(
			'carousel',
			'_rsc_carousel_max_height',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta(
			'carousel',
			'_rsc_carousel_items',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'array',
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Removes media buttons from carousel post type
	 *
	 * @param array $settings  An array of editor arguments.
	 */
	public function carousel_editor_settings( $settings ) {
		$current_screen = get_current_screen();

		// Post types for which the media buttons should be removed.
		$post_types = array( 'carousel' );

		// Bail out if media buttons should not be removed for the current post type.
		if ( ! $current_screen || ! in_array( $current_screen->post_type, $post_types, true ) ) {
			return $settings;
		}

		$settings['media_buttons'] = false;

		return $settings;
	}

	/**
	 * Add carousel meta boxes
	 */
	public function add_carousel_meta_boxes() {
		add_meta_box( 'carousel-shortcode', __( 'Shortcode', 'rather-simple-carousel' ), array( $this, 'carousel_shortcode_meta_box' ), 'carousel', 'side', 'low' );
		add_meta_box( 'carousel-dimensions', __( 'Dimensions', 'rather-simple-carousel' ), array( $this, 'carousel_dimensions_meta_box' ), 'carousel', 'side', 'low' );
		add_meta_box( 'carousel-items', __( 'Carousel items', 'rather-simple-carousel' ), array( $this, 'carousel_items_meta_box' ), 'carousel', 'normal', 'low' );
		add_meta_box( 'carousel-caption', __( 'Caption', 'rather-simple-carousel' ), array( $this, 'carousel_caption_meta_box' ), 'carousel', 'normal', 'low' );
	}

	/**
	 * Carousel shortcode meta box
	 */
	public function carousel_shortcode_meta_box() {
		global $post;
		$shortcode = '[carousel id="' . $post->ID . '"]';
		?>
		<div class="form-wrap">
		<div class="form-field">
		<label for="carousel_get_shortcode"><?php _e( 'Your Shortcode:', 'rather-simple-carousel' ); ?></label>
		<input readonly="true" id="carousel_get_shortcode" type="text" class="widefat" name="" value="<?php echo esc_attr( $shortcode ); ?>" />
		<p><?php _e( 'Copy and paste this shortcode into your Post, Page or Custom Post editor.', 'rather-simple-carousel' ); ?></p>
		</div>
		</div>
		<?php
	}

	/**
	 * Carousel dimensions meta box
	 */
	public function carousel_dimensions_meta_box() {
		global $post;
		$carousel_max_height = ( get_post_meta( $post->ID, '_rsc_carousel_max_height', true ) ) ? get_post_meta( $post->ID, '_rsc_carousel_max_height', true ) : '300';
		wp_nonce_field( basename( __FILE__ ), 'rsc_metabox_nonce' );
		?>
		<div class="form-wrap">
		<div class="form-field">
		<label for="carousel_max_height"><?php _e( 'Max Height:', 'rather-simple-carousel' ); ?></label>
		<input id="carousel_max_height" type="number" name="carousel_max_height" value="<?php echo esc_attr( $carousel_max_height ); ?>" min="100" max="600" />
		<p><?php _e( 'Max height in pixels.', 'rather-simple-carousel' ); ?></p>
		</div>
		</div>
		<?php
	}

	/**
	 * Carousel items meta box
	 */
	public function carousel_items_meta_box() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'rsc_metabox_nonce' );
		?>
		<div id="carousel_images_container">
			<ul class="carousel_images">
				<?php
				if ( metadata_exists( 'post', $post->ID, '_rsc_carousel_items' ) ) {
					$carousel_items = get_post_meta( $post->ID, '_rsc_carousel_items', true );
				} else {
					$args           = array(
						'post_parent' => $post->ID,
						'post_type'   => 'attachment',
						'numberposts' => -1,
						'orderby'     => 'menu_order',
						'order'       => 'ASC',
						'fields'      => 'ids',
					);
					$attachment_ids = get_posts( $args );
					$carousel_items = implode( ',', $attachment_ids );
				}

				$attachments         = array_filter( explode( ',', $carousel_items ) );
				$update_meta         = false;
				$updated_gallery_ids = array();

				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						if ( wp_attachment_is_image( $attachment_id ) ) {
							$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
							echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
                                    ' . $attachment . '
                                    <ul class="actions">
                                        <li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete item', 'rather-simple-carousel' ) . '">' . __( 'Delete', 'rather-simple-carousel' ) . '</a></li>
                                    </ul>
                                </li>';
							// Rebuild ids to be saved.
							$updated_gallery_ids[] = $attachment_id;
						}
					}

					// Need to update carousel meta to set new gallery ids.
					if ( $update_meta ) {
						update_post_meta( $post->ID, '_rsc_carousel_items', implode( ',', $updated_gallery_ids ) );
					}
				}
				?>
			</ul>

			<input type="hidden" id="carousel_items" name="carousel_items" value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>" />

		</div>
		<p class="add_carousel_images hide-if-no-js">
			<a href="#" data-choose="<?php esc_attr_e( 'Add items to carousel', 'rather-simple-carousel' ); ?>"><?php _e( 'Add carousel items', 'rather-simple-carousel' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Carousel caption meta box
	 */
	public function carousel_caption_meta_box() {
		global $post;
		$carousel_caption = ( get_post_meta( $post->ID, '_rsc_carousel_caption', true ) ) ? get_post_meta( $post->ID, '_rsc_carousel_caption', true ) : '';
		wp_nonce_field( basename( __FILE__ ), 'rsc_metabox_nonce' );
		?>
		<div class="form-wrap">
		<div class="form-field">
		<textarea id="carousel_caption" type="text" name="carousel_caption" rows="3"><?php echo esc_textarea( $carousel_caption ); ?></textarea>
		</div>
		</div>
		<?php
	}

	/**
	 * Save carousel
	 *
	 * @param integer $post_id  The post ID.
	 */
	public function save_carousel( $post_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['rsc_metabox_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['rsc_metabox_nonce'] ), basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Is autosave?
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check permissions.
		if ( isset( $_POST['post_type'] ) ) {
			if ( 'page' === $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		if ( isset( $_POST['post_type'] ) && ( 'carousel' === $_POST['post_type'] ) ) {

			$carousel_max_height = isset( $_POST['carousel_max_height'] ) ? sanitize_text_field( wp_unslash( $_POST['carousel_max_height'] ) ) : '300';
			update_post_meta( $post_id, '_rsc_carousel_max_height', $carousel_max_height );

			$attachment_ids = isset( $_POST['carousel_items'] ) ? array_filter( explode( ',', sanitize_text_field( wp_unslash( $_POST['carousel_items'] ) ) ) ) : array();
			update_post_meta( $post_id, '_rsc_carousel_items', implode( ',', $attachment_ids ) );

			$carousel_caption = isset( $_POST['carousel_caption'] ) ? sanitize_textarea_field( wp_unslash( $_POST['carousel_caption'] ) ) : '';
			update_post_meta( $post_id, '_rsc_carousel_caption', $carousel_caption );

		}
	}

	/**
	 * Shortcode carousel
	 *
	 * @param array $attr  The shortcode attributes.
	 */
	public function shortcode_carousel( $attr ) {
		$html = $this->shortcode_atts( $attr );
		return $html;
	}

	/**
	 * Shortcode attributes
	 *
	 * @param array $attr  The shortcode attributes.
	 */
	public function shortcode_atts( $attr ) {
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$attr,
			'carousel'
		);
		$id   = $atts['id'];
		$html = '';
		if ( 'carousel' === get_post_type( $id ) ) {
			$html = $this->carousel_markup( $id );
		}
		return $html;
	}

	/**
	 * Carousel markup
	 *
	 * @param integer $id  The carousel ID.
	 */
	public function carousel_markup( $id ) {
		$html = '';

		$carousel_max_height = ( get_post_meta( $id, '_rsc_carousel_max_height', true ) ) ? get_post_meta( $id, '_rsc_carousel_max_height', true ) : '300';
		$carousel_items      = get_post_meta( $id, '_rsc_carousel_items', true );
		$carousel_caption    = ( get_post_meta( $id, '_rsc_carousel_caption', true ) ) ? get_post_meta( $id, '_rsc_carousel_caption', true ) : '';

		$attachments = array_filter( explode( ',', $carousel_items ) );
		if ( ! empty( $attachments ) ) {

			$html .= '<div id="carousel-' . esc_attr( $id ) . '" class="carousel">
                    <div class="carousel-wrapper">
                    <div class="carousel-frame">
                    <div class="carousel-items">';

			foreach ( $attachments as $attachment_id ) {
				if ( wp_attachment_is_image( $attachment_id ) ) {
					$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
					$html      .= '<div class="carousel-item"><img src="' . $attachment[0] . '" style="max-height: ' . $carousel_max_height . 'px;" /></div>';
				}
			}

			$html .= '</div>
                    </div>
                    <div class="carousel-arrow left"><span class="icon"><svg version="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m17.5 5v14l-11-7z"/></svg></span></div>
                    <div class="carousel-arrow right"><span class="icon"><svg version="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m6.5 5v14l11-7z"/></svg></span></div>
                    </div>
                    <div class="carousel-caption wp-element-caption">' . wpautop( $carousel_caption ) . '</div>';

			if ( current_user_can( 'edit_post', $id ) ) {
				$html .= '<div class="carousel-edit-link"><a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . __( 'Edit' ) . '</a></div>';
			}

			$html .= '</div>';

		}

		return $html;
	}

	/**
	 * Displays the media button
	 *
	 * @param string $editor_id  Unique editor identifier, e.g. 'content'.
	 */
	public function display_button( $editor_id = 'content' ) {
		// Print the button's HTML and CSS.
		?>
			<style type="text/css">
				.wp-media-buttons .insert-carousel span.wp-media-buttons-icon {
					margin-top: -2px;
				}
				.wp-media-buttons .insert-carousel span.wp-media-buttons-icon:before {
					content: "\f233";
					font: 400 18px/1 dashicons;
					speak: none;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}
			</style>

			<a href="#TB_inline?width=480&amp;inlineId=select-carousel" class="button thickbox insert-carousel" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a Carousel', 'rather-simple-carousel' ); ?>">
				<span class="wp-media-buttons-icon dashicons dashicons-format-image"></span><?php _e( 'Add Carousel', 'rather-simple-carousel' ); ?>
			</a>
		<?php
	}

	/**
	 * Prints the thickbox for our media button
	 */
	public function print_thickbox() {
		?>
			<style type="text/css">
				#TB_window .section {
					padding: 15px 15px 0 15px;
				}
			</style>

			<script type="text/javascript">
				/**
				 * Sends a shortcode to the post/page editor
				 */
				function insertCarousel() {

					// Get the carousel ID
					var id = jQuery( '#carousel' ).val();

					// Display alert and bail if no slideshow was selected
					if ( '-1' === id) {
						return alert("<?php _e( 'Please select a carousel', 'rather-simple-carousel' ); ?>");
					}

					// Send shortcode to editor
					send_to_editor( '[<?php echo esc_attr( 'carousel' ); ?> id=\"'+ id +'\"]' );

					// Close thickbox
					tb_remove();

				}
			</script>

			<div id="select-carousel" style="display: none;">
				<div class="section">
					<h2><?php _e( 'Add a carousel', 'rather-simple-carousel' ); ?></h2>
					<span><?php _e( 'Select a carousel to insert from the dropdown below:', 'rather-simple-carousel' ); ?></span>
				</div>

				<div class="section">
					<select name="carousel" id="carousel">
						<option value="-1"><?php _e( 'Select a carousel', 'rather-simple-carousel' ); ?></option>
						<?php
							$args      = array(
								'post_type'   => 'carousel',
								'numberposts' => -1,
								'orderby'     => 'title',
								'order'       => 'ASC',
							);
							$carousels = get_posts( $args );
							?>
						<?php foreach ( $carousels as $carousel ) : ?>
							<option value="<?php echo esc_attr( $carousel->ID ); ?>"><?php echo esc_html( sprintf( '%s (ID #%d)', $carousel->post_title, $carousel->ID ) ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="section">
					<button id="insert-carousel" class="button-primary" onClick="insertCarousel();"><?php _e( 'Insert Carousel', 'rather-simple-carousel' ); ?></button>
					<button id="close-carousel-thickbox" class="button-secondary" style="margin-left: 5px;" onClick="tb_remove();"><?php _e( 'Close', 'rather-simple-carousel' ); ?></a>
				</div>
			</div>
		<?php
	}

	/**
	 * Carousel columns
	 *
	 * @param array $columns An associative array of column headings.
	 */
	public function carousel_columns( $columns ) {
		$new = array();
		foreach ( $columns as $key => $value ) {
			if ( 'date' === $key ) {
				// Put the Shortcode column before the Date column.
				$new['shortcode'] = __( 'Shortcode', 'rather-simple-carousel' );
			}
			$new[ $key ] = $value;
		}
		return $new;
	}

	/**
	 * Carousel custom column
	 *
	 * @param string  $column   The name of the column to display.
	 * @param integer $post_id  The carousel ID.
	 */
	public function carousel_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				$shortcode = sprintf( esc_html( '[carousel id="%d"]' ), $post_id );
				echo $shortcode;
				break;
		}
	}

}

add_action( 'plugins_loaded', array( Rather_Simple_Carousel::get_instance(), 'plugin_setup' ) );
