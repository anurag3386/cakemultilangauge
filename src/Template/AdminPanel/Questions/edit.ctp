<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Question</h3>
	</div>
	<?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true]) ?>
	<?php echo $this->Form->hidden('old_sun_sign_code', ['value' => $data['sun_sign_code']]); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">English</a></li>
						<li><a href="#tab_2" data-toggle="tab">Dansk</a></li>
						 <li><a href="#tab_3" data-toggle="tab">Details</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Sun Sign *</label>
										<?php echo $this->Form->select('sun_sign_code', $sunSignOptions, ['class' => 'form-control validate[required]', 'empty' => 'Select Sunsign', 'value' => $data['sun_sign_code']] ); ?>
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Question *</label>
											<?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['title']]) ?>
									</div>
								</div>
							</div>
							<?php for($i=0;$i < 3; $i++){ ?>
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label>Answer <?php echo ($i+1); ?>*</label>
											<?php echo $this->Form->text('answers.'.$i.'.title', ['class' => 'form-control validate[required]', 'maxlength' => 255,'value'=>@$data['answers'][$i]['title']]) ?>
											<?php echo $this->Form->hidden('answers.'.$i.'.id', ['value'=>@$data['answers'][$i]['id']]) ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Answer <?php echo ($i+1); ?> belong to sun sign</label>
											<?php echo $this->Form->select('answers.'.$i.'.sun_sign_code', $sunSignOptions, ['class' => 'form-control validate[required]', 'empty' => 'Select Answer','value'=>@$data['answers'][$i]['sun_sign_code']] ); ?>
										</div>
									</div>	
								</div>
							<?php } ?>
						</div>
						<div class="tab-pane" id="tab_2">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Question *</label>
										<?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['_translations']['da']['title'] ]) ?>
									</div>
								</div>
							</div>
							<?php for($i=0;$i < 3; $i++){ ?>
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label>Answer <?php echo ($i+1); ?>*</label>
											<?php echo $this->Form->text('answers.'.$i.'._translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255,'value'=>@$data['answers'][$i]['_translations']['da']['title']]) ?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="tab-pane" id="tab_3">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Status</label>
										 <?php 
											$options = ['1' => 'Active', '2' => 'Inactive'];
											echo $this->Form->select('status', $options, ['class' => 'form-control', 'value' => $data['status']])
										  ?>
									</div>
								</div>
							</div>
						 </div>
					</div>
				</div>
			</div>
		</div>
	    <div class="box-tools pull-right">
			<?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
	    </div>
	</div>
	<?php echo $this->Form->end() ?>
</div>

