
<?php 

if (is_user_logged_in()){
	$user_level = get_user_meta(get_current_user_id(),'wp_user_level')[0];
	if ($user_level==0){
		echo "<script>location.href = '/teams/';</script>";
	}
	else{
		echo "<script>location.href = '/homepage/';</script>";
	}
	exit();
}
if (isset($_GET['login'])) {
	$error = $_GET['login'];
}
 
	get_header(); 
	// print_r(get_user_by('email', 'andreyvasiliev1204@gmail.com')->user_pass);
	?>
<h1 class="h1_main">Вход</h1>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      	<form name="loginform" id="sigin_loginform" method="post" action="<?php echo site_url( '/wp-login.php' ); ?>"> 
			  <div class="form_group">
			    <label class="lbl2">Email</label>
			    <input name="log" type="text" class="input_main" id="user_login">
			  </div>
			  <div class="form_group">
			    <label class="lbl2">Пароль</label>
			    <input name="pwd" type="password" class="input_main" id="user_pass">
			  </div>
			  
			  <p class="error">
				<?php 
				if (isset($error)) {
					echo $error;
				}
				?>
				</p>
			  <!-- <button id='wp-submit=type="submit" class="btn btn-primary">Войти</button> -->
			  <input type="hidden" class="btn btn-primary" value="<?php echo esc_attr( site_url().'/homepage/' ); ?>" name="redirect_to">
		</form>
		<div class="btn_sub_form">
		<input type="button" class="btn_submit btn_sum_in" value="Войти" name="wp-submit" >
		</div>
		<p class="no_sign">Еще нет аккаунта? <a href="/sign-up">Зарегестироваться</a></p>
    </div>
  </div>
</div>

<div class="modal-background"></div>
<div id="error_form" class="modal">
<div class="form_edit_content">
<h2 class="block_main_h">Ошибка!</h2>
<p id="error_info">Возможно, некоторые поля пусты</p>
<button id="cancel_error" class="btn_form_ok">Ок</button>
</div>
</div>


<?
// }
// 453%eTWEq3DKQ(231&
?>
<? get_footer(); ?>