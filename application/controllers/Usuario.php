<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{

	public function inserir()
	{

		$json = file_get_contents('php://input');
		$resultado = json_decode($json);

		$usuario = $resultado->usuario;
		$senha = $resultado->senha;
		$nome = $resultado->nome;
		$tipo_usuario = strtoupper($resultado->tipo_usuario);

		$usu_sistema = strtoupper($resultado->usu_sistema);

		if (trim($usu_sistema) == '') {
			$retorno = array('codigo' => 7, 'msg' => 'Usuario do sistema não informado');

		} elseif (trim($usuario) == '') {
			$retorno = array('codigo' => 2, 'msg' => 'Usuario não informado');

		} elseif (trim($senha) == '') {
			$retorno = array('codigo' => 3, 'msg' => 'Senha não informada');

		} elseif (trim($nome) == '') {
			$retorno = array('codigo' => 4, 'msg' => 'Nome não informado');

		} elseif ((trim($tipo_usuario) != 'ADMINISTRADOR' && trim($tipo_usuario) != 'COMUM') || trim($tipo_usuario) == '') {
			$retorno = array(
				'codigo' => 5,
				'msg' => 'Tipo de usuário inválido'
			);

		} else {
			$this->load->model('m_usuario');

			$retorno = $this->m_usuario->inserir($usuario, $senha, $nome, $tipo_usuario, $usu_sistema); 
		}

		echo json_encode($retorno);
	}

	public function consultar(){
		$json = file_get_contents('php://input');
		$resultado = json_decode($json);

		$usuario = $resultado->usuario;
		$nome = $resultado->nome;
		$tipo_usuario = trim(strtoupper($resultado->tipo_usuario));

		if($tipo_usuario != 'ADMINISTRADOR' && $tipo_usuario != 'COMUM' && $tipo_usuario != ''){
				$retorno = array('codigo' => 5, 'msg' => 'Tipo de usuário inválido');
		}else{
			$this -> load->model('m_usuario');

			$retorno = $this->m_usuario->consultar($usuario, $nome, $tipo_usuario);
		}

		echo json_encode($retorno);
  }

	public function alterar() {
		$json = file_get_contents('php://input');
		$resultado = json_decode($json);

		$usuario = $resultado->usuario;
		$senha = $resultado->senha;
		$nome = $resultado->nome;
		$tipo_usuario = strtoupper($resultado->tipo_usuario);

		$tipo_usuario = trim($tipo_usuario);
		if($tipo_usuario != '' && ($tipo_usuario != 'ADMINISTRADOR' && $tipo_usuario != 'COMUM')) {

			$retorno = array('codigo' => 5, 'msg' => 'Tipo de usuário inválido');
		} elseif($usuario == '') {

			$retorno = array('codigo' => 2, 'msg' => 'Usuário não informado');
		} else {

			$this->load->model('m_usuario');

			$retorno = $this->m_usuario->alterar($usuario, $nome, $senha, $tipo_usuario);
		}

		echo json_encode($retorno);
	}

	public function desativar() {
		$json = file_get_contents('php://input');
		$resultado = json_decode($json);

		$usuario = $resultado->usuario;

		if(trim($usuario == '')) {
			$resultado = array('codigo' => 2,
						'msg' => 'Usuário não informado');
		} else {
			$this->load->model('m_usuario');

			$retorno = $this->m_usuario->desativar($usuario);
		}

		echo json_encode($retorno);
	}
}