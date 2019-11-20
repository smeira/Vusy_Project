<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {


	public function autenticar() {
		//se o usuário existir
		$usuario = Container::getModel('Usuario');

		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha'])); // compara hash com hash

		$usuario->autenticar();

		if($usuario->__get('id') != '' && $usuario->__get('nome')) {
			
			session_start();

			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');

			// Redireciona automaticamente para a view protegida
			header('Location: /timeline');
			// Redireciona automaticamente para a raiz
		} else {
			header('Location: /?login=erro');
		}

	}

	public function sair() {
		session_start();
		session_destroy();
		header('Location: /');
	}
}