<?php 
if (!is_user_logged_in()){
echo "<script>location.href = '/landing/';</script>";
exit();
}

$user_level = get_user_meta(get_current_user_id(),'wp_user_level')[0];
// update_user_meta(44, 'wp_user_level', 2);
// update_user_meta(16,'description',"специалист");

if (isset($_POST['number'])){
	$post=array();
	$post['ID']=$_POST['number'];
	$post['post_content']=$_POST['more'];
	$post['post_title']=$_POST['title'];
	wp_update_post(wp_slash($post));
	update_post_meta($post['ID'], 'members', $_POST['users']);
	echo "<script>location.href = '/teams/';</script>";
}

if (!isset($_COOKIE['notif4'])){
    setcookie('notif4',0,time()+60*60*24*30,'/teams/');
}
if (!isset($_COOKIE['notif5'])){
    setcookie('notif5',0,time()+60*60*24*30,'/teams/');
}
if (!isset($_COOKIE['notif6'])){
    setcookie('notif6',0,time()+60*60*24*30,'/teams/');
}

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
get_header();
// $members=get_fields(123)['members'];
// $c=count($members);
// for ($i=0; $i<$c;$i++){
// 	if ($members[$i]['ID']==16){
// 		unset($members[$i]);
// 	}
// }
// print_r($members);
// print_r(get_user_meta(34,'wp_user_level',true));
?>
<div class="container">
	<div class="row">
		<div class="col-md-12"> 
