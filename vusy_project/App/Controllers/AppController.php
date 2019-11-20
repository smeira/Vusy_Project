<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {


	public function timeline() {

		$this->validaAutenticacao();
			
		//recuperação dos vus
		$vus = Container::getModel('vus');

		$vus->__set('id_usuario', $_SESSION['id']);

		$vus = $vus->getAll();

		$this->view->vus = $vus;

		// instâcia do model usuário
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		//associa diretamente às variáveis da view
		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_vus = $usuario->getTotalVus();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();

		$this->render('timeline');
		
		
	}

	public function vus() {

		//testa se o usuário está logado para fazer o vus
		$this->validaAutenticacao();

		// o Container permite executar o método estático getModel passando o model que se quer trabalhar, no caso vus. E esse método retorna um objeto já com a conexão com o banco configurada
		$vus = Container::getModel('vus');

		$vus->__set('vus', $_POST['vus']); // o texto (vus)
		$vus->__set('id_usuario', $_SESSION['id']); // o usuário

		$vus->salvar(); //salva o registro no banco ( o método está na Model vus)		

		header('Location: /timeline'); //após salvar o vus(tweet) redireciona para a timeline
		
	}

		

	public function validaAutenticacao() {

		session_start();

		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
			header('Location: /?login=erro');
		}	

	}

	public function quemSeguir() {

		$this->validaAutenticacao();

		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

		//obter relação de usuários
		$usuarios = array();

		if($pesquisarPor != '') {
			
			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $pesquisarPor);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();

		}

		$this->view->usuarios = $usuarios;

		$this->render('quemSeguir');
	}	

	// dispara a ação seguir ou deixar de seguir
	public function acao() {

		$this->validaAutenticacao();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		if($acao == 'seguir') {
			$usuario->seguirUsuario($id_usuario_seguindo);

		} else if($acao == 'deixar_de_seguir') {
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);

		}

		header('Location: /quem_seguir');
	}

	public function remover() {

		$this->validaAutenticacao();

		$remover = isset($_GET['remover']) ? $_GET['remover'] : '';
		

		$vus = Container::getModel('vus');
		$vus->__set('id', $_SESSION['id']);
		

		$vus->remover(); //remove o registro no banco ( o método está na Model vus)		

		header('Location: /timeline'); //após remover o vus(tweet) redireciona para a timeline
	}
}

?>