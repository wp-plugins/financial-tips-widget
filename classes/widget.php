<?php
	class FinancialTips_Widget extends WP_Widget {
			
		/** constructor */
		function __construct() {
			parent::WP_Widget( /* Base ID */'prosperity_widget', /* Name */'Financial Tips', array( 'description' => 'Show Financial Tips' ) );
		}
		
		// outputs the content of the widget
		function widget($args, $instance) {				
			extract( $args );
			
			if (!class_exists("FinancialTips_Tips")) {
				include("financial-tips-selector.php");
			}
			$financial_tips = new FinancialTips_Tips();

			$tips = $financial_tips->get_financial_tips($instance['category'], $instance['num_tips'], $instance['start_date'], $instance['rotation_frequency']);
			
			$title = apply_filters( 'widget_title', $instance['category'] );
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			echo $tips;
			echo $after_widget;
			echo '<p style="margin-top:5px;"><a href="http://coolchecks.net/blog/wordpress-plugins-financial-tips.html" target="_blank" style="font-size:8pt;"> Financial Tips Widget By Coolchecks.net</a></p>';
		}
		
		// updates widget options in DB
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			//$instance['title'] = strip_tags($new_instance['title']);
			$instance['category'] = strip_tags($new_instance['category']);
			$instance['num_tips'] = strip_tags($new_instance['num_tips']);
			$instance['start_date'] = strip_tags($new_instance['start_date']);
			$instance['rotation_frequency'] = strip_tags($new_instance['rotation_frequency']);
			return $instance;
		}
		
		// admin form in widget
		function form( $instance ) {
			if ( $instance ) {
				//$title = esc_attr( $instance[ 'title' ] );
				$category = esc_attr( $instance[ 'category' ] );
				$num_tips = esc_attr( $instance[ 'num_tips' ] );
				$start_date = esc_attr( $instance[ 'start_date' ] );
				$rotation_frequency = esc_attr( $instance[ 'rotation_frequency' ] );
			}
			else {
				$title = __( 'New title', 'text_domain' );
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_id('start_date'); ?>"><?php _e('Start Date:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('start_date'); ?>" name="<?php echo $this->get_field_name('start_date'); ?>" type="text" value="<?php echo ($start_date)? $start_date: date('m/d/Y'); ?>" />
			<span style="font-style:italic;font-size:8pt;color:#666666;">(mm/dd/YYYY)</span>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('rotation_frequency'); ?>"><?php _e('Rotation Frequency:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('rotation_frequency'); ?>" name="<?php echo $this->get_field_name('rotation_frequency'); ?>" size="1">
			  <option value="Daily"<?=($rotation_frequency == 'Daily')?' selected="selected"':''?>>Daily</option>
			  <option value="Weekly"<?=($rotation_frequency == 'Weekly')?' selected="selected"':''?>>Weekly</option>
			  <option value="Monthly"<?=($rotation_frequency == 'Monthly')?' selected="selected"':''?>>Monthly</option>
			</select>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Category:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" size="1">
                <option value="Financial Tips">-All Financial Tips-</option>
<?         
					global $wpdb;
                      $query="SELECT DISTINCT category FROM ".$wpdb->prefix."financial_tips order by category";
                      $rows=$wpdb->get_results($query,'ARRAY_A');
                    
                                     
                           foreach($rows as $row){ 
?>
						<option value="<?=$row['category']?>"<?=(isset($category) && $category == $row['category'])?' selected="selected"':''?>><?=$row['category']?></option>
<?
}
?>                			
			</select>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('num_tips'); ?>"><?php _e('Number of Tips:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('num_tips'); ?>" name="<?php echo $this->get_field_name('num_tips'); ?>" type="text" value="<?php echo $num_tips; ?>" />
			</p>			
			<?php 
		}
	}	
	
?>