<?php use Cake\Cache\Cache;?>
<?php use Cake\I18n\I18n;?>
<?php use Cake\Routing\Router;?>
<?php $locale = strtolower(substr(I18n::locale(), 0, 2));?>
<!-- <link rel="stylesheet" href="<?php echo $this->request->webroot?>css/responsive-tabs.css">
 -->
<div id="et-main-area">
	<div id="main-content">

		<article id="post-37" class="post-37 page type-page status-publish hentry">

			<div class="entry-content">
				<?php
					/**
					 * Added class if loggedin user is elite user for responsive design
					 * Created By : Krishan Kumar
					 * Created Date : March 10, 2017
					 */
					// $eliteClass = '';
					// if (!empty($this->request->session()->read('Auth.User.role')) && (strtolower($this->request->session()->read('Auth.User.role')) == 'elite' ) ) {
					// 	$eliteClass = ' home_sunsign_banner_elite';
					// }
					// END
					?>
					<div class="home_sunsign_banner">
						<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">


							<?php echo $this->element('sunsigns');?>


							<?php if( !empty($settings) ):?>
								<?php 

								foreach ($settings as $setting)
								{

									if( $setting->field_key == "banner_text_".$locale):
										$banner_text = $setting->field_value;
									endif;

									if( $setting->field_key == "home_banner"):
										$banner_image = $setting->field_value;
									endif;



									if( $setting->field_key == "banner_subtext_".$locale):
										$banner_subtext = $setting->field_value;
									endif;

									if( $setting->field_key == "banner_button_".$locale):
										$banner_button = $setting->field_value;
									endif;

								} 

								?>
							<?php endif; ?>
						</div> <!-- .et_pb_section -->
					</div>

					<div class="home_page_banner">
						<div class="et_pb_section  et_pb_section_1 et_pb_with_background et_section_regular" style='background-image:url(<?php echo $this->request->webroot.$banner_image?>); '>
							<div class=" et_pb_row et_pb_row_0">
								<div class="et_pb_column et_pb_column_1_2  et_pb_column_0">
									<div id="home-banner" class="et_pb_text et_pb_module et_pb_bg_layout_dark et_pb_text_align_left  et_pb_text_0">
										<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
											<h1><?php echo  __d('default', $banner_text);?></h1>
										<?php /*} else { ?>
											<h2><?php echo  __d('default', $banner_text);?></h2>
										<?php }*/ ?>
										<p>
											<?php echo  __d('default', $banner_subtext);?>

											<br>
											<?php echo $this->Html->link(__d('default', $banner_button), ['controller' => 'Pages', 'action' => 'menu-pages', 'free-astropage'], ['class' => 'btn btn-blue']);
											?>						
										</a></p>
									</div> <!-- .et_pb_text -->
								</div> <!-- .et_pb_column -->


								<?php 
								$user_id = $this->request->session()->read('user_id');



								if( !isset($user_id) || empty($user_id)):
									?>
								<div class="et_pb_column et_pb_column_1_2  et_pb_column_1">

									<div class="et_pb_code et_pb_module  et_pb_code_0">

										<div id="home-sign-up">
											<div class="top">
												<h5><?= __('Sign Up');?></h5>
												<p>... <?= __('and get your unique personalized Astro Page, Free Mini Report based on your birth planets as your welcome gift.');?></p>
												<?php 
												echo $this->Html->link(__('Want to learn more? Click Here'), ['controller' => 'Pages', 'action' => 'menu-pages', 'free-astropage']);
												?>

											</div>
											<div class="bottom">
												<?php echo $this->Form->create('Sign Up', ['url'=>[
												'controller' => 'Users', 'action' => 'sign-up'] , 'id' => 'form1']); ?>

												<label for="fname"><?= __('First Name'); ?></label>
												<?php echo $this->Form->text('fname', ['placeholder'=>'John', 'id'=>'fname'])?>

												<label for="lname"><?= __('Last Name');?> </label>
												<?php echo $this->Form->text('lname', ['placeholder'=>'Doe', 'id'=>'lname'])?>

												<label for="email"><?= __('Your Email'); ?></label>
												<?php echo $this->Form->text('usrname', ['placeholder'=>'youremail@example.com', 'id'=>'usrname'])?>

												<label for="birthdate"><?= __('Birthdate');?></label>
												<?php echo $this->Form->text('birth_date', ['class' => 'validate[required]', 'id' => 'datepicker' , 'autocomplete' => 'off'  ] ); ?>
												<p> <?php echo $this->Form->button(__('SIGN UP'), ['class'=>'btn btn-red btn-large'])?></p>
												
												<?php echo $this->Form->end();?>


												<?php
													//if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
														$astropageVideoText = $astropageVideoLink = '';
														if($this->request->session()->read('locale') == 'da') {
															$astropageVideoText = 'Se AstroSide Video';
															$astropageVideoLink = 'https://youtu.be/z4gKG0GHYJ8';
														} else {
															$astropageVideoText = 'See AstroPage Video';
															$astropageVideoLink = 'https://youtu.be/D9lNVB74zHs';
														} ?>
														<div style="width: 100%; text-align: center;">
														<?php
															echo $this->Html->link($astropageVideoText, $astropageVideoLink, ['target' => '_blank', 'style' => ['color: #ffffff; font-weight: bold; font-size: 16px;']]);
														?>
														</div>
												<?php //} ?>

											</div>
										</div>
									</div> <!-- .et_pb_code -->
								</div> <!-- .et_pb_column -->
							<?php endif;?>
						</div> <!-- .et_pb_row -->
					</div> <!-- .et_pb_section -->
				</div>


				<?php echo $this->Element('products/our_reports');?>

				<div class="testimonials_common">
					<div class="et_pb_section  et_pb_section_3 et_pb_with_background et_section_regular">
						<?php if(!$testimonials->isEmpty()):?>
							<?php foreach($testimonials as $testimonial):?>

								<div class=" et_pb_row et_pb_row_4">

									<div class="et_pb_column et_pb_column_4_4  et_pb_column_5">

										<div class="et_pb_testimonial  et_pb_testimonial_0 et_pb_icon_off et_pb_module et_pb_bg_layout_dark et_pb_text_align_center et_pb_testimonial_no_bg clearfix">
											<div class="et_pb_testimonial_portrait" style="background-image: url(<?php echo $this->request->webroot.'uploads/testimonials/'.$testimonial->image?>);">
											</div>
											<div class="et_pb_testimonial_description">
												<div class="et_pb_testimonial_description_inner" style="width: 967px;">

													<p>“<?php echo $testimonial->description;?>”</p>

													<strong class="et_pb_testimonial_author"><?php echo $testimonial->name;?></strong>
													<p class="et_pb_testimonial_meta"><?php echo $testimonial->profile;?></p>
												</div> <!-- .et_pb_testimonial_description_inner -->
											</div> <!-- .et_pb_testimonial_description -->
										</div> <!-- .et_pb_testimonial -->
									</div> <!-- .et_pb_column -->

								</div> <!-- .et_pb_row -->
							<?php endforeach;?>
						<?php else:?>

							<div  class="et_pb_testimonial_description_inner p:first-of-type" style="color:white">
								<p><?= __('No Data Found');?></p>

							</div>


						<?php endif;?>



					</div> <!-- .et_pb_section -->
				</div>


				<div class="wow_software_common">
					<div class="et_pb_section  et_pb_section_4 et_pb_with_background et_section_regular">
						<div class=" et_pb_row et_pb_row_5">
							<div class="et_pb_column et_pb_column_1_2  et_pb_column_empty et_pb_column_6">
							</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_2  et_pb_column_7">
							<div id="home_software" class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_3">

								<h2><?= __('WOW SOFTWARE');?></h2>
								<p><?= __('Astrology software from WOW distinguishes itself from other horoscope software, because no previous knowledge of astrological techniques is required.');?></p>
								<ul>
									<li><?= __('If your goal is understanding of yourself and your life path, than our CDs or shareware provide the perfect tool for you.');?></li>
									<li><?= __('If you are a student of astrology, you will find World of Wisdom software to be a tutor and companion on your way to become a skilled	astrologer.');?></li>
								</ul>
								<p>
									<a href="https://itunes.apple.com/us/app/astrowow/id892775222?mt=8" target="_blank">
									<?php echo $this->Html->image('icon_app_store.png',['alt'=>'icon_app_store', 'class'=>'alignnone size-full wp-image-93' , 'width'=>'107', 'height'=>'29'])?>
									</a>
									<?php  //echo $this->Html->image('icon_windows.png',['alt'=>'icon_windows','class'=>'alignnone size-full wp-image-95','height'=>'24', 'width'=>'116'])?></p>
								<p>
									<?php echo $this->Html->link(__('VIEW ALL OF OUR APPLICATIONS'), ['controller' => 'Products', 'action' => 'astrology', 'software'], ['class' => 'btn btn-red btn-large']);?>
									<!-- <a href="#" class="btn btn-red btn-large">VIEW ALL OF OUR APPLICATIONS</a> -->

								</p>

							</div> <!-- .et_pb_text -->
						</div> <!-- .et_pb_column -->

					</div> <!-- .et_pb_row -->

				</div> <!-- .et_pb_section -->
			</div>


			<div class="astrology_consultation_common">
				<div class="et_pb_section  et_pb_section_5 et_pb_with_background et_section_regular">



					<div class=" et_pb_row et_pb_row_6">

						<div class="et_pb_column et_pb_column_4_4  et_pb_column_8">

							<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_4">

								<h2><?= __('ASTROLOGY CONSULTATION');?> </h2>
								<p><?= __('Adrian Ross Duncan is a full-time practicing astrologer. He speaks regularly at congresses all over the world. Countries  where he has spoken: France, Holland, Britain, Norway, Sweden, Denmark, Finland, Ireland, Canada, the USA, Australia and New Zealand.');?>

								</p>

							</div> <!-- .et_pb_text -->
						</div> <!-- .et_pb_column -->

					</div> <!-- .et_pb_row --><div class=" et_pb_row et_pb_row_7">

					<div class="et_pb_column et_pb_column_1_3  et_pb_column_9">

						<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_0 et_always_center_on_mobile et-animated">
							<?php 
								// echo $this->Html->link($this->Html->image('temp_video1.jpg',array('alt'=>'consultation')),'https://www.youtube.com/watch?v=Ze8VwNle15I', array('target'=>'_blank','escape'=>false)); 
							?>
							<iframe width="340" height="200" src="https://www.youtube.com/embed/Ze8VwNle15I" frameborder="0" allowfullscreen></iframe>


						</div>
					</div> <!-- .et_pb_column -->
					<div class="et_pb_column et_pb_column_1_3  et_pb_column_10">

						<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_1 et_always_center_on_mobile et-animated">
							<?php //echo $this->Html->image('temp_video2.jpg',['alt'=>''])?>
							<iframe width="340" height="200" src="https://www.youtube.com/embed/76wipJ7Wk_U" frameborder="0" allowfullscreen></iframe>


						</div>
					</div> <!-- .et_pb_column -->
					<div class="et_pb_column et_pb_column_1_3  et_pb_column_11">

						<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_2 et_always_center_on_mobile et-animated">
							<?php //echo $this->Html->image('temp_video3.jpg',['alt'=>''])?>
							<iframe width="340" height="200" src="https://www.youtube.com/embed/fm7DLUCfo9c" frameborder="0" allowfullscreen></iframe>

						</div>
					</div> <!-- .et_pb_column -->

				</div> <!-- .et_pb_row -->
				<div class=" et_pb_row et_pb_row_8">

					<div class="et_pb_column et_pb_column_4_4  et_pb_column_12">

						<div id="home_consultation" class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_5">

							<p><?= __('Click on the button below to get a consultation with Adrian Duncan: ');?></p>
							<p>
								<?php echo $this->Html->link(__('ASK THE ASTROLOGER'), ['controller' => 'pages', 'action' => 'menu-pages', 'consultation' ], ['class' => 'btn btn-red btn-large']);?>
								<!-- <a href="#" class="btn btn-red btn-large"><?= __('ASK THE ASTROLOGER');?></a> -->

							</p>

						</div> <!-- .et_pb_text -->
					</div> <!-- .et_pb_column -->

				</div> <!-- .et_pb_row -->

			</div> <!-- .et_pb_section -->
		</div>


		<?php echo $this->element('books');?>
		<div class="about_astrowow_common">		
			<div class="et_pb_section  et_pb_section_5 et_pb_with_background et_section_regular">



				<div class=" et_pb_row et_pb_row_6">

					<div class="et_pb_column et_pb_column_4_4  et_pb_column_8">

						<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_4">

							<h2><?= __('About Astrowow.com');?> </h2>
							<p><?= __('AstroWOW is a new development of World of Wisdom, which has been providing astrology content on the net since 1995. Designed and written by astrologer Adrian Ross Duncan, this site is designed to give you a fully immersive experience of astrology and of your own personal horoscope. We believe that astrology should be presented in an easily accessible way, so even those people who have little knowledge of astrology and horoscopes can have access to the amazing richness of this ancient art.');?>

							</p>
							<h3>
								<?= __('The 12 Signs of the Zodiac');?>
							</h3>
							<p>
								<?= __('On the initial level we provide daily horoscopes, weekly horoscopes, monthly horoscopes and yearly horoscopes, which are written by us – fully professional astrologers – using the techniques of horary astrology to make our sunsigns or star signs as accurate as possible. We look at over 400 horoscopes and write 16,000 words each and every month to satisfy over a million users worldwide.');?>
							</p>

						</div> <!-- .et_pb_text -->
					</div> <!-- .et_pb_column -->

				</div> <!-- .et_pb_row -->

				<div class=" et_pb_row et_pb_row_8">

					<div class="et_pb_column et_pb_column_4_4  et_pb_column_12">

						<div id="home_consultation" class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_5">
							<p>
								<?php echo $this->Html->link(__('READ MORE'), ['controller' => 'pages', 'action' => 'menu-pages', 'about-astrowow' ], ['class' => 'btn btn-red btn-large']);?>

							</p>

						</div> <!-- .et_pb_text -->
					</div> <!-- .et_pb_column -->

				</div> <!-- .et_pb_row -->

			</div> <!-- .et_pb_section -->
		</div>
