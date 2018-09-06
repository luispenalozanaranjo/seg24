<?php
class DataManager{
 
	//errores Numerico y Mensajes
	private static $error;
	private static $mensajeError;

    
    static public function getInstance()
	{
    	global $dsn;
      	$db = null;
		
		try 
		{
			//$db = new PDO("sqlsrv:Server=$server;Database=NetsupportSD",$username,$password);
			$db = new PDO('mysql:dbname=siedt;host=10.13.71.85', 'system-edt', 'f4&h7)J=0A');
			//$db = new PDO('mysql:dbname=srav;host=localhost', 'root', '');
			$db->query('SET NAMES utf8'); 
		} 
		catch (exception $e) 
		{
			self::$mensajeError = $e->getMessage();
            self::$error = true;
        	echo self::$mensajeError;
        	$db = null;
        	exit;	
		}

		return $db;	
	}
	
    static public function gethost()
    {
    	return self::$host;
    }
    
    static public function getuser()
    {
    	return self::$user;
    }
    static public function getpass()
    {
    	return self::$pass;
    }
    static public function getdbselect()
    {
    	return self::$dbselect;
    }
     /*       try {
				//$dsn = "odbc:Driver={SQL Server};Server=".self::$host.";Database=".self::$dbselect.";UID=".self::$user.";PWD=".self::$pass;
				$dsn = "odbc:Driver={mysql};Server=".self::$host.";Database=".self::$dbselect.";UID=".self::$user.";PWD=".self::$pass;
				$db = new PDO($dsn);
            } catch (exception $e) {
                self::$mensajeError = $e->getMessage();
                self::$error = true;
        		echo self::$mensajeError;
        		$db = null;
        		exit;	
            }
*/
        
}//final de la classe dataManager  
?>
