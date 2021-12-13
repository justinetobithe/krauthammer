<?php
/*
Template Name: Test
*/

?>

<?php
get_header('page');
?>

<!-- Main Content -->
<div class="container">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <?php //echo date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " + 1 days" )); ?>
      <?php cms_post_content(); ?>
      <?php //cms_generate_payment_button(); ?>

      <!-- <form action="<?php echo get_bloginfo('baseurl') ?>/api/test-subscribe/basic-plan">
        <button class="btn btn-primary">Subscribe</button>
      </form> -->
    </div>
  </div>
</div>

<?php
get_footer();
?>