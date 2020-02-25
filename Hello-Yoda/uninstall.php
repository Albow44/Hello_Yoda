<?php
     if (!defined("WP_UNINSTALL_PLUGIN")) exit;
     
global $wpdb;
$wpdb->query("DROP TABLE {$wpdb->prefix}quotes");

?>