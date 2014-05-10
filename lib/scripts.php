<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.min.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.11.0.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr-2.7.0.min.js
 * 3. /theme/assets/js/main.min.js (in footer)
 */
function roots_scripts() {
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false, '5ad8833f48d20859f0663f39c206b9b8');

  // jQuery is loaded using the same method from HTML5 Boilerplate:
  // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null, false);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', array(), null, false);
  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array(), '48e4d59cb50f8e42b83f35af0de4180d', true);
  wp_enqueue_script('modernizr');
  wp_enqueue_script('jquery');
  wp_enqueue_script('roots_scripts');
}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.11.0.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');

/*================================================
* The Google Map Script 
* ==============================================*/

/* Add Google Map Script to Specific Page */

function cw_map_style() {
	
	// Run on the front page (static page) only
	if (is_page('69')) {
		echo '<style>#map_canvas { width: 100%; height: 548px; }</style>';
	}
}

function carawebs_initialise_googlemaps(){
	
	if (is_page('69')) {
	    $googlescript = '<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>';
	    echo $googlescript;
	}
}

function carawebs_googlemaps_control(){
	
	if (is_page('69')) {
	
    // Register the control script - in a folder called js in the active Thesis skin
    wp_register_script( 'carawebs_googlemap', get_template_directory_uri() . '/assets/js/vendor/google-map.js');
	
	// Enqueue the masonry controls - they will be built into the footer
    wp_enqueue_script('carawebs_googlemap');
    
	}
}


// Add hooks for front-end
add_action('wp_head', 'cw_map_style', 1);
add_action('wp_head', 'carawebs_initialise_googlemaps', 2);
add_action('wp_enqueue_scripts', 'carawebs_googlemaps_control', 105);

/*====================================================================*/

function roots_google_analytics() { ?>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if (GOOGLE_ANALYTICS_ID && !current_user_can('manage_options')) {
  add_action('wp_footer', 'roots_google_analytics', 20);
}
