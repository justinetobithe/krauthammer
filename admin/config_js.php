<?php
/*
 * Including this file in <head> will allow all JS files to use the config file environmental variables.
 * This file is not to be modified.
 */
include 'config.php';
?>
CONFIG = (function() {
    var config_val = {
        'FRONTEND_URL': '<?php echo FRONTEND_URL; ?>',
        'URL': '<?php echo URL; ?>'
    };

    return {
        get: function(name) {
            return config_val[name];
        }
    };
})();