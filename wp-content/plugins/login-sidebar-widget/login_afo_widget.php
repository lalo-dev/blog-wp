<?php

class login_wid extends WP_Widget {
	
	public function __construct() {
		add_action( 'wp_head', array( $this, 'custom_styles_afo' ) );
		parent::__construct(
	 		'login_wid',
			'Login Widget',
			array( 'description' => __( 'This is a simple login form in the widget.', 'login-sidebar-widget' ), )
		);
	 }

	public function widget( $args, $instance ) {
		extract( $args );
		
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->loginForm( $args['widget_id'] );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = '';
		if(!empty($instance[ 'wid_title' ])){
			$wid_title = esc_html($instance[ 'wid_title' ]);
		}
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','login-sidebar-widget'); ?> </label>
        <?php form_class::form_input('text',$this->get_field_name('wid_title'),$this->get_field_id('wid_title'),$wid_title,'widefat');?>
		</p>
		<?php 
	}
	
	public function add_remember_me(){
		$login_afo_rem = get_option('login_afo_rem');
		if($login_afo_rem == 'Yes'){
			echo '<div class="log-form-group"><label for="remember"> '.__('Remember Me','login-sidebar-widget').'</label>  '.form_class::form_checkbox('remember','','Yes','','','','',false,'',false).'</div>';
		}
	}
	
	public function add_extra_links(){
		$login_afo_forgot_pass_link = get_option('login_afo_forgot_pass_link');
		$login_afo_register_link = get_option('login_afo_register_link');
		if($login_afo_forgot_pass_link){
			echo '<a href="'. esc_url( get_permalink($login_afo_forgot_pass_link) ).'">'.__('Lost Password?','login-sidebar-widget').'</a>';
		}
		
		if( $login_afo_forgot_pass_link and $login_afo_register_link ){
			echo ' | ';
		}
		
		if($login_afo_register_link){
			echo '<a href="'. esc_url( get_permalink($login_afo_register_link) ) .'">'.__('Register','login-sidebar-widget').'</a>';
		}
	}
	
	public static function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}

	public function gen_redirect_url(){
		$redirect_page = get_option('redirect_page');
		$redirect_page_url = get_option('redirect_page_url');
		
		if(isset($_REQUEST['redirect'])){
			$redirect = sanitize_text_field($_REQUEST['redirect']);
		} elseif(isset($_REQUEST['redirect_to'])){
			$redirect = sanitize_text_field($_REQUEST['redirect_to']);
		} else {
			if($redirect_page_url){
				$redirect = $redirect_page_url;
			} else {
				if($redirect_page){
					$redirect = get_permalink($redirect_page);
				} else {
					$redirect = $this->curPageURL();
				}
			}
		}
		return esc_url( $redirect );
	}
	
	public function loginForm( $wid_id = '' ){
		$this->load_script();
		if(!is_user_logged_in()){		
		?>
		<div id="log_forms" class="log_forms <?php echo $wid_id;?>">
        <?php $this->error_message();?>
		<form name="login" id="login" method="post" action="" autocomplete="off">
		<input type="hidden" name="option" value="afo_user_login" />
		<input type="hidden" name="redirect" value="<?php echo $this->gen_redirect_url(); ?>" />
		<div class="log-form-group">
			<label for="username"><?php _e('Username','login-sidebar-widget');?> </label>
			<input type="text" name="userusername" required="required" title="<?php _e('Please enter username','login-sidebar-widget');?>"/>
		</div>
		<div class="log-form-group">
			<label for="password"><?php _e('Password','login-sidebar-widget');?> </label>
			<input type="password" name="userpassword" required="required" title="<?php _e('Please enter password','login-sidebar-widget');?>"/>
		</div>
        
        <?php do_action('login_afo_form');?>
        
        <?php do_action('login_form');?>
		
		<?php $this->add_remember_me();?>

		<div class="log-form-group"><input name="login" type="submit" value="<?php _e('Login','login-sidebar-widget');?>" /></div>
		<div class="log-form-group extra-links">
			<?php $this->add_extra_links();?>
		</div>
		</form>
		</div>
		<?php 
		} else {
		$logout_redirect_page = get_option('logout_redirect_page');
		$link_in_username = get_option('link_in_username');
		if($logout_redirect_page){
			$logout_redirect_page = get_permalink($logout_redirect_page);
		} else {
			$logout_redirect_page = $this->curPageURL();
		}
		$current_user = wp_get_current_user();
     	
		if($link_in_username){
			$link_with_username = '<a href="'. esc_url( get_permalink($link_in_username) ) .'">'.__('Howdy','login-sidebar-widget').', '.$current_user->display_name.'</a>';
		} else {
			$link_with_username = __('Howdy','login-sidebar-widget').', '.$current_user->display_name;
		}
		?>
        <div class="logged-in"><?php echo $link_with_username;?> | <a href="<?php echo wp_logout_url( apply_filters( 'lwws_logout_redirect', $logout_redirect_page, $current_user->ID ) ); ?>" title="<?php _e('Logout','login-sidebar-widget');?>"><?php _e('Logout','login-sidebar-widget');?></a></div>
		<?php 
		}
	}
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].$this->message_close_button().'</div>';
		}
	}
	
	public function message_close_button(){
		$cb = '<span href="javascript:void(0);" onclick="closeMessage();" class="close_button_afo"></span>';
		return $cb;
	}
	
	public function custom_styles_afo(){
		echo '<style>';
		echo stripslashes(get_option('custom_style_afo'));
		echo '</style>';
	}
	
	public function load_script(){?>
		<script type="text/javascript">
			function closeMessage(){jQuery('.error_wid_login').hide();}
			jQuery(document).ready(function () {
				jQuery('#login').validate({ errorClass: "lw-error" });
			});
		</script>
	<?php }
	
} 

function login_validate(){
	if( isset($_POST['option']) and $_POST['option'] == "afo_user_login"){
		global $aperror;
		$lla = new login_log_adds;
		$aperror = new WP_Error;
		
		if($_POST['userusername'] != "" and $_POST['userpassword'] != ""){
			$creds = array();
			$creds['user_login'] = sanitize_text_field($_POST['userusername']);
			$creds['user_password'] = $_POST['userpassword'];
			
			if(isset($_POST['remember']) and $_POST['remember'] == "Yes"){
				$remember = true;
			} else {
				$remember = false;
			}
			$creds['remember'] = $remember;
			$user = wp_signon( $creds, true );
			if(isset($user->ID) and $user->ID != ''){
				wp_set_auth_cookie($user->ID, $remember);
				$lla->log_add($_SERVER['REMOTE_ADDR'], 'Login success', date("Y-m-d H:i:s"), 'success');
				wp_redirect( apply_filters( 'lwws_login_redirect', sanitize_text_field($_POST['redirect']), $user->ID ) );
				exit;
			} else{
				$aperror->add( "msg_class", "error_wid_login" );
				$aperror->add( "msg", __(get_login_error_message_text($user),'login-sidebar-widget') );								
				do_action('afo_login_log_front', $user);
			}
		} else {
			$aperror->add( "msg_class", "error_wid_login" );
			$aperror->add( "msg", __('Username or password is empty!','login-sidebar-widget') );
			$lla->log_add($_SERVER['REMOTE_ADDR'], 'Username or password is empty', date("Y-m-d H:i:s"), 'failed');
		}
	}
}