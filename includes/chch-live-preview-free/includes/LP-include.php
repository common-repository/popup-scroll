<?php 
  //include LP_Objects with holds all LP instances
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Objects.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Objects.php');  
  }
  
  //include main LP class
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP.php');  
  }
  
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Sanitize.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Sanitize.php');  
  }
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Data.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Data.php');  
  }
  
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Templates.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Templates.php');  
  }
  
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Fields.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Fields.php');  
  }
  
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Form.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Form.php');  
  }
   
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Views.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_Views.php');  
  }
  
  //include LP_wp_hooks wich handles wp hooks
  if(file_exists(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_wp_hooks.php')){
    include_once(CHCH_LIVE_PREVIEW_FREE_DIR.'includes/LP_wp_hooks.php');  
  }

?>