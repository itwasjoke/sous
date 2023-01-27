<?php
//Enqueue scripts and styles.
function sp_scripts() {
	$ver=intval(microtime(1));
	wp_enqueue_style('style', get_template_directory_uri().'/style.css');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
	
	wp_enqueue_script('bootstrap', get_template_directory_uri().'/js/bootstrap.js');
	wp_register_script('script', get_template_directory_uri().'/js/script.js',array(),$ver);
	wp_enqueue_script('script', get_template_directory_uri().'/js/script.js',array(),$ver);
	wp_register_style('maincss', get_template_directory_uri().'/css/main.css',array(),$ver);
	wp_enqueue_style('maincss', get_template_directory_uri().'/css/main.css',array(),$ver);
	wp_register_style('headercss', get_template_directory_uri().'/css/header.css',array(),$ver);
	wp_enqueue_style('headercss', get_template_directory_uri().'/css/header.css',array(),$ver);
	
}
add_action( 'wp_enqueue_scripts', 'sp_scripts' );

add_filter('show_admin_bar', '__return_false');

// Custom Functions for CSS/Javascript Versioning
$GLOBALS["TEMPLATE_URL"] = get_bloginfo('template_url')."/";
$GLOBALS["TEMPLATE_RELATIVE_URL"] = wp_make_link_relative($GLOBALS["TEMPLATE_URL"]);

include 'inc/disabled-default_styles_a_scripts.php'; // disabled default
include 'inc/upload_svg_files.php'; // add svg support

