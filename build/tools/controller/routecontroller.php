<?php

class RouteController extends Router {
	protected $routes;
	private $positions = [
		''
	];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];
	}

	public function index() {
		echo 'tools<br>';
	}
}