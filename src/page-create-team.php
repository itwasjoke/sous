<?php 
$user_level = get_user_meta(get_current_user_id(),'wp_user_level')[0];
$meta_query['members'] = array(
    'key' => 'members',
    'value' => sprintf(':"%s";', get_current_user_id()),
    'compare' => 'LIKE',
);
$meta_query['relation'] = 'OR';

$teams = get_posts( array(
	'numberposts' => -1,
	'orderby'     => 'title',
	'order'       => 'ASC',
	'meta_query' => $meta_query,
	'post_type'   => 'team',
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );
if (!is_user_logged_in() || $user_level!=2 || (!$teams && $user_level!=2)){
echo "<script>location.href = '/landing/';</script>";
exit();

}
if (isset($_POST['send'])){
	$name=$_POST['title'];
	$desc=$_POST['description'];
	$school=$_POST['school'];
	$users[] = get_current_user_id();
	$post = array(
		'post_title' => $name,
		'post_content' => $desc,
		'post_type' => 'team',
		'post_status'    => 'publish',
	);
	$id_post= wp_insert_post($post);
	update_field('creator', get_current_user_id(), $id_post);
	update_field('members', $users, $id_post);
	update_field('school', $school, $id_post);
	echo "<script>location.href = '/homepage/';</script>";
	exit();
}

?>

<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
<form name='team' method="post" action="/create-team">
	<h1 class="h1_main">Создать сообщество</h1>
	<input class="input_form" name='title' type='text' placeholder="Название"/>
	<textarea class="input_form" placeholder="Описание" name="description"></textarea>
	<label class="lbl2">Настоящее название учебного заведения, по которому участники будут находить ваш соус</label>
	<input class="input_form" name='school' type='text' placeholder="Например: ГБОУ Лицей №144"/>
	<div class="btn_sub_form">
	<input class="btn_submit" name='send' type="submit">
	</div>
</form>
</div>
</div>
</div>
<? get_footer(); ?>