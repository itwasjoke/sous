<?php 

if (!is_user_logged_in()){
echo "<script>location.href = '/landing/';</script>";
exit();
}
$id_user=get_current_user_id();
$userdata=get_userdata($id_user);
$name1=$userdata->user_firstname;
$name2=$userdata->user_lastname;
$level=get_user_meta($id_user,'wp_user_level',true);
$contact=get_user_meta($id_user,'contact',true);
$class=get_user_meta($id_user,'class',true);
$postuser=$userdata->user_description;
$email=$userdata->user_email;
$password=$userdata->user_pass;


get_header(); 
// echo $contact;
$posts = get_posts( array(
	'numberposts' => -1,
	'orderby'     => 'date',
	'order'       => 'ASC',
	'post_type'   => 'post',
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );
// DESC
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="h1_main">Профиль</h1>
			<form name="profile_edit" action="/homepage/" method="POST">
				<label class="lbl1">Имя</label>
				<input type="text" name="first_name" value="<?=$name1?>" class="input_main">
				<label class="lbl1">Фамилия</label>
				<input type="text" name="last_name" value="<?=$name2?>" class="input_main">
				<label class="lbl1">Должность</label>
				<input type="text" name="description" value="<?=$postuser?>" class="input_main">
				<label class="lbl1">Класс</label>
				<input type="text" name="class" value="<?=$class?>" class="input_main" placeholder="11A">
				<label class="lbl1">Контакты (ссылка на Telegram)</label>
				<input type="text" name="contact" value="<?=$contact?>" class="input_main" placeholder="t.me/username">
				 <p class="confirm_user_p errors hide">Йоу, какие-то поля не заполнены</p>
				 <div class="btn_sub_form">
				<input type="submit" name="edit_profile" value="Сохранить" class="btn_submit">
			</div>
			</form>
			<?
			// echo $level.'<br>';

			?>

		</div>
	</div>
</div>

<?php get_footer(); ?>