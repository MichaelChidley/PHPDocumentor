<?php

/*
-----------------------------------------------------------------------------------------------------------
Class: Documentor
Version: 1.0
Release Date: 
-----------------------------------------------------------------------------------------------------------
Overview: Class to create a HTML document from PHP classes using a defined template  
-----------------------------------------------------------------------------------------------------------
History:
28/03/2014      1.0	MJC	Created
01/04/2014      1.0     MJC     Added improved output formatting on the history section
05/04/2014      1.1     MJC     Improved the output formatting for both in and out parameters
-----------------------------------------------------------------------------------------------------------
Uses:

*/

error_reporting(0);


class Documentor
{
        private $file;
        private $fileName;
        private $contents;
        private $path;
        
        private $documentationFile = '';


        public function Documentor($strFile)
        {
                $this->file = $strFile; 
                
                $this->buildFileHeader();
                
                $fopen = file_get_contents($this->file);
                
                $this->contents = explode(PHP_EOL, $fopen); 
        }
        
        
        private function buildFileHeader()
        {
                $this->addInfo("<html>");
                $this->addInfo("<head>");
                        $this->addInfo("<link rel='stylesheet' type='text/css' href='style.css' />");
                $this->addInfo("</head>");
                        
                $this->addInfo("<body>");
        }
        
        private function buildFileFooter()
        {
                $this->addInfo("</body>");
                $this->addInfo("</html>");         
        }
        
        
        private function createCSS()
        {
                $open = fopen($this->path."documents/style.css", "w");
                
                $css = "*
                        {
                                margin: 0px;
                                border: 0px;
                        }
                        
                        
                        div
                        {
                                padding: 5px;
                        }
                        
                        #heading
                        {
                                font-size: 24px;
                                padding: 5px;
                        }
                        
                        .methodHeading
                        {
                                font-size: 22px;
                                padding: 5px;
                        }
                        
                        
                        .separator
                        {
                                border-bottom: 2px solid grey;
                                margin-top: 5px;
                                margin-bottom: 5px;
                        }
                        
                        .bold
                        {
                                font-weight: bold;
                                float: left;
                        }
                        
                        .floatl
                        {
                                float: left;
                        }
                        
                        .clear
                        {
                                clear: both;
                        }
                        
                        .history
                        {
                                margin: 0px;
                                padding: 0px;
                                padding-left: 5px;
                        }
                        
                        .variable
                        {
                                color: #14b4dc;
                        }
                        
                        .variableType
                        {
                                font-style: Italic;
                                padding-left: 10px; 
                        }
                        
                        .paramDesc
                        {
                                padding-left: 10px;
                        }
                        
                        .history td
                        {
                                padding-right: 25px;
                        }
                        
                        .methodTable
                        {
                                padding-bottom: 50px;
                                width: 100%;
                        }
                        
                        .methodHeading
                        {
                                background: #eeeeee;
                                text-align: left;
                        }
                        
                        .methodTable td
                        {
                                padding-left: 5px;
                                
                        }
                        
                        .tableLabel
                        {
                                width: 287px;
                        }";
                fwrite($open, $css);
                fclose($open);
        }
        
                
        private function addInfo($strAdd)
        {
                $this->documentationFile .= $strAdd;
                
                return true;
        }
        
        
        private function writeToDocument()
        {
                if (!is_dir($this->path."documents/")) 
                {
                        mkdir($this->path."documents/");         
                }     
                           
                $open = fopen($this->path."documents/".$this->fileName."-----document.html", "w");
                fwrite($open, $this->documentationFile);
                fclose($open);
        }
        
        
        
        
        public function buildClassFileInfo()
        {         
                $intCount = 0;     
                foreach($this->contents as $indLine)
                {
                        if(($indLine == '/*') && ((strpos($this->contents[$intCount+1], 'Class')===false) || (strpos($this->contents[$intCount+1], 'File')===false)))
                        {
                        
                               
                               $classorfile = substr($this->contents[$intCount+2], strpos($this->contents[$intCount+2], ":") + 1);
                               $version = substr($this->contents[$intCount+3], strpos($this->contents[$intCount+3], ":") + 1);
                               $release = substr($this->contents[$intCount+4], strpos($this->contents[$intCount+4], ":") + 1);
                               
                               $overview = trim(substr($this->contents[$intCount+6], strpos($this->contents[$intCount+6], ":") + 1));
                               $date = substr($this->contents[$intCount+9], strpos($this->contents[$intCount+9], ":") + 1);
                               
                               
                               $this->addInfo("<div id='heading'>".$classorfile." Overview</div>");
                               $this->addInfo("<div class='separator'></div>");
                               
                               $this->addInfo("<div>".$overview."</div>");
                               
                               $this->addInfo("<div class='bold'>Version: </div><div class='floatl'>".$version."</div>");
                               
                               $this->addInfo("<div class='clear'></div>");
                               
                               
                               $this->addInfo("<div class='bold'>History: </div><div class='floatl'></div>");                              
                               
                               $this->addInfo("<div class='clear'></div>");
                               
                               $dateOffset = $intCount+9;
                               
                               
                               $this->addInfo("<table class='history'>");
                               while(is_numeric($this->contents[$dateOffset][0]))
                               {
                                        $arrHistory = explode(' ',trim($this->contents[$dateOffset]));
                                        
                                        $intCounter = 0;
                                        foreach($arrHistory as $arrIndHistory)
                                        {
                                                if(trim($arrIndHistory) == null)
                                                {
                                                        unset($arrHistory[$intCounter]);
                                                }
                                                
                                                $intCounter++;
                                        }
                                        $arrHistory = array_values($arrHistory);
                                        
                                        
                                        $this->addInfo("<tr><td>".$arrHistory[0]."</td><td>".$arrHistory[1]."</td>");
                                        $this->addInfo("<td><strong>".$arrHistory[2]."</strong></td>");
                                        $this->addInfo("<td><em>");
                                        
                                        for($intCount=3;$intCount<=count($arrHistory)-1;$intCount++)
                                        {
                                                $this->addInfo($arrHistory[$intCount]." ");
                                        }
                                        
                                        $this->addInfo("</em></td></tr>");
                                        
                                        $dateOffset++;
                               }
                               $this->addInfo("</table>");
                               
                               $this->addInfo("<div class='clear'></div>");
                               
                               $this->addInfo("<div class='separator'></div>");
                               
                               break;
                        } 
                        
                        $intCount++; 
                }
        }
        
        
        
