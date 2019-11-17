<?php
/*
Plugin Name: Breadcrumbs Simple
Plugin URI:  https://wordpress.org/plugins/breadcrumb-simple/
Description: This plugin will create a breadcrumbs for pages. Use the shortcode "[breadcrumb_simple]" OR use Function "breadcrumb_simple();" .
Version:     1.3
Author:      Abhay Yadav
Author URI:  http://inizsoft.com
License:     GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Ah ah ah, you didn\'t say the magic word' );

add_option('brs_sep');

add_action( 'admin_menu', 'brs_menu' );
function brs_menu() {
    add_options_page( 'Breadcrumbs Options', 'Breadcrumbs option', 'manage_options', 'brs_options', 'brs_options' );
}
function brs_options() {
    
    $hidden_field_name = 'ele_submit_hidden';
    $brs_sep = 'brs_sep';
	$brs_sitetitle = 'brs_sitetitle';
    $organizer_id = 'ele_organizer_id';
   
    $brs_sep_val = get_option($brs_sep);
	$brs_sitetitle_val = get_option($brs_sitetitle);
  
  
    if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        $brs_sep_val = sanitize_text_field($_POST[$brs_sep]);
		$brs_sitetitle_val = sanitize_text_field($_POST[$brs_sitetitle]);
        update_option( $brs_sep, $brs_sep_val );
		update_option( $brs_sitetitle, $brs_sitetitle_val );
      ?>
            <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-brs' ); ?></strong></p></div>
        <?php
    }
	
   echo "<h1>" . __( 'Breadcrumbs Setting', 'menu-brs' ) . "</h1>";

    echo '<div class="card pressthis">';
   
?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<table class="widefat importers striped">
  <tbody>
  	<tr>
      <td class="import-system row-title"><?php _e("Home Title:", 'menu-brs' ); ?></td>
      <td class="desc"><input type="text" name="<?php echo $brs_sitetitle; ?>" value="<?php echo $brs_sitetitle_val; ?>" size="20"></td>
    </tr>
    <tr>
      <td class="import-system row-title"><?php _e("Breadcrumbs Seprater:", 'menu-brs' ); ?></td>
      <td class="desc"><input type="text" name="<?php echo $brs_sep; ?>" value="<?php echo $brs_sep_val; ?>" size="20"></td>
    </tr>
        
  </tbody>
</table>
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
</form>
</div>
<?php

}

add_shortcode('breadcrumb_simple', 'breadcrumb_simple');
function breadcrumb_simple() {
    global $post;
	$separator = get_option('brs_sep');
	$hometitle = get_option('brs_sitetitle');
	
    echo '<div class="breadcrumb">';
	if (!is_front_page()) {
		echo '<a href="';
		echo get_option('home');
		echo '">';
		if($hometitle){ 
		 echo $hometitle;
		} else {
			bloginfo('name');
		}
		
		echo "</a><span class='seprater'>".$separator."</span>";
		if ( is_category() || is_single() ) {
			the_category(', ');
			if ( is_single() ) {
				echo "<span class='seprater'>".$separator."</span>";
				the_title();
			}
		} elseif ( is_page() && $post->post_parent ) {
			$home = get_page(get_option('page_on_front'));
			for ($i = count($post->ancestors)-1; $i >= 0; $i--) {
				if (($home->ID) != ($post->ancestors[$i])) {
					echo '<a href="';
					echo get_permalink($post->ancestors[$i]); 
					echo '">';
					echo get_the_title($post->ancestors[$i]);
					echo "</a><span class='seprater'>".$separator."</span>";
				}
			}
			echo "<span class='current'>".get_the_title()."</span>";
		} elseif (is_page()) {
			echo "<span class='current'>".get_the_title()."</span>";
		} elseif (is_404()) {
			echo "<span class='current'>".get_the_title()."</span>";
		}
	} else {
		if($hometitle){ 
		 echo $hometitle;
		} else {
			bloginfo('name');
		}
	}
	echo '</div>';
}
?>