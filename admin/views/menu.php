<div class="main-content">
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>
					<ul class="breadcrumb">
						<li>
							<i class="icon-home home-icon"></i>
							<a href="#">Home</a>

							<span class="divider">
								<i class="icon-angle-right arrow-icon"></i>
							</span>
						</li>
						<li class="active"><?php if(isset($page['page'])){echo $page['page'];} ?></li>
					</ul><!--.breadcrumb-->

					<div class="nav-search" id="nav-search">
						<form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="input-small nav-search-input" id="nav-search-input" autocomplete="off" />
								<i class="icon-search nav-search-icon"></i>
							</span>
						</form>
					</div><!--#nav-search-->
				</div>

        <div class="page-content">
                        <div class="page-header position-relative">
                            <h1>
                                <?php if(isset($page['page'])): ?>
                                <?php echo $page['page']; ?>
                                <?php if(isset($page['title'])): ?>
                                <small>
                                    <i class="icon-double-angle-right"></i>
                                    <?php echo $page['title']; ?>
                                </small>
                                <?php endif; ?>
                                <?php endif; ?>
                            </h1>
                        </div><!--/.page-header-->

				<div class="page-content">
					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->
                                                        