        public function buildMethodDocs()
        {
                $intCount = 0;  
                
                foreach($this->contents as $indLine)
                {
                        if((strpos($indLine, '/*-----')!==false) && (strpos($this->contents[$intCount+1], 'Function')!==false))
                        {
                                $this->addInfo("<table class='methodTable'>");
                                
                                $this->addInfo("<tr><th class='methodHeading' colspan='2'>Method Summary</th></tr>");
                                $intCounterTwo = $intCount;
                                
                                while(strpos($this->contents[$intCounterTwo], '--------*/') === false)
                                {
                                
                                        if(strpos($this->contents[$intCounterTwo], '/*-----') === false)
                                        {
                                                $label = trim(strstr($this->contents[$intCounterTwo], ':', true));
                                                $content = trim(str_replace(':','',trim(strstr($this->contents[$intCounterTwo], ':'))));
                                                
                                                if(($label !== '') && ($content == ''))
                                                {
                                                        $label = '';
                                                }                                                
                                                
                                                if(empty($content) && (!empty($this->contents[$intCounterTwo])))
                                                {
                                                        if(strpos($this->contents[$intCounterTwo], 'In')===false)
                                                        {
                                                                if(trim($this->contents[$intCounterTwo])!='')
                                                                {
                                                                        $content =  trim($this->contents[$intCounterTwo]);
                                                                }
                                                        }   
                                                }
                                                
                                                $bAdded = false;
                                                $intCounterThree = $intCounterTwo;
                                                
                                                
                                                //if label says in and label after is nothing but content exists....
                                                if(($label == 'In'))
                                                {
                                                        while((strpos($this->contents[$intCounterThree], '---') === false) && (strpos($this->contents[$intCounterThree], 'Out') === false))
                                                        {
                                                                $strContent = trim(str_replace("In:",'',$this->contents[$intCounterThree]));
                                                                $arrContent = explode(' ',$strContent);
                                                                
                                                                $intCounterFour = 0;
                                                                foreach($arrContent as $arrIndContent)
                                                                {
                                                                        if(trim($arrIndContent) == null)
                                                                        {
                                                                                unset($arrContent[$intCounterFour]);
                                                                        }
                                                                        
                                                                        $intCounterFour++;
                                                                }
                                                                $arrContent = array_values($arrContent);
                                                                
                                                                //print_r($arrContent);
                                                                
                                                                if(sizeof($arrContent)>0)
                                                                {
                                                                        $strParam = "<span class='variable'>".$arrContent[0]."</span>";
                                                                        $strParam .= "<span class='variableType'>".$arrContent[1]."</span>";
                                                                        
                                                                        $strParam .= "<span class='paramDesc'>";
                                                                                for($i=2;$i<=count($arrContent)-1;$i++)
                                                                                {
                                                                                        $strParam .= $arrContent[$i]." ";
                                                                                }
                                                                        $strParam .= "</span>";
                                                                        
                                                                        //echo $strParam."<br>";
                                                                        
                                                                        $bAdded = true;
                                                                        $content = $strParam;
                                                                        
                                                                        $isLabel = false;
                                                                        $setLabel = '';
                                                                        if($intCounterThree >0)
                                                                        {
                                                                                $isLabel = true;
                                                                        }
                                                                        if($isLabel == true)
                                                                        {
                                                                                $setLabel = $label;
                                                                        }
                                                                        
                                                                        if(isset($this->contents[$intCounterThree]))
                                                                        {
                                                                                unset($this->contents[$intCounterThree]);
                                                                        }
                                                                        $this->addInfo("<tr><td class='tableLabel'>".$setLabel."</td><td>".$content."</td>\n");
                                                                        
                                                                        $label = '';
                                                                        $content = '';
                                                                        
                                                                         
                                                                }
                                                                
                                                                $intCounterThree++;
                                                        }
                                                }
                                                
                                                
                                                $intCounterThree = $intCounterTwo;
                                                if($label == 'Out')
                                                {
                                                        $strContent = trim(str_replace("Out:",'',$this->contents[$intCounterThree]));
                                                        $arrContent = explode(' ',$strContent);
                                                        
                                                        
                                                        $intCounterFour = 0;
                                                        foreach($arrContent as $arrIndContent)
                                                        {
                                                                if(trim($arrIndContent) == null)
                                                                {
                                                                        unset($arrContent[$intCounterFour]);
                                                                }
                                                                
                                                                $intCounterFour++;
                                                        }
                                                        $arrContent = array_values($arrContent);
                                                                
                                                        if(sizeof($arrContent)>0)
                                                        {
                                                                $strParam = "<span class='variable'>".$arrContent[0]."</span>";
                                                                $strParam .= "<span class='variableType'>".$arrContent[1]."</span>";
                                                                
                                                                $strParam .= "<span class='paramDesc'>";
                                                                        for($i=2;$i<=count($arrContent)-1;$i++)
                                                                        {
                                                                                $strParam .= $arrContent[$i]." ";
                                                                        }
                                                                $strParam .= "</span>";
                                                                
                                                                //echo $strParam."<br>";
                                                                
                                                                $bAdded = true;
                                                                $content = $strParam;
                                                                
                                                                $isLabel = false;
                                                                $setLabel = '';
                                                                if($intCounterThree >0)
                                                                {
                                                                        $isLabel = true;
                                                                }
                                                                if($isLabel == true)
                                                                {
                                                                        $setLabel = $label;
                                                                }
                                                                
                                                                if(isset($this->contents[$intCounterThree]))
                                                                {
                                                                        unset($this->contents[$intCounterThree]);
                                                                }
                                                                $this->addInfo("<tr><td class='tableLabel'>".$setLabel."</td><td>".$content."</td>\n");
                                                                
                                                                $label = '';
                                                                $content = '';
                                                                
                                                                 
                                                        }
                                                        
                                                        $intCounterThree++;
                                                        
                                                }
                                                
                                                
                                                
                                                $this->addInfo("<tr><td class='tableLabel'>".$label."</td><td>".$content."</td>\n");
                                                
                                                
                                                        
                                                
                                                
                                        }
                                        $intCounterTwo++; 
                                         
                                }
                                
                                $this->addInfo("</table>\n\n");
                        }
                        
                        $intCount++;    
                }
        }
        
        
        public function setPath($strPath)
        {
                $this->path = $strPath;
                
                return true;
        }
        
        public function setFileName($strFileName)
        {
                $this->fileName = $strFileName;
                
                return true;
        }
        
        
        public function createDocument()
        {
                $this->createCSS();
                $this->buildFileHeader();
                $this->buildClassFileInfo();
                $this->buildMethodDocs();
                $this->buildFileFooter();
                
                
                $this->writeToDocument();
        }

}
?>