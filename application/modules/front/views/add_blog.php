<form role="form bor-rad" enctype="multipart/form-data" action="<?php echo base_url().'blog/add_edit'?>" method="post">
  <div class="box-body">
    <div class="row">
          
						
					
						<div class="col-md-6">
						  <div class="form-group">
						    <label for="">Title</label>
						    <input type="text" name="title" value="<?php echo isset($userData->title)?$userData->title:'';?>" class="form-control" placeholder="title">
						  </div>
						</div>
					
						<div class="col-md-6">
						  <div class="form-group">
						    <label for="">Description</label>
						    <textArea  name="description" value="<?php echo isset($userData->description)?$userData->description:'';?>" class="form-control" placeholder="Description"></textArea>
						  </div>
						</div>
					
          
        <div class="col-md-6">
				          <div class="form-group">
				            <label for="status"> Status</label>
				            <select name="status" id="" class="form-control">
		        			<option value="1" <?php echo (isset($userData->status) && $userData->status == 1 )?'selected':''; ?> >Active</option>
		        			
		        			<option value="0" <?php echo (isset($userData->status) && $userData->status == 0 )?'selected':''; ?> >Deleted</option>
		        			
				            </select>
				          </div>
				        </div>
          
                       
        </div>
        <?php if(!empty($userData->id)){?>
        <input type="hidden"  name="id" value="<?php echo isset($userData->id)?$userData->id:'';?>">
        
        <div class="box-footer sub-btn-wdt">
          <button type="submit" name="edit" value="edit" class="btn btn-success wdt-bg">Update</button>
        </div>
              <!-- /.box-body -->
        <?php }else{?>
        <div class="box-footer sub-btn-wdt">
          <button type="submit" name="submit" value="add" class="btn btn-success wdt-bg">Add</button>
        </div>
        <?php }?>
      </form>