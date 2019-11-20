<?php

namespace App\Models;

use MF\Model\Model;

class vus extends Model {
	private $id;
	private $id_usuario;
	private $vus;
	private $data;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//salva o vus (tweet)
	public function salvar() {

		$query = "insert into vus(id_usuario, vus)values(:id_usuario, :vus)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':vus', $this->__get('vus'));
		$stmt->execute();

		return $this; //retorna o próprio vus (tweet)
	}

	
	//remove o vus (tweet)
	public function remover() {

		$query = "delete from vus where(id_usuario, vus)values(:id_usuario, :vus)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':vus', $this->__get('vus'));
		$stmt->execute();

		//return $this; //retorna o próprio vus (tweet)
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
	}

	//recuperar o vus (tweet) -> listar
	public function getAll() {

		$query = "
			select 
				t.id, 
				t.id_usuario, 
				u.nome, 
				t.vus, 
				DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
			from 
				vus as t
				left join usuarios as u on (t.id_usuario = u.id)
			where 
				t.id_usuario = :id_usuario
				or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
			order by
				t.data desc 
		";
		//t.data desc -> ordena os vus (tweets) de forma decrescente

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		//retorna um array associativo com os vus (tweets)
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}