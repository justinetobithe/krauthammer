<?php
/*
Template Name: Contact Us
*/
?>

<?php
get_header();
?>

<div class="containment">

  <section class="container-fluid contact-us-banner d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="left col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center align-items-center">
          <h4 class="title f_j_b t32 c_fff text-center text-md-left text-lg-left">Are in interested in <span class="d-block d-sm-inline-block"><span class="f_j_s c_008ece d-block">Leadership
                Management</span></span> <span class="d-block"> &
              <span class="f_j_s c_008ece d-inline">Sales Behaviour</span> <span class="d-inline d-sm-inline-block">Training?</span></span></h4>
          <a href="#contact-form" class="btn btnInterested btn-center f_j_s t16 c_fff text-center text-uppercase">Yes , I am
            interested</a>
        </div>
        <div class="right col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center align-items-center">
          <h5 class="f_j_s t16 c_fff text-uppercase text-center text-lg-left">Singapore Office 317 Outram Road #02-46 Concorde Shopping Centre Singapore 169075</h5>
          <p class="f_j_s t14 c_b7b7b7 text-uppercase text-center text-lg-left">Find me at this
            address
          </p>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center align-items-center">
              <h5 class="f_j_s t16 c_fff text-center text-lg-left"><?php echo get_system_option(['option_name' => 'company_contact_number']); ?></h5>
              <p class="f_j_s t14 c_b7b7b7 text-uppercase text-center text-lg-left">Call me</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center align-items-center">
              <h5 class="f_j_s t16 c_fff text-center text-lg-left"><?php echo get_system_option(['option_name' => 'company_email']); ?></h5>
              <p class="f_j_s t14 c_b7b7b7 text-uppercase text-center text-lg-left">Send me email</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container-fluid contactform b_fff">
    <div class="container">

      <div class="row d-flex">
        <div class="left col-12 col-md-6 col-lg-6 col-xl-6 d-flex align-items-center justify-content-center order-md-0 order-12 order-lg-0">
          <div class="row">
            <div class="col-12">
              <p class="businesstitle f_j_s t24 c_222 tp-resizeme">Are you ready for a better and a more productive
                business?
              </p>
            </div>
            <div class="col-12">
              <div class="box">
                <!--<ul class="list list-unstyled">-->
                <!--  <li>-->
                <!--    <p class="f_j_b t16 c_222 tp-resizeme">Working Days-->
                <!--      <span class="time f_j_b t16 c_222 tp-resizeme">10AM - 7PM</span></p>-->
                <!--  </li>-->
                <!--  <hr>-->
                <!--  <li>-->
                <!--    <p class="f_j_b t16 c_222 tp-resizeme">Saturday-->
                <!--      <span class="time f_j_b t16 c_222 tp-resizeme">10AM - 6PM</span></p>-->
                <!--  </li>-->
                <!--  <hr>-->
                <!--  <li>-->
                <!--    <p class="f_j_b t6 c_222 tp-resizeme">Sunday-->
                <!--      <span class="time f_j_b t16 c_222 tp-resizeme">10AM - 5PM</span></p>-->
                <!--  </li>-->
                <!--</ul>-->
              </div>
            </div>
            <div class="col-12 col-md-12 col-sm-6 col-xl-6 d-block image m-auto p-0">
              <img class="img-responsive img-fluid business-hours" src="<?php bloginfo('template_directory'); ?>/assets/images/contacts/business-hours.png" alt="Business Hours">
            </div>
          </div>
        </div>

        <div id="contact-form" class="right col-12 col-md-6 col-lg-6 col-xl-6 d-flex align-items-center justify-content-center order-0 order-md-12 order-lg-12">
          <div class="row contact">
            <div class="col-12">
              <?php echo cms_get_convert_contact_forms('[contact-form id="10" title="Contact Form Full Width"]'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<?php
get_footer();
?>
 