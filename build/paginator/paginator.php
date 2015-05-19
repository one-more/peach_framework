<?php

class Paginator {
	use trait_extension;

	public function __construct() {
		$this->register_autoload();
	}

	/**
	 * @param $params - array in format
	 * [
	 * 	'btns_links' => range(1,5),
		'total' => 1,
		'per_page' => 1,
		'current' => 1,
		'show_links' => 5,
		'id' => 'paging',
		'class' => 'pagination',
		'link_class' => 'pagination-link'
	 * ]
	 * @return string
	 */
	public function get_paging($params) {
		$default = [
			'btns_links' => range(1,5),
			'total' => 1,
			'per_page' => 1,
			'current' => 1,
			'show_links' => 5,
			'id' => 'paging',
			'class' => 'pagination',
			'link_class' => 'pagination-link'
		];
		$params = array_merge($default, $params);
		extract($params);
		$pages = ceil($total / $per_page);
		$links = $show_links > $pages ? $pages : $show_links;
		if($pages == 1) {
			return '';
		} else {
			if($current == 1) {
				$start = 1;
				$end = $links;
			} elseif ($current == $pages) {
				$start = $pages - ($links-1) > 0 ? $pages - ($links-1) : 1;
				$end = $pages;
			} else {
				$start = $current - floor($links/2);
				if($start < 1) {
					$start = 1;
					$end = $links;
				} else {
					$end = $current + floor($links/2);
					if($end > $pages) {
						$end = $pages;
						$start = $pages - ($links-1);
					}
				}
			}
		}
		$smarty = new Smarty();
		$smarty->setTemplateDir($this->path.DS.'templates');
		$smarty->assign('pages', range($start, $end));
		$smarty->assign('links', $btns_links);
		$smarty->assign('id', $id);
		$smarty->assign('class', $class);
		$smarty->assign('current', $current);
		$smarty->assign('pages_count', $pages);
		$smarty->assign('link_class', $link_class);
		return $smarty->getTemplate('paging.tpl.html');
	}
} 