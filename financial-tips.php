<?php
/**
 * @package Financial Tips Plugin
 * @version 1.0.1
 */
/*
Plugin Name: Financial Tips
Description: Displays preconfigured or user configured financial quotes and tips in a text widget. Automatically changes tips based on selection of daily, weekly or monthly. Can be used multiple times in sidebar area.
Author: Sherry Tingley
Version: 1.0.1
Author URI: http://www.coolchecks.net/blog/wordpress-plugins-financial-tips.html
*/
if (!class_exists("FinancialTips")) {
	class FinancialTips {
		public function __construct() {
			// Now we set that function up to execute when the admin_notices action is called
			add_action( 'admin_notices', array($this, 'financial_tips') );
			add_action( 'admin_head', array($this, 'financial_tips_css') );			
			add_shortcode( 'FinancialTips', array($this, 'FinancialTips_Handler') );
			//add_action( 'admin_menu', array($this, 'add_FinancialTips_menu') );
			
			add_action('widgets_init', array($this, 'RegisterFinancialTipsWidget') );
			add_filter('plugin_row_meta', array($this, 'create_financial_tips_plugin_links'), 10, 2);			
		}
		
		// This just echoes the chosen line, we'll position it later
		public function financial_tips() {	
			include("classes/financial-tips-selector.php");
			$prosperity = new FinancialTips_Tips();
			$chosen = $prosperity->get_financial_tips();
			
			$extra = '';
			echo "<p id='prosper'>".$chosen.$extra."</p>";
		}
		
		
		
		// We need some CSS to position the paragraph
		public function financial_tips_css() {
			// This makes sure that the positioning is also good for right-to-left languages
			$x = is_rtl() ? 'left' : 'right';
		
			echo "
			<style type='text/css'>
			#prosper {
				float: $x;
				padding-$x: 15px;
				padding-top: 5px;		
				margin: 0;
				font-size: 11px;
				text-align: $x;
			}
			ul li{background:url('images/financial-tips.gif') no-repeat;}
			</style>
			";
		}		
		
		// Taken from Google XML Sitemaps from Arne Brachhold
		public function create_financial_tips_plugin_links($links, $file) {
			
			if ( $file == plugin_basename(__FILE__) ) {			
				$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VWZC5KNTQ7DG8">' . __('Donate', 'prosperity') . '</a>';
				$links[] = '<a href="http://www.coolchecks.net/blog/wordpress-plugins-financial-tips.html">' . __('Visit Plguin Site', 'Financial Tips') . '</a>';				
			}
			return $links;
		}		
		
		public function displayFinancialTips() {
			include("classes/financial-tips-selector.php");
			$prosperity = new FinancialTips_Tips();
			$chosen = $prosperity->get_financial_tips();
			
			return "<p>$chosen</p>";
		}
		
		//Add shortcode to display scripture in content
		public function FinancialTips_Handler() {
			return $this->displayFinancialTips();
		}		
		
		// Create Admin Panel
		public function add_FinancialTips_menu()
		{
			add_menu_page(__('FinancialTips','menu-FinancialTips'), __('FinancialTips','menu-FinancialTips'), 'manage_options', 'FinancialTips-admin', array($this, 'showFinancialTipsMenu') );
		}
		
		public function showFinancialTipsMenu()
		{	
			?>
  
			<?php
		}		
		
		//Add widget functionality
		public function RegisterFinancialTipsWidget() {		
			//Include Widget
			include("classes/widget.php");		
			register_widget("FinancialTips_Widget");
		}		
	}
}
//add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_options_page( 'Financial Tips', 'Financial Tips', 'manage_options', 'financial-tips', 'financial_tips_options' );
}

function financial_tips_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>

