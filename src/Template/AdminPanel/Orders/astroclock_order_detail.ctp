<?php use Cake\Routing\Router;?>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <?php
          $url = Router::url('/', true);
          if(!$orderDetail) {
            echo "No results found!";
          } else {
            $seo_url = $orderDetail['product']['seo_url'];
        ?>
          <h3 class="box-title">Order Detail</h3>
          <span class="add_btn">
            <h3 class="box-title">Order Number: <?php echo $orderDetail['id'];?></h3>
          </span>
      </div>
      <div class="box-body">
        <div class = "panel panel-primary">
          <div class = "panel-heading"><h3 class = "panel-title">Order Detail</h3></div>
          <div class = "panel-body">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <td>Order id</td>
                  <td><?php echo $orderDetail['payer_order_id'];?></td>
                </tr>
                <tr>
                  <td>Order Status</td>
                  <td><span id='current_status'><?php echo ucwords($orderDetail['state']['name']);?><span></td>
                </tr>
                <tr>
                  <td>Order Date</td>
                  <td><?php echo $this->Custom->newDateFormat($orderDetail['order_date']);?></td>
                </tr>
                <?php if( !empty($orderDetail['order_transaction']) ) { ?>
                  <tr>
                    <td>Payment Confirmation Date</td>
                    <td><?php echo $this->Custom->newDateFormat($orderDetail['order_transaction']['payment_date']);?></td>
                  </tr>
                <?php } ?>
                <tr>
                  <td>Product Name</td>
                  <td><?php echo ucwords($orderDetail['product']['name']);?></td>
                </tr>
                <tr>
                  <td>Product Type</td>
                  <td><?php echo ucwords($orderDetail['product_types']['name']);?></td>
                </tr>

                <tr>
                  <td>Delivery Option</td>
                  <td><?php echo ucwords($orderDetail['delivery_options']['name']);?></td>
                </tr>
                
                <?php /*
                <tr>
                  <td>Price</td>
                  <td><?php echo $orderDetail['currency']['symbol'].sprintf('%0.2f', $orderDetail['price'])?></td>
                </tr>
                */ ?>
                
                <tr>
                  <td>User Name</td>
                  <td>
                    <?= ucwords($orderDetail['birthdata']['first_name'].' '.$orderDetail['birthdata']['last_name']); ?>
                  </td>
                </tr>
                <?php if(!empty($orderDetail['birthdata'])) { ?>
                  <tr>
                    <td>Name On Report</td>
                    <td>
                      <?php
                        if( !empty($orderDetail['birthdata']['name_on_report']) ) {
                          echo $orderDetail['birthdata']['name_on_report'];
                        } else {
                          echo $this->Custom->getFullName( $orderDetail['birthdata']['first_name'], $orderDetail['birthdata']['last_name']);
                        }
                      ?>
                    </td>
                  </tr>
                <?php }?>

                <tr>
                  <td>Email ID</td>
                  <td><?php echo $orderDetail['email'];?></td>
                </tr>
                <tr>
                  <td>Language Code</td>
                  <td><?php echo $orderDetail['language']['name'];?></td>
                </tr>
                <?php if( file_exists(WWW_ROOT ."reports/var/spool/".$orderDetail['id'].".bundle.pdf") && $orderDetail['product']['category_id'] == 2): ?>
                  <tr>
                    <td>Download PDF</td>
                    <td>
                      <a href="<?= $url ; ?>reports/var/spool/<?= $orderDetail['id'].'.bundle.pdf'; ?>" target="_blank">
                        <?php echo $this->Html->image($url.'uploads/products/'.$orderDetail['product']['image'], ['title' => 'Product Image', 'alt' => 'Product Image', 'width' => '50px', 'height' => '50px']); ?>
                      </a>
                    </td>
                  </tr>
                <?php endif;?>
                <tr>
                  <td>Change Status</td>
                  <td>
                    <div class="row">
                      <div class="col-md-3">
                        <?php echo $this->Form->select('status', $statusOptions, ['class' => 'form-control col-md-3', 'style' => ['text-transform: capitalize'], 'default' => $orderDetail['state']['id'], 'id' => 'status'] ); ?>
                      </div>
                      <div class="col-md-3">
                        <?php echo $this->Form->button(__('Change'), ['class' => 'btn btn-primary btn-block btn-flat', 'onClick' => "changeOrderStatus('orders',".$orderDetail['id'].", document.getElementById('status').value)"]); ?>
                      </div>
                    </div>
                  </td>
                </tr>

                <?php
                  if(!empty($orderDetail['order_shipping'])) {
                    $country = $this->Custom->getCountryData($orderDetail['order_shipping']['country']);
                    $address2 = (empty($orderDetail['order_shipping']['address_2'])) ? '' : ucwords($orderDetail['order_shipping']['address_2']). ", <br>";
                    $state    = (empty($orderDetail['order_shipping']['state'])) ? '' : ucwords($orderDetail['order_shipping']['state'])."-" ;
                ?>
                    <tr>
                      <td>Shipping Address</td>
                      <td><?php echo ucwords($orderDetail['order_shipping']['address_1']).",<br>".$address2.ucwords($orderDetail['order_shipping']['city']).", ".$state.$orderDetail['order_shipping']['postal_code'].",<br>".$country['name'];?></td>
                    </tr>
                    <tr>
                      <td>Phone</td>
                      <td><?php echo ucwords($orderDetail['order_shipping']['phone']);?></td>
                    </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php
          if(strtolower($seo_url) == 'comprehensive-lovers-report') {
            $loversData = $this->Custom->getLoversData($orderDetail['id']);
            echo "<div class='row'>";
            $i = 0;
            foreach($loversData as $data) {
              $zoneData = $this->Custom->getTimezoneAndSummerTimezoneOnDashboard($data->zoneref);
              ++$i;
        ?>
              <div class="col-md-6">
                <div class = "panel panel-primary">
                  <div class = "panel-heading">
                    <h3 class = "panel-title">Person <?php echo $i;?></h3>
                  </div>
               
                  <div class = "panel-body">
                    <table class="table table-hover">
                      <tbody></tbody>
                      <tr>
                        <td>Name</td>
                        <td><?php echo $data->name_on_report;?></td>
                      </tr>
                      <tr>
                        <td>Gender</td>
                        <td><?php echo $data->gender;?></td>
                      </tr>
                      <tr>
                        <td>Date of Birth</td>
                        <td><?php echo date('d/m/Y', strtotime($data->day.'-'.$data->month.'-'.$data->year) );?></td>
                      </tr>
                      <tr>
                        <td>Birth Time</td>
                        <td><?php echo date('H:i a', strtotime($data->hour.':'.$data->minute) );?></td>
                      </tr>
                      <tr>
                        <td>Place</td>
                        <td><?php echo ucwords($data->place);?></td>
                      </tr>
                      <tr>
                        <td>Country</td>
                        <td><?php 
                             $country = $this->Custom->getCountryDataByAbbreviation($data->state);
                             echo $country['name'];
                        ?></td>
                      </tr>
                      <tr>
                        <td>Timezone</td>
                        <td><?php echo ucwords($zoneData['timezone']);?></td>
                      </tr>
                      <tr>
                        <td>Summer Time</td>
                        <td><?php echo ucwords($zoneData['summerreff']);?></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } } ?>
      </div>
    </div>
    <!-- /.box -->
  </div>
</div>