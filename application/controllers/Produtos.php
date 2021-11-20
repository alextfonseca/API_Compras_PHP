<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller{

  public function inserir(){

    $json = file_get_contents('php://input');
    $resultado = json_decode($json);

    $descricao  = $resultado->descricao;
    $unid_medida  = $resultado->unid_medida;
    $estoque_minimo  = $resultado->estoque_minimo;
    $estoque_maximo  = $resultado->estoque_maximo;
    $usuario  = $resultado->usuario;

    if(trim($descricao) == ''){
      $retorno = array('codigo' => 2,
                      'msg' => 'Descrição não informada');
    }elseif ($unid_medida == 0){
      $retorno = array('codigo' => 3,
                      'msg' => 'Unidade de medida não informada');
    }elseif($estoque_minimo == 0 && $estoque_minimo == '') {
      $retorno = array('codigo' => 4,
                      'msg' => 'Estoque minimo não informada');
    }elseif($estoque_maximo == 0 && $estoque_maximo == '') {
      $retorno = array('codigo' => 5,
                      'msg' => 'Estoque maximo não informada');
    }elseif($usuario == ''){
      $retorno = array('codigo' => 6,
                      'msg' => 'usuário não informado');
    }else{
      $this->load->model('m_produto');

      $retorno = $this->m_produto->inserir($descricao, $unid_medida, $estoque_minimo, $estoque_maximo, $usuario);
    }

    echo json_encode($retorno);
  }


  public function consultar(){
    $json = file_get_contents('php://input');
    $resultado = json_decode($json);

    $codigo = $resultado->codigo;
    $descricao = $resultado->descricao;
    $unid_medida = $resultado->unid_medida;

    $this->load->model('m_produto');

    $retorno = $this->m_produto->consultar($codigo, $descricao,$unid_medida);

    echo json_encode($retorno);

  }

  public function alterar(){


    $json = file_get_contents('php://input');
    $resultado = json_decode($json);

    $codigo = $resultado->codigo;
    $descricao = $resultado->descricao;
    $unid_medida = $resultado->unid_medida;
    $estoque_minimo = $resultado->estoque_minimo;
    $estoque_maximo = $resultado->estoque_maximo;
    $usuario = $resultado->usuario;

    if(trim($codigo) == '' || trim($codigo) == 0){
      $retorno = array('codigo' => 2,
                      'msg' => 'Codigo produto não informado');
    }elseif(trim($descricao) == '' && $unid_medida == 0 && $estoque_minimo == 0 && $estoque_maximo == 0){
      $retorno = array('codigo' => 3,
                      'msg' => 'Nenhum campo possui alteração, informe pelo menos 1');
    }elseif(trim($usuario) == ''){
      $retorno = array('codigo' => 4,
                      'msg' => 'Usuario não informado');
    }else{
      $this->load->model('m_produto');

      $retorno = $this->m_produto->alterar($codigo, $descricao, $unid_medida, $estoque_minimo, $estoque_maximo, $usuario);
    }

    echo json_encode(($retorno));
  }

  public function desativar(){

    $json = file_get_contents('php://input');
    $resultado = json_decode($json);

    $codigo = $resultado->codigo;
    $usuario = $resultado->usuario;

    if(trim($codigo == '') || trim($codigo == 0)){
      $retorno = array('codigo' => 2,
                      'msg' => 'Código do produto não informado');
    }elseif(trim($usuario) == ''){
      $retorno = array('codigo' => 3,
                      'msg' => 'Usuário não informado');
    }else{
      $this->load->model('m_produto');

      $retorno = $this->m_produto->desativar($codigo, $usuario);
    }

    echo json_encode($retorno);


  }


}


  ?>