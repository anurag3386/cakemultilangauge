<?php use Cake\I18n\I18n;?>
<?php $locale = I18n::locale();?>
<p><?php echo $this->Html->image('paypal_seal.jpg',['alt'=>'paypal_seal','class'=>'alignleft size-full wp-image-181','width'=>'181','height'=>'89'])?>

</p>
<p class="small"><?= __('Security: All transactions are done through SSL (Secure Socket Layer) 256 bit encryption.')?><br><?= __('Privacy: We comply with strict privacy and antispam policy please refer to our');?> 
	<?php echo $this->Html->link(__('Privacy Policy'), ['controller' => 'pages' , 'action' => 'menu-pages', 'privacy-policy' ]);?>
	<?= __('section to know more');?>…</p>
