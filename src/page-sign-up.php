<?php

$teams = get_posts( array(
	'numberposts' => -1,
	'orderby'     => 'title',
	'order'       => 'ASC',
	'post_type'   => 'team',
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );

if (!is_user_logged_in() && !empty($_POST)){

	if (empty($_POST['email']) || empty($_POST['password'])){
		echo "<script>location.href = '/sign-up/';</script>";
		exit();
	}
	$user = wp_create_user($_POST['email'], $_POST['password'], $_POST['email']);


	if (isset($_POST['have_sous'])){
		if (isset($_POST['school'])){
			$id_school=$_POST['school'];
			$fields=get_fields($id_school);
			$members=$fields['members'];
			$users=array();
			foreach ($members as $member) {
				$users[]=$member['ID'];
			}
			$users[]=$user;
			// print_r($users);
			update_field('members', $users, $id_school);

		}
	}

	if ( is_wp_error( $user ) ) {
		echo $user->get_error_message();
	} else {
		if (!isset($_POST['have_sous'])){
			update_user_meta($user, 'wp_user_level', 2);
		}
		$contact=$_POST['contact'];
	    if (str_contains($contact, 'https://')){
	        $contact=mb_substr($contact,8);
	    }
		update_user_meta($user, 'first_name', $_POST['first_name']);
		update_user_meta($user, 'last_name', $_POST['second_name']);
		update_user_meta($user, 'contact', $contact);
		update_user_meta($user, 'class', $_POST['class']);
		echo "<script>location.href = '/sign-in/';</script>";
		exit();
	}
}
if (is_user_logged_in()){
	echo "<script>location.href = '/sign-in/';</script>";
	exit();
}
else{
?>
<?php 

get_header(); 

?>



<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="h1_main">Регистрация</h1>

			<form id="register_f" name="register" method="post" action="/sign-up">
				<div class="form_group">
				<label class="lbl2">Укажите настоящее имя</label>
				<input name="first_name" class="input_main" type="text" maxlength="20">
				<label class="lbl2">Укажите настояющую фамилию</label>
				<input name="second_name" class="input_main" type="text" maxlength="20">
				<label class="lbl2">Контакты (ссылка на Telegram)</label>
				<input name="contact" class="input_main" type="text" maxlength="30">
				<label class="lbl2">Класс с буквой (11А)</label>
				<input name="class" class="input_main" type="text" maxlength="3">
				<label class="lbl2">Если в вашем уч. заведении есть школьный совет, укажите свою школу</label>
				<input name="have_sous" id="havesous" class="form-check-input have_sous" type="checkbox" value="1" checked>
				<label for="havesous" class="lbl3">Есть совет?</label>
				<input type="text" name="school_f" placeholder="Выберете школу" class="input_main" list="schools">
				<input type="hidden" name="school" value="0">
				<datalist id="schools">
					<?
					foreach ($teams as $team) {
						$school = get_fields($team->ID)['school'];
						$name_sous=$team->post_title;
					?>
					<option value="<?=$school?>"><?=$name_sous?></option>
					<? } ?>
				</datalist>
				

<!-- 				<select name="school" class="form-select school input_main">
				<option value="0">Выберете школу</option>
				</select> -->
				
				<label class="lbl2">Email</label>
				<input name="email" class="input_main" type="email" placeholder="">
				<label class="lbl2">Пароль</label>
				<input name="password" class="input_main" type="password" maxlength="20" placeholder="">
				<div style="display: grid; grid-template-columns: 1fr 9fr;">
				<input name="agree_politics" id="agreepolitics" class="form-check-input check" type="checkbox" value="0">
				<label for="agreepolitics" class="lbl3">Регистрируясь, вы соглашаетесь с <a href="/privacy/">Политикой конфиденциальности</a></label>
				</div>
				</div>
			</form>
			<div class="btn_sub_form">
			<input name="enter" class="btn_submit" id="sbm_reg" type="button" value="Отправить">
		</div>
		<p class="no_sign">Уже есть аккаунт? <a href="/sign-in">Войти</a></p>
		</div>
	</div>
</div>
<div class="modal-background"></div>
<div id="error_form" class="modal">
<div class="form_edit_content">
<h2 class="block_main_h">Ошибка!</h2>
<p id="error_sign_up">Возможно, некоторые поля пусты</p>
<button id="cancel_error" class="btn_form_ok">Ок</button>
</div>
</div>
<?
}

?>
<? get_footer(); ?>
