<?php

class Paginator {
	use trait_extension;

	public function __construct() {
		spl_autoload_register(['Paginator', 'load_extension_class']);
	}

	public function get_paging($params) {
		$default = [
			'link' => '/',
			'first_link' => null,
			'total' => 1,
			'per_page' => 1,
			'current' => 1,
			'show_links' => 5,
			'id' => 'paging',
			'class' => 'pagination'
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
		$smarty->assign('link', $link);
		$smarty->assign('id', $id);
		$smarty->assign('class', $class);
		$smarty->assign('first_link', $first_link);
		$smarty->assign('current', $current);
		$smarty->assign('pages_count', $pages);
		return $smarty->getTemplate('paging.tpl.html');
	}
} 