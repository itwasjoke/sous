<?php 

if (!is_user_logged_in()){
echo "<script>location.href = '/landing/';</script>";
exit();



}
$post_p=5;
if (!isset($_COOKIE['notif1'])){
    setcookie('notif1',0,time()+60*60*24*30,'/homepage/');
}
if (!isset($_COOKIE['notif2'])){
    setcookie('notif2',0,time()+60*60*24*30,'/homepage/');
}
if (!isset($_COOKIE['notif3'])){
    setcookie('notif3',0,time()+60*60*24*30,'/homepage/');
}
if (!isset($_COOKIE['notif7'])){
    setcookie('notif7',0,time()+60*60*24*30,'/homepage/');
}

$user_level = get_user_meta(get_current_user_id(),'wp_user_level')[0];
$inOUS=False;
// print_r($user_level);
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
        'order'       => 'ASC',
        'meta_query' => $meta_query,
        'post_type'   => 'team',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );



    


    if ($teams){
        setcookie('team', $teams[0]->ID, 0, '/');
        $team_cookie=$teams[0]->ID;
        $inOUS=True;
        $notifVis="hide";
        if (isset($_COOKIE['notif1'],$_COOKIE['notif2'],$_COOKIE['notif3'],$_COOKIE['notif7'])){
            $notif1=$_COOKIE['notif1'];
            $notif2=$_COOKIE['notif2'];
            $notif3=$_COOKIE['notif3'];
            $notif7=$_COOKIE['notif7'];

            if (!$notif1){
                $notifVis="";
                $notifText="Уведомления о новых задачах будут приходить на почту, которую Вы указывали при регистрации.";
                $notifNum=1;
            }
            else if(!$notif2){
                $notifVis="";
                $notifText="Впервые в соусе? Откройте <a href='/faq'>руководство</a> и узнайте ответы на все вопросы!";
                $notifNum=2;
            }
            else if(!$notif3){
                $notifVis="";
                $notifText="Все ещё не понимаете, зачем состоите в школьном совете? Мы подробно это объяснили в <a href='/whyneedsous'>нашей статье</a>.";
                $notifNum=3;
            }
            else if(!$notif7){
                $notifVis="";
                $notifText="Если в работе сайта возникли сложности, попробуйте обновить кэш браузера. <a href='https://lifehacker.ru/kak-ochistit-kesh-brauzera/'>Как это сделать?</a>.";
                $notifNum=7;
            }
            else{
                $notifVis="hide";
            }
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
                    'key' => 'assigne',
                    'value' => get_current_user_id(),
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
            'posts_per_page' => $post_p,
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        ) );


    }
    else{
        $inOUS=False;
    }

}



get_header();
$link="ахахаха https://vk.com/starclass144?w=wall-128377406_4141";
// print_r(explode(" ", $link));
// print_r(mb_substr($link, strripos($link, 'wall')+4));
if (isset($_POST['fuckyou'])){
    $curl1=curl_init();
    curl_setopt($curl1, CURLOPT_URL, "https://oauth.vk.com/authorize?client_id=51462550&display=page&redirect_uri=http://sous.spb.ru&response_type=token&v=5.131");
    // curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);http://sous.spb.ru/homepage/
    curl_exec($curl1);
    curl_close($curl1);
    // print_r($access_token1);
}
// $task_meta=array();
// $result=explode(" ", $link);
//     $result_link="";
//     $flag=false;
//     for ($i=0;$i<count($result);$i++){
//         // print_r(strripos($result[$i], "https://vk.com"));
//         if (strval(strripos($result[$i], "https://vk.com"))=="0" || strripos($result[$i], "https://vk.com")>0){
//             $result_link=mb_substr(trim($result[$i]," "), strripos($result[$i], 'wall')+4);
//             print_r($result_link);
//             $flag=true;
//             break;
//         }
//     }
//     if ($flag){
//         $curl=curl_init();
//         curl_setopt($curl, CURLOPT_URL, "https://api.vk.com/method/wall.getById?access_token=b6217ef9b6217ef9b6217ef9a9b5303f6fbb621b6217ef9d57cda67997492bc1cc4e80d&posts=".$result_link."&v=5.131");
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//         $vkpost=json_decode(curl_exec($curl));
//         $text=($vkpost->response)[0]->text;
//         $images=(($vkpost->response)[0]->attachments);
//         $all_images="";
//         for ($i=0;$i<count($images);$i++){
//             $img=(array)(($images[$i]->photo->sizes));
//             for ($j=0; $j<count($img);$j++){
//                 $img_in=(array)$img[$j];
//                 if ($img_in['type']=="w"){
//                     $all_images.="<img src='".$img_in['url']."' width='100%'>";
//                 }
//             }
//         }
//         curl_close($curl);
//         $task_meta['post']=true;
//         $task_meta['vk_text']=$text;
//         $task_meta['vk_img']=$all_images;
//     }

