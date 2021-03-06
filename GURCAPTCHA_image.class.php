<?php

Class GURCAPTCHA_image {

    public $image;

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function generateImage($id) {
        $imageRow = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM wp_GURCAPTCHA_Images WHERE `order`= %d', array(intval($id))));
        $this->image = imagecreatefromstring($imageRow->image);
    }

    /**
     * Output the image generated by generateImage()<br />
     * File size may change to make it more difficult for bots.
     * 
     */
    public function output() {
        header('Content-Type: image/jpeg');
        imagejpeg($this->image, NULL, rand(75, 100));
    }

}

?>
