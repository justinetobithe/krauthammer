    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2016</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?php bloginfo('template_directory'); ?>/resources/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php bloginfo('template_directory'); ?>/resources/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="<?php bloginfo('template_directory'); ?>/resources/js/jqBootstrapValidation.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/resources/js/contact_me.js"></script>

    <script src='https://www.google.com/recaptcha/api.js'></script>
    
    <!-- Theme JavaScript -->
    <script src="<?php bloginfo('template_directory'); ?>/resources/js/clean-blog.min.js"></script>

    <?php
    /*
    *
    function "cms_promotional_popup()" is located inside /system_plugin/promotional-popup/libraries/Library.php
    *
    */
    ?>
    <?php cms_promotional_popup(); ?>
</body>

</html>