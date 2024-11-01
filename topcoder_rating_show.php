<?php
/*
Plugin Name: Topcoder Rating Show
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Show your topcoder rating on your blog.
Version: 0.0.1
Author: Yi Lu
Author URI: http://luyi0619.org
*/

/*  Copyright 2012  Yi Lu  (email : luyi0619@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class TopCoder extends WP_Widget
{

	function TopCoder() {
        	parent::WP_Widget(false, $name = 'TopCoder');	
   	 }

	
    	function update($new_instance, $old_instance) {				
        	return $new_instance;
    	}

	
	function form($instance){
		$title = esc_attr($instance['title']);
		$user = esc_attr($instance['user']);
?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" />
		</p>
<?php	
		}
 

		function widget($args,$instance){

			extract( $args );
       			?>
              			<?php echo $before_widget; ?>
                  			<?php echo $before_title
                      				. $instance['title']
                     				 . $after_title; 
					?>
              			<?php echo $after_widget; ?>
        		<?php
			
			$title = apply_filters( 'widget_title', $instance['title'] );
			$user = $instance['user'];
			

			$url = 'http://community.topcoder.com/tc?module=MemberProfile&cr='.$user; 
			$s =  strip_tags(file_get_contents($url));
			$rating = Array('Algorithm Rating','High School Rating','Conceptualization Rating','Architecture Rating','Design Rating','Development Rating','Assembly Rating',
					'Test Suites Rating','Test Scenarios Rating','UI Prototype Rating','RIA Build Rating','Marathon Matches Rating'
				);
			$str = "";
			$len = strlen($s);
			for($i =0 ; $i < $len ; $i ++)
			{
				if( $s[$i] == '&' ||  ($s[$i] >= '0' && $s[$i] <= '9')  || ($s[$i] >= 'A' && $s[$i] <= 'Z') || ($s[$i] >= 'a' && $s[$i] <= 'z'))
				{
					$str .= $s[$i];
				}
			}
				
			foreach($rating as $now)
			{
				$s = str_replace(" ","",$now); 
				$mch = '/' . $s . 'notrated/';
				if (preg_match($mch, $str, $match))
				{	
					continue;
				}

				$mch = '/' . $s . '([0-9]+)/';
				if(preg_match($mch, $str, $match) == 0)
					continue;
				$r = $match[1] ;
				echo $now . ":";
				if ($r < 900)
				{
					echo "<font color=#999999> $r </font>";
				}
				else if ($r < 1200)
				{
					echo "<font color=#00A900> $r </font>";
				}
				else if ($r < 1500)
				{
					echo "<font color=#6666FF> $r </font>";
				}
				else if ($r < 2200)
				{
					echo "<font color=#DDCC00> $r </font>";
				}
				else
				{
					echo "<font color=#EE0000> $r </font>";
				}
				echo "<br />" ;
				
			}
			
		}

}

add_action('widgets_init', 'RegisterPlugin');
function RegisterPlugin(){
	register_widget('TopCoder');
}

?>