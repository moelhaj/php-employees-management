<?php
	class Database
	{
	     
	    public static  $conn;
	     
	    public static function connect()
		{
			
		    self::$conn = null;    
	        try
			{
	            self::$conn = new PDO("mysql:host=localhost;dbname=abc",'root','');
				self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	        }
			catch(PDOException $exception)
			{
	            echo "Connection error: " . $exception->getMessage();
	        }
	         
	        return self::$conn;
	    }
	}