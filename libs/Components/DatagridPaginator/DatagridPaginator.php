<?php

/** 
 * DatagridPagiantor 
 * 
 * This component for the NETTE framework is designed for paged the set of entities generated by the OBO framework (http://www.obophp.org/)
 * 
 * @version 1.1
 * @author Adam Suba, http://www.adamsuba.cz/
 * @copyright 2011 Adam Suba
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */
class DatagridPagiantor extends \Nette\Application\UI\Control implements \obo\Interfaces\IPaginator {
    private $paginator;
    private $datagridSnippetName = null;

    public $page = 1;

    public function __construct(Nette\ComponentModel\IContainer $parent = null, $name = null){
        parent::__construct($parent, $name);
        $this->template->setFile(dirname(__FILE__) . '/DatagridPaginator.latte');
    }

    /**
     * @return \Nette\Utils\Paginator 
     */
    public function getPaginator(){
        if (\is_a($this->paginator, "\Nette\Utils\Paginator")) return $this->paginator;
        return $this->setPaginator(new \Nette\Utils\Paginator());
    }

    public function setPaginator(\Nette\Utils\Paginator $paginator){
        return $this->paginator = $paginator;
    }

    public function getDatagridSnippetName() {
        return $this->datagridSnippetName;
    }

    public function setDatagridSnippetName($datagridSnippetName) {
        $this->datagridSnippetName = $datagridSnippetName;
        return $this;
    }
    
    public function setItemsPerPage($itemsPerPage){
        $this->getPaginator()->setItemsPerPage($itemsPerPage);
        return $this;
    }



    public function setItemCount($itemCount){
        $this->getPaginator()->setItemCount($itemCount);
    }
    
    public function getItemCount(){
        return $this->getPaginator()->getItemCount();
    }
    
    public function getPageCount(){
        return $this->getPaginator()->getPageCount();
    }
    
    public function getItemsPerPage(){
        return $this->getPaginator()->getItemsPerPage();
    }
    
    public function getOffset(){
        return $this->getPaginator()->getOffset();
    }
    
    public function isFirst(){
        return $this->getPaginator()->isFirst();
    }
    
    public function isLast(){
        return $this->getPaginator()->isLast();
    }
    
    
    public function getShownFrom(){
        return $this->getItemCount()? ((($this->page-1) * ($this->getItemsPerPage())) + 1) : 0;
    }
        
    public function getShownTo(){
        return (($this->page-1) *($this->getItemsPerPage()) + ($this->getPaginator()->length));
    }
    
    public function handleGoToPage($page){
        $this->page = $page;
        $this->getPaginator()->page = $page;
        $this->invalidateControl("datagridPaginator");
        $this->presenter->invalidateControl($this->getDatagridSnippetName());
    }

    public function render(){
        $paginator = $this->getPaginator();
        $page = $paginator->page;
        /**
         * This solution is mostly based on Visual paginator control.
         *
         * @copyright  Copyright (c) 2009 David Grudl
         * @author     Adam Suba
         */
        if ($paginator->pageCount < 2) {
            $steps = array($page);
        } else {
            $arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
            $count = 4;
            $quotient = ($paginator->pageCount - 1) / $count;
            for ($i = 0; $i <= $count; $i++) {
                    $arr[] = round($quotient * $i) + $paginator->firstPage;
            }
            sort($arr);
            $steps = array_values(array_unique($arr));
        }

        $this->template->steps = $steps;
        $this->template->paginator = $this;
        $this->template->render();
    }

}