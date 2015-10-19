<?php

class Pagination {
 
	private $_total;
	private $_limit;
	private $_page;
	private $_links;
	
	public function __construct($page, $total, $limit = 20, $links = 5) {		

		$this->_total = $total;
		$this->_limit = $limit;
		$this->_page  = $page;
		$this->_links  = $links;
		return $this;
	}
	
	public function createLinks() {
		 
		$last 	= floor($this->_total / $this->_limit);
 		
		$start 	= (($this->_page - $this->_links) > 0) ? $this->_page - $this->_links : 1;
		$end 	= (($this->_page + $this->_links) < $last) ? $this->_page + $this->_links : $last;
 
		$html 	= '<ul class="pagination">';
 
		
		if($this->_page <= 1)
			$link = '<li class="disabled"><a href="#"><span aria-hidden="true">&laquo;</span></a></li>';
		else
			$link = '<li><a href="?page='.($this->_page - 1).'"><span aria-hidden="true">&laquo;</span></a></li>';
			
		
		$html .= $link;
		
 
		if($start > 1)
		{
			$html .= '<li><a href="?page=1">1</a></li>';
			$html .= '<li class="disabled"><span aria-hidden="true">...</span></li>';
		}
 
		for($i = $start ; $i <= $end; $i++) 
		{
			$class	= ($this->_page == $i) ? "active" : "";
			$html	.= '<li class="' . $class . '"><a href="?page='.$i.'">' . $i . '</a></li>';
		}
 
		if($end < $last)
		{
			$html	.= '<li class="disabled"><span aria-hidden="true">...</span></li>';
			$html   .= '<li><a href="?page='.$last.'">' . $last . '</a></li>';
		}
 
 
	
		if($this->_page >= $last)
			$link = '<li class="disabled"><a href="#"><span aria-hidden="true">&raquo;</span></a></li>';
		else
			$link = '<li><a href="?page='.($this->_page + 1).'"><span aria-hidden="true">&raquo;</span></a></li>';
			
		$html	.= $link . '</ul>';

		return $html;
	}
}