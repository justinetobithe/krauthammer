<div class="main-content">
     
  <div class="page-content">
		<div class="page-header position-relative">
			<h1>
				Contact-Forms
			</h1>
		</div><!--/.page-header-->

  <?php
    echo Session::flash('contact_form_delete','success','Contact form deleted.'); 
  ?>

		<div class="row-fluid">
			<div class="span12">
				<!--PAGE CONTENT BEGINS-->
        <div id="showMessage"></div>
        <div class="well well-small">
          <a href='<?php echo URL.'contact-forms/add'; ?>' class='btn btn-small btn-primary'><i class="icon icon-plus"></i> Add New</a>
        </div>
        <form class='form-inline' style="display: none;">
          <input type='hidden' id='action' value='list' />

          <div class="control-group">
            <div class="controls">
              <select style='width:134px;' id='applyAction'>
                <option value='bulk'>Bulk Actions</option>
                <option value='delete'>Delete</option>
              </select>&nbsp;&nbsp;
              <button class='btn btn-primary btn-mini' id='btnApply'>Apply</button>
            </div>
          </div>
        </form>
        
        <table id="tableContactForm" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="center" style="width:30px;">
                <label>
                  <input type="checkbox" class="ace" id='chkAll'/>
                  <span class="lbl"></span>
                </label>
              </th>
              <th><small>Title</small></th>
              <th><small>Shortcode</small></th>
              <th><small>Author</small></th>
              <th><small>Date</small></th>
              <th></th>
            </tr>

          </thead>

          <tbody id='tblContactForm'>
            
          </tbody>
        </table>
                
      </div>
    </div>
  </div>

</div>