<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package AccesspressLite
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
	<?php 
		global $accesspresslite_options;
		$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );

		if ( is_active_sidebar( 'footer-1' ) ||  is_active_sidebar( 'footer-2' )  || is_active_sidebar( 'footer-3' )  || is_active_sidebar( 'footer-4' ) || !empty($accesspresslite_settings['google_map']) || !empty($accesspresslite_settings['contact_address'])) : ?>
		<div id="top-footer">
		<div class="ak-container">
			<div class="footer1 footer">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php endif; ?>	
			</div>

			<div class="footer2 footer">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php endif; ?>	
			</div>

			<div class="clearfix hide"></div>

			<div class="footer3 footer">
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
				<?php endif; ?>	
			</div>

			<div class="footer4 footer">
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<?php dynamic_sidebar( 'footer-4' ); ?>
				<?php else:
                if(!empty($accesspresslite_settings['google_map']) || !empty($accesspresslite_settings['contact_address'])){ ?>
                    
                    <h3 class="widget-title"><?php _e('Find Us','accesspresslite')?></h3>
				    <?php if(!empty($accesspresslite_settings['google_map'])) { ?>

                        <div class="ak-google-map"><?php echo $accesspresslite_settings['google_map']; ?></div>
						
						<?php }
						if(!empty($accesspresslite_settings['contact_address'])) { ?>
						
						<div class="ak-contact-address"><?php echo wpautop($accesspresslite_settings['contact_address']); ?></div>

						<?php }
					
						if($accesspresslite_settings['show_social_footer'] == 0){
						do_action( 'accesspresslite_social_links' ); 
						}
					 }
				endif; ?>	
			</div>
		</div>
		</div>
	<?php endif; ?>

		
		<div id="bottom-footer">
		<div class="ak-container">
			<h1 class="site-info">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'accesspresslite' ) ); ?>"><?php _e( 'Free WordPress Theme', 'accesspresslite' ); ?></a>
				<span class="sep"> | </span>
				<a href="<?php echo esc_url('http://accesspressthemes.com/');?>" title="AccessPress Themes" target="_blank">AccessPress Lite</a>
			</h1><!-- .site-info -->

			<div class="copyright">
				Copyright &copy; <?php echo date('Y') ?> 
				<a href="<?php echo home_url(); ?>">
				<?php if(!empty($accesspresslite_settings['footer_copyright'])){
					echo $accesspresslite_settings['footer_copyright']; 
					}else{
						echo bloginfo('name');
					} ?>
				</a>
			</div>
		</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
