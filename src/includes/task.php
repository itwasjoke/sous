<?php

$name1_1=get_user_meta($reporter, 'first_name', true);
$name1_2=get_user_meta($reporter, 'last_name', true);
$name2_1=get_user_meta($assigne, 'first_name', true);
$name2_2=get_user_meta($assigne, 'last_name', true);
$to_txt="От: ".$name1_1." ".$name1_2;
$from_txt="Для: ".$name2_1." ".$name2_2;
$name=$task->post_title;
$desc=$task->post_content;
$date1=$task->post_date_gmt;
$date2=$fields['deadline'];

?>
<div class="home_task_all">
    <div>
        <p class="home_task_h1"><?=$name?></p>
    </div>
    <div>
        <p class="home_task_h2"><?=$desc?></p>
    </div>
    <div class="home_task_grid">
        <div>
            <p class="home_task_h2">
                <span class="home_task_span">
                    <?=$date2?>
                </span>
            </p>
        </div>
        <div>
            <p class="home_task_h2">
                <span class="home_task_span">
                    <?
                    echo ($logic) ? $from_txt : $to_txt;
                    ?>
                </span>
            </p>
        </div>
    </div>
</div>