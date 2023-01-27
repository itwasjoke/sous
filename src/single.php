<?php
/*
 * The template for displaying all single posts
 */

get_header();
// print_r ($post);
$date=get_the_date('d.m.Y H:i',$post->ID);
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
		<h1 class="h1_blog"><? echo $post->post_title; ?></h1>
		<p class="p_blog_date">Опубликовано: <?=$date?></p>
		<? echo $post->post_content; ?>
		</div>
	</div>
</div>
<?

get_footer();