<?
}
if (class_exists("FinancialTips")) {
    global $ob_FinancialTips;
	$ob_FinancialTips = new FinancialTips();
}

       wp_enqueue_script('jquery'); 
       wp_enqueue_script('jquery.validate', '/wp-content/plugins/financial-tips/js/jquery.validate.js', array('jquery'));

      
      register_activation_hook(__FILE__,'install_financialtips');
      
      add_action('admin_menu',    'financialtips_plugin_menu');  
      
      /* Add our function to the widgets_init hook. */
      //add_action( 'widgets_init', 'verticalScrollSet' );

      function install_financialtips(){
      
           global $wpdb;
           $table_name = $wpdb->prefix . "financial_tips";
           
                  $sql = "CREATE TABLE " . $table_name . " (
                       id int(10) unsigned NOT NULL auto_increment,
                       tip varchar(255) NOT NULL,
                       category varchar(100) NOT NULL,
                      PRIMARY KEY  (id)
                );";
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               dbDelta($sql);
               $query = "
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(3, 'Get paid what you''re worth and spend less than you earn.', 'Budgeting Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(4, 'Keep good financial records so you can be ready for tax time.', 'Budgeting Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(5, 'The first step to budgeting is to write down your monthly expenses.', 'Budgeting Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(6, 'You need a budget whether you make $15 thousand or hundreds of thousands of dollars a year.', 'Budgeting Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(7, 'Increase your contributions to your 401K plan.', 'Investment Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(8, 'Maximize your employment benefits like a 401(k) plan, flexible spending accounts, medical and dental insurance.', 'Investment Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(9, 'The most powerful force in the world is compound interest. ~ Albert Einstein', 'Investment Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(10, 'The world doesn''t owe you a living. Your destiny is up to you.', 'Investment Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(11, 'You need to have something earning money for you while you sleep.', 'Investment Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(12, 'Use check fraud protection when ordering checks.', 'Banking Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(13, 'Check banking activity online', 'Banking Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(14, 'Make more income and more deposits.', 'Banking Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(15, 'Ask about your bank about identity theft protection services.', 'Banking Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(16, 'Avoid debt. Pay off all credit card expenses within the month but do have a credit card so you have a credit history.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(17, 'Credit card debt is the number one obstacle to getting ahead financially.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(18, 'Never charge something that you can''t pay for if you lost your job.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(19, 'Pay your credit card balance off every month. No exceptions.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(20, 'Do you know when you will be debt free? Set your debt free date.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(21, 'The worst kind of debt is the kind you can''t afford to repay.', 'Money Management Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(22, '&quot;The critical ingredient is getting off your butt and doing something. It''s as simple as that. A lot of people have ideas, but there are few who decide to do something about them now. Not tomorrow. Not next week. But today. The true entrepreneur is a doer, not a dreamer.&quot; – Nolan Bushnell', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(23, 'Bag your lunch at work. You could save $5 day or $1,300 a year.', 'Savings Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(24, 'Never buy brand stuff - buy generic.', 'Savings Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(25, 'Resolve to set aside a minimum of 5% to 10% of your salary for savings BEFORE you start paying your bills.', 'Savings Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(26, 'The average pack-a-day smoker spends about $5 a day for cigarettes. That''s $1,825 per year. Stop smoking.', 'Savings Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(27, 'Substitute water for soda. The $2 a day you may spend for a soda could save you $500 a year.', 'Savings Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(28, 'Don''t join the 70% of Americans who don''t have a will.', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(29, 'Don''t trade time for money. Hours are limited, therefore your income will be, too.', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(30, 'I have found no greater satisfaction than achieving success through honest dealing and strict adherence to the view that, for you to gain, those you deal with should gain as well – Alan Greenspan', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(31, 'It is not the strongest of the species that survive, nor the most intelligent, but the one most responsive to change – Charles Darwin', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(32, 'The first rule of any technology used in a business is that automation applied to an efficient operation will magnify the efficiency.  The second is that automation applied to an inefficient operation will magnify the inefficiency – Bill Gates', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(33, 'True happiness is a state of mind which can not be attained by  material things.', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(34, 'Whatever the mind of man can conceive and believe, it can achieve. Thoughts are things! And powerful things at that, when mixed with definiteness of purpose, and burning desire, can be translated into riches – Napoleon Hill', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(35, 'You must prepare for your own financial future and not depend on anyone.', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(36, 'You''re most unhappy customers are your greatest source of learning – Bill Gates', 'Strategy Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(37, 'Make a budget that includes saving money every month.', 'Budgeting Tips');
					INSERT INTO `".$table_name."` (`id`, `tip`, `category`) VALUES(38, 'It may take years to set up a budget that works for you. Stick with it until you find what works.', 'Money Management Tips');               
               ";
               dbDelta($query);
      } 

	  
      function financialtips_plugin_menu(){
      
       // add_menu_page(__('Financial Tips'), __("Financial Tips"), 'administrator', 'Financialtips-settings','managetips');
        add_options_page( 'Financial Tips', __("Financial Tips"), 'administrator', 'Financialtips-settings', 'managetips' );
       
      }
      
 
    /* Function that registers our widget. */
    function verticalScrollSet() {
            register_widget( 'verticalScroll' );
        }
     
  
    function managetips(){
      
      $action='gridview';
      global $wpdb;
      
     
      if(isset($_GET['action']) and $_GET['action']!=''){
         
   
         $action=trim($_GET['action']);
       }
       
    ?>
    <style type="text/css">
       .news_error{
         color:red;
        }
       .succMsg{
            background:#E2F3DA ;
             border: 1px solid #9ADF8F;
             color:#556652 !important;
             width:100% !important;
             padding:8px 8px 8px 36px;
             text-align:left;
             margin:5px;
             margin-left: 0px;
             margin-top: 30px;
             width:750px !important;
       }
      .errMsg{

             background:#FFCECE ;     
             border: 1px solid #DF8F8F;
             color:#665252 !important;
             width:100% !important;
             padding:8px 8px 8px 36px; 
             text-align:left;
             margin:5px;
             margin-left: 0px;
             margin-top: 30px;
             width:750px !important;
     
        }

   </style>    
			<div id='wrap'>

			<h2>Financial Tips</h2>
			<p>Keep your blog constantly updated with new financial tips. Tips will rotate daily, weekly or monthly.<br /> You can select the number of tips to display and
			the categories to display. Go to Appearance > Widgets > Financial Tips Widgets to choose these options.<br /> You can aslo display multiple widgets.
			To add, edit or delete any existing tips, use the management tool below.</p> 
			<p><strong>Shortcodes:</strong> <code>[FinancialTips]</code> - Adding this shortcode to any of your posts will also display random financial tips to your visitors.</p>

		


																			</div>        
   <?php
      if(strtolower($action)==strtolower('gridview')){ 
      
      require_once("Pager.php");
      
   ?> 
       <div class="wrap">
        
        <?php 
             
             $messages=get_option('financialtips_messages'); 
             $type='';
             $message='';
             if(isset($messages['type']) and $messages['type']!=""){
             
                $type=$messages['type'];
                $message=$messages['message'];
                
             }  
             
  
             if($type=='err'){ echo "<div class='errMsg'>"; echo $message; echo "</div>";}
             else if($type=='succ'){ echo "<div class='succMsg'>"; echo $message; echo "</div>";}
             
             
             update_option('financialtips_messages', array());     
        ?>

      <div style="width: 100%;">  
        <div style="float:left;width:69%;" >
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2 style="color:#0562A7">Manage Your Financial Tips <a class="button add-new-h2" href="admin.php?page=Financialtips-settings&action=addedit">Add New</a> </h2>
        <br/>    
        
        <form method="POST" action="admin.php?page=Financialtips-settings&action=deleteselected" id="posts-filter">


              <div class="alignleft actions">
                <select name="action_upper">
                    <option selected="selected" value="-1">Bulk Actions</option>
                    <option value="delete">delete</option>
                </select>
                <input type="submit" value="Apply" class="button-secondary action" id="deleteselected" name="deleteselected">
                <strong>Browse By:</strong> <select name="bcategory" size="1" onchange="self.location='admin.php?page=Financialtips-settings&action=gridview&opt=browse&tid='+this.value">
                <option value="">-All Financial Tips-</option>
<?                        
                      $query="SELECT DISTINCT category FROM ".$wpdb->prefix."financial_tips order by category";
                      $rows=$wpdb->get_results($query,'ARRAY_A');
                    
                                     
                           foreach($rows as $row){ 
?>
						<option value="<?=$row['category']?>"<?=(isset($_GET['tid']) && $_GET['tid'] == $row['category'])?' selected="selected"':''?>><?=$row['category']?></option>
<?
}
?>                
                </select>
            </div>
          <br/>  
             <br/>  
         <br class="clear">
         <table cellspacing="0" class="wp-list-table widefat fixed posts" style="width:100%">
         <thead>
         <tr>
         <th style="width:30px" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
        <th style="width:200px" class="manage-column column-tip sortable desc" scope="col"><span>Tip</span></th>
        <th style="width:100px"  class="manage-column column-author sortable desc" scope="col"><span>Category</span></th>
        <th style="width:50px" class="manage-column column-author sortable desc" scope="col"><span>Edit</span></th>
        <th style="width:50px" class="manage-column column-author sortable desc" scope="col"><span>Delete</span></th>
        </thead>

    <tfoot>
    <tr>
        <th  style="width:30px" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
        <th style="width:200px" class="manage-column column-tip sortable desc" scope="col"><span>Tip</span></th>
        <th style="width:100px" class="manage-column column-category sortable desc" scope="col"><span>Category</span></th>
        <th style="width:50px" class="manage-column column-author sortable desc" scope="col"><span>Edit</span></th>
        <th style="width:50px" class="manage-column column-author sortable desc" scope="col"><span>Delete</span></th>
    </tr>
    </tfoot>

    <tbody id="the-list">
                   <?php
                      $query="SELECT * FROM ".$wpdb->prefix."financial_tips";
                      if(isset($_GET['opt']) && $_GET['opt'] == 'browse' && isset($_GET['tid']) && $_GET['tid'])
                      {
						  $query.= " WHERE category = '".$_GET['tid']."'";
					  }
                      $query.= " order by id desc";
                      $rows=$wpdb->get_results($query,'ARRAY_A');
                    
                    if(count($rows) > 0){
                    
                        $params = array(
                                'mode'     => 'Sliding',
                                'perPage'  => 10,
                                'delta'    => 10,
                                'itemData' => $rows,
                                'fixFileName' => false,
                               );
                           // generate pager object
                           $pager =& Pager::factory($params);

                           // get data for current page and print
                           $pageset = $pager->getPageData();

                           $rows = $pageset;

                                      
                           foreach($rows as $row){ 
                               
                               $id=$row['id'];
                               $editlink="admin.php?page=Financialtips-settings&action=addedit&id=$id";
                               $deletelink="admin.php?page=Financialtips-settings&action=delete&id=$id";
                               
                            ?>
                            <tr valign="top" class="alternate author-self status-publish format-default iedit" id="post-113">
                                <th style="width:30px" class="check-column" scope="row"><input type="checkbox" value="<?php echo $row['id'] ?>" name="tips[]"></th>
                                <td style="width:200px" class="post-title page-title column-title"><strong><?php echo stripslashes($row['tip']) ?></strong></td>  
                                <td style="width:100px" class="date column-date"><abbr title=""><?php echo $row['category'] ?></td>
                                <td style="width:50px" class="post-title page-title column-title"><strong><a href='<?php echo $editlink; ?>' title="edit">Edit</a></strong></td>  
                                <td style="width:50px" class="post-title page-title column-title"><strong><a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();"  title="delete">Delete</a> </strong></td>  
                           </tr>
                     <?php 
                             } 
                    }
                   else{
                       ?>
                   
                      <tr valign="top" class="alternate author-self status-publish format-default iedit" id="post-113">
                                <td colspan="5" class="post-title page-title column-title" align="center"><strong>No Tips found</strong></td>  
                           </tr>
                  <?php 
                   } 
                 ?>      
        </tbody>
  </table>
  <?php
    if(sizeof($rows)>0){
    
     $links = $pager->getLinks();
     echo "<div class='paggingDiv' style='padding-top:10px'>";
     echo $links['all'];
     echo "</div>";
    }
  ?>
    <br/>
    <div class="alignleft actions">
        <select name="action">
            <option selected="selected" value="-1">Bulk Actions</option>
            <option value="delete">delete</option>
        </select>
        <input type="submit" value="Apply" class="button-secondary action" id="deleteselected" name="deleteselected">
    </div>

    </form>
        <script type="text/JavaScript">

            function  confirmDelete(){
            var agree=confirm("Are you sure you want to delete this Tips ?");
            if (agree)
                 return true ;
            else
                 return false;
        }
     </script>


        <br class="clear">
        </div>
           
           </div>
            
    </div>  
   <br class="clear">         
<?php 
  }   
  else if(strtolower($action)==strtolower('addedit')){
    ?>
     <span><h3 style="color: #21759B;">Manage Financial Tips</h3></span>
  <?php        
    if(isset($_POST['btnsave'])){
    
       //edit save
       if(isset($_POST['tipid'])){
       
            //add new
          
                $tip=trim(addslashes($_POST['tip']));
                $new_category=trim($_POST['new_category']);
                $category=trim(addslashes($_POST['category']));
                $tipId=trim($_POST['tipid']);
                if($new_category)
                	$category = $new_category;
                $location='admin.php?page=Financialtips-settings';
                
                try{
                        $query = "update ".$wpdb->prefix."financial_tips set tip='$tip',category='$category'
                                  where id=$tipId";
                        $wpdb->query($query); 
                       
                         $financialtips_messages=array();
                         $financialtips_messages['type']='succ';
                         $financialtips_messages['message']='Tip has been updated successfully.';
                         update_option('financialtips_messages', $financialtips_messages);

     
                 }
               catch(Exception $e){
               
                      $financialtips_messages=array();
                      $financialtips_messages['type']='err';
                      $financialtips_messages['message']='Error while updating Tip.';
                      update_option('financialtips_messages', $financialtips_messages);
                }  
                          
              echo "<script> location.href='$location&action=gridview&opt=browse&tid=".urlencode($category)."';</script>";
       }
      else{
      
             //add new
          
                $tip=trim(addslashes($_POST['tip']));
                $new_category=trim($_POST['new_category']);
                $category=trim(addslashes($_POST['category']));
                if($new_category)
                {
					$category=$new_category;
				}
                
                $location='admin.php?page=Financialtips-settings';
                
                try{
                        $query = "INSERT INTO ".$wpdb->prefix."financial_tips (category, tip) 
                                  VALUES ('$category','$tip')";
                        $wpdb->query($query); 
                       
                         $financialtips_messages=array();
                         $financialtips_messages['type']='succ';
                         $financialtips_messages['message']='New Tip has been added successfully.';
                         update_option('financialtips_messages', $financialtips_messages);

     
                 }
               catch(Exception $e){
               
                      $financialtips_messages=array();
                      $financialtips_messages['type']='err';
                      $financialtips_messages['message']='Error while adding Tip.';
                      update_option('financialtips_messages', $financialtips_messages);
                }  
                          
                echo "<script> location.href='$location';</script>";          
            
       } 
        
    }
   else{ 
  
  ?>

     <div style="width: 100%;">  
        <div style="float:left;width:69%;" >
            <div class="wrap">
          <?php if(isset($_GET['id']) and $_GET['id']>0)
          { 
               
                $id= $_GET['id'];
                $query="SELECT * FROM ".$wpdb->prefix."financial_tips WHERE id=$id";
                $myrow  = $wpdb->get_row($query);
                
                if(is_object($myrow)){
                
                  $tip=stripslashes($myrow->tip);
                  $category=$myrow->category;
                  
                }   
              
          ?>
           
            <h2>Update Financial Tip</h2>
               
          <?php }else{ 
                  
                  $tip='';
                  $category='';
          
          ?>
          <h2>Add Financial Tip </h2>
          <?php } ?>
            
            <br/>
            <div id="poststuff">
              <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                  <form method="post" action="" id="addnews" name="addnews">
                
                      <div class="stuffbox" id="namediv" style="min-width:550px;">
                         <h3><label for="link_name">Category</label></h3>
                        <div class="inside">
                        <select name="category" id="category" class="" size="1"> 
<?                        
                      $query="SELECT DISTINCT category FROM ".$wpdb->prefix."financial_tips order by category";
                      $rows=$wpdb->get_results($query,'ARRAY_A');
                    
                                     
                           foreach($rows as $row){ 
?>
						<option value="<?=$row['category']?>"<?=($category == $row['category'])?' selected="selected"':''?>><?=$row['category']?></option>
<?
}
?>
							</select> <strong>OR</strong><br /><br />
                            New Category: <input type="text" id="new_category"  class="" tabindex="1" size="30" name="new_category">
                             <div style="clear:both"></div>
                         </div>
                      </div>
                      <div class="stuffbox" id="namediv" style="min-width:550px;">
                         <h3><label for="link_name">Tip</label></h3>
                        <div class="inside">
                            <input type="text" id="tip" class="required tip"  tabindex="1" size="30" name="tip" value="<?php echo $tip; ?>">
                             <div style="clear:both"></div>
                             <div></div>
                             <div style="clear:both"></div>
                         </div>
                      </div>

                        <?php if(isset($_GET['id']) and $_GET['id']>0){ ?> 
                           <input type="hidden" name="tipid" id="tipid" value="<?php echo $_GET['id'];?>">
                        <?php
                        } 
                        ?>
                       <input type="submit" name="btnsave" id="btnsave" value="Save Changes" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancel" id="cancel" value="Cancel" class="button-primary" onclick="location.href='admin.php?page=Financialtips-settings'">
                                  
                 </form> 
                  <script>
                     var $n = jQuery.noConflict();  
                     $n(document).ready(function() {  
                        $n("#addnews").validate({
                                 errorClass: "news_error",
                                 errorPlacement: function(error, element) {
                                 error.appendTo( element.next().next().next());
                             }

                        })
                    });
                    
                </script> 

                </div>
          </div>
        </div>  
     </div>      
         </div>
           
           
           </div> 
     
     
    <?php 
    } 
  }else if(strtolower($action)==strtolower('delete')){
  
        $location='admin.php?page=Financialtips-settings';
        $deleteId=(int)$_GET['id'];
                
                try{
                        $query = "delete from  ".$wpdb->prefix."financial_tips where id=$deleteId";
                        $wpdb->query($query); 
                       
                         $financialtips_messages=array();
                         $financialtips_messages['type']='succ';
                         $financialtips_messages['message']='Tip has been deleted successfully.';
                         update_option('financialtips_messages', $financialtips_messages);

     
                 }
               catch(Exception $e){
               
                      $financialtips_messages=array();
                      $financialtips_messages['type']='err';
                      $financialtips_messages['message']='Error while deleting Tip.';
                      update_option('financialtips_messages', $financialtips_messages);
                }  
                          
          echo "<script> location.href='$location';</script>";
              
  }  
  else if(strtolower($action)==strtolower('deleteselected')){
  
           $location='admin.php?page=Financialtips-settings'; 
          if(isset($_POST) and isset($_POST['deleteselected']) and  ( $_POST['action']=='delete' or $_POST['action_upper']=='delete')){
          
                if(sizeof($_POST['tip']) >0){
                
                        $deleteto=$_POST['tips'];
                        $implode=implode(',',$deleteto);   
                        
                        try{
                                $query = "delete from  ".$wpdb->prefix."financial_tips where id in ($implode)";
                                $wpdb->query($query); 
                               
                                 $financialtips_messages=array();
                                 $financialtips_messages['type']='succ';
                                 $financialtips_messages['message']='Selected Tips have been deleted successfully.';
                                 update_option('financialtips_messages', $financialtips_messages);

             
                         }
                       catch(Exception $e){
                       
                              $financialtips_messages=array();
                              $financialtips_messages['type']='err';
                              $financialtips_messages['message']='Error while deleting Tip.';
                              update_option('financialtips_messages', $financialtips_messages);
                        }  
                              
                       echo "<script> location.href='$location';</script>";
                
                
                }
                else{
                
                    echo "<script> location.href='$location';</script>";   
                }
            
           }
           else{
           
                echo "<script> location.href='$location';</script>";      
           }
     
      }    
?>
			
			<p><em>If you enjoy using this plugin, please consider donating! All donations are appreciated!</em></p>

			 <p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="VWZC5KNTQ7DG8">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form></p>	
<?
}  
    
 
?>