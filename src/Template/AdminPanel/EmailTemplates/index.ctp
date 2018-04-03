<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Email Templates</h3>

        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'email-templates', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add New</a></span>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Title</th>
            <th>Template Short Code</th>
            <th class="status">Preview</th>
            <th class="action">Action</th>
          </tr>

          <?php 
          if($data) {
            $count = $this->Paginator->params()['perPage'];
            $page = $this->Paginator->params()['page'];
            $i =  (( $page - 1) * $count) + 1 ;
            foreach($data as $item) {
              $id = $item['id'];
            
          ?>    
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $item['name']; ?></td>
              <td><?php echo $item['short_code']; ?></td>
              <td align="center">
                  <a href="javascript:previewTemplate(<?php echo $id; ?>);" class="btn btn-primary btn-xs">Preview</a>
                </span>
              </td>
              <td align="center">
                <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'email-templates', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
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