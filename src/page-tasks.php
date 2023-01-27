<?php 

if (!is_user_logged_in()){
echo "<script>location.href = '/landing/';</script>";
exit();



}
$post_p=5;
$user_level = get_user_meta(get_current_user_id(),'wp_user_level')[0];
$inOUS=False;
if ($user_level>0){

    $meta_query['members'] = array(
        'key' => 'members',
        'value' => sprintf(':"%s";', get_current_user_id()),
        'compare' => 'LIKE',
    );
    $meta_query['relation'] = 'OR';

    $teams = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'DESC',
        'meta_query' => $meta_query,
        'post_type'   => 'team',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );
    $team_author=$teams[0]->post_author;


    if ($teams){
        $inOUS=True;
        if (isset($_COOKIE['team']))   
        {   
            $team_cookie=$_COOKIE['team'];
        }
        else {
            setcookie('team', $teams[0]->ID, 0, '/');
            $team_cookie=$teams[0]->ID;
        }
        $title="Что?";
        $who="assigne";
        $type_act="received";
        if (isset($_POST['received'])){
            // echo 'archive_from';
            $who="assigne";
            $type_act="received";
            $title="Архив полученных задач";
        }
        else if (isset($_POST['submitted'])){
            // echo 'archive_to';
            $who="reporter";
            $type_act="submitted";
            $title="Архив отправленных задач";
        }



        $meta_query = array(
            'relation' => 'AND',
            array(
                'key' => 'team_id',
                'value' => $team_cookie,
                'compare' => 'LIKE',
            ),
            array(
                'relation'=> 'AND',
                array(
                    'key' => $who,
                    'value' => get_current_user_id(),
                    'compare' => 'LIKE',
                ),
                array(
                    'key'=>'status_task',
                    'value'=>'3',
                    'compare'=>'=',
                ),
            ),
        );
        $flag_plan=false;
        if (isset($_POST['plan'])){
            $flag_plan=true;
            $type_act="plan";
            $title="План работы соуса";
            // echo 'plan';
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


        }

        $tasks = get_posts( array(
            'numberposts' => -1,
            'orderby'     => array('meta_exists'=>'DESC'),
            // 'order'       => 'DESC',
            'meta_query' => $meta_query,
            'post_type'   => 'task',
            'posts_per_page' => $post_p,
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        ) );
       


    }
    else{
        $inOUS=False;
    }

}


get_header();
$user_id=get_current_user_id();
// print_r($team_author);
?>

<? 
if ($inOUS and $user_level>0) { 
// if (false){
?>
<div class="container">
<div class="row">
<div class="col-md-12"> 
    <h1 class="h1_main"><?=$title?></h1>
    <br>
    <div id="all_tasks">
    <?
    $k=true;
    if ($tasks){
        $k=false;
        $status_all=["Не начато", "В процессе","Выполнено", "Подтверждено", "Просрочено"];
        $count_tasks=0;
        foreach ($tasks as $task) {
            $count_tasks++;
            $fields=get_fields($task->ID);
            $id_task=$task->ID;
            // print_r($task);
            $assigne=$fields['assigne'];
            $reporter=$fields['reporter'];
            $name1_1=get_user_meta($reporter, 'first_name', true);
            $name1_2=get_user_meta($reporter, 'last_name', true);
            $name2_1=get_user_meta($assigne, 'first_name', true);
            $name2_2=get_user_meta($assigne, 'last_name', true);
            $from_txt=$name1_1." ".$name1_2;
            $to_txt=$name2_1." ".$name2_2;
            $name=$task->post_title;
            $desc=$task->post_content;
            $date=get_the_date('d.m.Y H:i',$id_task);
            $date2=$fields['deadline'];
            $statuses=["Не начато", "В процессе","Выполнено", "Подтверждено", "Просрочено"];
            $st_id=$fields['status_task'];
            $result=get_field('result_task',$id_task);
            if ($result){
                $result="<span>Результат:</span> ".$result;
            }
            else{
                $result='';
            }
            // echo date('d.m.Y h:i');помниш
            if ((strtotime($date2)<strtotime(date('d.m.Y H:i'))) && $st_id<2){
                update_post_meta($id_task,'status_task','4');
                $st_id=4;
            }
            $status=$statuses[$st_id];
            if ($count_tasks==$post_p){
                $last_t="last='last'";
            }
            else{
                $last_t="";
            }
            ?>
            <div id="<?=$id_task?>" class="div_task_arch">
                <? if (!$flag_plan || $reporter==$user_id || $team_author==$user_id) {?>
                    <button class="btn_del_task_arch" del_task="<?=$id_task?>">Удалить</button>
                <? } ?>
                <p class="task_name"><?=$name?></p>
                <p class="task_desc"><?=$desc?></p>
                <p class="task_arch res_arch"><?=$result?></p>
                <div class="notneedinfo">
                    <p class="task_arch grey">Дата создания: <?=$date?></p>
                    <p class="task_arch">Дата выполнения: <?=$date2?></p>
                </div>
                <div class="notneedinfo">
                    <p class="task_arch grey">Отправитель: <?=$from_txt?></p>
                    <p class="task_arch">Получатель: <?=$to_txt?></p>
                </div>
                <p class="task_status status<?=$st_id?>"><?=$status?></p>
            </div>
            <?
        }
    }
    if ($k): ?>
        <p class="p_main p_title">Задачи отсутствуют</p>
    <? endif; ?>
    </div>
    <? if (!$k && $count_tasks==5): ?>
    <button class="btn_loadmore" id="load_more_t" last_id="1" team='<?=$team_cookie?>' user_id='<?=$user_id?>' act="<?=$type_act?>">Показать ещё</button>
    <? endif; ?>    
</div>
</div>
</div>




<? } else { 

    if ($user_level==2){

    ?>
    <div class="container">
    <div class="row">
    <div class="col-md-12">
    
    <h1 class="h1_main">А чего это мы тут забыли?</h1>
    <p class="p_main p_title">Тебе сюда не надо</p>
    <center><a class="p_main" href="/">Вернуться на главную</a></center>
    </div>
    </div>
    </div>
<?php
}
else if ($user_level==0){
    ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
                <h1 class="h1_main">А чего это мы тут забыли?</h1>
                <p class="p_main p_title">Тебе сюда не надо</p>
                <center><a class="p_main" href="/">Вернуться на главную</a></center>
                
        </div>
    </div>
</div>


    <?
}

}
?>


 <?
get_footer();
?>