<?php

if (isset($_GET['logout1'])){
    wp_logout();
    echo "<script>location.href = '/landing/';</script>";
    exit();
};

 ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/logo3.ico" type="image/x-icon">



    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover maximum-scale=1.0, user-scalable=0">
    <title><?php wp_title(''); ?> <?php if ( !(is_404()) && (is_single()) or (is_page()) or (is_archive()) ) { ?><?php } ?> <?php bloginfo('name'); ?></title>

    <?php wp_head();?>
</head>
<body <?php body_class(); ?>> 

    <header class='header'>
       <div class="container">
           <div class="header_body">
               <a href="/landing/">
                   <img class="header_logo" src="<?php echo get_template_directory_uri(); ?>/img/logo2.svg" alt="">
               </a>
               <div class="header_burger">
                   <span>
                       
                   </span>
               </div>
               <nav class="header_menu">
                   <ul class="header_list">
                        <li>
                           <a href="/" class="header_link">Главная</a>
                        </li>
                        <? if (is_user_logged_in()){ ?>
                           <li>
                               <a href="/homepage" class="header_link">Задачи</a>
                           </li>
                           <li>
                               <a href="/profile" class="header_link">Профиль</a>
                           </li>
                           <li>
                               <a href="/teams" class="header_link">Ваш соус</a>
                           </li>
                         <? }?>
                        <li>
                            <a href="/blog" class="header_link">Блог</a>
                        </li>
                   
               
                   <?
                    // $b1=(get_page_uri()=="sign-in" or get_page_uri()=="sign-up");
                    if (is_user_logged_in()){
                        ?>
                            <li><a onclick="document.getElementById('exit_form_header').submit();" class="header_link action_link">Выход</a>
                            <form method="POST" id="exit_form_header" action="/landing/" name="youdontneed">
                            <input type="hidden" name="logout" class="logout_form btn_form_del" value="Выйти">
                            </form>
                        </div>
                        <? }
                    else{
                            // if(!$b1){
                            ?>
                            <li><a href = '/sign-up' class="header_link action_link">Регистрация</a></li>
                            <li><a href='/sign-in' class="header_link action_link">Вход</a></li>
                            <?
                        // }
                        }
                    ?>
                    </ul>
               </nav>
           </div>
       </div>
    </header>
    <div class="wrapper">
    <div class="content">