<!-- Tab Section -->
	<div class="astrology_horoscope_tabs">		
			<div class="et_pb_section  et_pb_section_5 et_pb_with_background et_section_regular">

				<div class=" et_pb_row et_pb_row_6">

					
	<div class="responsive-tabs">

			<h2><?= __('About Astrology');?></h2>
			<div>
				<p><?= __('Astrology has been around for many thousands of years, but never has it been as popular and respected as it is today. Why? Because astrology works, and when people read something as simple as their daily, weekly or monthly horoscope, they sense the resonance there is between their zodiac sign and their character.');
					?>
					</p>
					<p>
<?= __('But of course astrology is far more complex than just the 12 star signs. The idea behind astrology is that the smallest thing in the universe is subject to the same forces as the largest thing, and events in one field are reflected by events in another, as described by the old adage “As above, so below”. Nobody knows why this is the case, and there is no known force in the universe that has been proven to have this effect. Yet people relate to their horoscopes and sense their close affinity with the cosmos.');?>
</p>
<p>
<?= __('Western astrology is based on planetary movement in our solar system, and not so much on the stars outside the solar system. The 12 star signs are more accurately called sun signs, as they reflect the Earth’s orbit around the sun which is registered on Earth by the four seasons, and specifically by the spring and autumn equinox and the summer and winter solstices. These four points represent the start of the four signs: Aries and Libra, and Cancer and Capricorn respectively. The intermediate signs – Taurus/Gemini, Leo/Virgo, Scorpio/Sagittarius and Aquarius/Pisces are then inserted at 30 degree intervals. When making predictions or character analyses, astrologers look at the movement of the planets through these 12 zodiac signs, and the relation of the planets to each other.');
?>
	
