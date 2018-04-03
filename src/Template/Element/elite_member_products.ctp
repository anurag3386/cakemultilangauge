<div class="common-content-right floatR">
	<div class="sml-common-box box-shadow margin0">
		<h2><?= __('Quick Dashboard');?></h2>
		<div class="content">
			<ul>
				<li>
					<?= $this->Html->link (__('View Orders'), array ('controller' => 'elite-users', 'action' => 'order-list')); ?>
					<!-- <a href="/elite-customer/view-orders.php">View Orders</a> -->
				</li>
				<li><?= __('Total new order(s)');?> : <?= $newOrders; ?></li>
				<li><?= __('Total in process order(s)');?> : <?= $inProcessOrders; ?></li>
				<?php /* ?><li><?= __('Total ready order(s)');?> : <?= $readyOrders; ?></li><?php */ ?>
				<li><?= __('Total closed order(s)');?> : <?= $closedOrders; ?></li>
				<li><?= __('Total order(s)');?> : <?= $totalOrders; ?></li>
			</ul>
		</div>
	</div>
	<div class="clear"></div><br>
	<div class="sml-common-box box-shadow margin0">					
		<h2><?= __('Quick Dashboard');?></h2>
		<div class="content">
			<ul>
				<li>
					<?= $this->Html->link (__('About Us'), ['controller' => 'Pages', 'action' => 'menu-pages', 'about-astrowow']); ?>
				</li>
				<!-- <a href="http://astrowow.com/elite-customer/my-dashboard.php"></a> -->
				
				<li>
					<?= $this->Html->link (__('My Account'), ['controller' => 'users', 'action' => 'edit-profile', md5('astrowow.com')]); ?>
					<!-- <a href="http://astrowow.com/elite-customer/my-dashboard.php"></a>
					<a href="http://astrowow.com/elite-customer/my-account.php">My Account</a> -->
				</li>
				<li>
					<?= $this->Html->link (__('Customize Reports'), array ('controller' => 'elite-users', 'action' => 'customize-reports')); ?>
					<!-- <a href="http://astrowow.com/elite-customer/customize-reports.php">Customize Reports</a> -->
				</li>
			</ul>
		</div>		
	</div>
</div>