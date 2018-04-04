<?php

class NotFound 
{
	public static function error()
	{
		View::render('error.html');
	}
}