</p>
			</div>

			<h2><?= __('Horoscopes');?></h2>
			<div>
				<p><?= __('A horoscope is a two-dimensional map of the solar system, which shows exactly where the sun, moon and planets are in the 12 zodiac signs: Aries, Taurus, Gemini, Cancer, Leo, Virgo, Libra, Scorpio, Sagittarius, Capricorn, Aquarius and Pisces. Based on the exact time and place of birth, the 12 Houses of the horoscope can also be calculated, and the planets in signs are placed in this framework – the personal horoscope.');
				?>
				</p>
				<p>
		<?= __('Basically this is an incredibly advanced way of displaying a particular point in time at a particular point in space. You can even work backwards from a horoscope drawing to divine the exact time and place of birth. There are 1440 simple combinations of planets in signs and houses, but astrology gets much, much more complicated than that.');
		?>
		</p>
		<p>
		<?= __('Of course, most people think of horoscopes as the daily, weekly, monthly or yearly horoscopes seen in newspapers and magazine, or online. (The 12 signs of the zodiac). Really these are sunsign horoscopes, because they are simply based on where your sun is on any particular birth date, and naturally these star signs are very general. They can be quite accurate though, because the sun is such a dominating factor in a horoscope. Nevertheless, the moon and planets each have very specific and very strong influences, and the only way you can find out what that is, is by having your personal horoscope calculated.');
		?>
