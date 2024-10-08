<?php
/**
 * Plugin Name:       My Block Plugin
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-block-plugin
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_my_block_plugin_block_init() {
    // Iterate through block-1 to block-5
    for ( $i = 1; $i <= 5; $i++ ) {
        $block_folder = __DIR__ . '/build/block-' . $i;

        if ( file_exists( $block_folder ) ) {
            register_block_type( $block_folder );

            // Enqueue block editor styles only
            add_action( 'enqueue_block_editor_assets', function() use ( $i ) {
                wp_enqueue_style(
                    'my-block-plugin-style-' . $i,
                    get_template_directory_uri() . '/plugin-blocks/build/block-' . $i . '/index.css',
                    array(),
                    filemtime( get_template_directory() . '/plugin-blocks/build/block-' . $i . '/index.css' ) // Cache busting
                );
            });
        }
    }
}
add_action( 'init', 'create_block_my_block_plugin_block_init' );