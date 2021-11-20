<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_unidmedida extends CI_Model
{
	public function inserir($sigla, $descricao, $usuario)
	{

		$ret_usuario = $this->verificaUsuario($usuario);

		if($ret_usuario['codigo'] == 8 || $ret_usuario['codigo'] == 9){
			$dados = $ret_usuario;
		}else{





		$sql = "insert into unid_medida (sigla, descricao, usucria)
				values ('$sigla', '$descricao', '$usuario')";

				
		$this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
			$this->load->model('m_log');

			$retorno_log = $this->m_log->inserir_log($usuario, $sql);

			if ($retorno_log['codigo'] == 1) {
				$dados = array(
					'codigo' => 1,
					'msg' => 'Unidade de medida cadastrada corretamente'
				);
			} else {
				$dados = array(
					'codigo' => 7,
					'msg' => 'Houve algum problema no salvamento do log, porém, Unidade de Medida cadastrada corretamente'
				);
			}
		} else {
			$dados = array(
				'codigo' => 6,
				'msg' => 'Houve algum problema na inserção na tabela de unidade de medida'
			);
		}

	}

		return $dados;
	}

	public function consultar($codigo, $sigla, $descricao)
	{
		$sql = "select * from unid_medida where estatus = '' ";

		if ($codigo != '' && $codigo != 0) {
			$sql = $sql . "and cod_unidade = '$codigo'";
		}

		if ($sigla != '') {
			$sql = $sql . "and sigla = '%sigla'";
		}


		if ($descricao != '') {
			$sql = $sql . "and descricao like '%$descricao%'";
		}


		$retorno = $this->db->query($sql);

		if ($retorno->num_rows() > 0) {
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

	public function alterar($codigo, $sigla, $descricao, $usuario)
	{
		if (trim($sigla) != '' && trim($descricao) != '') {
			$sql = "update unid_medida set sigla = '$sigla', descricao = '$descricao' where cod_unidade = $codigo";
		} elseif (trim($sigla) != '') {
			$sql = "update unid_medida set sigla = '$sigla' where cod_unidade = $codigo";
		} else {
			$sql = "update unid_medida set descricao = '$descricao' where cod_unidade = $codigo";
		}
		$this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
			$this->load->model('M_log');
			$retorno_log = $this->M_log->inserir_log($usuario, $sql);

			if ($retorno_log['codigo'] == 1) {
				$dados = array(
					'codigo' => 1,
					'msg' => 'Unidade de medida atualizada corretamente'
				);
			} else {
				$dados = array(
					'codigo' => 7,
					'msg' => 'Houve algum problema no salvamento do Log, porem, unidade de medida cadastrada corretamente'
				);
			}
		} else {
			$dados = array(
				'codigo' => 6,
				'msg' => 'Houve algum problema na atualizacao na tabela de unidade de medida'
			);
		}
		return $dados;
	}
	public function desativar($codigo, $usuario)
	{
		$sql = "select * from produtos where unid_medida = $codigo and estatus = '' ";
		$retorno = $this->db->query($sql);

		if ($retorno->num_rows() > 0) {
			$dados = array(
				'codigo' => 3,
				'msg' => 'Não podemos desativar, existem produtos com essa unidade de medida cadastrados'
			);
		} else {
			$sql2 = "update unid_medida set estatus = 'D' where cod_unidade = $codigo";
			$this->db->query($sql2);

			if ($this->db->affected_rows() > 0) {
				$this->load->model('M_log');
				$retorno_log = $this->M_log->inserir_log($usuario, $sql2);

				if ($retorno_log['codigo'] == 1) {
					$dados = array(
						'codigo' => 1,
						'msg' => 'Unidade de medida DESATIVADA corretamente'
					);
				} else {
					$dados = array(
						'codigo' => 7,
						'msg' => 'Houve algum problema na DESATIVACAO da unidade de medida'
					);
				}
			}
			return $dados;
		}
	}

	public function verificaUM($cod_unidmedida)
	{

		$sql = "select * from unid_medida where cod_unidade = $cod_unidmedida and estatus = ''";

		$retorno = $this->db->query($sql);

		if ($retorno->num_rows() > 0) {
			$dados = array(
				'codigo' => 1,
				'msg' => 'Unidade de medida válida'
			);
		} else {
			$dados = array(
				'codigo' => 2,
				'msg' => 'Unidade de medida não cadastrada'
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