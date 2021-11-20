<?php

Defined("BASEPATH") or exit('No direct script access allowed');

class M_produto extends CI_Model
{

  public function inserir($descricao, $unid_medida, $estoque_minimo, $estoque_maximo, $usuario)
  {

    $this->load->model('m_usuario');

    $ret_usuario = $this->m_usuario->verificaUsuario($usuario);

    if ($ret_usuario['codigo'] == 8 || $ret_usuario['codigo'] == 9) {
      $dados = $ret_usuario;
    } else {

      $this->load->model('m_unidmedida');

      $ret_unidmed = $this->m_unidmedida->verificaUM($unid_medida);

      if ($ret_unidmed['codigo'] == 1) {
        $sql = "insert into produtos (descricao, unid_medida, estoq_minimo, estoq_maximo, usucria) 
        values ('$descricao', '$unid_medida', '$estoque_minimo', '$estoque_maximo', '$usuario')";

        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
          $this->load->model('m_log');

          $retorno_log = $this->m_log->inserir_log($usuario, $sql);


          if ($retorno_log['codigo'] == 1) {
            $dados = array(
              'codigo' => 1,
              'msg' => 'Produto cadastrado corretamente'
            );
          } else {
            $dados = array(
              'codigo' => 8,
              'msg' => 'Houve algum problema no salvamento do log, porém produto cadastrado corretamente'
            );
          }
        } else {
          $dados = array(
            'codigo' => 7,
            'msg' => 'Houve algum problema na inserção na tabela de produtos'
          );
        }
      } else {
        $dados = $ret_unidmed;
      }
    }
    return $dados;
  }

  public function consultar($codigo, $descricao, $unid_medida)
  {


    if ($unid_medida == 0 || trim($unid_medida) == '') {
      $ret_unidmed['codigo'] = 1;
    } else {
      $this->load->model('m_unidmedida');

      $ret_unidmed = $this->m_unidmedida->verificaUM($unid_medida);
    }

    if ($ret_unidmed['codigo'] == 1) {

      $sql = "select * from produtos where estatus = '' ";

      if ($codigo != '' && $codigo != 0) {
        $sql = $sql . "and unid_produto = $codigo";
      }

      if ($unid_medida != '' && $unid_medida != 0) {
        $sql = $sql . "and unid_medida = '$unid_medida'";
      }

      if ($descricao != '') {
        $sql = $sql . "amd descricao like '%$descricao%'";
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
    } else {
      return $ret_unidmed;
    }

    return $dados;
  }


  private function verificaProduto($codigo)
  {

    $sql = "select * from produtos where cod_produto = $codigo";

    $retorno = $this->db->query($sql);

    if ($retorno->num_rows() > 0) {
      if ($retorno->row()->estatus == 'D') {
        $dados = array(
          'codigo' => 4,
          'msg' => 'Produto JÁ DESATIVADO no cadastro'
        );
      } else {
        $dados = array(
          'codigo' => 1,
          'msg' => 'Produto Ativo'
        );
      }
    } else {
      $dados = array(
        'codigo' => 5,
        'msg' => 'Produto não cadastrado'
      );
    }

    return $dados;
  }

  public function alterar($codigo, $descricao, $unid_medida, $estoque_minimo, $estoque_maximo, $usuario)
  {

    if ($unid_medida != 0) {
      $this->load->model('m_unidmedida');

      $ret_unidmed = $this->m_unidmedida->verificaUM($unid_medida);
    } else {
      $ret_unidmed['codigo'] = 1;
    }

    if ($ret_unidmed['codigo'] == 1) {

      $retorno_prod = $this->verificaProduto($codigo);

      if ($retorno_prod['codigo'] == 1) {
        $this->load->model('m_usuario');

        $retorno_usu = $this->m_usuario->verificaUsuario($usuario);

        if ($retorno_usu['codigo'] != 1) {
          return $retorno_usu;
        } else {

          $sql = "update produtos set ";

          if (trim($descricao) != '') {
            $sql = $sql . " descricao = '$descricao',";
          }

          if ($unid_medida != 0) {
            $sql = $sql . " unid_medida = $unid_medida,";
          }

          if ($estoque_minimo != 0) {
            $sql = $sql . " estoq_minimo = $estoque_minimo,";
          }

          if ($estoque_maximo != 0) {
            $sql = $sql . " estoq_maximo = $estoque_maximo,";
          }

          $sql = substr($sql, 0, -1);
          $sql = $sql . " where cod_produto = $codigo";

          $this->db->query($sql);

          if ($this->db->affected_rows() > 0) {
            $this->load->model('m_log');

            $retorno_log = $this->m_log->inserir_log($usuario, $sql);

            if ($retorno_log['codigo'] == 1) {
              $dados = array(
                'codigo' => 1,
                'msg' => 'Produto atualizado corretamente'
              );
            } else {
              $dados = array(
                'codigo' => 7,
                'msg' => 'Houve algum problema no salvamento do log, porém produto alterado corretamente'
              );
            }
          } else {
            $dados = array(
              'codigo' => 6,
              'msg' => 'Dados já atualizados'
            );
          }
        }
      } else {
        return $retorno_prod;
      }
    } else {
      return $ret_unidmed;
    }

    return $dados;
  }

  public function desativar($codigo, $usuario)
  {


    $retorno_prod = $this->verificaProduto($codigo);

    if ($retorno_prod['codigo'] == 1) {
      $this->load->model('m_usuario');

      $retorno_usu = $this->m_usuario->verificaUsuario($usuario);

      if ($retorno_usu['codigo'] != 1) {
        return $retorno_usu;
      } else {

        $sql = "update produtos set estatus = 'D'
                where cod_produto = $codigo";

        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
          $this->load->model('m_log');

          $retorno_log = $this->m_log->inserir_log($usuario, $sql);

          if ($retorno_log['codigo'] == 1) {
            $dados = array(
              'codigo' => 1,
              'msg' => 'Produto DESATIVADO corretamente'
            );
          } else {
            $dados = array(
              'codigo' => 7,
              'msg' => 'Houve algum problema no salvamento do log, porém produto DESATIVADO corretamente'
            );
          }
        } else {
          $dados = array(
            'codigo' => 6,
            'msg' => 'Houve algum problema na DESATIVAÇÃO do produto'
          );
        }
      }
    } else {
      return $retorno_prod;
    }

    return $dados;
  }
}