<?php

function is_authenticated() {
	return isset($_SESSION['u']) && $_SESSION['u'] !== null;
}

function auth_check($login_url='/auth/login') {	
	//TODO: Transformar num middleware
	if (!is_authenticated()) {
		http_redirect($login_url);
		die;
	}
}

function auth_logout($redirect_url=null) {
	//TODO: Entrypoint
	unset($_SESSION['u']);
	http_redirect($redirect_url);
}
