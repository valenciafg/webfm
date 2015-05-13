<?php
/**
 * @package AccesspressLite
 */
?>
<?php
global $accesspresslite_options;
$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
$cat_blog = $accesspresslite_settings['blog_cat'];
$cat_event = $accesspresslite_settings['event_cat'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
            
		<?php 
		if(has_category( $cat_event) && !empty($cat_event)){ 
		$accesspresslite_event_day = get_post_meta( $post->ID, 'accesspresslite_event_day', true );
		$accesspresslite_event_month = get_post_meta( $post->ID, 'accesspresslite_event_month', true );
		$accesspresslite_event_year = get_post_meta( $post->ID, 'accesspresslite_event_year', true );
		?>
		<div class="event-date-archive"><?php echo get_cat_name( $cat_event )?> on <?php echo $accesspresslite_event_day." ".$accesspresslite_event_month." , ".$accesspresslite_event_year ?></div>
		<?php 
			}else if(has_category( $cat_blog) && !empty($cat_blog)){?>
			<div class="entry-meta">
				<?php accesspresslite_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php }?>
	</header><!-- .entry-header -->


	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'accesspresslite' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	<?php if(has_category( $cat_blog) && !empty($cat_blog)){

			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'accesspresslite' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'accesspresslite' ) );

			if ( ! accesspresslite_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'accesspresslite' );
				} else {
					$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'accesspresslite' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'accesspresslite' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'accesspresslite' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink()
			);
		?>
	<?php } ?>
		<?php edit_post_link( __( 'Edit', 'accesspresslite' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
