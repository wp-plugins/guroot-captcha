<?php
/**
 * This is the HTML template of the captcha
 */
?>

<style type='text/css'>
    #captcha ul li {float:left !important; }
    #captcha img {margin-right:10px;border-style : solide;border-width:1px;border-color:white }
    #captcha ul {padding: 0;}
</style>
<div style='display:block;width:100%;min-height:120px;padding-top:12px;' id='captcha'>
    <div><p>Please, sort the following images in ascending order.</p></div>
    <ul style='list-style-type: none;' id='GURCAPTCHA_ul'>
        <li id='0'><img src='<?php echo  get_bloginfo('wpurl') ?>?GURCAPTCHA_image=0?timestamp=<?php echo time() ?> ' /></li>
        <li id='1'><img src='<?php echo  get_bloginfo('wpurl') ?>?GURCAPTCHA_image=1?timestamp=<?php echo time() ?>' /></li>
        <li id='2'><img src='<?php echo  get_bloginfo('wpurl') ?>?GURCAPTCHA_image=2?timestamp=<?php echo time() ?>' /></li>
        <li id='3'><img src='<?php echo  get_bloginfo('wpurl') ?>?GURCAPTCHA_image=3?timestamp=<?php echo time() ?>' /></li>
        <li id='4'><img src='<?php echo  get_bloginfo('wpurl') ?>?GURCAPTCHA_image=4?timestamp=<?php echo time() ?>' /></li>
    </ul>
    <input type=hidden id='Captcha_Solution' name='Captcha_Solution' />
</div>
<script type='text/javascript'>
    jQuery(document).ready(function(){
        jQuery('#GURCAPTCHA_ul').sortable();            
        jQuery('#commentform').submit(function(){
            var sorted = jQuery( '#GURCAPTCHA_ul' ).sortable( 'toArray');
            jQuery('#Captcha_Solution').val(sorted.toString());
        })           
    })
</script>