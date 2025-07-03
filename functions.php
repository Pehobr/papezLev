<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Načtení souboru s logikou pro boční menu
require_once( get_stylesheet_directory() . '/bocni-menu.php' );

// Načtení souboru s logikou pro kopírování příspěvků
require_once( get_stylesheet_directory() . '/kopirovani-prispevku.php' );


// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'font-awesome' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

/**
 * Načte styly a skripty pro child šablonu.
 */
function moje_theme_assets() {
    // 1. CSS pro boční menu
    wp_enqueue_style(
        'moje-bocni-menu-style',
        get_stylesheet_directory_uri() . '/style/bocni-menu.css',
        array(),
        filemtime(get_stylesheet_directory() . '/style/bocni-menu.css')
    );

    // 2. JavaScript pro boční menu
    wp_enqueue_script(
        'moje-bocni-menu-script',
        get_stylesheet_directory_uri() . '/js/bocni-menu.js',
        array(),
        filemtime(get_stylesheet_directory() . '/js/bocni-menu.js'),
        true
    );

    // 3. Styly a skripty pro kopírování - načítají se pouze na stránce příspěvku
    if ( is_single() ) {
        // CSS pro ikonu kopírování
        wp_enqueue_style(
            'moje-kopirovani-style',
            get_stylesheet_directory_uri() . '/style/kopirovani-prispevku.css', // ZMĚNA ZDE
            array(),
            filemtime(get_stylesheet_directory() . '/style/kopirovani-prispevku.css') // ZMĚNA ZDE
        );

        // Knihovna Font Awesome pro ikony
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
            [],
            '4.7.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'moje_theme_assets' );

?>