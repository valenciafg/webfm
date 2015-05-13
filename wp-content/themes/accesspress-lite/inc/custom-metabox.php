<?php
/**
 * AccessPress Lite Theme Options
 *
 * @package AccesspressLite
 */

global $accesspresslite_options;
$accesspresslite_settings = get_option( 'accesspresslite_options', $accesspresslite_options );
add_action('add_meta_boxes', 'accesspresslite_add_sidebar_layout_box');
$accesspresslite_event_category = !empty($accesspresslite_settings['event_cat']) ? $accesspresslite_settings['event_cat']:"";

function accesspresslite_add_sidebar_layout_box()
{
    add_meta_box(
                 'accesspresslite_sidebar_layout', // $id
                 'Sidebar Layout', // $title
                 'accesspresslite_sidebar_layout_callback', // $callback
                 'post', // $page
                 'normal', // $context
                 'high'); // $priority

    add_meta_box(
                 'accesspresslite_sidebar_layout', // $id
                 'Sidebar Layout', // $title
                 'accesspresslite_sidebar_layout_callback', // $callback
                 'page', // $page
                 'normal', // $context
                 'high'); // $priority
    
    add_meta_box(
                 'accesspresslite_event_date', // $id
                 'Event Date', // $title
                 'accesspresslite_event_date_callback', // $callback
                 'post', // $page
                 'side', // $context
                 'high'); // $priority
}


