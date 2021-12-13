<?php
/*
Template Name: Leadership Management
*/
?>

<?php
get_header();
?>

<div class="containment">
  <div class="leadership-management-body">

    <section class="container-fluid leadership-management-banner d-flex align-items-center">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-5 col-xl-4 d-flex flex-column align-items-start f_j_b t16 c_141515">
            <h1 class="title f_j_bld t20 c_2a7db1">Today's succesful leaders and mangers understand true engagement
            </h1>
            <p class="f_j_b t16 c_222">It's a fact that inspiring leadership and management drive employee engagement. Our learning journest
              creatfe lasting behavioural change leading to a positive infectious attitude.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="container-fluid values">
      <div class="container">
        <div class="row">
          <div class="left col-12 col-lg-6 col-xl-6 f_j_b t16 c_141515 order-lg-0">
            <p class="challenges f_j_b t16 c_222 line-height-half">The Krauthammer management training programmes and leadership
              development courses are worldwide recognized and awarded as highly efficient and impactful. The focus in
              our learning journeys is not on the know-how, but on actually changing leadership and management
              behaviours and skills so they become intuitive.</p>
            <p class="challenges f_j_b t16 c_222 line-height-half">Our learning journeys combine +45 years of experience with our
              result proven training methodology, blended with latest digital and experimental training techniques.
              All designed to deliver lasting behavioural change.</p>

          </div>

          <div class="left-two d-flex col-lg-6 col-xl-6 order-lg-4 align-items-center justify-content-center">
            <img class="img-responsive img-fluid" src="<?php bloginfo('template_directory'); ?>/assets/images/leadership-and-management/winner.png" alt="Winner" />
          </div>

          <div class="right col-12 col-lg-6 col-xl-6 order-lg-12">
            <ul class="list-unstyled list-inline list f_j_b t16 c_222 row">
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">effective leadership and communication</h5>
                  <p>Become a more effective communicator and influence without authority</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Personal effectiveness</h5>
                  <p>Guide succesful meetings, master presentation skills, problem solving and conflict handling</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">People management</h5>
                  <p>Get to know your own management style and cultivate your ability to lead and manage</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">High potentials and board teams performance</h5>
                  <p>Implement lean ang agile management methods to develop effective leaders</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Engaged leadership</h5>
                  <p>Motivate and engage individuals and teams and raise collaboration, trust and performance</p>
                </div>
              </li>
            </ul>
          </div>

          <div class="right-two d-block d-lg-flex col-lg-6 col-xl-6 order-lg-8 align-items-center justify-content-center">
            <img class="img-responsive img-fluid" src="<?php bloginfo('template_directory'); ?>/assets/images/leadership-and-management/active-reference.png" alt="Active Reference" />
          </div>

        </div>
      </div>
    </section>

    <section class="container-fluid client-leadership">
      <div class="container">
        <div class="intro row">
          <div class="col-12">
            <h4 class="title f_j_s t32 c_fff text-center">Client Cases</h4>
            <p class="sub-title f_j_b t16 c_fff text-center">Leadership & Management development in action</p>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="video w-100 row">
                <iframe src="https://player.vimeo.com/video/180198146" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="row"> 
              <?php echo _get_post_by_parent_id(12); ?> 
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php cms_get_fragment('', 'contact'); ?>


  </div>
</div>

<?php
get_footer();
?>