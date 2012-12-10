<?php

namespace Rebolon\Component\Pager;

/**
 * Pager converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
interface PagerConfigInterface
{   
   /**
    * @param int $suffixName
    */
   public function setSuffixName($suffixName);
   
   /**
    * @param int $itemPerPage
    */
   public function setItemPerPage($itemPerPage);

   /**
    * @param int $maxPagerItem
    */
   public function setMaxPagerItem($maxPagerItem);
}