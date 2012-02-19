<?php
/**
 * Storefront_View_Helper_Breadcrumb
 * 
 * Display the category breadcrumb
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class JP_View_Helper_Bread extends Zend_View_Helper_Abstract
{       
    public function bread($product = null)
    {
        if ($this->view->bread) {
            $bread = $this->view->bread;
            if(false===$bread[0]){return false;}
            $crumbs = array();
            $bread = array_reverse($bread);
            if(count($bread)>0){
            $href = $this->view->url(array('controller'=>'kategorijas', 
            'action'=>'category', 'id'=>0)
                );
            $crumbs[] = '<a href="' . $href . '">Sākums</a>';
            }   
            
            foreach ($bread as $category) {
                $href = $this->view->url(array('controller'=>'kategorijas', 
            'action'=>'category', 'id'=>$category->id)
                );
                $crumbs[] = '<a href="' . $href . '">' . $this->view->Escape($category->title) . '</a>';
            }
            
            if (null !== $product) {
                $crumbs[] = $this->view->Escape($product->title);
            }
            
            return join(' &raquo; ', $crumbs);
        }
    }    
}
