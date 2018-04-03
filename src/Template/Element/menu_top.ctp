<?php use Cake\Cache\Cache; 
      use Cake\Routing\Router;
      $locale = $this->request->session()->read('locale');
      $page_locale = ($locale == 'da') ? 'dk/' : ''; 
?>
<?php if(!empty($topMenus)):?>
  <?php foreach($topMenus as $key):?>
    <?php
      $children=$this->Menu->findChild($key['id'],'top');
      if(!empty($children)):
    ?>
        <li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item "> 
        <?php
          if( $key['url'] == 'reports'):
            echo $this->Html->link( __d('default',$key['title']), ['controller'=> 'Products', 'action' => 'astrology-reports']);
          elseif( $key['url'] == 'astrology-software'):
            echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'Products', 'action' => 'astrology', $key['url']]);
          elseif(empty($key['url'])):
            echo $this->Html->link(__d('default', $key['title']),'#');
          else:
            echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'Pages', 'action' => 'menu-pages', $key['url']]);
          endif;
        ?>

<?php else: ?>
        <li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page menu-item <?php echo (strtolower($key['title'])=='home')?'current-menu-item page_item page-item current_page_item':''?>">
    <?php if( !empty($key['url']) ):?>
<?php
       if($key['url'] == 'home' ):
         	echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'pages', 'action' => 'index']);
        elseif( $key['url'] == 'free-horoscope'):
          
          //echo '<a href="/sun-signs/free-horoscope">'.__d('default', $key['title']).'</a>';
          
          echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'sun-signs', 'action' => 'free-horoscope']);

        elseif( $key['url'] == 'blog'):
         //  if( $locale == 'da')
         //  {
         //   echo $this->Html->link( __d('default', $key['title']), SITE_URL."blog/da");
         // } else
         // {
           echo $this->Html->link( __d('default', $key['title']), SITE_URL."blog/");
         //}
        else:
          echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'Pages', 'action' => 'menu-pages', $key['url']]);
			  endif;
?>

<?php else:?>
<?php echo $this->Html->link( __d('default', $key['title']), ['controller'=> 'pages', 'action' => 'menu-pages', '#']);
?>
<?php endif;?>
  </li>
<?php endif;?>
<?php   $children=$this->Menu->findChild($key['id'],'top');
        if(!empty($children)):
?>
<ul class="sub-menu">
  <?php
         foreach($children as $data): 

          // echo 'here=>'. $data['url'];
        //   die;         
  ?>
  <li id="menu-item-65" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-65"> 
    <?php
    if( $data['url'] == 'courses-events'):
           echo $this->Html->link( __d('default', $data['title']), ['controller'=> 'Users', 'action' => 'events']);

     elseif( $data['url'] == 'astrology-calendar-report' || $data['url'] == 'character-and-destiny-report' || $data['url'] == 'comprehensive-lovers-report' || $data['url'] == 'essential-year-ahead-report'):

         $product = $this->Menu->getProducts($data['url']);
       if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite')) {
          
           echo $this->Html->link( __d('default', $data['title']), Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $product['seo_url']). '/'.__('elite-full-report') , true));

//            echo $this->Html->link( __d('default', $data['title']), ['controller'=> 'Products', 'action' => 'detail', $product['seo_url'], 'elite-full-report', $product['id']]);
          
        } else {
           
           echo $this->Html->link( __d('default', $data['title']), Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $product['seo_url']). '/'.__('full-reports') , true));

          //echo $this->Html->link( __d('default', $data['title']), ['controller'=> 'Products', 'action' => 'detail', $product['seo_url'], 'full-reports']);
        }

    elseif( $data['url'] == 'horoscope-interpreter' || $data['url'] =='astrology-for-lovers' ||  $data['url'] =='astrology-calendar' ):

     $product = $this->Menu->getProducts($data['url']);
     
        // echo $this->Html->link( __d('default', $data['title']), ['controller'=> 'Products', 'action' => 'detail',$product['seo_url'], 'software-cd']);
         
         
         echo $this->Html->link( __d('default', $data['title']), Router::url('/'.$page_locale.__('astrology-software').'/'. __d('default', $product['seo_url']). '/software-cd' , true));
    
elseif(empty($data['url'])):
    echo $this->Html->link($data['title'],'#');
  else:
    echo $this->Html->link( __d('default', $data['title']), ['controller'=> 'Pages', 'action' => 'menu-pages', __d('default', $data['url']) ]);
endif;

    ?> 
  </li>
  <?php endforeach;?>
</ul>
</li>
<?php endif;?>
<?php endforeach;?>
<?php else:?>
<p>No Menus Found</p>
<?php endif;?>
