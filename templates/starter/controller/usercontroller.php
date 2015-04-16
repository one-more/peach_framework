<?php

class UserController {
	use trait_controller;

	public function is_token_valid() {
		$client_token = Request::get_var('token');
		$str_to_hash = Request::get_var('user', null, '')
			.Request::get_var('pfm_session_id', null, '')
			.$_SERVER['HTTP_USER_AGENT'];
		return md5($str_to_hash) == $client_token;
	}
}