</p>
<p>
<?= __('Basically your date of birth will show you in which sign the Sun, Mercury, Venus, Mars, Jupiter, Saturn, Uranus, Neptune and Pluto are in your horoscope, because they are unlikely to change signs during the course of a day. For example – from the standpoint of someone on Earth – the sun takes one month to move from one star sign to another, whilst Uranus takes seven years. As the moon moves from one sign to another in a little over two days, you need the birth time and place to be sure of where it is.');
?>
</p>
<p>
<?= __('
Knowing the birth time and place makes it possible to calculate the 12 houses of the horoscope, and the main framework of the 12 houses are the four cardinal points:');
?>
</p>
<div><?= __('The Ascendant – or first house (the Eastern horizon)');?></div>
<div><?= __('The IC – or fourth house (The sun’s position at midnight)');?></div>
<div><?= __('The Descendant – or seventh house (the Western horizon)');?></div>
<div><?= __('The MC – or tenth house (the sun’s position at noon)');?></div>
<br>
<p><?= __('Once you have the planet in sign and house, you can make the interpretation. So the Moon in Aries in the 7th house, for example, would show someone emotionally receptive (moon) in a rather impulsive way (Aries) to other people (7th house).');?></p>
<p><?= __('On the AstroWiki section of this site you can find interpretations of all these astrological combinations. And of course you can also have your personal horoscope calculated – and we’ll interpret it for you!');
?></p>
			</div>
			
			<h2><?= __('Zodiac Signs')?></h2>
			<div>
				<p><?= __('There is a myth associated with astrology that sun signs are superficial and that anyone can write them. It is true that sun signs are general, but they are by no means easy to write. In fact they demand tremendous experience. Only excellent astrologers can write accurate sunsigns. And most of the sun signs you see on major astrology sites are written by excellent astrologers. (Although this is not always the case with newspapers).');
				   ?>
				</p>
<p>
<?= __('The astrologers on our site have been practicing astrology for a minimum of ten years, some over 30 years. When we write daily horoscopes, weekly horoscopes and monthly horoscopes, we actually look at over 400 horoscopes for each star sign, each day and each week during the course of a month. And write around 16,000 words.')
?>
</p>
<p>
<?= __('Sun signs are sometimes called star signs, but they are based on the orbit of the Earth around the sun, which we register here on Earth as the equinoxes and solstices, which are when day and night are of equal length (March 20th and September 22nd) or when it is the longest day or longest night (21st June and December). These form the first degree of the Cardinal Signs – Aries, Cancer, Libra and Capricorn – and the remaining signs are placed between them. In this way with get the 12 zodiac signs, which should rightfully be called sun signs rather than star signs.');
?>
</p>
<p>
<?= __('Skilled sun sign astrologers follow the movement of the planets through each of these signs, and predict what will happen according to the relationships of the planets in the signs and to themselves. What is crucial for sun sign judgments is how strong or weak planets are in the signs. Planets are for example strong in the star sign they rule, and weak in the opposite sign of the zodiac. For each zodiac sign we place the sign as the 1st house of the horoscope and then make an Equal House horoscope, so that each subsequent sign corresponds to a subsequent house. So if we make a horoscope for Leo, then Virgo will be in the solar 2nd house (finances). The key to it all is the planet which “rules” each star sign.');
?>
</p>
<p><?= __('It works like this:');?> </p>
<div><?= __('For the Aries horoscope: Check out Mars in sign and house');?> </div>
<div><?= __('For the Taurus horoscope: Check out Venus in sign and house');?> </div>
<div><?= __('For the Gemini horoscope:Check out Mercury in sign and house');?> </div>
<div><?= __('For the Cancer horoscope:Check out the Moon in sign and house');?> </div>
<div><?= __('For the Leo horoscope:Check out the Sun in sign and house');?> </div>
<div><?= __('For the Virgo horoscope:Check out Mercury in sign and house');?> </div>
<div><?= __('For the Libra horoscope:Check out Venus in sign and house');?> </div>
<div><?= __('For the Scorpio horoscope:Check out Mars in sign and house');?> </div>
<div><?= __('For the Sagittarius horoscope:Check out Jupiter in sign and house');?> </div>

 
<div><?= __('For the Capricorn horoscope:Check out Saturn in sign and house');?> </div>
<div><?= __('For the Aquarius horoscope:Check out Saturn in sign and house');?> </div>
<div><?= __('For the Pisces horoscope:Check out Jupiter in sign and house');?> </div>
<br>

<p><?= __('The outer planets Uranus, Neptune and Pluto are not used as “rulers” in sun sign astrology, even though they are related to Aquarius, Pisces and Scorpio respectively. If you want to learn more about sunsign astrology check out my article at: Sun sign astrology');?></p>
			</div>
		</div>


			

				</div> <!-- .et_pb_row -->

				
			</div> <!-- .et_pb_section -->
		</div>



		<!-- blog section -->

		<div class="blog_common">		
			<div class="et_pb_section  et_pb_section_5 et_pb_with_background et_section_regular">

				<div class=" et_pb_row et_pb_row_6">
				<h2><?= __('Astrology Blogs');?></h2>

                  <?php 
                      if( !empty($astro_blogs) )
                      {
                      	foreach ($astro_blogs as $blog) {
                      		
                  ?>
					<div class="col-md-4">
						<div class="testimonials">
							<div class="active item">
								
								<div class="carousel-info">
								<?php
									if (empty($blog['image'])) {
										echo $this->Html->image('/images/no-image-available-thumb.jpeg', ['title' => $blog['title'], 'alt' => $blog['title']]);
									} else {
										echo $this->Html->image('/uploads/mini-blog/thumb/sm/'.$blog['image'], ['title' => $blog['title'], 'alt' => $blog['title']]);
									}
								?>									<div class="pull-left">
										<span class="testimonials-name"><?= $blog['title']?></span>
										
									</div>
								</div>
								<blockquote><p><?= implode(' ', array_slice(explode(' ', strip_tags($blog['description'])), 0, 30)); ?>
								<a class="readmore" href="<?= Router::url([
                                                                    'controller' => 'mini-blogs', 'action'=> 'post', $blog['slug'] ]); ?>">
                                                                            <?= __('...Read More'); ?>
                                                                        </a>
									
								</p></blockquote>
							</div>
						</div>
					</div>
					<?php }?>
					<?php }?>

				</div> <!-- .et_pb_row -->
			
			</div> <!-- .et_pb_section -->
		</div>




		<?php 
		//if( !isset($user_id) || empty($user_id)):
		?>
		<?php //echo $this->element('newsletter'); ?>
	</div> <!-- .et_pb_row -->
