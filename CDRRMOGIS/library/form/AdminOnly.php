<?php

if(isset($_SESSION['USER_USERNAME']) and isset($_SESSION['USER_TYPE']))
{
    if($_SESSION['USER_TYPE'] !=  'A')
    {
    
        header('Location: index.php');
        exit;
    }
}
else {
	
    header('Location: index.php');
    exit;
}
?>