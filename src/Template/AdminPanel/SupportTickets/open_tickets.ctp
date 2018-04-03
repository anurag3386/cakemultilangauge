<style>
  .table td{vertical-align: middle !important;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Open Tickets</h3>
      </div>
      <div class="box-body">
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th style="width: 5%">Ticket Id</th>
              <th style="width: 18%">Subject</th>
              <th style="width: 10%">User Name</th>
              <th style="width: 10%">Email</th>
              <th style="width: 7%">Handled By</th>
              <th style="width: 20%">Description</th>
              <th style="width: 5%">Status</th>
              <th style="width: 10%">Raised On</th>
              <th style="width: 10%">Last Commented On</th>
              <th style="width: 5%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(!$opened->isEmpty()) {
                $options  = ['1' => 'Nethues', '2' => 'Adrian', '3' => 'Other'];
                $count = $this->Paginator->params()['perPage'];
                $page  = $this->Paginator->params()['page'];
                $i =  (( $page - 1) * $count) + 1 ;
                foreach($opened as $odata) {
                  $id = $odata['id'];
              ?>
                  <tr>
                    <td><?= $id;?></td>
                    <td><?= $this->Html->link(ucfirst( $odata['subject']), ['controller' => 'support-tickets', 'action' => 'view', 'opened', $id], ['style'=>['color:black;']]);?></td>
                    <td><?= ucwords( $odata['Profiles']['first_name'].' '.$odata['Profiles']['last_name'] );?></td>
                    <td><?= $odata['Users']['username'];?></td>
                    <td>
                      <?php
                        if(!empty($odata['handled_by'])) {
                          echo $options[$odata['handled_by']];
                        } else {
                          echo 'N/A';
                        }
                      ?>
                    </td>
                    <td>
                      <?php /*if( strlen(strip_tags($odata['description'])) > 30) {
                        echo substr($odata['description'], strpos($odata['description'], ' ', 30)) ;
                      } else {
                        echo  $odata['description'];
                      }*/
                      echo $dd = (strlen(strip_tags($odata['description'])) > 100) ? substr(strip_tags($odata['description']),0,100).'...' : strip_tags($odata['description']);
                      ?>
                    </td>
                    <td><?= $approval_status[$odata['approved']];?> </td>
                    <td><?= date('d/m/Y h:i:s A', strtotime( $odata['created']) );?> </td>
                    <td>
                      <?php /*$lastCommentedOn = $this->Comment->getLastComment($odata['id']);
                        if($lastCommentedOn == false) {
                          echo date( 'd/m/Y h:i:s A', strtotime( $odata['created']) );
                        } else {
                          echo $lastCommentedOn;
                        } */
                        echo $this->Comment->getLastCommentedBy($odata['id']);
                      ?>
                    </td>
                    <td>
                      <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'support-tickets', 'action' => 'view/opened/'.$id])?>" title="View Ticket Detail"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'support-tickets', 'action' => 'delete/opened/'.$id])?>" title="Delete Ticket" onclick="return confirm('Are you sure to delete this support ticket?')">
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