</div> <!-- .et_pb_section -->
<?php // endif;?>

</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->



</div> <!-- #main-content -->
<!-- <script src="<?php echo $this->request->webroot?>js/responsiveTabs.min.js"></script>
 -->
<!-- <script src="js/responsiveTabs.js"></script>
 -->		<script>
		// $(document).ready(function() {
		// 	RESPONSIVEUI.responsiveTabs();
		// })
		</script>



<span class="et_pb_scroll_top et-pb-icon et-hidden" style="display: inline;"></span>
<style type="text/css" id="et-builder-advanced-style">
	.et_pb_image_2 { text-align: center; }
	.et_pb_image_1 { text-align: center; }
	.et_pb_section_6 { background-color:#dcdce5; }
	.et_pb_image_3 { margin-left: 0; }
	.et_pb_image_4 { margin-left: 0; }
	.et_pb_image_0 { text-align: center; }
	.et_pb_text_4 { font-size: 16px; }
	.et_pb_text_1 { font-size: 16px; }
	.et_pb_section_2 { background-color:#e5e5ed; }
	.et_pb_section_3 { background-image:url(<?php echo $this->request->webroot?>img/home_background_1.jpg); }
	.et_pb_section_4 { background-image:url(<?php echo $this->request->webroot?>img/software_background.jpg); min-height: 700px;background-size: contain; }
	.et_pb_section_5 { background-color:#e5e5ed; }

	@media only screen and ( min-width: 981px ) {
		.et_pb_section_3 { padding-top: 100px; padding-bottom: 100px; }
	}


</style>
