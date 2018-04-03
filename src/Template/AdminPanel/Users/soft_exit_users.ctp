<?php use Cake\Routing\Router; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="row">
          <div class="col-md-8">
            <h3 class="box-title">Manage Soft Exit Users</h3>
            <h5>Total Soft Exit Users: <?= $count; ?></h5>
          </div>
          <div class="col-md-3">
            <?php echo $this->Form->create($entity, ['url' => ['controller' => 'Users', 'action' => 'soft-exit-users'], 'type' => 'get']) ?>
              <div class="input-group input-group-sm">
                <?php echo $this->Form->text('search_txt', ['class' => 'form-control', 'placeholder' => 'Search Users by User Name or Email']); ?>
                <span class="input-group-btn">
                  <?php echo $this->Form->button(__('Go!'), ['class' => 'btn btn-info btn-flat']); ?>
                  <?php echo $this->Html->link(__('Clear'), ['controller' => 'Users', 'action' => 'soft-exit-users'], ['class' => 'btn btn-danger btn-flat']); ?>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>   
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Name of User</th>
            <th>Email Address</th>            
            <th>IP</th>
            <th>Details</th>
            <th>Birth Details</th>
            <th>Language</th>
            <th>Reg. Date</th>
          </tr>
          <?php $search_txt = '';
            if ( !empty($this->request->data) ) {
              $search_txt = $this->request->data['search_txt'];
                $search_txt;
            }
            if(!$output->isEmpty()) {
              //$i = 1;
              $count = $this->Paginator->params()['perPage'];
              $page = $this->Paginator->params()['page'];
              $i =  (( $page - 1) * $count) + 1 ;
              foreach($output as $item) {
                $id = $item['Users']['id'];
                $class="btn btn-danger btn-xs";
                $stType="Inactive";
                $stVal=1;
                if($item['Users']['status']==1) {
                  $class="btn btn-success btn-xs";
                  $stType="Active";
                  $stVal=2;
                }
                $member_name = $item['Profiles']['first_name'];
                if(!empty($item['Profiles']['last_name'])) {
                  $member_name = $member_name.' '.$item['Profiles']['last_name'];
                }
                $phone = $item['Profiles']['phone'];
          ?>    
                <tr>
                  <td><?= $i; ?></td>
                  <td><?= $member_name; ?></td>
                  <td><?= $item['Users']['username']; ?></td>
                  <td><?= $item['ip']; ?></td>
                  <td>
                    <?php echo $item['Profiles']['gender'].', '.$this->Custom->getUserAge($item['BirthDetails']['date']); ?>
                  </td>
                  <td>
                    <?php
                      if( isset($item['BirthDetails']['date']) && !empty($item['BirthDetails']['date']) ) {
                        $defaultDob = $this->Custom->newDateTimeFormat($item['BirthDetails']['date'], $item['BirthDetails']['time']);
                      } else {
                        $defaultDob = "";
                      }
                      echo $defaultDob;
                    ?>
                  </td>
                  <td>
                    <?= $item['Languages']['name']; ?>
                  </td>
                  <td><?= $this->Custom->newDateFormat($item['Users']['created']); ?></td>
                </tr>
              <?php $i++;
              }
            } else {
              echo '<tr><td  colspan="5" align="center">No results found!</td></tr>';
            }
          ?>
        </table>
      </div>
      <div class="box-footer clearfix">
        <?php if($this->Paginator->params()['pageCount'] > 1) { ?>
          <ul class="pagination pagination-sm no-margin pull-right">
            <?php // echo $this->Paginator->options(array('url' => $this->passedArgs)); ?>
            <?php $this->Paginator->options = array('url' => $this->passedArgs);?>
            <?php echo $this->Paginator->prev(' << '); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->next(' >> '); ?>
          </ul>
        <?php } ?>
      </div>
    </div>
    <!-- /.box -->
  </div>
</div>