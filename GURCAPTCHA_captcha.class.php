<?php

/**
 *
 * @author guroot
 */
class GURCAPTCHA_captcha {
private static $instance = null;
    
    
    function __construct() {
    }
    

    /**
     * Initialize current class
     * @return GURCAPTCHA_install
     */
    static function getInstance() {
        if (is_null(self::$instance))
            self::$instance = new GURCAPTCHA_captcha ();
        return self::$instance;
    }
    

    /**
     * Client side way to change the captcha position
     * Thanks to adscaptcha.com where I got this great hack
     */
    private function _rePosition() {
        echo("<script type='text/javascript'>");
        echo "jQuery(document).ready(function(){";
        echo("var oComment = document.getElementById('comment');");
        echo("var oParent = oComment.parentNode;");
        echo("var oCaptcha = document.getElementById('captcha');");
        echo("oParent.appendChild(oCaptcha, oComment);");
        echo "});";
        echo("</script>");
    }

   

    
    /**
     * Doing some magic to create the captcha
     */
    public function process() {   
        $order = array(0,1,2,3,4);        
        $initialOrder = $order;
        // The order must be different of the accepted answer
        while($order === $initialOrder)
            shuffle($order); 
        GURCAPTCHA_cookie::getInstance()->addServerSideData(GURCAPTCHA_cookie::getInstance()->getId(), array('order'=>$order));
        include_once __DIR__ . DIRECTORY_SEPARATOR . 'GURCAPTCHA_template.php';
        $this->_rePosition();
    }

    /**
     * Validate if the user solve the Captcha 
     */
    public function preprocess($comment) {
        $input = split(',', $_POST['Captcha_Solution']);
        $solution = GURCAPTCHA_cookie::getInstance()->getServerSideData(GURCAPTCHA_cookie::getInstance()->getId(),'order');
        $spam = false;
       
        // Without solution, obviously it's a spam
        if (!$solution)
            $spam = true;
        // Validate if posted order is the same as server side.
        foreach ($solution as $order => $solution)
            if (intval($input[$solution]) !== ($order)) {
                $spam = true;
            }
        if ($spam)
            add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
        return $comment;
    }

}

?>
