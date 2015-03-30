<?php

namespace Phpmvc\paginering;
/**
* Class to log what happens.
*
* @package LydiaCore
*/
class CPagineringNameUrl extends CQueryString {

        private $hits, $page, $maxx, $minn;

    function __construct() {

    }

    public function setTotalRows($hits, $page, $max){
    // How many rows to display per page.
    $this->hits = $hits;
    // Which is the current page to display, use this to calculate the offset value
    $this->page = $page;
    // Max pages in the table: SELECT COUNT(id) AS rows FROM VMovie
    $this->maxx = ceil($max/$hits);
    // Startpage, usually 0 or 1, what you feel is convienient
    $this->minn = 1;

    }
/**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @return string as a link to this page.
 */
    private function getHitsPerPage($hits, $hitsName) {
      $nav = "<div class='hits'>";
      foreach($hits AS $val) {
          if($val == $this->hits){
        $nav .= "<label class='hitsActive'><a class='tag' href='" . $this->getQueryString(array($hitsName => $val)) . "'>$val</a></label>";
          }
          else{
          $nav .= "<label class='hits'><a class='pageNav' href='" . $this->getQueryString(array($hitsName => $val)) . "'>$val</a></label>";
          }
      }
        $nav .= "</div>";
      return $nav;
    }
    //$getHitsPerPage = getHitsPerPage(array(2, 4, 8));
/**
 * Create navigation among pages.
 *
 * @param integer $hits per page.
 * @param integer $page current page.
 * @param integer $max number of pages.
 * @param integer $min is the first page number, usually 0 or 1.
 * @return string as a link to this page.
 */
    private function getPageNavigation($hits, $page, $max, $min=1, $pageName) {
      $nav  = "<div class='page'><label class='page'><a class='tag' href='" . $this->getQueryString(array($pageName => $min)) . "'>&#8676;</a></label> ";
      $nav .= "<label class='page'><a class='tag' href='" . $this->getQueryString(array($pageName => ($page > $min ? $page - 1 : $min) )) . "' >&#10096;</a> </label>";

      for($i=$min; $i<=$max; $i++) {
        if($i == $page){
            $nav .= "<label class='pageActive'>".$i."</label>";
        }
        else{
            $nav .= "<label class='page'><a class='tag' href='" . $this->getQueryString(array($pageName => $i)) . "' class='pageNav' >$i</a></label> ";
        }
      }

      $nav .= "<label class='page'><a class='tag' href='" . $this->getQueryString(array($pageName => ($page < $max ? $page + 1 : $max) )) . "' class='pageNav'>&#10097;</a></label> ";
      $nav .= "<label class='page'><a class='tag' href='" . $this->getQueryString(array($pageName => $max)) . "' class='pageNav' >&#x21e5;</a></label></div>";
      return $nav;
    }



    public function GetPageNav($pageName){

        return $getPageNavigation = $this->getPageNavigation($this->hits, $this->page, $this->maxx, $this->minn, $pageName);

    }



    public function GetNbrOfHitsPerPage($hitsName){
        return $getHitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $hitsName);
    }
}
