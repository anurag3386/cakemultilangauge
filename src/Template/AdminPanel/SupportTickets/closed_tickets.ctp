<style>
  .table td{vertical-align: middle !important;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Closed Tickets</h3>
      </div>
      <div class="box-body">
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>Ticket Id</th>
              <th>Subject</th>
              <th>User Name</th>
              <th>Email</th>
              <th>Opened On</th>
              <th>Closed On</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(!$closed->isEmpty()) {
              $count = $this->Paginator->params()['perPage'];
              $page  = $this->Paginator->params()['page'];
              $i =  (( $page - 1) * $count) + 1 ;
              foreach($closed as $cdata) {
                    $id = $cdata['id'];
            ?>
                <tr>
                  <td><?= $id;?></td>
                  <td><?= ucfirst( $cdata['subject'] );?></td>
                  <td><?= ucwords( $cdata['Profiles']['first_name'].' '.$cdata['Profiles']['last_name'] );?></td>
                  <td><?= $cdata['Users']['username'];?></td>
                  <td><?= date('d/m/Y H:i:s', strtotime( $cdata['created']) );?> </td>
                  <td><?= date('d/m/Y H:i:s', strtotime( $cdata['modified']) );?> </td>
                
                  <td> 
                    <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'support-tickets', 'action' => 'view/closed/'.$id])?>" title="View Ticket Detail"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'support-tickets', 'action' => 'delete/closed/'.$id])?>" title="Delete Ticket" onclick="return confirm('Are you sure to delete this support ticket?')">
                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                  </a>
                  </td>
                </tr>
                <?php
              }
            } else {
              echo "<tr><td colspan = '6' class='text-center'><strong>No data found</strong></td></tr>";  
            } ?>
          </tbody>
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