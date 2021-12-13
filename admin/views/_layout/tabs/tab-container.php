<div class="tabbable tabs-left" >
    <ul class="nav nav-tabs" id="<?php echo $cms_tab_id; ?>">
    <?php 
        $tabs = $this->get_component_files('pages/tabs'); 
        $active = false;
    ?>
    <?php foreach ($tabs as $key => $value): ?>
        <li class="<?php echo !$active ? 'active' : ""; $active = true; ?>">
            <a data-toggle="tab" href="#<?php echo $value['id']; ?>">
                <i class="green icon-barcode bigger-110"></i>
                <?php echo $value['title'] ?>
            </a>
        </li>
    <?php endforeach ?>
    </ul>

    <div class="tab-content">
        <?php $this->include_component_files('pages/tabs', isset($variables) ? $variables : array()); ?>
    </div>
</div>