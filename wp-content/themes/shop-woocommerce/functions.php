<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )exit;


function shop_woocommerce_settings( $values ) {
  $values[ 'primary_color' ] = '#000';
  $values[ 'secondary_color' ] = '#1f5ac1';
  $values[ 'heading_font' ] = 'Poppins';
  $values[ 'body_font' ] = 'Poppins';

  $values[ 'woo_bar_color' ] = '#000000';
  $values[ 'woo_bar_bg_color' ] = '#D4D8DD00';

  $values[ 'preloader_enabled' ] = false;

  $values[ 'logo_width' ] = 130;
  $values[ 'layout_width' ] = 1280;

  $values[ 'header_layout' ] = 'woocommerce-bar';
  $values[ 'enable_search' ] = true;
  $values[ 'ed_social_links' ] = true;

  $values[ 'subscription_shortcode' ] = '';

  $values[ 'enable_top_bar' ] = true;
  $values[ 'top_bar_left_content' ] = 'contacts';
  $values[ 'top_bar_left_text' ] = esc_html__( 'edit top bar text', 'shop-woocommerce' );
  $values[ 'top_bar_right_content' ] = 'menu_social';

  $values[ 'footer_text_color' ] = '#eee';
  $values[ 'footer_color' ] = '#1c1c1c';
  $values[ 'footer_link' ] = 'https://gradientthemes.com/';

  $values[ 'page_sidebar_layout' ] = 'right-sidebar';
  $values[ 'post_sidebar_layout' ] = 'right-sidebar';
  $values[ 'layout_style' ] = 'right-sidebar';
  $values[ 'woo_sidebar_layout' ] = 'left-sidebar';

  return $values;

}


add_filter( 'best_shop_settings', 'shop_woocommerce_settings' );


//  PARENT ACTION

if ( !function_exists( 'shop_woocommerce_cfg_locale_css' ) ):
  function shop_woocommerce_cfg_locale_css( $uri ) {
    if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
      $uri = get_template_directory_uri() . '/rtl.css';
    return $uri;
  }
endif;

add_filter( 'locale_stylesheet_uri', 'shop_woocommerce_cfg_locale_css' );

if ( !function_exists( 'shop_woocommerce_cfg_parent_css' ) ):
  function shop_woocommerce_cfg_parent_css() {
    wp_enqueue_style( 'shop_woocommerce_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array() );
  }
endif;

add_action( 'wp_enqueue_scripts', 'shop_woocommerce_cfg_parent_css', 10 );

// Add prealoder js
function shop_woocommerce_custom_scripts() {
  wp_enqueue_script( 'shop-woocommerce', get_stylesheet_directory_uri() . '/assests/preloader.js', array( 'jquery' ), '', true );
}

add_action( 'wp_enqueue_scripts', 'shop_woocommerce_custom_scripts' );

// END ENQUEUE PARENT ACTION

if ( !function_exists( 'shop_woocommerce_customize_register' ) ):
  /**
   * Add postMessage support for site title and description for the Theme Customizer.
   *
   * @param WP_Customize_Manager $wp_customize Theme Customizer object.
   */
  function shop_woocommerce_customize_register( $wp_customize ) {

    $wp_customize->add_section(
      'subscription_settings',
      array(
        'title' => esc_html__( 'Email Subscription', 'shop-woocommerce' ),
        'priority' => 199,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_options',
        'description' => __( 'Add email subscription plugin shortcode.', 'shop-woocommerce' ),

      )
    );

    /** Footer Copyright */
    $wp_customize->add_setting(
      'subscription_shortcode',
      array(
        'default' => best_shop_default_settings( 'subscription_shortcode' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
      )
    );

    $wp_customize->add_control(
      'subscription_shortcode',
      array(
        'label' => esc_html__( 'Subscription Plugin Shortcode', 'shop-woocommerce' ),
        'section' => 'subscription_settings',
        'type' => 'text',
      )
    );

    //preloader
    $wp_customize->add_section(
      'preloader_settings',
      array(
        'title' => esc_html__( 'Preloader', 'shop-woocommerce' ),
        'priority' => 200,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_options',

      )
    );

    $wp_customize->add_setting(
      'preloader_enabled',
      array(
        'default' => best_shop_default_settings( 'preloader_enabled' ),
        'sanitize_callback' => 'best_shop_sanitize_checkbox',
        'transport' => 'refresh'
      )
    );

    $wp_customize->add_control(
      'preloader_enabled',
      array(
        'label' => esc_html__( 'Enable Preloader', 'shop-woocommerce' ),
        'section' => 'preloader_settings',
        'type' => 'checkbox',
      )
    );


  }
endif;
add_action( 'customize_register', 'shop_woocommerce_customize_register' );


/*
 * Add default header image
 */

function shop_woocommerce_header_style() {
  add_theme_support(
    'custom-header',
    apply_filters(
      'shop_woocommerce_custom_header_args',
      array(
        'default-text-color' => '#000000',
        'width' => 1920,
        'height' => 760,
        'flex-height' => true,
        'video' => true,
        'wp-head-callback' => 'shop_woocommerce_header_style',
      )
    )
  );
  add_theme_support( 'automatic-feed-links' );
}

add_action( 'after_setup_theme', 'shop_woocommerce_header_style' );

