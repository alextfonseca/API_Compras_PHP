<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos extends CI_Controller
{

  public function inserir()
  {

    $json = file_get_contents('php://input');
    $resultado = json_decode($json);

    $valid_produto = 0;

    $usuario = $resultado->usuario;
    $prazo = $resultado->prazo;
    $observacao = $resultado->observacao;
    $produtos = $resultado->produtos;

    if ($usuario == '') {
      $retorno = array(
        'codigo' => 2,
        'msg' => 'Usuário não informado'
      );
    } elseif ($prazo == '') {
      $retorno = array(
        'codigo' => 3,
        'msg' => 'Prazo não informado'
      );
    } elseif (sizeOf($produtos) == 0) {
      $retorno = array(
        'codigo' => 4,
        'msg' => 'Produto(s) não informado(s)'
      );
    } else {

      foreach ($produtos as $linha => $value) {

        if ($produtos[$linha]->codigo == 0) {
          $retorno = array(
            'codigo', 5,
            'msg' => 'Produto não informado'
          );
          $valid_produto = 1;
          break;
        }

        if ($produtos[$linha]->qtde == 0) {
          $retorno = array(
            'codigo' => 6,
            'msg' => 'Quantidade zerada'
          );
          $valid_produto = 1;
          break;
        }
      }

      if ($valid_produto == 0) {
        $this->load->model('m_pedido');

        $retorno = $this->m_pedido->inserir($usuario, $prazo, $observacao, $produtos);
      }
    }

    echo json_encode($retorno);
  }
}