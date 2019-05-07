<?php
/**
 * WooCommerce Cart Tab Customizer Class
 *
 * @author   jameskoster
 * @package  woocommerce-cart-tab
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce_Cart_Tab_Customizer' ) ) :

	/**
	 * Cart tab Customizer class
	 */
	class WooCommerce_Cart_Tab_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			if ( 'storefront' == get_option( 'template' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css_storefront' ), 9999 );
			} else {
				add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
				add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 9999 );
			}
		}

		/**
		 * Customizer controls / settings
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {

			/**
			 * Create defaults from existing options set using the old method
			 */
			$cart_tab_position_default = get_option( 'wc_ct_horizontal_position' );
			$cart_tab_width_buttons_default = '48';

			if ( $cart_tab_position_default ) {
				delete_option( 'wc_ct_horizontal_position' );
			} else {
				$cart_tab_position_default = 'right';
			}

			/**
			 * Sections
			 */
			if ( 'storefront' == get_option( 'template' ) ) {
				$wp_customize->add_section( 'woocommerce_cart_tab' , array(
					'title'    => __( 'Cart Tab', 'storefront' ),
					'priority' => 85,
				) );
			} else {
				$wp_customize->add_section( 'woocommerce_cart_tab' , array(
					'title'    => __( 'Settings panel Cart Tab', 'woocommerce-cart-tab' ),
					'priority' => 85,
				) );
			}

			/**
			 * Settings
			 */
			$wp_customize->add_setting( 'woocommerce_cart_tab_position' , array(
				'default'           => $cart_tab_position_default,
				'transport'         => 'refresh',
				'sanitize_callback' => 'woocommerce_cart_tab_sanitize_choices',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_width_buttons' , array(
				'default'           => $cart_tab_width_buttons_default,
				'transport'         => 'refresh',
				'sanitize_callback' => 'woocommerce_cart_tab_sanitize_choices',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_size_heading' , array(
				'default'           => '17',
				'transport'         => 'refresh',
				'sanitize_callback' => 'woocommerce_cart_tab_sanitize_choices',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_background', array(
				'default'           	=> '#fff',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_accent', array(
				'default'           	=> '#333',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_button_color', array(
				'default'           	=> '#96588a',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_text_button_color', array(
				'default'           	=> '#fff',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_text_color', array(
				'default'           	=> '#333',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'woocommerce_cart_tab_list_color', array(
				'default'           	=> '#5d96ad',
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			/**
			 * Controls
			 */
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'woocommerce_cart_tab_position', array(
				'label'    => __( 'Position', 'woocommerce-cart-tab' ),
				'section'  => 'woocommerce_cart_tab',
				'settings' => 'woocommerce_cart_tab_position',
				'type'     => 'select',
				'choices'  => array(
					'right' => __( 'Right', 'woocommerce-cart-tab' ),
					'left'  => __( 'Left', 'woocommerce-cart-tab' ),
				),
			) ) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'woocommerce_cart_tab_width_buttons', array(
				'label'    => __( 'Width buttons', 'woocommerce-cart-tab' ),
				'section'  => 'woocommerce_cart_tab',
				'settings' => 'woocommerce_cart_tab_width_buttons',
				'type'     => 'select',
				'choices'  => array(
					'48' => __( '50%', 'woocommerce-cart-tab' ),
					'100'  => __( '100%', 'woocommerce-cart-tab' ),
				),
			) ) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'woocommerce_cart_tab_size_heading', array(
				'label'    => __( 'Heading size panel Cart Tab', 'woocommerce-cart-tab' ),
				'section'  => 'woocommerce_cart_tab',
				'settings' => 'woocommerce_cart_tab_size_heading',
				'type'     => 'radio',
				'choices'  => array(
					'12' => __( 'Small', 'woocommerce-cart-tab' ),
					'17'  => __( 'Middle', 'woocommerce-cart-tab' ),
					'22'  => __( 'Big', 'woocommerce-cart-tab' ),
				),
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_background', array(
				'label'	   				=> __( 'Background color', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_background',
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_accent', array(
				'label'	   				=> __( 'Icon color', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_accent',
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_button_color', array(
				'label'	   				=> __( 'Buttons color', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_button_color',
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_text_button_color', array(
				'label'	   				=> __( 'Color text buttons', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_text_button_color',
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_text_color', array(
				'label'	   				=> __( 'Color content', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_text_color',
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'woocommerce_cart_tab_list_color', array(
				'label'	   				=> __( 'Color text list products', 'woocommerce-cart-tab' ),
				'section'  				=> 'woocommerce_cart_tab',
				'settings' 				=> 'woocommerce_cart_tab_list_color',
			) ) );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			$background = get_theme_mod( 'woocommerce_cart_tab_background', '#fff' );
			$accent     = get_theme_mod( 'woocommerce_cart_tab_accent', '#333' );
			$button_color     = get_theme_mod( 'woocommerce_cart_tab_button_color', '#96588a' );
			$text_button_color     = get_theme_mod( 'woocommerce_cart_tab_text_button_color', '#fff' );
			$width_buttons     = get_theme_mod( 'woocommerce_cart_tab_width_buttons', '48' );
			$size_heading     = get_theme_mod( 'woocommerce_cart_tab_size_heading', '17' );
			$color_content     = get_theme_mod( 'woocommerce_cart_tab_text_color', '#333' );
			$cart_list_color	=	get_theme_mod( 'woocommerce_cart_tab_list_color', '#5d96ad' );


			$styles                = '
			.woocommerce-cart-tab-container {
				background-color: ' . $this->woocommerce_cart_tab_adjust_color_brightness( $background, -7 ) . ';
			}

			.woocommerce-cart-tab,
			.woocommerce-cart-tab-container .widget_shopping_cart .widgettitle,
			.woocommerce-cart-tab-container .widget_shopping_cart .buttons {
				background-color: ' . $background . ';
			}

			.woocommerce-cart-tab,
			.woocommerce-cart-tab:hover {
				color: ' . $background . ';
			}

			.woocommerce-cart-tab__contents {
				background-color: ' . $accent . ';
			}

			.woocommerce-cart-tab__icon-bag {
				fill: ' . $accent . ';
			}

			.woocommerce-cart-tab-container .widget_shopping_cart .buttons .button {
				background-color: ' . $button_color . ';
				color: ' . $text_button_color . ';
				width: ' . $width_buttons . '%;
			}

			.woocommerce-mini-cart__total, .woocommerce-cart-tab-container .widget_shopping_cart .widgettitle {
				color: ' . $color_content . ';
			}

			.woocommerce-cart-tab-container .widget_shopping_cart .widgettitle {
				font-size: ' . $size_heading . 'px;
			}

			.woocommerce-mini-cart.cart_list.product_list_widget {
				color: ' . $cart_list_color . ';
			}';

			if ( $width_buttons == '100' ) {
				$styles                .= '
				.woocommerce-cart-tab-container .widget_shopping_cart .buttons .button:first-child {
					margin-bottom: 10px;
				}';
			}


			wp_add_inline_style( 'cart-tab-styles', $styles );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer - Storefront edition
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css_storefront() {
			$background        = get_theme_mod( 'storefront_header_background_color' );
			$header_link_color = get_theme_mod( 'storefront_header_link_color' );
			$header_text_color = get_theme_mod( 'storefront_header_text_color' );
			$button_background = get_theme_mod( 'storefront_button_alt_background_color' );
			$button_text       = get_theme_mod( 'storefront_button_alt_text_color' );

			$styles                = '
			.woocommerce-cart-tab-container {
				background-color: ' . $this->woocommerce_cart_tab_adjust_color_brightness( $background, 10 ) . ';
			}

			.woocommerce-cart-tab,
			.woocommerce-cart-tab-container .widget_shopping_cart .widgettitle,
			.woocommerce-cart-tab-container .widget_shopping_cart .buttons {
				background-color: ' . $this->woocommerce_cart_tab_adjust_color_brightness( $background, 20 ) . ';
			}

			.woocommerce-cart-tab,
			.woocommerce-cart-tab:hover {
				color: ' . $this->woocommerce_cart_tab_adjust_color_brightness( $background, 10 ) . ';
			}

			.woocommerce-cart-tab-container .widget_shopping_cart {
				color: ' . $header_text_color . ';
			}

			.woocommerce-cart-tab-container .widget_shopping_cart a:not(.button),
			.woocommerce-cart-tab-container .widget_shopping_cart .widgettitle {
				color: ' . $header_link_color . ';
			}

			.woocommerce-cart-tab__contents {
				background-color: ' . $button_background . ';
				color: ' . $button_text . ';
			}

			.woocommerce-cart-tab__icon-bag {
				fill: ' . $header_link_color . ';
			}';

			wp_add_inline_style( 'cart-tab-styles-storefront', $styles );
		}

		/**
		 * Adjust a hex color brightness
		 * Allows us to create hover styles for custom link colors
		 *
		 * @param  strong  $hex   hex color e.g. #111111.
		 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
		 * @return string        brightened/darkened hex color
		 * @since  1.0.0
		 */
		public function woocommerce_cart_tab_adjust_color_brightness( $hex, $steps ) {
			// Steps should be between -255 and 255. Negative = darker, positive = lighter.
			$steps  = max( -255, min( 255, $steps ) );

			// Format the hex color string.
			$hex    = str_replace( '#', '', $hex );

			if ( 3 == strlen( $hex ) ) {
				$hex    = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
			}

			// Get decimal values.
			$r  = hexdec( substr( $hex, 0, 2 ) );
			$g  = hexdec( substr( $hex, 2, 2 ) );
			$b  = hexdec( substr( $hex, 4, 2 ) );

			// Adjust number of steps and keep it inside 0 to 255.
			$r  = max( 0, min( 255, $r + $steps ) );
			$g  = max( 0, min( 255, $g + $steps ) );
			$b  = max( 0, min( 255, $b + $steps ) );

			$r_hex  = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
			$g_hex  = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
			$b_hex  = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

			return '#' . $r_hex . $g_hex . $b_hex;
		}
	}

endif;

return new WooCommerce_Cart_Tab_Customizer();
