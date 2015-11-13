<?php

/*
-----------------------------------------------------------------------------------------------------------
File: index.php
Version: 1.0
Release Date:
-----------------------------------------------------------------------------------------------------------
Overview: Page documentor
-----------------------------------------------------------------------------------------------------------
History:
28/03/2014      1.0	MJC	Created
-----------------------------------------------------------------------------------------------------------
Uses:

*/

include("clsDocumentor.php");


$path = "\path\to\php\classes\\";


$files = scandir($path);

foreach($files as $indFiles)
{
        if(strpos($indFiles,'.')!==false)
        {
                if(($indFiles != '.') && ($indFiles != '..'))
                {
                        $document = new Documentor($path.$indFiles);
                        $document->setPath($path);
                        $document->setFileName($indFiles);
                        $document->createDocument();
                }
        }
}


?>