add_action('init', 'my_team_init');
function my_team_init(){
	register_post_type('team', array(
		'labels'             => array(
			'name'               => 'Команда', // Основное название типа записи
			'singular_name'      => 'Команда', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить новый Команду',
			'edit_item'          => 'Редактировать Команду',
			'new_item'           => 'Новая Команда',
			'view_item'          => 'Посмотреть Команды',
			'search_items'       => 'Найти Команду',
			'not_found'          => 'Новость не найдены',
			'not_found_in_trash' => 'В корзине новость не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Команды'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor')
	) );
}


add_action('init', 'my_task_init');
function my_task_init(){
	register_post_type('task', array(
		'labels'             => array(
			'name'               => 'Задача', // Основное название типа записи
			'singular_name'      => 'Задача', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить новый Задача',
			'edit_item'          => 'Редактировать Задача',
			'new_item'           => 'Новая Задача',
			'view_item'          => 'Посмотреть Задача',
			'search_items'       => 'Найти Задача',
			'not_found'          => 'Задача не найдены',
			'not_found_in_trash' => 'В корзине Задача не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Задачи'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor')
	) );
}


add_action( 'wp_ajax_confirm_users', 'confirm_users' );
add_action( 'wp_ajax_nopriv_confirm_users', 'confirm_users' );

function confirm_users(){
	$response=array();
	$user_id=$_POST['user_id'];
	$response['user_id']=$user_id;
	update_user_meta($user_id, 'wp_user_level', 1);
	return wp_send_json_success($response);
	wp_die();
}
add_action( 'wp_ajax_get_user_data', 'get_user_data' );
add_action( 'wp_ajax_nopriv_get_user_data', 'get_user_data' );

function get_user_data(){
	$user_id=$_POST['user_id'];
	$response=array();
	$response['ID']=$user_id;
	$response['first_name']=get_user_meta($user_id,'first_name',true);
	$response['last_name']=get_user_meta($user_id,'last_name',true);
	$response['description']=get_user_meta($user_id,'description',true);
	$response['wp_user_level']=get_user_meta($user_id,'wp_user_level',true);
	return wp_send_json_success($response);
	wp_die();
}

add_action( 'wp_ajax_update_user_desc', 'update_user_desc' );
add_action( 'wp_ajax_nopriv_update_user_desc', 'update_user_desc' );

function update_user_desc(){
	$user_id=$_POST['user_id'];
	$response=array();
	$user_val=$_POST['user_val'];
	$user_level=$_POST['user_level'];
	update_user_meta($user_id, 'wp_user_level', $user_level);
	$response['user']=$user_val;
	update_user_meta($user_id,'description',$user_val);

	wp_die();
}

add_action( 'wp_ajax_update_team_info', 'update_team_info' );
add_action( 'wp_ajax_nopriv_update_team_info', 'update_team_info' );

function update_team_info(){
	$name=$_POST['name_team'];
	$desc=$_POST['desc_team'];
	$school=$_POST['school_team'];
	$id=$_POST['id_team'];
	$post=array();
	$post['ID']=$id;
	$post['post_title']=$name;
	$post['post_content']=$desc;
	wp_update_post($post);
	update_post_meta($id,'school',$school);
	wp_die();
}

add_action( 'wp_ajax_get_tasks', 'get_tasks' );
add_action( 'wp_ajax_nopriv_get_tasks', 'get_tasks' );

function get_tasks(){
	$task_type=$_POST['task_t'];
	$user_id=$_POST['user_id'];
	$team_id=$_POST['team_id'];
	$action="reporter";
	if (strcasecmp($task_type,"submitted")==0){
		$action="reporter";
		$flag=true;
	}
	else{
		$action="assigne";
		$flag=false;
	}
	// $meta_query = array(
 //        'relation' => 'AND',
 //        array(
 //            'key' => 'team_id',
 //            'value' => $team_id,
 //            'compare' => 'LIKE',
 //        ),
 //        array(
 //            'key' => $action,
 //            'value' => $user_id,
 //            'compare' => 'LIKE',
 //        ),
 //    );

    $meta_query = array(
        'relation' => 'AND',
        array(
            'key' => 'team_id',
            'value' => $team_id,
            'compare' => '=',
        ),
        array(
            'relation'=> 'AND',
            array(
                'key' => $action,
                'value' => $user_id,
                'compare' => '=',
            ),
            array(
                'key'=>'status_task',
                'value'=>'3',
                'compare'=>'!=',
            ),
        ),
    );

    $tasks = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
        'meta_query' => $meta_query,
        'post_type'   => 'task',
        'posts_per_page' => 5,
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );
    $tasks=(array)$tasks;
    $len=count($tasks);
    for ($i=0; $i < $len; $i++) { 
    	$tasks[$i]=(array)$tasks[$i];
    	$id_task=$tasks[$i]['ID'];
    	$fields=get_fields($id_task);
		$name1_1="Для кого: ".get_user_meta($fields['assigne'], 'first_name', true);
		$name1_2=get_user_meta($fields['assigne'], 'last_name', true);
    	
    	$user_name=$name1_1.' '.$name1_2;
    	
    	if ((strtotime($fields['deadline'])<strtotime(date('d.m.Y h:i'))) && $fields['status_task']<2){
            update_post_meta($id_task,'status_task','4');
            $fields['status_task']=4;
        }

    	$tasks[$i]['deadline']=$fields['deadline'];
    	if ($flag){
    		$tasks[$i]['user_fromto']=$user_name;
    	}
    	else{
    		$tasks[$i]['user_fromto']="";
    	}
   	 	
    	$tasks[$i]['status']=$fields['status_task'];
    	$tasks[$i]['task_type']=$task_type;
    }
    return wp_send_json_success($tasks);
    wp_die();
}

add_action( 'wp_ajax_get_single_task', 'get_single_task' );
add_action( 'wp_ajax_nopriv_get_single_task', 'get_single_task' );

function get_single_task(){
	$id_task=$_POST['task_id'];
	$task=get_post($id_task);
	$task_meta['ID']=$task->ID;
	$task_meta['post_date']=get_the_date('d.m.Y H:i',$id_task);
	$task_meta['post_desc']=$task->post_content;
	$task_meta['post_title']=$task->post_title;
	$fields=get_fields($id_task);
	$task_meta['status']=$fields['status_task'];
	$task_meta['deadline']=$fields['deadline'];
	$task_meta['asssigne_id']=$fields['assigne'];
	$task_meta['result']=get_field('result_task',$id_task);
	$task_meta['post']=false;
//$fields['result_task'];
	$result=explode(" ", $task_meta['result']);
	$result_link="";
	$flag=false;
	for ($i=0;$i<count($result);$i++){
		if (strval(strripos($result[$i], "https://vk.com"))=="0" || strripos($result[$i], "https://vk.com")>0){
			$result_link=mb_substr(trim($result[$i]," "), strripos($result[$i], 'wall')+4);
			$flag=true;
			break;
		}
	}
	if ($flag){
		$curl=curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.vk.com/method/wall.getById?access_token=b6217ef9b6217ef9b6217ef9a9b5303f6fbb621b6217ef9d57cda67997492bc1cc4e80d&posts=".$result_link."&v=5.131");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$vkpost=json_decode(curl_exec($curl));
		$text=($vkpost->response)[0]->text;
		$images=(($vkpost->response)[0]->attachments);
		$all_images="";
		for ($i=0;$i<count($images);$i++){
		    $img=(array)(($images[$i]->photo->sizes));
		    for ($j=0; $j<count($img);$j++){
		        $img_in=(array)$img[$j];
		        if ($img_in['type']=="w"){
		            $all_images.="<img src='".$img_in['url']."' width='50%'>";
		        }
		    }
		}
		curl_close($curl);
		$text1=str_replace("\n\n", "</p><p>", $text);
		$text2="<p>".$text1."</p>";
		$task_meta['post']=true;
		$task_meta['vk_text']=$text2;
		$task_meta['vk_img']=$all_images;
	}
	$name1_1=get_user_meta($fields['assigne'], 'first_name', true);
	$name1_2=get_user_meta($fields['assigne'], 'last_name', true);
	$task_meta['assigne']=$name1_1.' '.$name1_2;

	$name1_1=get_user_meta($fields['reporter'], 'first_name', true);
	$name1_2=get_user_meta($fields['reporter'], 'last_name', true);
	$task_meta['reporter']=$name1_1.' '.$name1_2;
	
	return wp_send_json_success($task_meta);
	wp_die();

}

add_action( 'wp_ajax_update_task_sous', 'update_task_sous' );
add_action( 'wp_ajax_nopriv_update_task_sous', 'update_task_sous' );

function update_task_sous(){
	$task_type=$_POST['task_type'];
	$id=$_POST['task_id'];
	if (strcasecmp($task_type,"submitted")==0){
		$title=$_POST['title'];
		$desc=$_POST['desc'];
		$post['ID']=$id;
		$post['post_title']=$title;
		$post['post_content']=$desc;
		wp_update_post($post);
		$datetime=$_POST['datetime'];
		$assigne=$_POST['assigne'];
		// if ($_POST['status']=='true'){
		if (strcasecmp($_POST['status'],"true")==0){
			$user_assigne=get_fields($id)['assigne'];
			// $count=(int)get_field('count_tasks',$user_assigne);
			$count=get_user_meta($user_assigne,'count_tasks',true);
			$count=(int)$count+1;
			update_user_meta($user_assigne,'count_tasks',$count);
			update_post_meta($id,'status_task','3');
		}
		update_post_meta($id,'deadline',$datetime);
		update_post_meta($id,'assigne',$assigne);
	}
	else{
		$result=$_POST['result'];
		$status=$_POST['status'];
		update_post_meta($id,'result_task',$result);
		update_post_meta($id,'status_task',$status);
	}
	$task_meta['ID']=$id;
	return wp_send_json_success($task_meta);
	wp_die();
	
}
add_action( 'wp_ajax_check_user', 'check_user' );
add_action( 'wp_ajax_nopriv_check_user', 'check_user' );
function check_user(){
	$m['error']="";
	$m['exist']=false;
	$username=$_POST['username'];
	$password=$_POST['password'];
	$user = get_user_by('email', $username);
	if ($user){
		$hash=$user->user_pass;
		if (wp_check_password($password,$hash)){
			$m['exist']=true;
		}
		else{
			$m['error']="Пароль неправильный";
		}
	}
	else{
		$m['error']="Нет такого пользователя";
	}
	return wp_send_json_success($m);
	wp_die();
}

add_action( 'wp_ajax_get_more_posts', 'get_more_posts' );
add_action( 'wp_ajax_nopriv_get_more_posts', 'get_more_posts' );
function get_more_posts(){
	$task_type=$_POST['task_t'];
	$user_id=$_POST['user_id'];
	$team_id=$_POST['team_id'];
	$last=$_POST['last'];
	$action="reporter";
	$tp=$_POST['tp'];
	if (strcasecmp($task_type,"submitted")==0){
		$action="reporter";
		$flag=true;
	}
	else if (strcasecmp($task_type,"received")==0){
		$action="assigne";
		$flag=false;
	}
	// $meta_query = array(
	// 	'relation' => 'AND',
 //        array(
 //            'key' => 'team_id',
 //            'value' => $team_id,
 //            'compare' => 'LIKE',
 //        ),
 //        array(
 //            'key' => $action,
 //            'value' => $user_id,
 //            'compare' => 'LIKE',
 //        ),
 //    );
	$meta_query = array(
        'relation' => 'AND',
        array(
            'key' => 'team_id',
            'value' => $team_id,
            'compare' => '=',
        ),
        array(
            'relation'=> 'AND',
            array(
                'key' => $action,
                'value' => $user_id,
                'compare' => '=',
            ),
            array(
                'key'=>'status_task',
                'value'=>'3',
                'compare'=>$tp,
            ),
        ),
    );
    $b=false;
    $show_del=false;
    $flag_plan=false;
	if (strcasecmp($tp,"=")==0){
		$b=true;
		$show_del=true;
		$meta_query = array(
	        'relation' => 'AND',
	        array(
	            'key' => 'team_id',
	            'value' => $team_id,
	            'compare' => 'LIKE',
	        ),
	        array(
	            'relation'=> 'AND',
	            array(
	                'key' => $action,
	                'value' => $user_id,
	                'compare' => 'LIKE',
	            ),
	            array(
	                'key'=>'status_task',
	                'value'=>'3',
	                'compare'=>$tp,
	            ),
	        ),
	        'meta_exists'=>[
                    'key'=>'deadline',
                    'compare'=>'EXISTS',
            ],
	    );
		if (strcasecmp($task_type,"plan")==0){
			$flag_plan=true;
			$meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'team_id',
                    'value' => $team_cookie,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'=>'plan',
                    'value'=>'1',
                    'compare'=>'LIKE',
                ),
                'meta_exists'=>[
                    'key'=>'deadline',
                    'compare'=>'EXISTS',
                ],
            );

            $meta_quer['members'] = array(
		        'key' => 'members',
		        'value' => sprintf(':"%s";', get_current_user_id()),
		        'compare' => 'LIKE',
		    );
		    $meta_quer['relation'] = 'OR';

		    $teams = get_posts( array(
		        'numberposts' => -1,
		        'orderby'     => 'title',
		        'order'       => 'DESC',
		        'meta_query' => $meta_quer,
		        'post_type'   => 'team',
		        'suppress_filters' => true,
		    ) );
		    $team_author=$teams[0]->post_author;
		}
		$tasks = get_posts( array(
	        'numberposts' => -1,
	        'meta_query' => $meta_query,
	        'orderby'     => array('meta_exists'=>'DESC'),
	        'post_type'   => 'task',
	        'posts_per_page' => 5,
	        'offset'=>$last,
	        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    	) );
		
	}
	else{
		$tasks = get_posts( array(
	        'numberposts' => -1,
	        'orderby'     => 'ID',
	        'order'       => 'DESC',
	        'meta_query' => $meta_query,
	        'post_type'   => 'task',
	        'posts_per_page' => 5,
	        'offset'=>$last,
	        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    	) );
	}
    

    $tasks=(array)$tasks;
    $len=count($tasks);
    for ($i=0; $i < $len; $i++) { 
    	$tasks[$i]=(array)$tasks[$i];
    	$id_task=$tasks[$i]['ID'];
    	$fields=get_fields($id_task);
    	if ($b){
    			if ($flag_plan){
    				if ($fields['reporter']==get_current_user_id()|| $team_author==get_current_user_id()){
    					$show_del=true;
    				}
    				else{
    					$show_del=false;
    				}
    			}
    			$tasks[$i]['show_del']=$show_del;
    			$tasks[$i]['date_create']=get_the_date('d.m.Y H:i',$id_task);
	    		$name1_1=get_user_meta($fields['assigne'], 'first_name', true);
				$name1_2=get_user_meta($fields['assigne'], 'last_name', true);
    		    $tasks[$i]['to']=$name1_1.' '.$name1_2;
    		    $name1_1=get_user_meta($fields['reporter'], 'first_name', true);
				$name1_2=get_user_meta($fields['reporter'], 'last_name', true);
    			$tasks[$i]['from']=$name1_1.' '.$name1_2;
    			$tasks[$i]['result']=get_field('result_task',$id_task);
    	}
    	else{
    		$name1_1="Для кого: ".get_user_meta($fields['assigne'], 'first_name', true);
			$name1_2=get_user_meta($fields['assigne'], 'last_name', true);
	    	$user_name=$name1_1.' '.$name1_2;
	    	if ($flag){
	    		$tasks[$i]['user_fromto']=$user_name;
	    	}
	    	else{
	    		$tasks[$i]['user_fromto']="";
	    	}
    	}


    	if ((strtotime($fields['deadline'])<strtotime(date('d.m.Y h:i'))) && $fields['status_task']<2){
            update_post_meta($id_task,'status_task','4');
            $fields['status_task']=4;
        }

    	$tasks[$i]['deadline']=$fields['deadline'];
    	
    	$tasks[$i]['status']=$fields['status_task'];
    	$tasks[$i]['task_type']=$task_type;
    }
    return wp_send_json_success($tasks);
    wp_die();
}