// $curl=curl_init();
// curl_setopt($curl, CURLOPT_URL, "https://api.vk.com/method/wall.getById?access_token=b6217ef9b6217ef9b6217ef9a9b5303f6fbb621b6217ef9d57cda67997492bc1cc4e80d&posts=-128377406_4141&v=5.131");
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// $vkpost=json_decode(curl_exec($curl));
// $text=($vkpost->response)[0]->text;
// $images=(($vkpost->response)[0]->attachments);
// $all_images="";
// for ($i=0;$i<count($images);$i++){
//     $img=(array)(($images[$i]->photo->sizes));
//     for ($j=0; $j<count($img);$j++){
//         $img_in=(array)$img[$j];
//         if ($img_in['type']=="w"){
//             $all_images.="<img src='".$img_in['url']."' width='100%'>";
//         }
//     }
    
// }
// curl_close($curl);
// echo "<pre>";
// print_r($task_meta['vk_text']);
// print_r($task_meta['vk_img']);
// echo "</pre>";
$user_id=get_current_user_id();
if (isset($_POST['edit_profile'])){
    $firstname=$_POST['first_name'];
    $lastname=$_POST['last_name'];
    $description=$_POST['description'];
    $class=$_POST['class'];
    $contact=$_POST['contact'];
    if ($firstname!='' && $lastname!='' && $description!='' && $class!='' && $contact!=''){
        if (str_contains($contact, 'https://')){
            $contact=mb_substr($contact,8);
        }
        update_user_meta($user_id,'first_name',$firstname);
        update_user_meta($user_id,'last_name',$lastname);
        update_user_meta($user_id,'description',$description);
        update_user_meta($user_id,'contact',$contact);
        update_user_meta($user_id,'class',$class);
    }
}
?>

