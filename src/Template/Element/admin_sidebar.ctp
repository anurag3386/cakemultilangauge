<?php
  //pr($this->request->params); die;
  $controller = strtolower($this->request->params['controller']);
  $action = $this->request->params['action'];
  if($controller != 'products')
  {
    $this->request->session()->delete('categoryType');
    $this->request->session()->delete('productType');
  }
?>
 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $this->request->webroot; ?>img/logo.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->request->session()->read('name'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="<?php echo ($action == 'dashboard')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'dashboard']); ?>">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
       
        <li class="<?php echo ($controller == 'users' && $action != 'dashboard' && $action != 'astrologers' && $action != 'softExitUsers'  )?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'index']); ?>">
        <i class="fa fa-user"></i> <span>Manage Users</span></a></li>

        <li class="<?php echo ($controller == 'users' && $action == 'softExitUsers' )?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'soft-exit-users']); ?>">
            <i class="fa fa-user"></i>
            <span>Manage Soft Exit Users</span>
          </a>
        </li>


        <li class="<?php echo ($controller == 'categories')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'categories', 'action' => 'index']); ?>">
        <i class="fa fa-bars"></i> <span>Manage Categories</span></a></li>
         <li class="<?php echo ($controller == 'products')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'products', 'action' => 'index']); ?>">
        <i class="fa fa-product-hunt"></i> <span>Manage Products</span></a></li>
        <a href="#" id="btn-1" data-toggle="collapse" data-target="#submenu1" aria-expanded="false"></a>


        <li class="<?php echo ($controller == 'orders' && ($action == 'index' || $action == 'view'))?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'Orders', 'action' => 'index']); ?>">
          <i class="fa fa-shopping-cart"></i> <span>Manage Orders</span></a>
        </li>

        <li class="<?php echo ($controller == 'orders' && ($action == 'astroclockOrderList' || $action == 'astroclockOrderDetail'))?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'Orders', 'action' => 'astroclock-order-list']); ?>">
          <i class="fa fa-apple"></i> <span>AstroClock Orders</span></a>
        </li>
        
        <li class="<?php echo ($controller == 'orders' && ($action == 'generateMiniReport'))?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'Orders', 'action' => 'generate-mini-report']); ?>">
          <i class="fa fa-envelope"></i> <span>Free Mini Report</span></a>
        </li>


         <li class="<?php echo ($controller == 'pages')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'pages', 'action' => 'index']); ?>">
        <i class="fa fa-file"></i> <span>Manage Pages</span></a></li>

        <li class="<?php echo ($controller == 'books')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'books', 'action' => 'index']); ?>">
        <i class="fa fa-book"></i> <span>Manage Books</span></a></li>

        <li class="<?php echo ($controller == 'events')?'active':''; ?>">

          <a href="<?php echo $this->Url->build(['controller' => 'events', 'action' => 'index']); ?>">
        <i class="fa fa-bookmark"></i> <span>Manage Events</span></a></li>

       
       <li class="<?php echo ($controller == 'testimonials')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'testimonials', 'action' => 'index']); ?>">
        <i class="fa fa-certificate"></i> <span>Manage Homepage Testimonial</span></a></li>

        <li class="<?php echo ($controller == 'emailtemplates')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'EmailTemplates', 'action' => 'index']); ?>">
        <i class="fa fa-code"></i> <span>Manage Email Templates</span></a></li>

        <li class="<?php echo ($controller == 'languages')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'languages', 'action' => 'index']); ?>">
        <i class="fa fa-language"></i> <span>Manage Languages</span></a></li>


        <li class="<?php echo ($controller == 'sunsignpredictions')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'index']); ?>">
        <i class="fa fa-sun-o"></i> <span>Manage Sunsign</span></a></li>

         <li class="<?php echo ($controller == 'currencies')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'currencies', 'action' => 'index']); ?>">
        <i class="fa fa-money"></i> <span>Manage Currencies</span></a></li>


        <li class="<?php echo ($controller == 'previewreports')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'PreviewReports', 'action' => 'index']); ?>">
        <i class="fa fa-plus"></i> <span>Sample Reports</span></a></li>

        <li class="<?php echo ($controller == 'menus')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'menus', 'action' => 'index']); ?>">
        <i class="fa fa-bars"></i> <span>Manage Footer Menu</span></a></li>

        <li class="<?php echo ($controller == 'settings')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'settings', 'action' => 'index']); ?>">
        <i class="fa fa-cog"></i> <span>Manage Home Page Banner</span></a></li>

        <!-- <li  class="<?php //echo ($controller == 'users' && $action == 'astrologers')?'active':''; ?>">
          <a href="<?php //echo $this->Url->build(['controller' => 'users', 'action' => 'astrologers']); ?>">
        <i class="fa fa-user-secret"></i> <span>Manage Astrologers</span></a></li> -->



       <li class="<?php echo ($controller == 'media')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'media', 'action' => 'index']); ?>">
        <i class="fa fa-file-video-o" aria-hidden="true"></i><span>Manage Media</span></a></li>

        <li class="<?php echo ($controller == 'miniblogs')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'mini-blogs', 'action' => 'index']); ?>">
        <i class="fa fa-wordpress" aria-hidden="true"></i><span>Manage Mini Blogs</span></a></li>

        <li class="<?php echo ($controller == 'usertestimonials')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'user-testimonials', 'action' => 'index']); ?>">
        <i class="fa fa-quote-left" aria-hidden="true"></i><span>Manage Product Testimonials</span></a></li>

         <li class="<?php echo ($controller == 'socialappkeys')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'SocialAppKeys', 'action' => 'index']); ?>">
        <i class="fa fa-key" aria-hidden="true"></i><span>Manage Social App Keys</span></a></li>
	
	 <li class="<?php echo ($controller == 'questions')?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'questions', 'action' => 'index']); ?>">
        <i class="fa fa-question-circle" aria-hidden="true"></i><span>Manage Quiz Questions</span></a></li>
	
        <li class="header" style="color:white"><h4>Support Tickets</h4></li>

        <li class="<?= ($controller == 'supporttickets' && ($action == 'openTickets' || ($action == 'view' && strtolower(($this->request->params['pass'][0]) == 'opened'))) )?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'SupportTickets', 'action' => 'open-tickets']); ?>">
            <i class="fa fa-ticket" aria-hidden="true"></i><span>Manage Open Tickets</span>
          </a>
        </li>

        <li class="<?php echo ($controller == 'supporttickets' && ($action == 'closedTickets' || ($action == 'view' && strtolower(($this->request->params['pass'][0]) == 'closed'))) )?'active':''; ?>">
          <a href="<?php echo $this->Url->build(['controller' => 'SupportTickets', 'action' => 'closed-tickets']); ?>">
        <i class="fa fa-close" aria-hidden="true"></i><span>Manage Closed Tickets</span></a></li>
        

<!--         <li class="<?php //echo ($controller == 'settings')?'active':''; ?>">
          <a href="<?php //echo $this->Url->build(['controller' => 'settings', 'action' => 'index']); ?>">
        <i class="fa fa-cog"></i> <span>Manage Settings</span></a></li> -->
        <!--
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
          </ul>
        </li>-->
      </ul>
    </section>
    <!-- /.sidebar -->

  </aside>
