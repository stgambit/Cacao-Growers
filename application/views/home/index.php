<?php if (isset($homeSettings)): ?>
	<?php foreach ($homeSettings as $key): ?>
		<?php if ($key->settings_name !== 'section4'): ?> 

			<?php echo $key->settings_value; ?>

			<?php else: ?>

				<?php if (isset($listPosts) && is_array($listPosts)): ?>
					
				  <div class="lates">
				    <div class="container">
				      <div class="text-center">
				        <h2>Latest updates</h2>
				      </div>
				      <?php foreach ($listPosts as $key): ?>

				      <div class="col-md-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
				      	<div class="posts-cover"><?=$key->cover?></div>
				      	<div class="posts-title"><h2><?=$key->title?></h2></div>
				      	<div class="posts-content"><?=$key->content?></div>
				      </div>
				      <?php endforeach ?>
				  	</div>
				</div>

				<?php endif ?>

				

		<?php endif ?>

		?>
	<?php endforeach ?>
<?php endif ?>