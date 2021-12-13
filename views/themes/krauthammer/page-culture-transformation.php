<?php
/*
Template Name: Culture Transformation
*/
?>

<?php
get_header();
?>

<div class="containment">
  <div class="culture-transformation-body">

    <section class="container-fluid culture-transformation-banner d-flex align-items-center">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-5 col-xl-4 d-flex flex-column align-items-start f_j_b t16 c_141515">
            <h1 class="title f_j_bld t20 c_2a7db1">Change starts and ends with people.</h1>
            <p>Whether your organisation wants to change the way it's run or transform itself to meet a vision, Krauthammer ensures that your people are engaged for that journey. This requires the touching of hearts and minds with fresh beliefs and behaviours, bringing out the best in people to engage in the new ways of working.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="container-fluid values">
      <div class="container">
        <div class="row">
          <div class="left col-12 col-lg-6 col-xl-6 f_j_b t16 c_141515">
            <h4 class="title f_j_s t32 c_2a7db1">Culture and Transformation</h4>
            <p class="challenges f_j_b t16 c_222">Some of the common culture and transformation challenges that we
              help our clients overcome.</p>
            <ul class="list-unstyled list-inline list f_j_b t16 c_222 row">
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Implement change with success</h5>
                  <p>Multiply your organisation's transformation success by improving communciation, leading by
                    example and engaging employees. </p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Innovate to stay ahead</h5>
                  <p>Ensure your organisation stays ahead by creating the right conditions and attitudes.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Operational excellence</h5>
                  <p>Focus on people side of business to release your organisation's full potential.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Build a network ogranization</h5>
                  <p>Develop behaviours that make sure that new ways of working in the infrastracture, processes and
                    culture work.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Develop collaborative approaches</h5>
                  <p>Engage stakeholders in a collaborative journey of consultation and engagement focused on
                    outcomes.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Build trust relationships</h5>
                  <p>Throught the right behaviours and actions, fosther the conditions needed for trust-based
                    relationships.</p>
                </div>
              </li>
            </ul>
          </div>
          <div class="right col-12 col-lg-6 col-xl-6">
            <ul class="list-unstyled list-inline list f_j_b t16 c_222 row">
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Merging 2 cultures into one</h5>
                  <p>Getting people on board is esseintial to shaping a new cpompany culture and speeding up
                    integration.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Embrace diversity</h5>
                  <p>Build bridges between generations and cultures to help them develop shared value and puprpose.
                  </p>
                </div>
              </li>
            </ul>
            <img class="img-responsive img-fluid" src="<?php bloginfo('template_directory'); ?>/assets/images/culture-transformation/brainstorm.png" alt="Brainstorm" />
          </div>
        </div>
      </div>
    </section>

    <section class="container-fluid client-culture">
      <div class="container">
        <div class="intro row">
          <div class="col-12">
            <h4 class="title f_j_s t32 c_fff text-center">Client Cases</h4>
            <p class="sub-title f_j_b t16 c_fff text-center">Sales Excellence in action</p>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
             <div class="video w-100 row">
                <iframe src="https://player.vimeo.com/video/338018910" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="row">
              <?php echo _get_post_by_parent_id(10); ?>
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