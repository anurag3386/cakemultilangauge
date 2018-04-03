<style>
  .table td{vertical-align: middle !important;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Events</h3>

        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'events', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add New</a></span>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Title</th>
            <th>Date</th>
            <th>Time</th>
            <th>Place</th>
            <th class="order">Order</th>
            <th class="status">Status</th>
            <th class="action">Action</th>
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
              <td><?php echo $item['title']; ?></td>
              <td><?php echo $item['date']; ?></td>
              <td><?php echo $item['time']; ?></td>
              <td><?php echo $item['place']; ?></td>
              <td align="center"><?php echo $item['sort_order']; ?></td>
              <td align="center">
                <span id="status_<?php echo $id; ?>">
                  <a href="javascript:changeStatus('events', <?php echo $id; ?>,<?php echo $stVal; ?>);" class="<?php echo $class; ?>"><?php echo $stType; ?></a>
                </span>
              </td>
              <td align="center">
                <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'events', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
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