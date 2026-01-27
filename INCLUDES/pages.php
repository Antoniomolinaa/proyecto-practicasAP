<?php

    if(isset($_SESSION['token']) and isset($_GET['page']))
    {
        if($_GET['page'] == "login")
        {
            header('Location: index');
            exit;
        }                 
    }
        

    if(!isset($_SESSION['token']))
    {
        include("PAGINAS/login.php");
    }                           
    else 
    {
        $allowedPages = array("logout", "landing");

        if(isset($_GET['page']))
        {
            if( in_array($_GET['page'], $allowedPages) )
            {
                include("PAGINAS/".$_GET['page'].".php");
            }
            else
            {
                header('Location: index');
            }  
        }
        else
        {
            include("PAGINAS/landing.php");
        }
    }
    

?>