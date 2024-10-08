<?php

// Add possibility of svg upload
function add_file_types_to_uploads($file_types) {
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');
// Add possibility of svg upload

// Add theme support
function template_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('block-templates');
    add_theme_support('post-thumbnails');
	add_theme_support('custom-logo');
}
add_action('after_setup_theme','template_theme_support');
// Add theme support

//Remove all comments
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
//Remove all comments

// Register styles - all
function template_register_styles() {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style('template-style', get_template_directory_uri() . "/style.css", array(), $version, 'all');
}
add_action('wp_enqueue_scripts', 'template_register_styles');
// Register styles - all

// Register scripts - all
function template_register_scripts(){
    $jsversion = wp_get_theme()->get( 'Version' );
    wp_enqueue_script('opacity-script', get_template_directory_uri() . "/assets/js/opacity.js", array(), $jsversion, false);
}
add_action('wp_enqueue_scripts', 'template_register_scripts');
// Register scripts - all

// Duplication
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
add_filter( 'page_row_actions', 'rd_duplicate_post_link', 10, 2 );
function rd_duplicate_post_link( $actions, $post ) {
	if( ! current_user_can( 'edit_posts' ) ) {
		return $actions;
	}

	$url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'rd_duplicate_post_as_draft',
				'post' => $post->ID,
			),
			'admin.php'
		),
		basename(__FILE__),
		'duplicate_nonce'
	);

	$actions[ 'duplicate' ] = '<a href="' . $url . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	return $actions;
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

function rd_duplicate_post_as_draft(){
	if ( empty( $_GET[ 'post' ] ) ) {
		wp_die( 'No post to duplicate has been provided!' );
	}
	if ( ! isset( $_GET[ 'duplicate_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'duplicate_nonce' ], basename( __FILE__ ) ) ) {
		return;
	}

	$post_id = absint( $_GET[ 'post' ] );
	$post = get_post( $post_id );
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	if ( $post ) {
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		$new_post_id = wp_insert_post( $args );
		$taxonomies = get_object_taxonomies( get_post_type( $post ) );

		if( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}
		}

		$post_meta = get_post_meta( $post_id );

		if( $post_meta ) {
			foreach ( $post_meta as $meta_key => $meta_values ) {
				if( '_wp_old_slug' == $meta_key ) {
					continue;
				}
				foreach ( $meta_values as $meta_value ) {
					add_post_meta( $new_post_id, $meta_key, $meta_value );
				}
			}
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => ( 'post' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
					'saved' => 'post_duplication_created'
				),
				admin_url( 'edit.php' )
			)
		);
		exit;
	} else {
		wp_die( 'Post creation failed, could not find original post.' );
	}
}
add_action( 'admin_notices', 'rudr_duplication_admin_notice' );

function rudr_duplication_admin_notice() {
	$screen = get_current_screen();
	if ( 'edit' !== $screen->base ) {
		return;
	}
    if ( isset( $_GET[ 'saved' ] ) && 'post_duplication_created' == $_GET[ 'saved' ] ) {
		 echo '<div class="notice notice-success is-dismissible"><p>Twój element został zduplikowany.</p></div>';
    }
}
// Duplication

//Plugin Gutenberg Blocks
require_once get_template_directory() . '/plugin-blocks/custom-blocks.php';
//Plugin Gutenberg Blocks

// Shortcode - Contact Form
add_action('init', 'custom_contact_form_submission');

function custom_contact_form_submission() {
    if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['message'])) {
        $to = "kontakt@virtuality.engineering";
        $from = sanitize_email($_POST['email']);
        $name = sanitize_text_field($_POST['name']);
        $message = wp_kses_post($_POST['message']);

        $subject = "Formularz kontaktowy";

        $headers = "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $body = "<!DOCTYPE html><html lang='pl'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
        $body .= "<table style='width: 100%;'>";
        $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";
        $body .= "</td></tr></thead><tbody><tr>";
        $body .= "<td style='border:none;'><strong>Name:</strong> {$name}</td>";
        $body .= "<td style='border:none;'><strong>Email:</strong> {$from}</td>";
        $body .= "</tr>";
        $body .= "<tr><td style='border:none;'><strong>Subject:</strong> {$subject}</td></tr>";
        $body .= "<tr><td></td></tr>";
        $body .= "<tr><td colspan='2' style='border:none;'>{$message}</td></tr>";
        $body .= "</tbody></table>";
        $body .= "</body></html>";

        $send = wp_mail($to, $subject, $body, $headers);

        if ($send) {
            wp_redirect(home_url());
            exit;
        } else {
            wp_redirect(home_url());
            exit;
        }
    }
}

function contact_form_shortcode() {
    ob_start();
    ?>
        <div class="virtuality__content-contact">
			<form class="form-contact contact_form" action=<?php echo esc_url( admin_url('admin-post.php') ); ?> method="post" id="contactForm" novalidate="novalidate">
				<input name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" placeholder="Email" required>
				<input name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Twoje imię'" placeholder="Twoje imię" required>
				<textarea name="message" id="message" cols="30" rows="8" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Twoja wiadomość'" placeholder=" Twoja wiadomość" required></textarea>
				<button type="submit">Wyślij</button>
			</form>
		</div>
    <?php
    return ob_get_clean();
}
add_shortcode('contact_form', 'contact_form_shortcode');
// Shortcode - Contact Form
?>