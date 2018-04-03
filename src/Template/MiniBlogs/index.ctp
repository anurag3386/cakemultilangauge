<?php use Cake\I18n\Time;?>

		<div id="et-main-area">
		<div class="mini_blogs_listing">
			<div id="main-content">
				<div class="container">
					<div id="content-area" class="clearfix">
						<div >

                        <?php 
	                          $i = 1552;

                              foreach($blogs as $data )
                              {
                        ?>
							<article id="post-<?= $i ?>" class="et_pb_post post-1552 post type-post status-publish format-standard hentry category-articles">


								<h2 class="entry-title">
								<?php 

								echo $this->Html->link( $data['title'], ['controller' => 'mini-blogs', 'action' => 'post', $data->slug])

								?>
								 
								
								</h2>
 								<?php $date = new Time($data->created);?>
								<p class="post-meta"><span class="published"><?= date('F j, Y', strtotime( $date->i18nFormat('YY-MM-dd'))  ); ?></span> </p><p>

                                 <?php
                                 	// limiting number of chars
                           			$content = strip_tags( $data->description );

                           			if( strlen($content) >= 300 )
                           			{
                           				$pos     = strpos($content, ' ', 300);
                           				$description = substr($content, 0, $pos);
                           				echo $description;
									?><span class="elipses">…</span>
                           			<?php
                           				echo $this->Html->link( __('Read More'), ['controller' => 'mini-blogs', 'action' => 'post', $data->slug], ['class' => 'more-link'])
                           			?>
                           				</p>
 									<?php
 									}
 									else
 									{
 										echo $content;
									}

                                 ?>
					
							</article> <!-- .et_pb_post -->
						<?php
						}
						?>

						<?php if ($this->Paginator->params()['count'] > $this->Paginator->params()['perPage']) { ?>
							<div class="box-footer clearfix">
								<?php 
 									$this->Paginator->options(array(
														   'url'=> array(
														   'controller' => 'mini-blogs',
														   'action' => 'index'
														)));
								?>
								<ul class="pull-right reportsPagination">
                                	<?= $this->Paginator->prev(' << '); ?>
                                  	<?= $this->Paginator->numbers(); ?>
                                  	<?= $this->Paginator->next(' >> '); ?>
                                </ul>
                            </div>
                        <?php } ?>



						<?php /*<div class="pagination clearfix">
							<div class="alignleft">
								 <div class="box-footer clearfix">
							        <ul class="pagination pagination-sm no-margin pull-right">
							          <?php echo $this->Paginator->prev(' << '); ?>
							          <?php echo $this->Paginator->numbers(); ?>
							          <?php echo $this->Paginator->next(' >> '); ?>
							        </ul>
							      </div>
							</div>
							<div class="alignright"></div>
						</div> */ ?>
					</div> <!-- #left-area -->
	</div> <!-- #content-area -->
</div> <!-- .container -->
</div>
</div> <!-- #main-content -->
