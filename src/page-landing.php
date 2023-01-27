<?php 

if (isset($_POST['logout'])){
	wp_logout();
	echo "<script>location.href = '/landing/';</script>";
}

$posts = get_posts( array(
	'numberposts' => -1,
	'orderby'     => 'date',
	'order'       => 'DESC',
	'post_type'   => 'post',
	'posts_per_page' => 3,
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );


get_header(); 
?>


<div class="container">

	<div class="row">
		<div class="col-md-12">
			<h1 class="h1_main">Организуй свою работу</h1>
			<p class="p_main p_title">СОУС - место, где рабочие процессы организовываются проще
			<br>Найди свой особенный соус!</p>
			<center>
			<? if(is_user_logged_in()){ 
			$user_action="/homepage"; 
			} else {
			$user_action="/sign-in";
			 } ?>
			 <button class="btn_ver1" onclick="window.location.href = '<?=$user_action?>'">Приступить</button>
			</center>
			<center><img class="img_sous_pic" src="<?php echo get_template_directory_uri();?>/img/sous_img.png"></center>
		</div>
	</div>
</div>
</div>
<div class="landing_about">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="landing_about_in">
			<img class="img_sous_mall" src="<?php echo get_template_directory_uri();?>/img/sousall.svg">
			<img class="img_sous_big" src="<?php echo get_template_directory_uri();?>/img/mockup.png">
			<h2>О проекте</h2>
			<p>Это проект, который сможет помочь организовать рабочие процессы внутри школьного совета. Речь идет о чётком распределении задач в коллективе, составление планов работы и связь с участниками органа ученического самоуправления. Вся информация структурирована и легко воспринимаема.</p>
			<p>Каждый может создать задачу, указать дедлайн и получить отчёт от ответственного за задачу. Из таких задач председатели советов могут сделать общедоступный план работы и следить за его выполнением.</p>
			<p>В блоге сайта будет располагаться полезная информация о том, каким образом лучше всего построить работу в соусе.</p>
			<p></p>
			</div>
		</div>
	</div>
</div>
</div>
<div class="content">
<div class="container">
<div class="row">
	<div class="col-md-12">

		
			

			<div class="kupl_out">
				<h2 class="h2_main">Состоишь в школьном совете? <img class="kupl" src="<?php echo get_template_directory_uri();?>/img/kupl.svg"></h2>
			</div>

		</div>
	</div>
</div>

			<div class="desc_all">
			<div class="desc_all_in">
				<div class="line_out right_line">
					<p class="p_main">Регистрируйся</p>
					<img src="<?php echo get_template_directory_uri();?>/img/right_line.svg">
				</div>
				<div class="line_out left_line">
				<img src="<?php echo get_template_directory_uri();?>/img/left_line.svg">
				<p class="p_main">Дождись подтверждения </p>
				</div>
				<div style="text-align: left;">
				<p class="p_main right_line">Получай задачи и выполняй их!</p>
				<button class="btn_ver2" onclick="window.location.href = '<?=$user_action?>'">Приступить</button>
				</div>
			</div>
			</div>
			

			

			<div class="kupl_out">
				<h2 class="h2_main">Хочешь свой соус? <img class="kupl" src="<?php echo get_template_directory_uri();?>/img/kupl.svg"></h2>
			</div>


			<div class="desc_all">
			<div class="desc_all_in">
				<div class="line_out right_line">
					<p class="p_main">Регистрируйся</p>
					<img src="<?php echo get_template_directory_uri();?>/img/right_line.svg">
				</div>
				<div class="line_out left_line">
				<img src="<?php echo get_template_directory_uri();?>/img/left_line.svg">
				<p class="p_main">Заполни информацию о своем соусе</p>
				</div>
				<div style="text-align: left;">
				<p class="p_main right_line last_line">Организовывай пространство своего соуса и наполняй участниками</p>
				</div>
				<div style="text-align: right;">
					<button class="btn_ver3" onclick="window.location.href = '<?=$user_action?>'">Засоусить</button>
				</div>
			</div>
			</div>
			
</div>
<div class="container">
<div class="row">
	
			



			<h1 class="h1_main">Новости  <a href="/blog">&#8250;</a></h1>

			  	<?
			  	$flag_slider="active";
				foreach ($posts as $key => $post) {
					$title=get_the_title($post->ID);
					$desc=get_the_content("", null,$post->ID);
					?>
					<div class="col-md-4">
					<div class="div_about news_div">
					<h2><?=$title?></h2>
					<p><?=$desc?></p>
					<div class="div_news">
					<button class="btn_ver3 news_btn" onclick="window.location.href = '<? echo get_post_permalink($post->ID); ?>'">Перейти к новости</button>
					</div>
					</div>
					</div>
					<?
					$flag_slider="";
				}
				?>
	
</div>
</div>


<div class="content">

<?php get_footer(); ?>