<style>
  .table td{vertical-align: middle !important;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage User Testimonials</h3>

        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'user-testimonials', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add New</a></span>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Name</th>
            <th>Product Name</th>
            <th>Profile</th>
            <th>Website</th>
            <th>Status</th>
            <th>Action</th>
          </tr>

          <?php 
          if($data) {
            $count = $this->Paginator->params()['perPage'];
            $page = $this->Paginator->params()['page'];
            $i =  (( $page - 1) * $count) + 1 ;
            foreach($data as $item) {
              $id = $item['id'];
              $class="btn btn-danger btn-xs";
              $stType="Inactive";
              $stVal=1;
              if($item['status']==1)
              {
                $class="btn btn-success btn-xs";
                $stType="Active";
                $stVal=2;
              }
          ?>    
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo stripslashes(($item['first_name'].' '.$item['last_name'])); ?></td>
              <td><?php echo stripslashes($item['Products']['name']); ?></td>
              <td><?php echo stripslashes($item['user_profile']); ?></td>
              <td><?php echo stripslashes($item['website']); ?></td>

              <td align="center">
                <span id="status_<?php echo $id; ?>">
                  <a href="javascript:changeStatus('user-testimonials', <?php echo $id; ?>,<?php echo $stVal; ?>);" class="<?php echo $class; ?>"><?php echo $stType; ?></a>
                </span>
              </td>

              <td align="center">
                <?= $this->Html->link('<i class="glyphicon glyphicon-edit"></i>', ['controller' => 'user-testimonials', 'action' => 'edit', $id], ['escape' => false, 'class' => 'btn btn-info btn-xs']); ?>
                <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller' => 'user-testimonials', 'action' => 'delete', $id], ['escape' => false, 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure to delete testimonial?')"]); ?>
                </a>
              </td>
            </tr>
          <?php 
              $i++;
            }
          }
          else {
            echo '<tr><td  colspan="5" align="center"><i>No results found!</i></td></tr>';
          }
          ?>
       
       
        </table>
      </div>

       <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
          <?php echo $this->Paginator->prev(' << '); ?>
          <?php echo $this->Paginator->numbers(); ?>
          <?php echo $this->Paginator->next(' >> '); ?>
        </ul>
      </div>


    </div>
    <!-- /.box -->
    </div>
   </div>