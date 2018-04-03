<?php use Cake\Routing\Router;?>
<?php use Cake\I18n\I18n;?>
<?php $locale = strtolower( substr (I18n::locale() , 0, 2) );?>
<div class="main_sunsigns">
	<div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
		<div id="signs_wrapper">
			<ul id="signs">
				<?php if( !empty($allsunsigns)):?>
					<?php foreach($allsunsigns as $sunsign):?>
						<li>
						  		<?php
                                if( $locale == 'da' )
                                { ?>
                            		<!-- Alt attribute update by Kingslay (2017-04-12) -->
									<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><img src="<?php echo Router::url('/', true).'webroot/uploads/sunsigns/'.$sunsign['icon']?>" alt='<?= __('horoscope for')." ".__($sunsign['name'])?>'/>
									</a>
									<?php
								}
									 else
									 {
									?>
									<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><img src="<?php echo Router::url('/', true).'uploads/sunsigns/'.$sunsign['icon']?>" alt="<?= __('horoscope for')." ".__($sunsign['name'])?>"/> </a>

									<?php 
									}
									?>

								<h5>  
								<?php 

								  if( $locale == 'da')
								  {
								  	?>
								  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><?= __d('default', $sunsign['name']);?></a>

								  	<?php
								  }
								  else
								  {
								  	?>
								  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><?= __d('default', $sunsign['name']);?></a>

								  <?php
								  }
 								?>
							
									</h5>
								</li>
							<?php endforeach;?>
						<?php else: ?>
							<h5> <?= __('No Sunsign Found');?></h5>
						<?php endif;?>
					</ul>
				</div>
			</div> <!-- .et_pb_fullwidth_code -->
		</div>
		<div class="freehoroscope_slider" id="siteslides" style="visibility: hidden;">
			<?php if( !empty($allsunsigns)):?>
				<?php foreach($allsunsigns as $sunsign):?>
   					<div class="slide">

						<?php
                        if( $locale == 'da' )
                        {
						?>
						<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><img src="<?php echo Router::url('/', true).'webroot/uploads/sunsigns/'.$sunsign['icon']?>" alt='<?= __($sunsign['name'])?>'/>    </a>
						<?php
						 }
						 else
						 {
						?>
						<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><img src="<?php echo Router::url('/', true).'webroot/uploads/sunsigns/'.$sunsign['icon']?>" alt='<?= __($sunsign['name'])?>'/>    </a>

						<?php 
						}
						?>




					<h5>  
						<?php 

								  if( $locale == 'da')
								  {
								  	?>
								  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><?= __d('default', $sunsign['name']);?></a>

								  	<?php
								  }
								  else
								  {
								  	?>
								  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><?= __d('default', $sunsign['name']);?></a>

								  <?php
								  }
								  ?>

						</h5>
					</div>
				<?php endforeach;?>
			<?php else: ?>
				<h5> <?= __('No Sunsign Found');?></h5>
			<?php endif;?>
		</div> 