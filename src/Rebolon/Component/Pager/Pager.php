<?php

namespace Rebolon\Component\Pager;

use Rebolon\Component\Pager\PagerAbstract;
use Rebolon\Component\Pager\PagerConfigInterface;

/**
 * Pager converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
class Pager 
    extends PagerAbstract 
    implements PagerConfigInterface
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $_container;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $_request;

    /**
     * @see \Rebolon\Component\Pager\PagerConfigInterface::setSuffixName()
     */
    public function setSuffixName($suffixName)
    {
        $this->_suffixName = !is_null($suffixName) ? $suffixName : $this->_suffixName;
        return $this;
    }

    /**
     * @see \Rebolon\Component\Pager\PagerConfigInterface::setItemPerPage()
     */
    public function setItemPerPage($itemPerPage)
    {
        $this->_itemPerPage = !is_null($itemPerPage) ? (int) $itemPerPage : $this->_itemPerPage;
        return $this;
    }

    /**
     * @see \Rebolon\Component\Pager\PagerConfigInterface::setMaxPagerItem()
     */
    public function setMaxPagerItem($maxPagerItem)
    {
        $this->_maxPagerItem = !is_null($maxPagerItem) ? (int) $maxPagerItem : $this->_maxPagerItem;
        return $this;
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getCurPageParam()
     */
    protected function getCurrentPageParam()
    {
        return (is_array( $_REQUEST ) 
            && array_key_exists($this->getSuffixNameGoToPage(), $_REQUEST) 
            && $_REQUEST[$this->getSuffixNameGoToPage()] >= 0 
            ? (int)$_REQUEST[$this->getSuffixNameGoToPage()] : 0);
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getItemPerPageParam()
     */
    public function getItemPerPageParam()
    {
        $tmp = array_key_exists($this->getSuffixNameItemPerPage(), $_REQUEST) ? (int)$_REQUEST[$this->getSuffixNameItemPerPage()] : $this->_itemPerPage;
        return $tmp > 0 ? $tmp : $this->_itemPerPage;
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getMaxPagerItemParam()
     */
    protected function getMaxPagerItemParam($totalPage)
    {
        $tmp = array_key_exists($this->getSuffixNameMaxPagerItem(), $_REQUEST) ? $_REQUEST[$this->getSuffixNameMaxPagerItem()] : $this->_maxPagerItem;
        return $tmp > $totalPage ? $totalPage : $tmp;
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getCurrentURL()
     */
    protected function getCurrentURL()
    {
        return (array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '/');
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getGoToPageURL()
     */
    protected function getGoToPageURL($page)
    {
        return $this->getCurrentURL() . '?' . $this->addParamToQueryString($this->getSuffixNameGoToPage(), $page);
    }
    
    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::addParamToQueryString()
     */
    protected function addParamToQueryString($keyGoToPage, $valueGoToPage) {
        $found = false;
        $qs = array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : '';
        $qsPart = explode('&', $qs);
        $newQsPart = array();

        foreach ($qsPart as $qsVal) {
            if ($qsVal) {
                list($key, $val) = explode('=', $qsVal);
                if ($key === $keyGoToPage) {
                    if ($found === false) {
                        $val = $valueGoToPage;
                        $found = true;
                    } else {
                        unset($key, $val);
                    }
                }

                if (isset($key)
                        && isset($val)) {
                    array_push($newQsPart, $key . '=' . $val);
                }
                unset($key, $val);
            }
        }

        return implode('&', $newQsPart);
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getTitleFirstPage()
     */
    protected function getTitleFirstPage()
    {
        return 'firstItem';
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getTitlePreviousPage()
     */
    protected function getTitlePreviousPage()
    {
        return 'previousItem';
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getTitleNextPage()
     */
    protected function getTitleNextPage()
    {
        return 'nextItem';
    }

    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getTitleLastPage()
     */
    protected function getTitleLastPage()
    {
        return 'lastItem';
    }

}