<? if ($teams){ ?>
	<h3 class="h3_nameblock">карточка соуса</h3>
	<?
	$id_team=0;
	$author=0;
	foreach ($teams as $team) {
		$id_team=$team->ID;
		$author=$team->post_author;
	    $name=get_the_title($team->ID);
	    $school = get_fields($team->ID)['school'];
	    $id=$team->ID;
	    $team_fields  = get_fields($team->ID);
	    $count_users=count($team_fields['members']);
	    $desc=get_the_content(null, null, $team->ID);
	    $members=$team_fields['members'];
		    
		?>

		<div class="block_info" id="<?=$id?>">
			<h1 class="block_main_h" id="name_team_p"><?=$name?></h1>
			<p class="block_text">О соусе: <br> <span id="desc_team_p"><?=$desc?></span></p>
			<p class="block_text block_ind">Учебное заведение: <span id="school_team_p"><?=$school?></span></p>
			<p class="block_text">Кол-во участников: <span><?=$count_users?></span></p>
			<? if ($user_level>1){ ?>
				<button class="start_edit_team edit_prof_button">Редактировать соус</button>
			<? } ?>
		</div>
		<?
		$notifVis="hide";
		if (isset($_COOKIE['notif4'],$_COOKIE['notif5'],$_COOKIE['notif6'])){
			$notif4=$_COOKIE['notif4'];
	        $notif5=$_COOKIE['notif5'];
	        $notif6=$_COOKIE['notif6'];
			if (!$notif4){
		        $notifVis="";
		        $notifText="Плохо понимаете, как организовать работу органа самоуправления? <a href='top-for-org/'>Топ 7 советов для организатора</a>";
		        $notifNum=4;
		    }
		    else if(!$notif5){
		    	if ($count_users<10){
		    		$notifVis="";
		        	$notifText="Обязательно добавьте в соус всех участников совета и повышайте активность❤️";
		        	$notifNum=5;
		    	}
		       
		    }
		    else if(!$notif6){
		        $notifVis="";
		        $notifText="Если в работе сайта возникли сложности, попробуйте обновить кэш браузера. <a href='https://lifehacker.ru/kak-ochistit-kesh-brauzera/'>Как это сделать?</a>";
		        $notifNum=6;
		    }
		    else{
		        $notifVis="hide";
		    }
		}
		?>
		<div id="/teams/" class="block_notif <?=$notifVis?>">
	        <p><?=$notifText?></p>
	        <div class="close" notif="<?=$notifNum?>"></div>
	    </div>
		<div class="block_btn_dif">
			<form id='plan' name="plan" class="hide" method="POST" action="/tasks">
				<input type="text" name="plan" value="1">
			</form>
			<h4 class="btn_tasks_dif"><p onclick="document.getElementById('plan').submit();">Открыть план работы</p></h4>
		</div>

		<? 
		if ($user_level==0){
			?>
			<div class="block_info">
				<p class="confirm_user_p">Ваш аккаунт ещё не подтвержден. Если прошло больше двух дней после регистрации, свяжитесь с председателем соуса.</p>
			</div>
			<?
		}
		else if ($user_level>0){
			for ($i=0; $i < $count_users; $i++) { 
				$level=get_user_meta($members[$i]['ID'],'wp_user_level',true);
				$class=get_user_meta($members[$i]['ID'],'class',true);
				$contact=get_user_meta($members[$i]['ID'],'contact',true);
				$members[$i]['level']=$level;
				$members[$i]['class']=$class;
				$members[$i]['contact']=$contact;
				$mq1=array(
					'relation'=>'AND',
					array(
						'key'=>'status_task',
						'value'=>3,
						'compare'=>'!=',
					), 
					array(
						'key' => 'assigne',
						'value'=>$members[$i]['ID'],
						'compare'=>'=',
					)
				);
				$arg=array(
					'numberposts' => 1,
		            'orderby'     => 'ID',
		            'order'       => 'DESC',
		            'meta_query' => $mq1,
		            'post_type'   => 'task',
		            'suppress_filters' => true, 
				);
				$c=count(get_posts($arg));
				if ($c==0){
					$members[$i]['count_tasks']="нет работы ●";
					$members[$i]['work']='work0';
				}
				else{
					$members[$i]['count_tasks']="в работе ●";
					$members[$i]['work']='work1';
				}
			}
			// print_r($members);
			?> 
			<h3 class="h3_nameblock">администраторы</h3>
			<div id="users_admins">
				<?
				foreach ($members as $member) {
					if ($member['level']>1){
						$id_edit_user=$member['ID'];
						if ($id_edit_user==$author){
							$leader='leader="yes"';
						}
						else{
							$leader="";
						}
						$name=$member['user_firstname'];
						$s_name=$member['user_lastname'];
						$class=$member['class'];
						$contact=$member['contact'];
						$ctasks=$member['count_tasks'];
						$ctasks_class=$member['work'];
						$user_description=$member['user_description'];
						if (!$user_description){
							$user_description="Должность отсутствует";
						}
						?>
						<div class="block_members users_list" id="div_user_id<?=$member['ID']?>">
							<div class="class_info">
								<h4><?=$class?></h4>
								<a class="hide contact_info<?=$member['ID']?>"><?=$contact?></a>
							</div>
							<div>
							<h2 class="nameuser_h2"><?=$name?> <?=$s_name?></h2>
							<p class="post_member">Должность: <span class="desc_user<?=$member['ID']?>"><?=$user_description?></span></p>
							<p class='<?=$ctasks_class?>'><?=$ctasks?></p>
							</div>
							<? if ($user_level>1){ ?>
								<div class="start_edit_user" <?=$leader?> level="2" id=<?=$id_edit_user?>>
									<img src="<?php echo get_template_directory_uri();?>/img/pencil.png">
								</div>
							<? } ?>
						</div>
						<?
					}
				}
				?>
			</div>
			<h3 class="h3_nameblock">участники</h3>
			<div id="users_members">
			<?
				foreach ($members as $member) {
					if ($member['level']==1 or ($user_level>1 and $member['level']==0)){
						$id_edit_user=$member['ID'];
						$name=$member['user_firstname'];
						$s_name=$member['user_lastname'];
						$class=$member['class'];
						$contact=$member['contact'];
						$ctasks=$member['count_tasks'];
						$ctasks_class=$member['work'];
						$user_description=$member['user_description'];
						?>
						<div class="block_members users_list" id="div_user_id<?=$member['ID']?>">
							<div class="class_info">
								<h4><?=$class?></h4>
								<? if ($user_level>1) { ?>
								<a class="hide contact_info<?=$member['ID']?>"><?=$contact?></a>
								<? } ?>
							</div>
							<div>
							<h2 class="nameuser_h2"><?=$name?> <?=$s_name?></h2>
							<?
							if ($member['level']==0){
								?>
								<p id="p_conf<?=$member['ID']?>" class="confirm_user_p">Неподтвержденный участник</p>
								</div>
								<div id="confirm<?=$member['ID']?>" class="btn_confirm_us"><button value="<?=$member['ID']?>" class="cofirm_button_js">Подтвердить</button></div>
								<?
							}
							else{
							?>
							<p class="post_member">Должность: <span class="desc_user<?=$member['ID']?>"><?=$user_description?></span></p>
							<p class='<?=$ctasks_class?>'><?=$ctasks?></p>
							</div>
							<? if ($user_level>1 and $member['level']>0){ ?>
								<div class="start_edit_user" level="2" id=<?=$id_edit_user?>>
									<img src="<?php echo get_template_directory_uri();?>/img/pencil.png">
								</div>
							<? } } ?>
						</div>
						<?
					}
				}
			?>
			</div>
			<?
		}
	}
}

