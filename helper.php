<?php

/**
 * Include the greatest js library 
 */
function _enqueue_jQuery() {
    wp_enqueue_script('jquery');
}

/**
 * Include Jquery UI (core, sortable,draggable,droppable)
 */
function _enqueue_jQuery_UI() {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');
}

/**
 * Validate if a table already exist
 * @param string $tableName The name of the table to validate
 * @return bool
 */
function tableExist($tableName) {
    global $wpdb;
    return $wpdb->query(
                    $wpdb->prepare("SHOW TABLES LIKE %s", $tableName)
    );
}

?>
