<?php

Defined("BASEPATH") or exit('No direct script access allowed');

class M_pedido extends CI_Model
{

  public function inserir($usuario, $prazo, $observacao, $produtos)
  {

    $this->load->model('m_usuario');

    $ret_usuario = $this->m_usuario->verificaUsuario($usuario);

    if ($ret_usuario['codigo'] == 8 || $ret_usuario['codigo'] == 9) {
      $dados = $ret_usuario;
    } else {
      $num_pedido = $this->numPedido();

      $sqlconcat = '';

      $sql = "insert into pedido_cab (num_pedido, usucria, prazo, observacao)
              values ($num_pedido, '$usuario', '$prazo', '$observacao')";

      $this->db->query($sql);

      if ($this->db->affected_rows() > 0) {
        $this->load->model('m_log');

        $retorno_log = $this->m_log->inserir_log($usuario, $sql);

        foreach ($produtos as $linhas => $value) {

          $cod_produto = $produtos[$linhas]->codigo;
          $qtde = $produtos[$linhas]->qtde;
          $sql2 = "insert into pedido_det (num_pedido, usucria, cod_produto, qtde)
                  values ($num_pedido, '$usuario', $cod_produto, $qtde)";

          $this->db->query($sql2);

          $sqlconcat = $sql2 . ";" . $sqlconcat;
        }

        $retorno_log = $this->m_log->inserir_log($usuario, $sqlconcat);



        if ($retorno_log['codigo'] == 1) {
          $dados = array(
            'codigo' => 1,
            'msg' => 'Pedido cadastrado corretamente'
          );
        } else {
          $dados = array(
            'codigo' => 8,
            'msg' => 'Houve algum problema no salvamento do log, porém , pedido cadastrado corretamente'
          );
        }
      } else {
        $dados = array(
          'codigo' => 7,
          'msg' => 'Houve algum problema na inserção na tabela de pedidos'
        );
      }
    }

    return $dados;
  }


  private function numPedido()
  {
    $sql = "select coalesce(max(num_pedido) + 1, 1) num_pedido from pedido_cab";

    $retorno = $this->db->query($sql);

    return $retorno->row()->num_pedido;
  }
}