$accesspresslite_sidebar_layout = array(
        'left-sidebar' => array(
                        'value'     => 'left-sidebar',
                        'label'     => __( 'Left sidebar', 'accesspresslite' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/admin-panel/images/left-sidebar.png'
                    ), 
        'right-sidebar' => array(
                        'value' => 'right-sidebar',
                        'label' => __( 'Right sidebar<br/>(default)', 'accesspresslite' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/admin-panel/images/right-sidebar.png'
                    ),
        'both-sidebar' => array(
                        'value'     => 'both-sidebar',
                        'label'     => __( 'Both Sidebar', 'accesspresslite' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/admin-panel/images/both-sidebar.png'
                    ),
       
        'no-sidebar' => array(
                        'value'     => 'no-sidebar',
                        'label'     => __( 'No sidebar', 'accesspresslite' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/admin-panel/images/no-sidebar.png'
                    )   

    );

function accesspresslite_sidebar_layout_callback()
{ 
global $post , $accesspresslite_sidebar_layout;
wp_nonce_field( basename( __FILE__ ), 'accesspresslite_sidebar_layout_nonce' ); 
?>

<table class="form-table">
<tr>
<td colspan="4"><em class="f13"><?php _e('Choose Sidebar Template','accesspresslite'); ?></em></td>
</tr>

<tr>
<td>
<?php  
   foreach ($accesspresslite_sidebar_layout as $field) {  
                $accesspresslite_sidebar_metalayout = get_post_meta( $post->ID, 'accesspresslite_sidebar_layout', true ); ?>

                <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                <label class="description">
                <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span></br>
                <input type="radio" name="accesspresslite_sidebar_layout" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $accesspresslite_sidebar_metalayout ); if(empty($accesspresslite_sidebar_metalayout) && $field['value']=='right-sidebar'){ echo "checked='checked'";} ?>/>&nbsp;<?php echo $field['label']; ?>
                </label>
                </div>
                <?php } // end foreach 
                ?>
                <div class="clear"></div>
</td>
</tr>
<tr>
    <td><em class="f13"><?php echo sprintf(__('You can set up the sidebar content <a href="%s" target="_blank">here</a> in Sidebar tab','accesspresslite'), admin_url('/themes.php?page=theme_options')); ?></em></td>
</tr>
</table>

<?php } 

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function accesspresslite_save_sidebar_layout( $post_id ) { 
    global $accesspresslite_sidebar_layout, $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspresslite_sidebar_layout_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspresslite_sidebar_layout_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }  
    

    foreach ($accesspresslite_sidebar_layout as $field) {  
        //Execute this saving function
        $old = get_post_meta( $post_id, 'accesspresslite_sidebar_layout', true); 
        $new = sanitize_text_field($_POST['accesspresslite_sidebar_layout']);
        if ($new && $new != $old) {  
            update_post_meta($post_id, 'accesspresslite_sidebar_layout', $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id,'accesspresslite_sidebar_layout', $old);  
        } 
     } // end foreach   
     
}
add_action('save_post', 'accesspresslite_save_sidebar_layout'); 

function accesspresslite_event_date_callback()
{ 
global $post , $accesspresslite_event_category;
wp_nonce_field( basename( __FILE__ ), 'accesspresslite_event_date_nonce' ); 
?>

<table>
<tr>
<td colspan="4"><em class="f13"><?php _e('Enter the Event Date','accesspresslite'); ?></em></td>
</tr>

<tr>
<td>
<?php  
$accesspresslite_event_day = get_post_meta( $post->ID, 'accesspresslite_event_day', true );
$accesspresslite_event_month = get_post_meta( $post->ID, 'accesspresslite_event_month', true );
$accesspresslite_event_year = get_post_meta( $post->ID, 'accesspresslite_event_year', true );
$accesspresslite_event_day = empty($accesspresslite_event_day) ? date('j'): $accesspresslite_event_day; 
$accesspresslite_event_month = empty($accesspresslite_event_month) ? date('M'): $accesspresslite_event_month; 
$accesspresslite_event_year = empty($accesspresslite_event_year) ? date('Y'): $accesspresslite_event_year; 
 ?>
    <select name="accesspresslite_event_day">
    <?php for($event_day=1; $event_day <= 31; $event_day++){?>
    <option value="<?php echo $event_day ?>"  <?php selected( $accesspresslite_event_day, $event_day); ?>><?php echo $event_day ?></option>
    <?php } ?>
    </select>

    <select name="accesspresslite_event_month">
        <option value="Jan" <?php selected( $accesspresslite_event_month, 'Jan'); ?>><?php _e('Jan','accesspresslite'); ?></option>
        <option value="Feb" <?php selected( $accesspresslite_event_month, 'Feb'); ?>><?php _e('Feb','accesspresslite'); ?></option>
        <option value="Mar" <?php selected( $accesspresslite_event_month, 'Mar'); ?>><?php _e('Mar','accesspresslite'); ?></option>
        <option value="Apr" <?php selected( $accesspresslite_event_month, 'Apr'); ?>><?php _e('Apr','accesspresslite'); ?></option>
        <option value="May" <?php selected( $accesspresslite_event_month, 'May'); ?>><?php _e('May','accesspresslite'); ?></option>
        <option value="Jun" <?php selected( $accesspresslite_event_month, 'Jun'); ?>><?php _e('Jun','accesspresslite'); ?></option>
        <option value="Jul" <?php selected( $accesspresslite_event_month, 'Jul'); ?>><?php _e('Jul','accesspresslite'); ?></option>
        <option value="Aug" <?php selected( $accesspresslite_event_month, 'Aug'); ?>><?php _e('Aug','accesspresslite'); ?></option>
        <option value="Sep" <?php selected( $accesspresslite_event_month, 'Sep'); ?>><?php _e('Sep','accesspresslite'); ?></option>
        <option value="Oct" <?php selected( $accesspresslite_event_month, 'Oct'); ?>><?php _e('Oct','accesspresslite'); ?></option>
        <option value="Nov" <?php selected( $accesspresslite_event_month, 'Nov'); ?>><?php _e('Nov','accesspresslite'); ?></option>
        <option value="Dec" <?php selected( $accesspresslite_event_month, 'Dec'); ?>><?php _e('Dec','accesspresslite'); ?></option>
    </select>

    <select name="accesspresslite_event_year">
    <?php for($event_year = 1990; $event_year <= 2030; $event_year++){?>
    <option value="<?php echo $event_year ?>"  <?php selected( $accesspresslite_event_year, $event_year); ?>><?php echo $event_year ?></option>
    <?php } ?>
    </select>
   
</td>
</tr>
</table>


<script type="text/javascript">
    (function($){
    $(window).bind('load', function(){ 
        if($('body #in-category-<?php echo $accesspresslite_event_category; ?>').is(':checked')){
            $('#accesspresslite_event_date').fadeIn(); 
        }else{
            $('#accesspresslite_event_date').fadeOut(); 
        }

    
        $(document).on('change','#categorychecklist input', function(){
            if($('#in-category-<?php echo $accesspresslite_event_category; ?>').is(':checked')){
               $('#accesspresslite_event_date').fadeIn(); 
            }else{
               $('#accesspresslite_event_date').fadeOut(); 
            }
        }).change();
    });
    })(jQuery);

</script>

<?php } 

function accesspresslite_save_event_date( $post_id ) { 
    global $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspresslite_event_date_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspresslite_event_date_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
    
     if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }  

        //Execute this saving function
        $old_day = get_post_meta( $post_id, 'accesspresslite_event_day', true);
        $old_month = get_post_meta( $post_id, 'accesspresslite_event_month', true);  
        $old_year = get_post_meta( $post_id, 'accesspresslite_event_year', true);  
        $new_day = sanitize_text_field($_POST['accesspresslite_event_day']);
        $new_month = sanitize_text_field($_POST['accesspresslite_event_month']);
        $new_year = sanitize_text_field($_POST['accesspresslite_event_year']);
        
        if ( $new_day && '' == $new_day ){
            add_post_meta( $post_id, 'accesspresslite_event_day', $new_day );
        }elseif ($new_day && $new_day != $old_day) {  
            update_post_meta($post_id, 'accesspresslite_event_day', $new_day);  
        } elseif ('' == $new_day && $old_day) {  
            delete_post_meta($post_id,'accesspresslite_event_day', $old_day);  
        } 

        if ( $new_month && '' == $new_month ){
            add_post_meta( $post_id, 'accesspresslite_event_month', $new_month );
        }elseif ($new_month && $new_month != $old_month) {  
            update_post_meta($post_id, 'accesspresslite_event_month', $new_month);  
        } elseif ('' == $new_month && $old_month) {  
            delete_post_meta($post_id,'accesspresslite_event_month', $old_month);  
        } 

        if ( $new_year && '' == $new_year ){
            add_post_meta( $post_id, 'accesspresslite_event_year', $new_year );
        }elseif ($new_year && $new_year != $old_year) {  
            update_post_meta($post_id, 'accesspresslite_event_year', $new_year);  
        } elseif ('' == $new_year && $old_year) {  
            delete_post_meta($post_id,'accesspresslite_event_year', $old_year);  
        } 
}
add_action('save_post', 'accesspresslite_save_event_date'); 
