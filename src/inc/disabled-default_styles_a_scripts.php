<?
// —— 0. remove 32px margin admin-bar
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');
// end —— 0. remove 32px margin admin-bar

// —— 1. disabled admin-bar scripts and styles
//function adminBar_dequeue() {
//    wp_dequeue_style('admin-bar');
//    wp_deregister_style('admin-bar');
//}
//add_action( 'wp_enqueue_scripts', 'adminBar_dequeue', 9999 );
//add_action( 'wp_head', 'adminBar_dequeue', 9999 );
// end —— 1. disabled admin-bar scripts and styles

function my_deregister_scripts(){
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// —— 2. disabled wp-block-library styles
function my_deregister_styles_and_scripts() {
    wp_dequeue_style('wp-block-library');
}
add_action( 'wp_print_styles', 'my_deregister_styles_and_scripts', 100 );
// end —— 2. disabled wp-block-library styles

// —— 3. disabled emoji
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' );
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {
 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }
return $urls;
}
// end —— 3. disabled emoji

?>
