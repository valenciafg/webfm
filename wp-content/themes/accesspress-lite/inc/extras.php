<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package AccesspressLite
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function accesspresslite_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'accesspresslite_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function accesspresslite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'accesspresslite_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
    /**
     * Filters wp_title to print a neat <title> tag based on what is being viewed.
     *
     * @param string $title Default title text for current view.
     * @param string $sep Optional separator.
     * @return string The filtered title.
     */
    function accesspresslite_wp_title( $title, $sep ) {
            if ( is_feed() ) {
                    return $title;
            }

            global $page, $paged;

            // Add the blog name
            $title .= get_bloginfo( 'name', 'display' );

            // Add the blog description for the home/front page.
            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) ) {
                    $title .= " $sep $site_description";
            }

            // Add a page number if necessary:
            if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
                    $title .= " $sep " . sprintf( __( 'Page %s', 'accesspress-root' ), max( $paged, $page ) );
            }

            return $title;
    }
    add_filter( 'wp_title', 'accesspresslite_wp_title', 10, 2 );

    /**
     * Title shim for sites older than WordPress 4.1.
     *
     * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function accesspresslite_render_title() {
            ?>
            <title><?php wp_title( '|', true, 'right' ); ?></title>
            <?php
    }
    add_action( 'wp_head', 'accesspresslite_render_title' );
endif;

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function accesspresslite_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'accesspresslite_setup_author' );

global $accesspresslite_options;
$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function accesspresslite_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'accesspresslite' ),
		'id'            => 'left-sidebar',
		'description'   => __( 'Display items in the Left Sidebar of the inner pages', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'accesspresslite' ),
		'id'            => 'right-sidebar',
		'description'   => __( 'Display items in the Right Sidebar of the inner pages', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Event Sidebar', 'accesspresslite' ),
		'id'            => 'event-sidebar',
		'description'   => __( 'Display items in the Left Sidebar of the inner pages', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Blog Right Sidebar', 'accesspresslite' ),
		'id'            => 'blog-sidebar',
		'description'   => __( 'Display items for the blog category in the Right Sidebar of the inner pages', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area One', 'accesspresslite' ),
		'id'            => 'footer-1',
		'description'   => __( 'Display items in First Footer Area', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area Two', 'accesspresslite' ),
		'id'            => 'footer-2',
		'description'   => __( 'Display items in Second Footer Area', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area Three', 'accesspresslite' ),
		'id'            => 'footer-3',
		'description'   => __( 'Display items in Third Footer Area', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area Four', 'accesspresslite' ),
		'id'            => 'footer-4',
		'description'   => __( 'Display items in Fourth Footer Area', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Left Block above footer', 'accesspresslite' ),
		'id'            => 'textblock-1',
		'description'   => __( 'Display items in the left just above the footer', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Middle Block above footer', 'accesspresslite' ),
		'id'            => 'textblock-2',
		'description'   => __( 'Display items in the middle just above the footer and replaces defaul gallery', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Block above footer', 'accesspresslite' ),
		'id'            => 'textblock-3',
		'description'   => __( 'Display items in the Right just above the footer and replaces Testimonials', 'accesspresslite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'accesspresslite_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function accesspresslite_scripts() {
	global $accesspresslite_options;
	$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
	$query_args = array(
		'family' => 'Open+Sans:400,400italic,300italic,300,600,600italic|Lato:400,100,300,700',
	);
	
	wp_enqueue_style( 'google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'fancybox-css', get_template_directory_uri() . '/css/nivo-lightbox.css' );
	wp_enqueue_style( 'bx-slider-style', get_template_directory_uri() . '/css/jquery.bxslider.css' );
	wp_enqueue_style( 'woo-commerce-style', get_template_directory_uri() . '/css/woocommerce.css' );
	wp_enqueue_style( 'font-style', get_template_directory_uri() . '/css/fonts.css' );
	wp_enqueue_style( 'accesspresslite-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery'); 
	wp_enqueue_script( 'bx-slider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), '4.1', true );
	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/nivo-lightbox.min.js', array('jquery'), '2.1', true );
	wp_enqueue_script( 'jquery-actual', get_template_directory_uri() . '/js/jquery.actual.min.js', array('jquery'), '1.0.16', true );
	wp_enqueue_script( 'accesspresslite-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.1', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

/**
* Loads up responsive css if it is not disabled
*/
	if ( $accesspresslite_settings[ 'responsive_design' ] == 0 ) {	
		wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'accesspresslite_scripts' );

