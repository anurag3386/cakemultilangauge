<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Astrologers</h3>

        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'add_astrologer'])?>" 
          class="btn btn-success btn-sm">Add New</a></span>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Astrologer</th>
            <th>User Name</th>
            <th>Phone</th>
            <th>Language</th>
            <th>Reg. Date</th>
            <th class="status">Status</th>
            <th class="action">Action</th>
          </tr>

          <?php 
          if($data) {
            $i = 1;
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
              <td><?php echo $item['astrologer']['first_name'].' '.$item['astrologer']['last_name']; ?></td>
              <td><?php echo $item['username']; ?></td>
              <td><?php echo $item['astrologer']['phone']; ?></td>
              <td><?php echo $this->Custom->getLanguages($item['astrologer']['languages']); ?></td>
              <td><?php echo $this->Custom->dateFormat( $item['created'] ); ?></td>
              <td align="center">
                <span id="status_<?php echo $id; ?>">
                  <a href="javascript:changeAstrologerStatus('users', <?php echo $id; ?>,<?php echo $stVal; ?>);" class="<?php echo $class; ?>"><?php echo $stType; ?></a>
                </span>
              </td>
              <td align="center">
                <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'edit_astrologer/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
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