<?php use Cake\Routing\Router; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="row">
          <div class="col-md-8">
            <h3 class="box-title">Manage Users</h3>
          </div>  
          <div class="col-md-3">  
            <?php echo $this->Form->create($form, ['url' => ['controller' => 'Users', 'action' => 'index'], 'type' => 'get']) ?>
            <div class="input-group input-group-sm">
              <?php echo $this->Form->text('search_txt', ['class' => 'form-control', 'placeholder' => 'Search Users']); ?>
                    <span class="input-group-btn">
                       <?php echo $this->Form->button(__('Go!'), ['class' => 'btn btn-info btn-flat']); ?>
                        <?php 
                             echo $this->Html->link(__('Clear'), ['controller' => 'Users', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat'] ); 
                        ?>
                    </span>
             <?php echo $this->Form->end() ?>
              </div>
          </div>
          <div class="col-md-1">
            <a style="margin-left:-12px;" href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'add'])?>" 
            class="btn btn-success btn-sm">Add New</a>
          </div>
          <!-- <span class="add_btn">
            <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'add'])?>" 
            class="btn btn-success btn-sm">Add New</a></span>
            <h5>Total registered Users: <?php echo count($data); ?></h5>   -->     
         </div>   
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Member Name</th>
            <th>User Name</th>            
            <th>Contact</th>
            <th>Details</th>
            <th>Birth Details</th>
            <th>Language</th>
            <th>Reg. Date</th>
            <th class="status">Status</th>
            <!-- <th class="action">Action</th> -->
            <th style="width: 90px;">Action</th>
          </tr>

          <?php 
          $search_txt = '';
          if ( !empty($this->request->data) ) {
            $search_txt = $this->request->data['search_txt'];
          }
          
           if(!$data->isEmpty()) {
            $count = $this->Paginator->params()['perPage'];
            $page = $this->Paginator->params()['page'];
            $i =  (( $page - 1) * $count) + 1 ;
            foreach($data as $item) {
              //echo $item['BirthDetails']['date']; die;
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

              
             $member_name = $item['Profiles']['first_name'].' '.$item['Profiles']['last_name'];
             //$member_name = $item['profile']['first_name'].' '.$item['profile']['last_name'];
             $username = $item['username'];
             $phone = $item['Profiles']['phone'];

             if ( !empty($this->request->data) ) {
              $member_name = 
                preg_replace("/\b([a-z]*${search_txt}[a-z]*)\b/i","<span class='highlight'>$1</span>",$member_name);
              $username = preg_replace("/\b([a-z]*${search_txt}[a-z]*)\b/i","<span class='highlight'>$1</span>",$username);
              $phone = preg_replace("/\b([a-z]*${search_txt}[a-z]*)\b/i","<span class='highlight'>$1</span>",$phone);


              //$username = preg_replace("/\b([a-z]*${search_txt}[a-z]*)\b/i","<span class='highlight'>$1</span>",$username);
             }

          ?>    
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $member_name; ?></td>
              <td><?php echo $username; ?></td>
              <td><?php echo $phone; ?></td>
              <td>
                <?php echo $item['Profiles']['gender'].', '.$this->Custom->getUserAge($item['BirthDetails']['date']); ?>
              </td>
              <td>
                <?php

                 if( isset($item['BirthDetails']['date']) && !empty($item['BirthDetails']['date']) )
                 {
                  //$defaultDob = $this->Custom->newDateTimeFormat($item['BirthDetails']['date'], $item['BirthDetails']['time']);
                  $defaultDob = $this->Custom->newDateTimeFormat($item['BirthDetails']['date'], $item['BirthDetails']['time']);
                 }
                 else
                 {
                  $defaultDob = "";
                 }

                 echo $defaultDob;
                 
                 ?>
              </td>
              <td>
                <?php //echo $this->Custom->getLanguageName($item['profile']['language_id']); ?>
                <?php echo $item['Languages']['name']; ?>
              </td>
              <td><?php echo $this->Custom->newDateFormat($item['created']); ?></td>
              <td align="center">
                <span id="status_<?php echo $id; ?>">
                  <a href="javascript:changeStatus('users', <?php echo $id; ?>,<?php echo $stVal; ?>);" class="<?php echo $class; ?>"><?php echo $stType; ?></a>
                </span>
              </td>
              <td align="center">

                <?php /* ?>
                <a class="btn btn-info btn-xs" onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=500, height=500, toolbar=1, resizable=0'); return false;" href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'user-login-by-admin', $id]); ?>">
                  <i class="glyphicon glyphicon-eye-open"></i>
                </a>
                <?php */ ?>
                
                <a class="btn btn-info btn-xs" target="_blank" href="<?= Router::url ('/', true).'users/login/'.base64_encode($item['id']); ?>">
                  <!-- <i class="glyphicon glyphicon-eye-open"></i> -->
                  <i class="glyphicon glyphicon-log-in"></i>
                </a>
                <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
                </a>
              </td>
            </tr>
          <?php 
              $i++;
            }
          }
          else {
            echo '<tr><td  colspan="5" align="center">No results found!</td></tr>';
          }
          ?>
       
       
        </table>
      </div>

       <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
          <?php // echo $this->Paginator->options(array('url' => $this->passedArgs)); ?>

          <?php $this->Paginator->options = array('url' => $this->passedArgs);?>
          <?php echo $this->Paginator->prev(' << '); ?>
          <?php echo $this->Paginator->numbers(); ?>
          <?php echo $this->Paginator->next(' >> '); ?>
        </ul>
      </div>


    </div>
    <!-- /.box -->
    </div>

   </div>