add_action( 'wp_ajax_create_task', 'create_task' );
add_action( 'wp_ajax_nopriv_create_task', 'create_task' );
function create_task(){
	$task_name=$_POST['task_name'];
	$task_desc=$_POST['task_desc'];
	$task_data=array(
	    'post_title' => $task_name,
	    'post_content' => $task_desc,
	    'post_type' => 'task',
	    'post_status'    => 'publish',
	);
	$id_task_post=wp_insert_post($task_data);

	$task_date=$_POST['task_date'];
	$task_time=$_POST['task_time'];
	$task_datetime=$task_date." ".$task_time;
	$task_user_from=get_current_user_id();
	$task_user_to=$_POST['to_who'];
	$task_team_id=$_POST['team'];
	$plan=$_POST['plan'];
	$email_to=get_userdata($task_user_to)->user_email;
	update_field('assigne', $task_user_to, $id_task_post);
	update_field('reporter', $task_user_from, $id_task_post);
	update_field('team_id', $task_team_id, $id_task_post);
	update_field('deadline', $task_datetime, $id_task_post);
	update_field('status_task', 0, $id_task_post);
	update_field('plan', $plan, $id_task_post);
	$fields=get_fields($id_task_post);
	$m['task_name']=$task_name;
	$m['task_desc']=$task_desc;
	$m['task_datetime']=$fields['deadline'];
	$m['id_task_post']=$id_task_post;
	$name1_1=get_user_meta($task_user_to, 'first_name', true);
	$name1_2=get_user_meta($task_user_to, 'last_name', true);
	$m['task_user_to']=$name1_1.' '.$name1_2;
	$task_name="style='font-style: normal; font-weight: 600; font-size: 20px; line-height: 25px; margin-bottom: 9px;'";
	$task_des="style='font-style: normal; font-weight: 400; font-size: 16px; margin-bottom: 7px; margin-top: 10px;'";
	$message="<p ".$task_name.">".$m['task_name']."</p><p ".$task_des.">".$m['task_desc']."</p><p ".$task_des.">Дата сдачи: ".$fields['deadline']."</p><p><a href='http://sous.spb.ru'>Посмотреть задачу на сайте</a>. Отвечать на это письмо не нужно.</p>";
	$headers=array(
		'content-type: text/html',
	);
	wp_mail($email_to,"Новая задача на sous.spb.ru", $message,$headers);
	return wp_send_json_success($m);
    wp_die();
}


