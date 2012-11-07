<?php
  class FinancialTips_Tips {
	  public function __construct	() {
		  
	  }
	  
	  public function get_financial_tips($category = 'Financial Tips', $num_tips = 2, $start_date = '', $rotation_frequency = 'Daily') {
		  	global $wpdb;
		  	$query="SELECT COUNT(*) AS num_tips FROM ".$wpdb->prefix."financial_tips WHERE 1 = 1";
			  if($category != '' && $category != 'Financial Tips')
			  	$query.= " AND category = '$category'";		  	
                $myrow  = $wpdb->get_row($query);
                  $num_ctips=stripslashes($myrow->num_tips);
              if($rotation_frequency == 'Daily')
              {
				  $start_date = ($start_date)? $start_date: date('m/d/Y');
				  $num_seconds = abs(strtotime(date('m/d/Y')) - strtotime($start_date));
				  $num_days = floor($num_seconds/60/60/24);
				  if(($num_days*$num_tips) >= $num_ctips)
					$start_tip = 0;
				  else
					$start_tip = $num_days*$num_tips;
			  }
              if($rotation_frequency == 'Weekly')
              {
				  $start_date = ($start_date)? $start_date: date('m/d/Y');
				  $weekdays = date('N', strtotime($start_date));
				  $bdays = 7 - $weekdays;
				  $num_seconds = abs(strtotime(date('m/d/Y')) - strtotime($start_date));
				  $num_seconds = abs(strtotime(date('m/d/Y')) - strtotime($start_date));
				  $num_days = floor($num_seconds/60/60/24)+$bdays;				  

				  $num_weeks = ceil($num_days/7);
				  if(($num_weeks*$num_tips) >= $num_ctips)
					$start_tip = 0;
				  else
					$start_tip = $num_weeks*$num_tips;
				//echo $start_tip;
			  }
              if($rotation_frequency == 'Monthly')
              {
				  
				  $start_date = ($start_date)? $start_date: date('m/d/Y');
				 // $num_seconds = abs(strtotime(date('m/d/Y')) - strtotime($start_date));
				  $num_days = date('n') - date('n', strtotime($start_date));
				  if(($num_days*$num_tips) >= $num_ctips)
					$start_tip = 0;
				  else
					$start_tip = $num_days*$num_tips;
			  }			  
			  $query="SELECT * FROM ".$wpdb->prefix."financial_tips WHERE 1 = 1";
			  if($category != '' && $category != 'Financial Tips')
			  	$query.= " AND category = '$category'";
			  $query.= " order by id";
			  $query.= " LIMIT $start_tip, ".$num_tips;
			  //echo $query;
			  $rows=$wpdb->get_results($query,'ARRAY_A');
			  $result = '';
			  $result.= '<ul id="tips" style="margin-left:0px;">';
  			foreach($rows as $row){ 
		  	
		  	
				$result.= '<li style="margin-bottom:4px;padding-left:20px;background:url(\''.get_bloginfo('url').'/wp-content/plugins/financial-tips/images/financial-tips.gif\') no-repeat 0px 3px;">'.$row['tip'].'</li>';
			}
			$result.='</ul>';
		  	// And then randomly choose a line
		  	return wptexturize( $result );
	  }
	  
 
  }
?>