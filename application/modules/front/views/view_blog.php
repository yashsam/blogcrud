<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
<!-- Main content -->
  <div class="col-md-12 form f-label">
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">      
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
  <?php } ?>
    <!-- Profile Image -->
    <div class="box box-success pad-profile">
     	<div class="box-header with-border">
        <h3 class="box-title">View <small></small></h3>
		<br />
		<a href="javascript:;" onclick="history.go(-1)" >Back</a>
      </div>
      <form method="post" enctype="multipart/form-data" action="<?php echo base_url().'user/add_edit' ?>" class="form-label-left">
        <div class="box-body box-profile">
         
          <div class="col-md-8">
            <h3><?php echo $user_data->title ?></h3>
            

					<div class="form-group has-feedback">
		              <?php echo $user_data->description; ?>
		            </div>

					

					

					
              <br>
            
           
          </div>
         <!-- /.box-body -->
        </div>
      </form>                     
      <!-- /.box -->
    </div>
    <!-- /.content -->
  </div>
</div>
<!-- /.content-wrapper -->