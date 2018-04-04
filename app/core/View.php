<?php

class View
{
	public static function render($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('../app/views');
            $twig = new \Twig_Environment($loader);
        }

        echo $twig->render($template, $args);
    }

    public static function redirect($url, $page = NULL)
	    {
	    if(!headers_sent()) {
	        if ($page) {
	        	header('Location:'.URL.$url.'?page='.$page);
	        	exit;
	        } else {
	        	header('Location:'.URL.$url);
	        	exit;
	        }
	        
	    } else {
	    	if ($page) {
	    		echo '<script type="text/javascript">';
		        echo 'window.location.href="'.URL.$url.'?page='.$page.'";';
		        echo '</script>';
		        exit;
	    	} else {
	    		echo '<script type="text/javascript">';
		        echo 'window.location.href="'.URL.$url.'";';
		        echo '</script>';
		        exit;
	    	}
	    }
    }
}