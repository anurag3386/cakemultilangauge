<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache; ?>
<?php 
      $firstName = isset($user['profile']['first_name'])? $user['profile']['first_name'] : '';
      $lastName  = isset($user['profile']['last_name'])? $user['profile']['last_name'] :'' ;
      $username  = isset($user['username']) ? $user['username'] : '' ;
      $order     = $this->request->session()->read('Order');
      $product_type = $order['product_type'];
      $language_id  = $order['language_id'];
?>
<div class="free-trial-parent">
<article id="post-1305" class="post-1305 page type-page status-publish hentry">
  <div class="entry-content">
    <div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
      <div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
      
        <?php echo $this->Form->create($form,  ['id' => 'step-1', 'class' => 'checkout_form' ])?>
     <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
    <div class=" et_pb_row et_pb_row_0">
      <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
        <div class="et_pb_code et_pb_module  et_pb_code_0 aligncenter">
          <?php 
          echo $this->Html->image("/uploads/products/".$product['image'] , [ 'alt' => __($product['name']), 'class' => 'img-responsive size-thumbnail wp-image-164']);  
          ?>
          <h3 class="checkout_title"><?php echo $product['name']?></h3>
          <h3 class="trial"><?= __('30 Days Free Trial');?></h3><br>
          <hr>
        </div> <!-- .et_pb_code -->
      </div> <!-- .et_pb_column -->
    </div> <!-- .et_pb_row -->
  </div>

  <div id="checkout_1_wrapper">
    <?php if( empty($user_id) ):?>
      <ul class="checkout_user_login">
        <li><?= __('Are you a returning user?');?> <?php echo $this->Html->link(__('Login here'), [ 'controller' => 'Users', 'action' => 'login'])?></li>
        <li><?= __('Or you can create a');?> <?php echo $this->Html->link(__('new account'), [ 'controller' => 'Users', 'action' => 'sign-up'])?></li>
      </ul>
    <?php endif;?>
    <h2><?= __('General Information');?></h2>
    <?php 
        
         // $productId        = $this->request->params['pass'][0];
           $productId         = $order['product_id']; 
         
          $languageOptions  = $this->Custom->getLanguaguesByProductId($productId);
          foreach($languageOptions as $key => $selectLanguage)
           {
            
             $val[$selectLanguage->language_id] = $selectLanguage['language']->name;
           }
           if(!isset($val) || empty($val))
           {
            $val = '';
           }
    ?>
    <label for=""><?= __('Language');?></label>     
    <?php 
      echo $this->Form->select('language_id', $val, ['class' => 'validate[required]', 'value' => $language_id] );
    ?>
    <label for=""><?= __('First Name');?></label>
    <?php echo $this->Form->text('profile.first_name', [ 'id' => 'firstname', 'tabindex' => '2', 'class' =>'inputLarge validate[required]', 'placeholder' => 'John', 'default' => $firstName] )
    ?>
    <br>
    <label for=""><?= __('Last Name');?></label>
    <?php echo $this->Form->text('profile.last_name', [ 'id' => 'last_name', 'tabindex' => '3', 'class' =>'inputLarge validate[required]', 'placeholder' => 'Doe' , 'default' => $lastName] )
    ?>
    <br>
    <label for=""><?= __('Email');?></label>
    <?php echo $this->Form->text('username', [ 'id' => 'username', 'tabindex' => '4', 'class' =>'inputLarge validate[required, custom[email]]', 'placeholder' => 'youremail@example.com', 'default' => $username ] );
    ?>
    <br>
      </div>
      <div class="et_pb_section  et_pb_section_2 et_section_regular">
        <div class=" et_pb_row et_pb_row_2">
          <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
            <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
              <hr>
              <?php 
               echo  $this->Form->button(__('Download').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'btnStartOrderProcess', 'class' => 'btn btn-red' , 'type' => 'submit']);
              ?>

              <!-- <button type="submit" name="btnStartOrderProcess" id="btnStartOrderProcess" class="btn btn-red">< ?= __('Download');?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button> -->


              <?php echo $this->Form->hidden('product_type', ['value' => $product_type])?>
            </div> <!-- .et_pb_text -->
          </div> <!-- .et_pb_column -->
        </div> <!-- .et_pb_row -->
      </div>
      <?php echo $this->Form->end();?>
    </div> <!-- .et_pb_fullwidth_code -->
  </div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->
</article>
</div>
<?php 
  $order['url']   = Router::url($this->request->here(), true);
  $this->request->session()->write('Order' , $order);
 ?>  
