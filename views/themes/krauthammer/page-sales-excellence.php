<?php
/*
Template Name: Sales Excellence
*/
?>

<?php
get_header();
?>

<div class="containment">
  <div class="sales-excellence-body">

    <section class="container-fluid sales-excellence-banner d-flex align-items-center">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-5 col-xl-4 d-flex flex-column align-items-start f_j_b t16 c_141515">
            <h1 class="title f_j_bld t20 c_2a7db1">Create a more entrepreneurial and sales efficient organization.
            </h1>
            <p>Engaging with clients, identifying key drivers and offering new perspectives:<br>Krauthammer leads the
              way and develops the skills for devlivering measurable results.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="container-fluid values">
      <div class="container">
        <div class="row">
          <div class="left col-12 col-lg-6 col-xl-6 f_j_b t16 c_141515">
            <h4 class="title f_j_s t32 c_2a7db1">Sales Excellence</h4>
            <p class="challenges f_j_b t16 c_222">Here are some of the most common client sales challenges we've
              worked on.</p>
            <ul class="list-unstyled list-inline list f_j_b t16 c_222 row">
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Sales management</h5>
                  <p>Build the foundation of a succesful sales organization.
                  </p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Consultancy based selling</h5>
                  <p>Acuire the expertise to have sales dialogues that matter.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Key account management</h5>
                  <p>Improve the ability of your key account managers to win complex deals.</p>
                </div>
              </li>
              <li class="col-12 col-md-12 list-inline-item d-flex flex-row align-items-start justify-content-start">
                <div class="block">
                  <h5 class="subtitle f_j_s t16 c_222">Sales skills and process</h5>
                  <p>Develop effective sales behaviours matching your company's ambition.</p>
                </div>
              </li>
            </ul>
          </div>
          <div class="right col-12 col-lg-6 col-xl-6">
            <img class="img-responsive img-fluid" src="<?php bloginfo('template_directory'); ?>/assets/images/sales-excellence/cards-on-table.png" alt="Cards on table" />
          </div>
        </div>
      </div>
    </section>

    <section class="container-fluid client-sales">
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
                <iframe src="https://player.vimeo.com/video/233268624" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="row"> 
              <?php echo _get_post_by_parent_id(16); ?> 
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