<li class="<?php echo get_current_url() == trim($header_menu['link_url'], ' ') ? 'active' : ' '; ?>">
  <a href="<?php echo $header_menu['link_url']; ?>" ><?php echo $header_menu['link_text']; ?></a>

  <?php if (count( $header_menu['sub_menus'] )): ?>
  	<ul>
  	<?php cms_generate_display_menu( $header_menu['sub_menus'], "" ) ?>
  	</ul>
  <?php endif ?>
</li>