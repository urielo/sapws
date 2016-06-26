<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . './libraries/REST_Controller.php';

class Get extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function uf_get()
    {
        $this->load->model('Model_uf');
        $id = $this->uri->segment(3);


        if (isset($id)):
            $uf = $this->Model_uf->get($id);
            if (isset($uf)):
                $this->response(array('status' => '000 - sucesso', 'response' => $uf));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $uf = $this->Model_uf->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $uf));
        endif;
    }

    function profissao_get()
    {
        $this->load->model('Model_profissao');
        $id = $this->uri->segment(3);

        if (isset($id)):
            $data = $this->Model_profissao->get($id);
            if (isset($data)):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_profissao->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }

    function ramoatividade_get()
    {
        $this->load->model('Model_ramoatividade');
        $id = $this->uri->segment(3);

        if (isset($id)):
            $data = $this->Model_ramoatividade->get($id);
            if (isset($data)):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_ramoatividade->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }

    function tipoveiculo_get()
    {
        $this->load->model('Model_tipoveiculo');
        $id = $this->uri->segment(3);

        if (isset($id)):
            $data = $this->Model_tipoveiculo->get($id);
            if (isset($data)):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_tipoveiculo->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }
    
    function tipocombustivel_get()
    {
        $this->load->model('Model_tipocombustivel');
        $id = $this->uri->segment(3);

        if (isset($id)):
            $data = $this->Model_tipocombustivel->get($id);
            if (isset($data)):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_tipocombustivel->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }

    function estadocivil_get()
    {
        $this->load->model('Model_estadocivil');
        $id = $this->uri->segment(3);

        if (isset($id)):
            $data = $this->Model_estadocivil->get($id);
            if (isset($data)):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_estadocivil->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }

    function produto_get()
    {
        $id = $this->uri->segment(3);
        $fields = 'idproduto, nomeproduto, descproduto, cararctproduto';
        if (isset($id)):
            $data = $this->Model_produto->fields($fields)->where(array('idproduto' => $id, 'codstatus' => '000'))->get();
            if ($data):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_produto->fields($fields)->where('codstatus', '000')->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }

    function formapagamento_get()
    {
        $id = $this->uri->segment(3);
        $fields = 'idformapgto, descformapgto';
        if (isset($id)):
            $data = $this->Model_parcela->fields($fields)->where(array('idformapgto' => $id))->get();
            if ($data):
                $this->response(array('status' => '000 - sucesso', 'response' => $data));
            else:
                $this->response(array('status' => 'error', 'response' => 'Id informado Ivalido'), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        else:
            $data = $this->Model_parcela->fields($fields)->get_all();
            $this->response(array('status' => '000 - sucesso', 'response' => $data));
        endif;
    }
}