<? if ($inOUS and $user_level>0) { 
    $user_name=get_user_meta($user_id, 'first_name',true)." ".get_user_meta($user_id, 'last_name',true);
    $user_post=get_user_meta($user_id,'description',true);
    $user_class=get_user_meta($user_id,'class',true);
    $user_contact=get_user_meta($user_id,'contact',true);
    $count_tasks_end = get_user_meta($user_id,'count_tasks',true);
    $name_team="";
    foreach ($teams as $team){
        $name_team=get_the_title($team->ID);
        $school = get_fields($team->ID)['school'];
    }
?>
<div class="container">
<div class="row">
<div class="col-md-12"> 
<!--     <form name="fuckyoutoo" method="post" action="/homepage">
        <input type="submit" name="fuckyou" value="Получить токен">
    </form> -->
    <h3 class="h3_nameblock">карточка пользователя</h3>
    <div class="block_info">
    <h1 class="block_main_h"><?=$user_name?></h1>
    <p class="block_text">Должность: <span><?=$user_post?></span><br>
        Класс: <span><?=$user_class?></span><br>
        Контакты: <span><a href="https://<?=$user_contact?>"><?=$user_contact?></a></span></p>
    <p class="block_text">
    Соус: <a href="/teams/"><span><?=$name_team?></span></a><br>
    Учебное заведение: <span><?=$school?></span><br>
    Выполнено задач: <span><?=$count_tasks_end?></span></p>
    <button class="edit_prof_button" onclick="location.href = '/profile/';">редактировать профиль</button>
    </div>
    <div id="/homepage/" class="block_notif <?=$notifVis?>">
        <p><?=$notifText?></p>
        <div class="close" notif="<?=$notifNum?>"></div>
    </div>
    <div class="block_btn_dif">
            <form id='plan' name="plan" class="hide" method="POST" action="/tasks">
                <input type="text" id="archive_input" name="received" value="1">
            </form>
            <h4 class="btn_tasks_dif"><p id='btn_archive' onclick="document.getElementById('plan').submit();">Архив полученных задач</p></h4>
        </div>
    <h3 class="h3_nameblock">задачи</h3>
    <div class="block_action">
        <div class="block_action_in">
            <button class="task_action btn_edit_action active_sel" id="received" team='<?=$team_cookie?>' user_id='<?=$user_id?>'>полученные</button>
        </div>
        <div class="block_action_in">
            <button class="task_action btn_edit_action" id="submitted" team='<?=$team_cookie?>' user_id='<?=$user_id?>'>отправленные</button>
        </div>
    </div>

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
            // $to_txt="Для: ".$name2_1." ".$name2_2;
            $name=$task->post_title;
            $desc=$task->post_content;
            $date1=$task->post_date_gmt;
            $date2=$fields['deadline'];
            $statuses=["Не начато", "В процессе","Выполнено", "Подтверждено", "Просрочено"];
            $st_id=$fields['status_task'];
            // echo date('d.m.Y h:i');
            if ((strtotime($date2)<strtotime(date('d.m.Y h:i'))) && $st_id<2){
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
            <div id="<?=$id_task?>" class="task_div" task="received">
                <p class="task_name"><?=$name?></p>
                <!-- <p class="task_desc"><?=$desc?></p> -->
                 <p class="task_date">Дата сдачи: <?=$date2?></p>
<!--                 <div class="notneedinfo">
                    <p class="task_date">Дата сдачи: <span><?=$date2?></span></p>
                    <p class="task_fromto">От кого: <span><?=$from_txt?></span></p>
                </div> -->
                <p class="task_status status<?=$st_id?>"><?=$status?></p>
            </div>
            <?
        }
    }
    if ($k): ?>
        <p class="p_main p_title">Задачи отсутствуют</p>
    <? endif; ?>
    </div>
    <? if (!$k){
        if ($count_tasks<5){
            $class_btn_loadmore="hide";
        }
        else{
            $class_btn_loadmore="";
        } ?>
    <button class="<?=$class_btn_loadmore?> btn_loadmore" id="load_more" last_id="1" team='<?=$team_cookie?>' user_id='<?=$user_id?>' act="received">Показать ещё</button>
    <? } ?>    
</div>
</div>
</div>

<div class="modal-background"></div>

<div id="create_task_form_div" class="modal">
<div class="form_edit_content">
<h2 class="edit_h">Создать задачу</h2>
    <label class="lbl2">Название</label>
    <input type="text" name="task_name" class="input_form">
    <label class="lbl2">Описание</label>
    <textarea name="task_desc" class="input_form"></textarea>
    <label class="lbl2">Для кого:</label>
    <select class="form-select input_form" id='to_who'>
        <?
        $post_team=get_fields($team_cookie);
        foreach ($post_team['members'] as $id_user){
            $n1=$id_user['user_firstname'];
            $n2=$id_user['user_lastname'];
            $id_select=$id_user['ID'];
            echo '<option value='.$id_select.'>'.$n1.' '.$n2.'</option>';
        }
        ?>
    </select>
    <label class="lbl2">Дедлайн по дате</label>
    <input type="date" name="task_date" class="input_form">
    <label class="lbl2">Дедлайн по времени</label>
    <input type="time" name="task_time" class="input_form">
    <? if ($user_level>1){ ?>
    <input name="have_sous" id="isplan" class="form-check-input have_sous" type="checkbox" value="0">
        <label for="havesous" class="lbl3">Эта задача является планом работы</label>
    <? } ?>
    <p class="confirm_user_p errors hide">Йоу, какие-то поля не заполнены</p>
    <input type="button" name="create_task" value="Создать" class="btn_1 btn_landing btn_task_create btn_form_ok">

<button class="btn_form_del" id="createtask_cancel">Отменить</button>
</div>
</div>