/**
* Loads up favicon
*/
	function accesspresslite_add_favicon(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		
		if( !empty($accesspresslite_settings[ 'media_upload' ])){
		echo '<link rel="shortcut icon" type="image/png" href="'. $accesspresslite_settings[ 'media_upload' ].'"/>';
		}
	}
	add_action('wp_head', 'accesspresslite_add_favicon');


	function accesspresslite_social_cb(){ 
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		?>
		<div class="socials">
		<?php if(!empty($accesspresslite_settings['accesspresslite_facebook'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_facebook']); ?>" class="facebook" title="Facebook" target="_blank"><span class="font-icon-social-facebook"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_twitter'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_twitter']); ?>" class="twitter" title="Twitter" target="_blank"><span class="font-icon-social-twitter"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_gplus'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_gplus']); ?>" class="gplus" title="Google Plus" target="_blank"><span class="font-icon-social-google-plus"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_youtube'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_youtube']); ?>" class="youtube" title="Youtube" target="_blank"><span class="font-icon-social-youtube"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_pinterest'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_pinterest']); ?>" class="pinterest" title="Pinterest" target="_blank"><span class="font-icon-social-pinterest"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_linkedin'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_linkedin']); ?>" class="linkedin" title="Linkedin" target="_blank"><span class="font-icon-social-linkedin"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_flickr'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_flickr']); ?>" class="flickr" title="Flickr" target="_blank"><span class="font-icon-social-flickr"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_vimeo'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_vimeo']); ?>" class="vimeo" title="Vimeo" target="_blank"><span class="font-icon-social-vimeo"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_stumbleupon'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_stumbleupon']); ?>" class="stumbleupon" title="Stumbleupon" target="_blank"><span class="font-icon-social-stumbleupon"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_instagram'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_instagram']); ?>" class="instagram" title="instagram" target="_blank"><span class="fa fa-instagram"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_sound_cloud'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_sound_cloud']); ?>" class="sound-cloud" title="sound-cloud" target="_blank"><span class="font-icon-social-soundcloud"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_skype'])){ ?>
		<a href="<?php echo "skype:".esc_attr($accesspresslite_settings['accesspresslite_skype']); ?>" class="skype" title="Skype"><span class="font-icon-social-skype"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_tumblr'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_tumblr']); ?>" class="tumblr" title="Tumblr"><span class="font-icon-social-tumblr"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_myspace'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_myspace']); ?>" class="myspace" title="Myspace"><span class="font-icon-social-myspace"></span></a>
		<?php } ?>

		<?php if(!empty($accesspresslite_settings['accesspresslite_rss'])){ ?>
		<a href="<?php echo esc_url($accesspresslite_settings['accesspresslite_rss']); ?>" class="rss" title="RSS" target="_blank"><span class="font-icon-rss"></span></a>
		<?php } ?>
		</div>
	<?php } 

	add_action( 'accesspresslite_social_links', 'accesspresslite_social_cb', 10 );	


	function accesspresslite_header_text_cb(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		if(!empty($accesspresslite_settings['header_text'])){
		echo '<div class="header-text">'.wpautop($accesspresslite_settings['header_text']).'</div>';
		}
	}

	add_action('accesspresslite_header_text','accesspresslite_header_text_cb', 10);

	function accesspresslite_menu_alignment_cb(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		if($accesspresslite_settings['menu_alignment'] =="Left"){
			$accesspresslite_alignment_class="menu-left";
		}elseif($accesspresslite_settings['menu_alignment'] == "Center"){
			$accesspresslite_alignment_class="menu-center";
		}elseif($accesspresslite_settings['menu_alignment'] == "Right"){
			$accesspresslite_alignment_class="menu-right";
		}else{
			$accesspresslite_alignment_class="";
		}
		echo $accesspresslite_alignment_class;
	}

	add_action('accesspresslite_menu_alignment','accesspresslite_menu_alignment_cb', 10);


	function accesspresslite_excerpt( $accesspresslite_content , $accesspresslite_letter_count ){
		$accesspresslite_striped_content = strip_shortcodes($accesspresslite_content);
		$accesspresslite_striped_content = strip_tags($accesspresslite_striped_content);
		$accesspresslite_excerpt = mb_substr($accesspresslite_striped_content, 0, $accesspresslite_letter_count );
		if($accesspresslite_striped_content > $accesspresslite_excerpt){
			$accesspresslite_excerpt .= "...";
		}
		return $accesspresslite_excerpt;
	}


	function accesspresslite_bxslidercb(){
		global $accesspresslite_options, $post;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
        ($accesspresslite_settings['slider_show_pager'] == 'yes1' || empty($accesspresslite_settings['slider_show_pager'])) ? ($a='true') : ($a='false');
        ($accesspresslite_settings['slider_show_controls'] == 'yes2' || empty($accesspresslite_settings['slider_show_controls'])) ? ($b='true') : ($b='false');
        ($accesspresslite_settings['slider_mode'] == 'slide' || empty($accesspresslite_settings['slider_mode'])) ? ($c='horizontal') : ($c='fade');
        ($accesspresslite_settings['slider_auto'] == 'yes3' || empty($accesspresslite_settings['slider_auto'])) ? ($d='true') : ($d='false');
		empty($accesspresslite_settings['slider_pause']) ? ($e ='5000') : ($e = $accesspresslite_settings['slider_pause']);

		if( $accesspresslite_settings['show_slider'] !='no') { 
		if((isset($accesspresslite_settings['slider1']) && !empty($accesspresslite_settings['slider1'])) 
			|| (isset($accesspresslite_settings['slider2']) && !empty($accesspresslite_settings['slider2'])) 
			|| (isset($accesspresslite_settings['slider3']) && !empty($accesspresslite_settings['slider3']))
			|| (isset($accesspresslite_settings['slider4']) && !empty($accesspresslite_settings['slider4'])) 
			|| (isset($accesspresslite_settings['slider_cat']) && !empty($accesspresslite_settings['slider_cat']))
		){

		?>
 		<script type="text/javascript">
            jQuery(function(){
				jQuery('.bx-slider').bxSlider({
					adaptiveHeight:true,
					pager:<?php echo $a; ?>,
					controls:<?php echo $b; ?>,
					mode:'<?php echo $c; ?>',
					auto :<?php echo $d; ?>,
					pause: '<?php echo $e; ?>',
					<?php if($accesspresslite_settings['slider_speed']) {?>
					speed:'<?php echo $accesspresslite_settings['slider_speed']; ?>'
					<?php } ?>
				});
			});
        </script>
        <?php 

            if($accesspresslite_settings['slider_options'] == 'single_post_slider'){
            	if(!empty($accesspresslite_settings['slider1']) || !empty($accesspresslite_settings['slider2']) || !empty($accesspresslite_settings['slider3']) || !empty($accesspresslite_settings['slider4'])){
            		$sliders = array($accesspresslite_settings['slider1'],$accesspresslite_settings['slider2'],$accesspresslite_settings['slider3'],$accesspresslite_settings['slider4']);
					$remove = array(0);
				    $sliders = array_diff($sliders, $remove);  ?>

				    <div class="bx-slider">
				    <?php
				    foreach ($sliders as $slider){
					$args = array (
					'p' => $slider
					);

						$loop = new WP_query( $args );
						if($loop->have_posts()){ 
						while($loop->have_posts()) : $loop-> the_post(); 
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
						?>
						<div class="slides">
							
								<img alt="<?php echo get_the_title(); ?>" src="<?php echo $image[0]; ?>">
								
								<?php if($accesspresslite_settings['slider_caption']=='yes4'):?>
								<div class="slider-caption">
									<div class="ak-container">
										<h1 class="caption-title"><?php the_title();?></h1>
										<h2 class="caption-description"><?php echo get_the_content();?></h2>
									</div>
								</div>
								<?php  endif; ?>
				
			            </div>
						<?php endwhile;
						}
					} ?>
				    </div>
            	<?php
            	}

            }elseif ($accesspresslite_settings['slider_options'] == 'cat_post_slider') { ?>
            	<div class="bx-slider">
				<?php
				$loop = new WP_Query(array(
						'cat' => $accesspresslite_settings['slider_cat'],
						'posts_per_page' => -1
					));
					if($loop->have_posts()){ 
					while($loop->have_posts()) : $loop-> the_post(); 
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
					?>
					<div class="slides">
							
						<img alt="<?php echo get_the_title(); ?>" src="<?php echo $image[0]; ?>">
								
						<?php if($accesspresslite_settings['slider_caption']=='yes4'):?>
						<div class="slider-caption">
							<div class="ak-container">
								<h1 class="caption-title"><?php the_title();?></h1>
								<h2 class="caption-description"><?php echo get_the_content();?></h2>
							</div>
						</div>
						<?php  endif; ?>
				
			            </div>
						<?php endwhile;
						} ?>
				</div>
            <?php
        	}
        	}else{ ?>

        	<script type="text/javascript">
            jQuery(function(){
				jQuery('.bx-slider').bxSlider({
					pager:<?php echo $a; ?>,
					controls:<?php echo $b; ?>,
					mode:'<?php echo $c; ?>',
					auto :<?php echo $d; ?>,
					pause: '<?php echo $e; ?>',
					<?php if($accesspresslite_settings['slider_speed']) {?>
					speed:'<?php echo $accesspresslite_settings['slider_speed']; ?>'
					<?php } ?>
				});
			});
            </script>
            <div class="bx-slider">
				<div class="slides">
					<img src="<?php echo get_template_directory_uri(); ?>/images/demo/slider1.jpg" alt="slider1">
                    <?php if($accesspresslite_settings['slider_caption']=='yes4' || empty($accesspresslite_settings['slider_caption'])):?>
					<div class="slider-caption">
						<div class="ak-container">
							<h1 class="caption-title">AccessPress Lite</h1>
							<h2 class="caption-description">Free Responsive, multi-purpose, business wordpress theme, perfect for any business on any device.</h2>
						</div>
					</div>
                    <?php  endif; ?>
				</div>
						
				<div class="slides">
					<img src="<?php echo get_template_directory_uri(); ?>/images/demo/slider2.jpg" alt="slider2">
                    <?php if($accesspresslite_settings['slider_caption']=='yes4' || empty($accesspresslite_settings['slider_caption'])):?>
					<div class="slider-caption">
						<div class="ak-container">
							<h1 class="caption-title">Easy Customization</h1>
							<h2 class="caption-description">A free theme with powerful theme options for customization. Style your wordpress and see changes live!</h2>
						</div>
					</div>
                    <?php  endif; ?>
				</div>
			</div>
		<?php
		}
	}
	}

   add_action('accesspresslite_bxslider','accesspresslite_bxslidercb', 10);

   function accesspresslite_layout_class($classes){
   	global $post;
   		if( is_404()){
		$classes[] = ' ';
		}elseif(is_singular()){
		$post_class = get_post_meta( $post -> ID, 'accesspresslite_sidebar_layout', true );
		$classes[] = $post_class;
		}else{
		$classes[] = 'right-sidebar';	
		}
		return $classes;
	}

   add_filter( 'body_class', 'accesspresslite_layout_class' );
   
   function accesspresslite_web_layout($classes){
    global $accesspresslite_options, $post;
	$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
    $weblayout = $accesspresslite_settings['accesspresslite_webpage_layout'];
    if($weblayout =='Boxed'){
        $classes[]= 'boxed-layout';
    }
    return $classes;
   }
   
   add_filter( 'body_class', 'accesspresslite_web_layout' );

	function accesspresslite_custom_css(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		echo '<style type="text/css">';
			echo $accesspresslite_settings['custom_css'];
		echo '</style>';
	}

	add_action('wp_head','accesspresslite_custom_css');

	function accesspresslite_custom_code(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
			echo $accesspresslite_settings['custom_code'];
	}

	add_action('wp_head','accesspresslite_custom_code');

	function accesspresslite_call_to_action_cb(){
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
		if(!empty($accesspresslite_settings['action_text'])){
		?>
		<section id="call-to-action">
		<div class="ak-container">
			<h4><?php echo $accesspresslite_settings['action_text']; ?></h4>
			<a class="action-btn" href="<?php echo $accesspresslite_settings['action_btn_link']; ?>"><?php echo $accesspresslite_settings['action_btn_text']; ?></a>
		</div>
		</section>
		<?php
		}
	}

	add_action('accesspresslite_call_to_action','accesspresslite_call_to_action_cb', 10);

	add_filter('widget_text', 'do_shortcode');