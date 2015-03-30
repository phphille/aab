<?php

namespace Phpmvc\flash;
/**
* Class to log what happens.
*
* @package LydiaCore
*/
class CFlash {

    private $arrayItemsToString;

    function __construct($array) {

         $this->arrayItemsToString = "";

        foreach($array as $item){
            $this->arrayItemsToString .= " ".$item;
        }

    }

   public function message($type, $message){

       if($type == 'error'){
            return $this->error($message);
       }
       else if($type == 'success'){
            return $this->success($message);
       }
       else if($type == 'notice'){
            return $this->notice($message);
       }
   }


    private function error($message){

        return "<div class='error'>".$message."</div>";
    }


    private function success($message){

        return "<div class='success'>".$message."</div>";
    }

    private function notice($message){

        return "<div class='notice'>".$message."</div>";
    }
}
