<div class="box box-default">
  <div class="box-header with-border">
 <div class="row">
    <div class="col-md-8">
    <h3 class="box-title">Sunsign Predictions</h3>
    </div>
    <div class="col-md-4">
       <div class="col-md-6">
        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add Prediction</a>
         </span>

       </div>
       <div class="col-md-6">
         <span>
           <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'search-prediction'])?>" 
          class="btn btn-success btn-sm">Search Prediction</a>
         </span>
       </div>
       
    </div>
  </div>
 </div>
 
<?php 
    $signs = array ('aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces' );

 if(isset($sunsign) && !empty($sunsign))
 {
?>

 <div class="box-body">
    <div class="panel panel-default">
       <div class="panel-body">
         <?php 
           if($sunsign != null)
            {
              if ($sunsign['language'] == 'uk') 
              {
                $sunsign['language'] = 'en';
              }
              
              $sunsign['schedule_date'] = date('d/m/Y', strtotime($sunsign['schedule_date']) );

              switch ($sunsign['scope']) 
              {
                case 0: /* general */
                  echo "Scope is GENERAL";
                  echo "Scheduled date is " .  $sunsign['schedule_date'] ;
                  echo "Language is";
                  break;
                case 1: /* daily */
                  echo "<div class='col-md-4'><h4>Scope : <span class='text-primary'>DAILY</span></h4></div>";
                  echo "<div class='col-md-4'><h4>Scheduled date : <span class='text-primary'>" . $sunsign['schedule_date']."</span></h4></div>" ;
                  echo "<div class='col-md-4'><h4>Language : <span class='text-primary'>" . strtoupper($sunsign['language']) ."</span></h4></div>";
                  break;
                case 2: /* weekly */
                  echo "<div class='col-md-4'><h4>Scope : <span class='text-primary'>WEEKLY</span></h4></div>";
                  echo "<div class='col-md-4'><h4>Scheduled date : <span class='text-primary'>" . $sunsign['schedule_date']."</span></h4></div>" ;
                  echo "<div class='col-md-4'><h4>Language : <span class='text-primary'>" . strtoupper($sunsign['language'] ) ."</span></h4></div>";
                  break;
                case 3: /* monthly */
                  echo "<div class='col-md-4'><h4>Scope : <span class='text-primary'>MONTHLY</span></h4></div>";
                  echo "<div class='col-md-4'><h4>Scheduled date : <span class='text-primary'>" . $sunsign['schedule_date']."</span></h4></div>" ;
                  echo "<div class='col-md-4'><h4>Language : <span class='text-primary'>" .  strtoupper($sunsign['language']  ) ."</span></h4></div>";
                  break;
                case 4: /* yearly */
                  echo "<div class='col-md-4'><h4>Scope : <span class='text-primary'>YEARLY</span></h4></div>";
                   echo "<div class='col-md-4'><h4>Scheduled date : <span class='text-primary'>" . $sunsign['schedule_date']."</span></h4></div>" ;
                  echo "<div class='col-md-4'><h4>Language : <span class='text-primary'>" .  strtoupper($sunsign['language']  ) ."</span></h4></div>";
                  break;
                default:
                 /* error */
                 break;
              }
            }
          ?>
      </div>
    </div>

        <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Name</th>
            <th>Prediction</th>
            <!-- <th class="action">Action</th> -->
          </tr>

         <?php 
          if(count($sunsign['content'])>=12) 
          {
            $j = 1;
             for($i=0;$i<count($sunsign['content']);$i++)
              {
          ?>    
            <tr>
              <td><?php echo $j; ?></td>
              <td><?php echo ucwords($signs [$sunsign['content'][$i]['sign'] - 1]) ?></td>
              <td><?php echo $sunsign['content'][$i]['content']; ?></td>
              <!--td align="center">
                <a class="btn btn-info btn-xs" href="<?php //echo $this->Url->build(['controller' => 'testimonials', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
                </a>
              </td-->
            </tr>
          <?php 
              $j++;
            }
          }
          else {
            echo '<tr><td  colspan="5" align="center"><i>No results found!</i></td></tr>';
          }
        
          ?>
       
       
        </table>
<div class="row">
      <div class="col-md-6">
       <h4 class="text-center"> <?php echo $this->Html->link('Back', ['controller' => 'SunSignPredictions', 'action'=>'index'], ['class' => 'btn btn-primary'])?></h4>
       </div>
<div class="col-md-6">
          <h4 class="text-center"><?php echo $this->Html->link('Upload More Files', ['controller' => 'SunSignPredictions', 'action'=>'index'], ['class' => 'btn btn-primary'])?></h4>
</div>
</div>
      </div>
      <?php 
}
else
{
      ?>

  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true, 'enctype' => 'multipart/form-data']) ?>
  <!-- /.box-header -->
  <div class="box-body">
       <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Upload File</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                   <div class="col-md-12">
                    <div class="form-group">
                     <p>This form currently supports the upload of day, week, month and year content. Browse to the content file and click the upload button.
                     </p>
                      <label>Prediction File *</label>
                      <?php echo $this->Form->file('prediction-file', ['class' => 'form-control validate[required]']) ?>
                    </div>    
                    <div class="form-group">
                    <p> Files must be in Plain Text format with filename  
                      <ul>
                          <li> 011017_uk.txt - Daily</li>
                          <li> week_051217_uk.txt - Weekly</li>
                          <li> month10_2017_uk.txt - Monthly</li>
                          <li> year2017_uk.txt - Yearly</li>
                      </ul>
                    </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>

    <div class="box-tools pull-right">
       <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div>
  </div>
  <?php echo $this->Form->end() ?>
  <!-- /.box-body -->
<?php
}
?>
 
</div>
<!-- /.box