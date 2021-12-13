<?php
get_header('page');
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10">
            <?php
            $gallery = cms_get_post_sliders(array('status'=>'active'));
            ?>
            <?php foreach ($gallery as $key => $value): ?>
                <div>
                    <h4>Album: <?php echo $value['album']['name']; ?></h4>
                    <ul class="list-unstyled list-inline">
                    <?php foreach ($value['photos'] as $pkey => $pvalue): ?>
                        <li>
                            <img src="<?php echo $pvalue['url'] ?>" alt="" style="max-width: 100; max-height: 100px;">
                            <p><?php echo $pvalue['description'] ?></p>
                        </li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
            <?php cms_post_content(); ?>
        </div>
        <div class="col-lg-4 col-md-2">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>