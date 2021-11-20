<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_usuario extends CI_Model
{

  public function inserir($usuario, $senha, $nome, $tipo_usuario, $usu_sistema)
  {
	$sql = "insert into usuarios (usuario, senha, nome, tipo) values ('$usuario', md5('$senha'), '$nome', '$tipo_usuario')";
	
    $this->db->query($sql);

    if ($this->db->affected_rows() > 0) {
		$this->load->model('m_log');

		$retorno_log = $this->m_log->inserir_log($usu_sistema, $sql);

		if($retorno_log['codigo'] == 1) {
			$dados = array(
				'codigo' => 1,
				'msg' => 'Usuário cadastrado corretamente'
			);
		} else {
			$dados = array(
				'codigo' => 8,
				'msg' => 'Houve algum problema no salvamento do log, porém o usuário foi cadastrado corretamente'
			);
		}
      $dados = array(
        'codigo' => 1,
        'msg' => 'Usuário cadastrado corretamente'
      );
    } else {
      $dados = array(
        'codigo' => 6,
        'msg' => 'Houve algum problema na inserção na tabela de usuários'
      );
    }

    return $dados;
  }

	public function consultar($usuario, $nome, $tipo_usuario) {
		$sql = "select * from usuarios where estatus = ''";

		if($usuario != '') {
			$sql = $sql . "and usuario = '$usuario' ";
		}

		if($tipo_usuario != '') {
			$sql = $sql . "and tipo = '$tipo_usuario' ";
		}

		if($nome != '') {
			$sql = $sql . "and nome like '%$nome%'";
		}

		$retorno = $this->db->query($sql);

		if($retorno->num_rows() > 0) {
			$dados = array(
				'codigo' => 1,
				'msg' => 'Consulta efetuada com sucesso',
				'dados' => $retorno->result()
			);
		} else {
			$dados = array(
				'codigo' => 6,
				'msg' => 'Dados não encontrados'
			);
		}

		return $dados;
	}
	public function alterar($usuario, $nome, $senha, $tipo_usuario){
		$sql = "update usuarios set ";

		if($nome != '') {
			$sql .= "nome = '$nome', ";
		}

		if($senha != '') {
			$sql .= "senha = md5('$senha'), ";
		}

		if($tipo_usuario != '') {
			$sql .= "tipo = '$tipo_usuario', ";
		}

		$sql .= "where usuario = '$usuario';";
		$sql = str_replace(', where', ' where', $sql);

		if($nome || $senha || $tipo_usuario) {
			$this->db->query($sql);
		}

		if($this->db->affected_rows() > 0){
			$dados = array(
				'codigo' => 1,
				'msg' => 'Usuário atualizado corretamente'
			);
		}else{
		  	$dados = array(
				'codigo' => 6,
				'msg' => 'Houve algum problema na atualização na tabela de usuários'
			);
		}

		return $dados;
	}
	public function desativar($usuario) {
		$usuario_desativado = $this->db->query("select usuario from usuarios where estatus = 'D' and usuario = '$usuario'");
		if($usuario_desativado->num_rows() > 0) {
			return array(
				'codigo' => 2,
				'msg' => 'O usuário já está desativado'
			);
		}

		$this->db->query("update usuarios set estatus = 'D'
			where usuario = '$usuario'");

		if($this->db->affected_rows() > 0) {
			$dados = array(
				'codigo' => 1,
				'msg' => 'Usuário DESATIVADO corretamente'
			);
		} else {
			$dados = array(
				'codigo' => 6,
				'msg' => 'House algum problema na DESATIVAÇÃO do usuário'
			);
		}

		return $dados;
	}


	public function verificaUsuario($usuario){

		$sql = "select * from usuarios where usuario = '$usuario'";

		$retorno= $this->db->query($sql);

		if($retorno->num_rows() > 0){
			if($retorno->row()->estatus == 'D'){
				$dados = array('codigo' => 8,
											'msg' => 'Não pode cadastrar, usuário informado está DESATIVADO');
			}else{
				$dados = array('codigo' => 1,
											'msg' => 'Usuário ativo na base de dados');
			}
		}else{
			$dados = array('codigo' => 9,
										'msg' => 'usuário não encontrado na base de dados');
		}

		return $dados;

	}





}