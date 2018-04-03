<?php use Cake\I18n\I18n; ?>
<style type="text/css">
.imageHeightWidth {width: 100px; height: 100px; float: right;}
.message{text-align: center;}
</style>
<div class="container">
<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
  <?= $this->Html->css(['magnific-popup']); ?>
  <?= $this->Html->script(['jquery.magnific-popup']); ?>
<?php /*} else { ?>
  <?php $this->Html->css(['lightbox.min']); ?>
  <?php $this->Html->script(['lightbox-plus-jquery.min']); ?>
<?php }*/ ?>


  <?= $this->Flash->render();?>
  <div class="col-sm-12">
    <?php if( strtolower($status) == __('opened')/* || strtolower($status) == 'åbnet'*/) {
      $commentTicketErrorDataDescription = !empty($this->request->session()->read('comment-support-ticket-data.description')) ? $this->request->session()->read('comment-support-ticket-data.description') : '';
      ?>
      <div id="reply-section">
        <!-- <div class="col-md-12"> -->
          <div class="col-sm-6 col-md-6 ticket-listing-links">
            <?= $this->Html->link(__('Open Tickets'), ['controller'=>'support-tickets', /*'action' => __('index'),*/ __('opened')], ['class' => 'btn btn-red']); ?>
            <?= $this->Html->link(ucwords(__('closed tickets')), ['controller'=>'support-tickets', /*'action' => __('index'), */__('closed')], ['class' => 'btn btn-red', 'style' => ['float: rigth;']]); ?>
          </div>
          <div class="col-sm-6 col-md-6">
            <h1><?= ucfirst($data['subject']); ?></h1>
            <div id="open-ticket-title"><?= __('Enter your comment'); ?></div>
          </div>
        <!-- </div> -->
        <div class="row">
          <div class="col-md-6">
            <div class="widget-area no-padding blank">
              <div class="status-upload">
                <?= $this->Form->create($form, ['class' => 'form-horizontal', 'id' => 'step-1', 'enctype' => 'multipart/form-data']) ?>
                  <?php
                    echo $this->Form->textarea('description', ['id' => 'description', 'class' => 'form-control ticket_editor validate[required]', 'value' => $commentTicketErrorDataDescription]);?>
                  <ul>
                    <li>
                      <?php echo $this->Form->file('file', ['class' => 'form-control']) ?>
                      <?= $this->Form->hidden('parent_id', ['value' => $id]);?>
                    </li>
    							</ul>
                  <div class="clearfix"></div>
                  <button type="submit" class="btn btn-success green"><i class="fa fa-share"></i> <?= __('Submit'); ?></button>
  							<?= $this->Form->end();?>
              </div><!-- Status Upload  -->
  					</div><!-- Widget Area -->
  				</div>
        </div>
        <div class="col-sm-6 col-md-6 ticket-listing-links-mobile">
          <?= $this->Html->link(__('Open Tickets'), ['controller'=>'support-tickets', /*'action' => __('index'),*/ __('opened')], ['class' => 'btn btn-red']); ?>
          <?= $this->Html->link(ucwords(__('closed tickets')), ['controller'=>'support-tickets', /*'action' => __('index'), */__('closed')], ['class' => 'btn btn-red', 'style' => ['float: rigth;']]); ?>
        </div>
          
      </div>
    <?php } else { ?>
      <div class="ticket-close-msg"><?= __('This ticket has been closed by Admin.'); ?></div>
    <?php } ?>
    <div id="comment-details">
      <?php if(!empty($data) ) { ?>
        
        <div class="parent-ticket-section">
          <div class="user-image">
            <div class="image">
              <?= $this->Html->image('noavatar.png', ['alt' => '']); ?>
            </div>
          </div>

          <div class="comment-history">
            <div class="panel panel-white post panel-shadow">
              <div class="post-heading">
                
                <div class="user-details">
                  <div class="meta">
                    <div class="title h5">
                      <!-- <a href="#"> --><b><?= ucfirst($data['profiles']['first_name']." ".$data['profiles']['last_name']); ?></b><!-- </a> -->
                    </div>
                    <h6 class="text-muted time">
                      <?php
                        if($this->request->session()->read('locale') == 'da') {
                          I18n::locale('en');
                          echo date('d/m/Y h:i A', strtotime( $data['created'] ) );
                          I18n::locale('da');
                        } else {
                          echo date('d/m/Y h:i A', strtotime($data['created']));
                        }
                        //date('d/m/Y h:i A', strtotime( $data['created'] ) );
                      ?>
                    </h6>
                  </div>
                </div>
              </div> 
              <div class="post-description"> 
                <p><?= stripslashes($data['description']); ?></p>
              </div>

              <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                <div class="comment-image">
                <?php if(!empty($data['comment_file']['file']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$data['comment_file']['file'])) { ?>
                  <a class="test-popup-link" href="/uploads/tickets/<?= $data['comment_file']['file']; ?>">
                    <span><?= $this->Html->image('../uploads/tickets/'.$data['comment_file']['file'], ['class' => 'zoom-image imageHeightWidth', 'title' => 'Click to expand image']); ?></span>
                  </a>
                <?php } ?>
                </div>
              <?php /*} else { ?>
                <div class="comment-image">
                  <?php if(!empty($data['comment_file']['file']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$data['comment_file']['file'])) { ?>
                    <a class="example-image-link" href="/uploads/tickets/<?= $data['comment_file']['file']; ?>" data-lightbox="example-1">
                      <img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $data['comment_file']['file']; ?>" alt="" title="Click to expand image"/>
                    </a>
                  <?php } ?>
                </div>
              <?php }*/ ?>


              <?php /*if(!empty($data['comment_file']['file'])) { ?>
                <div class="comment-image">
                  <a class="example-image-link" href="/uploads/tickets/<?= $data['comment_file']['file']; ?>" data-lightbox="example-1">
                    <img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $data['comment_file']['file']; ?>" alt="" title="Click to expand image"/>
                  </a>
                </div>
              <?php }*/ ?>
            </div>
          </div>
        </div>
      <?php }
		    $comments = $this->Comment->getComments($id);
        foreach ($comments as $cdata) { ?>
          <div class="user-comment-history">
            <div class="user-image">
              <div class="image">
                <?= $this->Html->image('noavatar.png', ['alt' => '']); ?>
              </div>
            </div>

            <div class="comment-history-tree">
              <div class="panel panel-white post panel-shadow">
                <div class="post-heading">
                  <div class="user-details">
                    <div class="meta">
                      <div class="title h5">
                        <?php if($cdata['commented_by'] == 2) {
      											  $uname = ucfirst($data['profiles']['first_name']." ".$data['profiles']['last_name']); 
      											} else {
      													$uname = 'admin';
      											}
      									?>
                        <!-- <a href="#"> --><b><?= ucfirst($uname); ?></b><!-- </a> -->
                      </div>
                      <h6 class="text-muted time">
                        <?php
                          if($this->request->session()->read('locale') == 'da') {
                            I18n::locale('en');
                            echo date('d/m/Y h:i A', strtotime( $cdata['created'] ) );
                            I18n::locale('da');
                          } else {
                            echo date('d/m/Y h:i A', strtotime($cdata['created']));
                          }
                          //date('d/m/Y h:i A', strtotime( $cdata['created'] ) );
                        ?>
                      </h6>
                    </div>
                  </div>
                </div> 
                <div class="post-description"> 
                  <p><?= stripslashes($cdata['description']); ?></p>
                </div>
                <?php if(!empty($cdata['comment_file']['file'])) { ?>

                  <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                    <div class="comment-tree-image">
                    <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$cdata['comment_file']['file'])) { ?>
                      <a class="test-popup-link" href="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>">
                        <span><?= $this->Html->image('../uploads/tickets/'.$cdata['comment_file']['file'], ['class' => 'zoom-image imageHeightWidth', 'title' => 'Click to expand image']); ?></span>
                      </a>
                    <?php } ?>
                    </div>
                  <?php /*} else { ?>
                    <div class="comment-tree-image">
                      <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$cdata['comment_file']['file'])) { ?>
                        <a class="example-image-link" href="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" data-lightbox="example-1">
                          <img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" alt="" title="Click to expand image"/>
                        </a>
                      <?php } ?>
                    </div>
                  <?php }*/ ?>




                  <?php /* ?>
                  <div class="comment-tree-image">
                    <a class="example-image-link" href="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" data-lightbox="example-1">
                      <img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" alt="" title="Click to expand image"/>
                    </a>
                  </div>
                <?php */} ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
  </div>
</div>
<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->
<script src="//cdn.ckeditor.com/4.5.5/standard/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace('description');
  /*tinymce.init({
    selector: 'textarea.ticket_editor'
  });*/
  $('document').ready(function(){
    $('body').addClass('handle-light-box-image-scroll');
  });
  <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
      $(document).ready(function() {
        $('.image-link').magnificPopup({type:'image'});
    });
    $('.test-popup-link').magnificPopup({
        type: 'image'
    });
    $('.test-popup-link').on('click', function(){
      $('#main-header').css('z-index', '999');
    });
    <?php //} ?>
</script>