<div id="edit_assigne_div" class="modal">
<div class="form_edit_content">
<!-- <h2 class="edit_h">Блок с задачей</h2> -->
<div id="task_form_from_edit">
     <p class="date_info"></p>
    <p class="title_info"></p>
    <p class="desc_info"></p>
    <p class="reporter_info"></p>
    <p class="deadline_info"></p>
    <label class="lbl2">Статус</label>
    <p class="task_status status4" id="status_pros"></p>
    <select class="form-select input_form" id='sel_status_as'  name='status_edit'>
        <option id="option_status_0" value="0">Не начато</option>
        <option id="option_status_1" value="1">В процессе</option>
        <option id="option_status_2" value="2">Выполнено</option>
    </select>
    <label class="lbl2">Отчёт</label>
    <textarea class="input_form" id="result" placeholder=""></textarea>
    <button class="btn_form_ok" id="save_from" id_task="">Сохранить</button>
    <button class="btn_form_del" id="save_from_cancel">Отменить</button>
</div>
</div>
</div>

<div id="edit_reporter_div" class="modal">
<div class="form_edit_content">
<img id="delete_task" src="<?php echo get_template_directory_uri();?>/img/icons8-trash.svg">
<h2 class="edit_h">Редактировать</h2>
<div id="task_form_from_edit">
    <label class="lbl2">Название задачи</label>
    <input type="text" name="task_name_edit" placeholder="Работай" class="input_form"></input>
    <label class="lbl2">Описание задачи</label>
    <textarea name="task_desc_edit" placeholder="Очень усердно и внимательно" class="input_form"></textarea>
    <label class="lbl2">Для кого задача:</label>
    <select class="form-select input_form" name='to_who_edit'>
        <?
        $post_team=get_fields($team_cookie);
        foreach ($post_team['members'] as $id_user){
            $n1=$id_user['user_firstname'];
            $n2=$id_user['user_lastname'];
            $id_select=$id_user['ID'];
            echo '<option id="user'.$id_select.'" value='.$id_select.'>'.$n1.' '.$n2.'</option>';
        }
        ?>
    </select>
    <label class="lbl2">Дедлайн по дате</label>
    <input type="date" name="task_date_edit" class="input_form" id="date_of_deadline">
    <label class="lbl2">Дедлайн по времени</label>
    <input type="time" name="task_time_edit" class="input_form">
    <label class="lbl2">Статус задачи</label>
    <p class="status_p"></p>
    <label class="lbl2" id="res_lbl">Предоставленный результат:</label>
    <p class="result_p"></p>
    <div class="vk_post hide">
        <img src="<?php echo get_template_directory_uri(); ?>/img/vk.png" class="vk_logo">
        <div class="vk_text"></div>
        <div class="vk_images"></div>
    </div>
    <button class="btn_conf_task" id="confirm_result_task" confirm="0">Подтвердить выполнение задачи</button>
    <p class="confirm_user_p errors hide">Йоу, какие-то поля не заполнены</p>
    <button class="btn_form_ok" id="save_to">Сохранить</button>
    <button class="btn_form_del" id="reporter_cancel">Отменить</button>
</div>
</div>
</div>


<div id="del_task_form" class="modal">
<div class="form_edit_content">
    <h2 class="block_main_h">Вы уверены?</h2>
    <p>Удалить данную задачу? Вся информация о ней будет удалена</p>
    <label class="lbl2">Чтобы отправить задачу в архив, необходимо подтвердить выполненную задачу</label>
        <button class="confirm_del_task btn_form_ok">Удалить</button>
    <button class="cancel_del_task btn_form_del">Отмена</button>
</div>
</div>

<button class="btn_create_task">+</button>

<? } else { 

    if ($user_level==2){

    ?>
    <div class="container">
    <div class="row">
    <div class="col-md-12">
    
    <h1 class="h1_main">Соуса нет :(</h1>
    <p class="p_main p_title">Мы более чем уверены, ваше учебное заведение нуждается в соусе!</p>
    <center><a class="p_main" href="/create-team/">Создать свое сообщество</a></center>
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
                <h1 class="h1_main">Ожидайте подтверждения</h1>
                <p class="p_main p_title">Необходимо немного подождать, прежде чем начать работу в сообществе</p>
                <center><a class="p_main" href="/teams/">Проверить соус</a></center>
                
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