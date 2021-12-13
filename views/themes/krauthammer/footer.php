<footer class="container-fluid b_122947">
    <div class="container">
        <div class="row f_j_b t16 c_fff">
            <div class="col-12 col-md-4 twin-columns">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-12 columns">
                        <h4 class="title f_j_s t20 c_fff">Singapore Office</h4>
                        <p>317 Outram Road #02-46</br> Concorde Shopping Centre</br> Singapore 169075</p>
                        <p>Tel: <?php echo get_system_option(['option_name' => 'company_contact_number']); ?></p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-12 columns">
                        <h4 class="title f_j_s t20 c_fff">Global Office</h4>
                        <p>
                            <?php echo get_system_option(['option_name' => 'company_address']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 columns">
                <h4 class="title f_j_s t20 c_fff">What We Offer</h4>
                <ul class="list-unstyled nav-list">
                    <?php cms_display_menu(array('name' => 'What We Offer Menu')); ?>
                </ul>
            </div>
            <div class="col-12 col-sm-4 col-md-4 columns">
                <h4 class="title f_j_s t20 c_fff">More Information</h4>
                <ul class="list-unstyled nav-list">
                    <?php echo cms_display_menu(array('name' => 'More Information Menu')); + 'sas' ?>
                    <li><a href="#" class="link">Privacy Statement</a></li>
                    <li><a href="#" class="link">Disclaimer</a></li>
                </ul>
            </div>
        </div>
        <div class="row copyright f_j_b t16 c_fff">
            <div class="col-12">
                <p class="text-center"><?php echo get_system_option(array('option_name' => 'website_footer_copyright_text')); ?></p>
            </div>
        </div>
    </div>
</footer>

<!-- Popper script -->
<!--
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/umd/popper.js"></script>
      -->
<!-- bootstrap script 4.0.0 -->
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- BEGIN css element queries 1.0.0 -->
<!--
        <script src="assets/third-party/css-element-queries/1.0.0/src/ResizeSensor.js"></script>
        <script src="assets/third-party/css-element-queries/1.0.0/src/ElementQueries.js"></script>
        -->

<!-- Ion Icons -->
<!--
          <script src="https://unpkg.com/ionicons@4.5.0/dist/ionicons.js"></script>
          -->

<!-- REVOLUTION SETTINGS STYLES 5.1.6 -->
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/jquery.themepunch.tools.min.js?rev=5.0"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/jquery.themepunch.revolution.min.js?rev=5.0"></script>

<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.video.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.actions.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.migration.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/revolution/5.1.6/js/extensions/revolution.extension.parallax.min.js"></script>
<!-- Custom Slider -->
<script src="<?php bloginfo('template_directory'); ?>/assets/js/revo-settings.js"></script>
<!-- For Maps -->
<!--
            <script defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwr50oLLrPjw6-c6YZDTXF5gUWaj68j-g&amp;"></script>
            <script>
              function initialize(lat,lng, title, description, index) {
              var center = window.innerWidth > 767 ? (lng - 0.01) : lng;

              var mapCanvas = document.getElementById('map_canvas_'+index);
              var myLatlng = new google.maps.LatLng(lat, lng);
              var mapOptions = {
                center: myLatlng,
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                gestureHandling: 'cooperative',
                mapTypeControl: true,
                mapTypeControlOptions: {
                  style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                  position: google.maps.ControlPosition.TOP_RIGHT
                },
              }
              var map = new google.maps.Map(mapCanvas, mapOptions);
              var contentString = "<body><b>"+title+"</b><p>"+description+"</p></body>";
              var infowindow = new google.maps.InfoWindow({
                content: contentString
              });
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map
                });
                google.maps.event.addListener(infowindow, 'domready', function() {
                });
                infowindow.open(map,marker);
              }
            </script>
            -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

<!-- Contact form script -->
<script>
    var baseURL = "";
</script>
<!-- Toast Script 1.3.1 -->
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/toast/1.3.2/dist/jquery.toast.min.js"></script>
<!-- Isotope 3.0.5 -->
<!--
              <script src="assets/third-party/isotope/3.0.5/isotope.pkgd.min.js"></script>
              -->
<!-- ScrollpostStyler 0.7.0 -->
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/scrollPosStyler/0.7.0/scrollPosStyler.js"></script>
<!-- jQuery UI datepicker -->
<script src="<?php bloginfo('template_directory'); ?>/assets/third-party/jquery-ui/1.12.1/jquery-ui.min.js"></script>
<!-- Bootstrap Selectpicker -->
<!--
                <script src="assets/third-party/bootstrap-select/1.13.0/js/bootstrap-select.min.js"></script>
                -->
<!-- Google Capcha  -->
<script src="https://www.google.com/recaptcha/api.js"></script>
<!-- Custom scripts in one -->
<script src="<?php bloginfo('template_directory'); ?>/assets/js/main.js"></script>
</body>

</html>