else { ?>
	<h1 class="h1_main">Cоуса нет :(</h1>
	<p class="p_main p_title"><a href="/create-team/">Создать свое сообщество</a></p>
<? }?>
</div>
</div>
</div>
<? if ($user_level>1){ ?>




<div class="modal-background"></div>
<div id="edit_user_team" class="modal">
<div class="form_edit_content">
	<img class='delete_user' src="<?php echo get_template_directory_uri();?>/img/icons8-delete-user-male-96.png">
	<h2 class="block_main_h">Карточка участника</h2>
	<label class="lbl2">Имя</label>
	<!-- <h2 class="nameuser_h2" id="name_user_edit">Андрей Васильев</h2> -->
	<input type="text" name="udontneed" class="input_form" readonly id="name_user_edit" value="Андрей Васильев"></input>
	<label class="lbl2">Должность в соусе</label>
	<input class="input_form" type="text" name="edit_desc_user" id="edit_desc_input" placeholder="Крутышка">
	<label class="lbl2">Контакты</label>
	<a href="" class="contact_info_form"></a><br>
	<label class="lbl2" id="root_lbl">Права в сообществе</label>
	<button class="edit_level_user btn_admin"></button>
	<div class="row">
		<div class="col-md-6"> 
			<button class="confirm_desc_user btn_form_ok">Сохранить</button>
		</div>
		<div class="col-md-6"> 
			<button class="cancel_desc_user btn_form_del">Отменить</button>
		</div>
	</div>
</div>
</div>

<div id="edit_team" class="modal">
<div class="form_edit_content">
	<h2 class="block_main_h">Карточка соуса</h2>
	<label class="lbl2">Название сообщества</label>
	<input type="text" class="input_form" name="name_team" id="name_team_js" placeholder="Совет Старшеклассников">
	<label class="lbl2">Описание соуса с подробной информацией о структуре, деятельности и должностях</label>
	<textarea class="input_form" name="desc_team" id="desc_team_js" placeholder="Самый лучший школьный совет!"></textarea>
	<label class="lbl2">Настоящее название учебного заведения, по которому участники будут находить ваш соус</label>
	<input type="text" class="input_form" name="school_team" id="school_team_js" placeholder="ГБОУ Лицей №144">
	<p class="confirm_user_p errors hide">Йоу, какие-то поля не заполнены</p>
	<button class="confirm_desc_team btn_form_ok" id=<?=$id_team?>>Сохранить</button>
	<button class="cancel_desc_team btn_form_del">Отменить</button>
</div>
</div>

<div id="del_us_form" class="modal">
<div class="form_edit_content">
	<h2 class="block_main_h">Вы уверены?</h2>
	<p>Вы собираетесь удалить участника :(</p>
		<button class="confirm_del_us btn_form_ok" team=<?=$id_team?>>Удалить</button>
	<button class="cancel_del_us btn_form_del">Отмена</button>
</div>
</div>
<? } ?>
<? get_footer(); ?>