add_action( 'wp_ajax_delete_item', 'delete_item' );
add_action( 'wp_ajax_nopriv_delete_item', 'delete_item' );
function delete_item(){
	$action=$_POST['info'];
	if ($action=="user"){
		$id_user=$_POST['id_user'];
		$id_team=$_POST['id_team'];
		$members=get_fields($id_team)['members'];
		$c=count($members);
		for ($i=0; $i<$c;$i++){
			if ($members[$i]['ID']==$id_user){
				unset($members[$i]);
			}
		}
		update_field('members',$members,$id_team);
		update_user_meta($id_user, 'wp_user_level', '0');
		wp_delete_user($id_user);
		$m['id_member']=$id_user;
	}
	else if ($action=="task"){
		$id_task=$_POST['task_id'];
		wp_delete_post($id_task,true);
		$m['task']=$id_task;
	}
	return wp_send_json_success($m);
    wp_die();
}

add_action( 'wp_ajax_email', 'email' );
add_action( 'wp_ajax_nopriv_email', 'email' );
function email(){
	$school=$_POST['school'];
	$meta_query['school'] = array(
	    'key' => 'school',
	    'value' => $school,
	    'compare' => '=',
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
	if ($teams){
		foreach ($teams as $team) {
			$m['team']=true;
			$m['team_id']=$team->ID;
		}
	}
	else{
		$m['team']=false;
	}
	$email=$_POST['info'];
	if (email_exists($email)){
		$m['check']=true;
	}
	else{
		$m['check']=false;
	}
	return wp_send_json_success($m);
    wp_die();
}






