<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . './libraries/REST_Controller.php';

class Buscar extends REST_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->load->helper('pdfgerator');
        $this->load->helper('message_error');
//        $this->load->library('m_pdf');
        date_default_timezone_set('America/Sao_Paulo');
    }


    public function emitidos_get()
    {
        $_SERVER;
        $certificado = $this->Model_certificado->with_proposta()->with_custos(['with' =>
            [
                'relation' => 'produto',
//            'where'=>'idseguradora = 3'
            ]
        ])->get(55);

        $custo = $this->Model_custos_produto->with_produto('where: idseguradora = 3')->get(2);

        $this->response(['message' => $custo,'certificado'=>$certificado['custos']]);
    }

}
