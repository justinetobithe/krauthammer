<div class="widget-box">
	<div class="row-fluid">
		<div class="span12">
			<p>These are the following function for front-end development. </p>
		</div>
	</div>

	<hr>

	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_bloginfo ( ) :</b> will echo the template directory or installation url<br>
				@param string - [ baseurl / template_directory / installation_url]<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					bloginfo('installation_url')<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_apply_thumbnail ( ) :</b> will return the url of the thumbnail<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo  cms_apply_thumbnail($options = array());<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_current_url ( ) :</b> will return the current url<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo  get_current_url();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_current_uri ( ) :</b> will return the current URI<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo  get_current_uri();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>bloginfo ( ) :</b> will echo the template directory or installation url<br>
				@param string - [ baseurl / template_directory / installation_url]<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					bloginfo('installation_url')<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_initialize ( ) :</b> will initialized some variables<br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_set_page_offset ( ) :</b> set the current page index<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_current_page_offset ( ) :</b> get the current page index<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_have_next_page_offset ( ) :</b> test if has next page index<br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_next_page_offset ( ) :</b> get the next page index<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_have_prev_page_offset ( ) :</b> check if has the previous page index<br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_prev_page_offset ( ) :</b> get the previous page index<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_max_post_offset ( ) :</b> get the max offset (max number of pages)<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_posts_count ( ) :</b> get the total number posts of the current query<br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_query_posts ( ) :</b> will query to know if there is a post<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					cms_query_posts($str_query);<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_is_page ( ) :</b> if the post is page then it will return true<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					if(cms_is_page($id)){}<br>
					[php]<br>
				</code><br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_have_posts ( ) :</b> check if there is/are post/s exist<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					if(cms_have_posts()){}<br>
					[php]<br>
				</code><br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_post_count ( ) :</b> return count of all the post<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					if(cms_post_count() &gt; 0){}<br>
					[php]<br>
				</code><br>
			@return int</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_the_post ( ) :</b> will shift the arrays to the current array<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					cms_the_post()<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_meta_title ( ) :</b>return current meta title<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_meta_title();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_meta_data ( ) :</b>return current meta data<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_meta_data();<br>
					[php]<br>
				</code><br>
			@return array</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_seo_description ( ) :</b>return current meta description<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_seo_description();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_post_title ( ) :</b>return current tile<br>
				@function <b>cms_get_seo_description ( ) :</b>return current meta description<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_post_title();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_post_title ( ) :</b>echo current tile<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					cms_post_title();<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_post_content ( ) :</b>return the content<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_post_content();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_post_excerpt ( ) :</b> Get excerpt from string<br>
				<br>
				@param String $str String to get an excerpt from<br>
				@param Integer $startPos Position int string to start excerpt from<br>
				@param Integer $maxLength Maximum length the excerpt may be<br>
				<br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_post_content ( ) :</b>check content if there is contact form inside the content and echo the content<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_post_content();<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_post_permalink ( ) :</b> return the url slug of the page<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_post_permalink();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_permalink ( ) :</b> echo the url slug of the page<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_permalink();<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_baseurl ( ) :</b> return the base URL<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_baseurl();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_reset_query ( ) :</b> setting the global variables to its default value<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					cms_reset_query();<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_page_template ( ) :</b> setting the global variables to its default value<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_get_page_template($options = array());<br>
					[php]<br>
				</code><br>
			@return void</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_menu ( ) :</b> will return the header menu<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$menu =  cms_get_menu(array('id' =&gt; id, 'name' =&gt; name)) ;<br>
					[php]<br>
				</code><br>
			@return array</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>sort_array ( ) :</b> will sort array<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$sorted_array = sort_array($rows,'sort_order','asc');<br>
					[php]<br>
				</code><br>
			@return array</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_system_option ( ) :</b> will sort array desc or asc<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$value = get_system_option(array('option_name' =&gt; option_name));<br>
					[php]<br>
				</code><br>
			@return array</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_google_analytics_is_on ( ) :</b> check if google analytics is on<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					if(cms_google_analytics_is_on()){}<br>
					[php]<br>
				</code><br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_google_analytics_code ( ) :</b> echo google analytic code<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					cms_google_analytics_code();<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_check_contact_form_exist ( ) :</b> echo google analytic code<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					if(cms_check_contact_form_exist($id)){}<br>
					[php]<br>
				</code><br>
			@return boolean</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_replacement_form_code ( ) :</b> echo google analytic code<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					echo cms_replacement_form_code($id);<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>change_tags ( ) :</b> change contact form tags to form input<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					$content = change_tags($data);<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_addons ( ) :</b> adding attributtes to input tags<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$content = get_addons($data,$input);<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_addons ( ) :</b> adding attributtes to input tags<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$content = get_options($data);<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>get_check ( ) :</b> adding attributtes and division in checkbox/radio inputs<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$content = get_check($data,$kind);<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 well well-small">
			<p class="">@function <b>cms_get_convert_contact_forms ( ) :</b> converts contact form short code to html form<br>
				<br>
				Here is an inline example:<br>
				<code><br>
					[php]<br>
					$content = get_check($data,$kind);<br>
					[php]<br>
				</code><br>
			@return string</p>
		</div>
	</div>
	
</div>