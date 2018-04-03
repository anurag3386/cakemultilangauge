<!--SELECT2 EXAMPLE -->
<?php use Cake\Routing\Router; ?>
<?php $url = Router::url('/', true); ?>
<?php //pr($this->request->params['prefix']); die; ?>
<div class="box box-default">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-8">
        <h3 class="box-title">Order List</h3>
      </div>
    </div>
  </div>
  <?= $this->Flash->render() ?>
  <?php echo $this->Form->create($form,['url' => ['controller' =>'Orders', 'action' => 'astroclock-order-list'], 'id' => 'form_id', 'type' => 'get']) ?>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-2">
          <div class="form-group">
            <label>Search </label>
            <?php echo $this->Form->text('search_txt', ['class' => 'form-control', 'placeholder' => 'Search By Email or Name or Order Id']); ?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Order Status </label>
            <?php echo $this->Form->select('status', $statusOptions, ['class' => 'form-control', 'empty' => 'All', 'style' => ['text-transform: capitalize']] ); ?>
          </div>
        </div>
        <!-- /.col -->

        <div class="col-md-2">
          <div class="form-group">
            <label>Monthly Search </label>
            <?php echo $this->Form->text('month', ['class' => 'form-control', 'placeholder' => 'Select Month', 'id' => 'monthly-datepicker', 'autocomplete' => false]); ?>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label>Start Date</label>
            <?php echo $this->Form->input('start-date', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __('Start Date'), 'readonly' => true]); ?>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label>End Date</label>
            <?php echo $this->Form->input('end-date', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __('End Date'), 'readonly' => true]); ?>
          </div>
        </div>
        
        <div class="col-md-2">
          <div class="form-group"><br />
            <div class="box-tools ">
              <div class="col-md-8">
                <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
              </div>
              <div class="col-md-4">
                <?php echo $this->Html->link(__('Reset'), ['controller' => 'Orders', 'action' => 'astroclock-order-list'], ['class' => 'btn btn-danger btn-flat'] ); ?>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
      <!-- end-->
    </div>
  <?php echo $this->Form->end() ?>


  <!-- /.box-body -->
  <table class="table table-bordered">
    <tr>
      <th class="sno" width="5%">Order Id</th>
      <th width="10%">User Name</th>
      <th width="15%">Name On Report</th>
      <th width="8%">Order Date</th>
      <!-- <th width="7%">Price</th> -->
      <th width="20%">Product Name</th>
      <th width="10%">Email Id</th>
      <th width="15%">Product Type</th>
      <th width="7%">Order Status</th>
      <th width="1%">Action</th>
    </tr>
    <?php
      if(!$orders->isEmpty()) {
        foreach($orders as $order) {
          $price = $order['currency']['symbol']." ".sprintf('%0.2f', $order['price']);
          $orderId = $order['payer_order_id'];
          $orderNo = $order['id'];
          $fullName = ucwords($order['birthdata']['first_name'].' '.$order['birthdata']['last_name']);
          
          $orderDate = $this->Custom->newDateFormat($order['order_date']);
          $productName = $order['product']['name'];
          ?>
          <tr>
            <td class="col-md-2"><?php echo $orderId; ?>
              <?php
                $file = '';
                if($order['product_id'] == 80){
                  $file = $orderNo.'.yr.mini.app.pdf';
                } else {
                  $file = $orderNo.'.bundle.pdf';
                }
              ?>
              <?php if(file_exists(WWW_ROOT ."reports/var/spool/".$file)) { ?>
                <br><a href="<?= $url ; ?>reports/var/spool/<?= $file; ?>" target="_blank"> Download PDF </a>
              <?php } ?>
            </td>
            <td><?= ucwords( $fullName ) ;?></td>
            <td>
              <?php
                if( !empty($order['birthdata']['name_on_report']) ) {
                  echo ucwords($order['birthdata']['name_on_report'] );
                } else {
                  echo $this->Custom->getFullName( $order['birthdata']['first_name'], $order['birthdata']['last_name']);
                }
              ?>
            </td>
            <td><?php echo $orderDate; ?></td>
            <td><?php echo ucwords($productName); ?></td>  
            <td class="col-md-2"><?php echo $order['email']?></td>
            <td><?php echo $order['product_types']['name']?></td>
            <td class="col-md-3">
              <?php echo ucwords($order['state']['name']); ?>
            </td>
            <td>
              <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'Orders', 'action' => 'astroclock-order-detail', strtolower($orderId) ])?>">
                <i class="glyphicon glyphicon-edit"></i>
              </a>
              <?php //echo $this->Html->link('Here', ['controller' => 'orders', 'action' => 'generate-mini-report']); ?>
              <?php /*<a href="<?php echo Router::url('/orders/abcdabcd/').$orderNo; ?>">Here</a> */ ?>
            </td>
          </tr>
    <?php
      }
    } else {
      echo '<tr><td  colspan="5" align="center">No results found!</td></tr>';
    }
    ?>
  </table>
  <div class="box-footer clearfix">
    <ul class="pagination pagination-sm no-margin pull-right">
      <?php //echo $this->Paginator->options(array('url' => $this->passedArgs)); ?>
      <?php $this->Paginator->options = array( 'url' => $this->passedArgs ); ?>
      <?php echo $this->Paginator->prev(' << '); ?>
      <?php echo $this->Paginator->numbers(); ?>
      <?php echo $this->Paginator->next(' >> '); ?>
    </ul>
  </div>
</div>
<!-- /.box -->

<script type="text/javascript">
  $(function() {
    $('#start-date').datepicker({
      endDate: '+0',
      autoclose: true, // Close datepicker automatically after date selection
      startView:2, // Show year, then month and in the last day
      forceParse: false,
      format: 'dd/mm/yyyy'
    });

    $('#end-date').datepicker({
      autoclose: true, // Close datepicker automatically after date selection
      endDate: '+0',
      startView:2, // Show year, then month and in the last day
      forceParse: false,
      format: 'dd/mm/yyyy'
    });
  });
</script>