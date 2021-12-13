<div id="category_listing_page" class="tab-pane">
	<div id="alert_listing_page"></div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Category Page Settings</h4>
		</div>
		<div class="widget-main">
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="order">Display Order:</label>
					<div class="controls">
						<select id="order">
							<option value="FEATURED_LISTING_TOP">Featured Listing (Top)</option>
							<option value="FEATURED_LISTING_BOTTOM">Featured Listing (Bottom)</option>
							<option value="PRODUCT_TITLE_A_Z">Product Title (A to Z)</option>
							<option value="PRODUCT_TITLE_Z_A">Product Title (Z to A)</option>
						</select>           
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="view">Display View:</label>
					<div class="controls">
						<select id="view">
							<option value="GRID">Grid</option>
							<option value="LIST">List</option>
							<option value="LIST_ONLY">List Only(Hide Others)</option>
						</select>           
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Listing Page Settings</h4>
		</div>
		<div class="widget-main">
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="related_items">Related Items:</label>
					<div class="controls">
						<input type="text" id="related_items" class="input-small"><span> Item per Page</span>   
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="view">Require Customer Login to View Products:</label>
					<div class="controls">
						<label>
							<input id="r_view_products" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_switch();">
							<span class="lbl"></span>
						</label>         
					</div>
				</div>
				<div class="control-group">

					<div class="controls">
						<button class="btn btn-success" onclick="save_page_settings(); return false;">Save</button>   
					</div>
				</div>
			</form>
		</div>
	</div>
</div>