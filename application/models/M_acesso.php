<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_acesso extends CI_Model
{

  public function validalogin($usuario, $senha)
  {
    $usuario = $this->db->query("select * from usuarios where usuario = '$usuario' limit 1");

			// and senha = md5('$senha')
			// and estatus = ''");

    if ($usuario->num_rows() <= 0) {
      $dados = array('codigo' => 2, 'msg' => 'Usuário não cadastrado');
    } else {
			$resultado = $usuario->result()[0];

			if($resultado->senha != md5($senha)) {
				$dados = array('codigo' => 3, 'msg' => 'A senha informada está incorreta');
			} elseif($resultado->estatus == 'D') {
				$dados = array('codigo' => 4, 'msg' => 'Usuário desabilitado para acesso');
			} else {
				$dados = array('codigo' => 4, 'msg' => 'Usuário correto');
			}
    }

    return $dados;
  }
}
