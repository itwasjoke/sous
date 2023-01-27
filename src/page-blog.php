<?php get_header(); 
$posts = get_posts( array(
	'numberposts' => -1,
	'orderby'     => 'date',
	'order'       => 'DESC',
	'post_type'   => 'post',
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );
// DESC
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="h1_main">Блог</h1>
			<?
			foreach ($posts as $key => $post) {
				$post_link=$post->post_name;
				$date=get_the_date('d.m.Y H:i',$post->ID);
				$title=get_the_title($post->ID);
				$desc=get_the_content(null, null,$post->ID);
				?>
				 <div onclick="window.location.href = '/<?=$post_link?>'" class="new_div">
	                <p class="task_name"><?=$title?></p>
	                <p class="task_desc"><?=$desc?></p>
	                <div class="notneedinfo">
	                <p class="task_arch grey"><?=$date?></p>
	            	</div>
	            </div>
				<?
			}
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>