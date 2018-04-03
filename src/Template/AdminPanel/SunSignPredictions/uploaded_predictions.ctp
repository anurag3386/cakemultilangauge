<style>
  .table td{vertical-align: middle !important;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Testimonials</h3>

        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'testimonials', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add New</a></span>
      </div>
      <div class="box-body">
<?php 
      if($sunsign != null)
            {
              if ($sunsign->language == 'uk') 
              {
                $sunsign->language = 'en';
              }
              echo "<ul>";
              switch ($sunsign->scope) {
                case 0: /* general */
                 echo "<li>Scope is GENERAL</li>";
                  echo "<li>Scheduled date is " . $sunsign->schedule_date . "</li>";
                  echo "<li>Language is</li>";
                  break;
                case 1: /* daily */
                 echo "<li>Scope is DAILY</li>";
                  echo "<li>Scheduled date is " . $sunsign->schedule_date . "</li>";
                  echo "<li>Language is " . $sunsign->language . "</li>";
                  break;
                case 2: /* weekly */
                 echo "<li>Scope is WEEKLY</li>";
                  echo "<li>Scheduled date is " . $sunsign->schedule_date . "</li>";
                  echo "<li>Language is " . $sunsign->language . "</li>";
                  break;
                case 3: /* monthly */
                 echo "<li>Scope is MONTHLY</li>";
                  echo "<li>Scheduled date is " . $sunsign->schedule_date . "</li>";
                  echo "<li>Language is " . $sunsign->language . "</li>";
                  break;
                case 4: /* yearly */
                 echo "<li>Scope is YEARLY</li>";
                  echo "<li>Scheduled date is " . $sunsign->schedule_date . "</li>";
                  echo "<li>Language is " . $sunsign->language . "</li>";
                  break;
                default:
                 /* error */
                 break;
              }
              echo "</ul>";

              /*
               * give the option re-upload if required
               */
              /* todo */

              echo "<hr />";

              if(count($sunsign->content)>=12)
              {
                
                for($i=0;$i<count($sunsign->content);$i++)
                {
                  
                  
                  echo "<dl>";
                  echo "<dt><strong>" . $signs [$sunsign->content[$i]['sign'] - 1] . "</strong></dt>";
                  echo "<dd>" . $sunsign->content[$i]['content'] . "</dd>";
                  echo "</dl>";
                }
              }

            //$sunsign->getDaily(3,10,2012,0);

            }
            else
            {
              echo "";
            }

            ?>
    <!--     <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Name</th>
            <th>Profile</th>
            <th class="order">Order</th>
            <th class="status">Status</th>
            <th class="action">Action</th>
          </tr>

          < ?php 
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
              <td>< ?php echo $i; ?></td>
              <td>< ?php echo $item['name']; ?></td>
              <td>< ?php echo $item['profile']; ?></td>
              <td align="center">< ?php echo $item['sort_order']; ?></td>
              <td align="center">
                <span id="status_< ?php echo $id; ?>">
                  <a href="javascript:changeStatus('testimonials', < ?php echo $id; ?>,< ?php echo $stVal; ?>);" class="< ?php echo $class; ?>">< ?php echo $stType; ?></a>
                </span>
              </td>
              <td align="center">
                <a class="btn btn-info btn-xs" href="< ?php echo $this->Url->build(['controller' => 'testimonials', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
                </a>
              </td>
            </tr>
          < ?php 
              $i++;
            }
          }
          else {
            echo '<tr><td  colspan="5" align="center"><i>No results found!</i></td></tr>';
          }
          ?>
       
       
        </table> -->
      </div>

       <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
         <!--  < ?php echo $this->Paginator->prev(' << '); ?>
          < ?php echo $this->Paginator->numbers(); ?>
          < ?php echo $this->Paginator->next(' >> '); ?> -->
        </ul>
      </div>


    </div>
    <!-- /.box -->
    </div>
   </div>