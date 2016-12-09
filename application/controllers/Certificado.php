<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . './libraries/REST_Controller.php';

class Certificado extends REST_Controller
{
    protected $_insert_id;

    protected $proprietario;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('message_error');

        error_reporting(E_ERROR);

        date_default_timezone_set('America/Sao_Paulo');
        header('Content-Type: application/json');
    }


    /**
     * @return array
     */


    function emitidas_get()
    {

        $certificados = Certificados::where('status_id', 1)->get();
        $retorno = [];
        $assitencia = [];

        foreach ($certificados as $certificado) {
            $coberturas = [];
            $dados = [
                "tipo_arquivo" => 2,
                "numero_certificado" => $certificado->id,
                "ano" => $certificado->proposta->cotacao->veiculo->veicano,
                "combustivel" => $certificado->proposta->cotacao->veiculo->combustivel->sigla,
                "numero_capitalizacao" => 0,
                "numero_serie_sorteio" => 0,
                "chassi" => $certificado->proposta->cotacao->veiculo->veicchassi,
                "renavam" => $certificado->proposta->cotacao->veiculo->veicrenav,
                "placa" => $certificado->proposta->cotacao->veiculo->veicplaca,
                "marca" => $certificado->proposta->cotacao->veiculo->fipe->marca,
                "modelo" => $certificado->proposta->cotacao->veiculo->fipe->modelo,
                "cod_fipe" => $certificado->proposta->cotacao->veiculo->fipe->codefipe,
                "cor" => $certificado->proposta->cotacao->veiculo->veiccor,
                "valor" => $certificado->proposta->cotacao->veiculo->fipe
                    ->anovalor()
                    ->where('ano', $certificado->proposta->cotacao->veiculo->veicano)
                    ->where('idcombustivel', $certificado->proposta->cotacao->veiculo->veictipocombus)
                    ->first()->valor,
                "zero_km" => ($certificado->proposta->cotacao->veiculo->veicautozero ? 'Y' : 'N'),
                "dono_veiculo" => ($certificado->proposta->cotacao->veiculo->propcpfcnpj != $certificado->proposta->cotacao->veiculo->clicpfcnpj ? 'N' : 'Y'),
                "nome" => $certificado->proposta->cotacao->segurado->clinomerazao,
                "cpf_cnpj" => $certificado->proposta->cotacao->segurado->clicpfcnpj,
                "cep" => $certificado->proposta->cotacao->segurado->clicep,
                "endereco" => $certificado->proposta->cotacao->segurado->cliendnm,
                "numero" => $certificado->proposta->cotacao->segurado->clinumero,
                "complemento" => $certificado->proposta->cotacao->segurado->cliendcomplet,
                "bairro" => $certificado->proposta->cotacao->segurado->bairro,
                "estado" => $certificado->proposta->cotacao->segurado->clicduf,
                "cidade" => $certificado->proposta->cotacao->segurado->clinmcidade,
                "data_nascimento" => date('Y-m-d', strtotime($certificado->proposta->cotacao->segurado->clidtnasc)),
                "estado_civil" => $certificado->proposta->cotacao->segurado->estadocivil->sigla,
                "valor_mensal_seguro" => $certificado->custo_mensal->sum('custo_mensal'),

                "categoria" => 3620,
                "data_venda" => $certificado->dt_inicio_virgencia,
            ];
            foreach ($certificado->custos as $custo) {
                $cobert = $custo->seguradora_produto()->where('idseguradora', 3)->first();
                $coberturas[] = [
                    'CodigoCobertura' => $cobert->id_produto_seguradora,
                    'Premio' => $custo->custo_mensal,
                ];
                $assitencia[] = (int)$cobert->id_produto_seguradora;
            }

            $assitencias = [
                "possui_assistencia" => (in_array(1004,$assitencia )? 'S' :'N'),
                "assistencia_reparo_vidro" => (in_array(1000,$assitencia )? 'S' :'N'),
                "assistencia_reparo_parachoque" => (in_array(1001,$assitencia )? 'S' :'N'),
                "assistencia_reparo_pintura" => (in_array(1002,$assitencia )? 'S' :'N'),
                "assistencia_residencial" => (in_array(1003,$assitencia )? 'S' :'N'),
                "assistencia_pt_colisao" => (in_array(238,$assitencia )? 'S' :'N'),
            ];


            $retorno[] = array_merge($dados, $coberturas,$assitencias);
        }

        $movimento = MovimentoCertificado::create([
            'datas_enviadas' => json_encode($retorno),
            'dt_envio' => date('Y-m-d H:i:s'),
            'status_id' => 26,
            'tipo_envio' => 'emitidos'
        ]);

        $retorno['id_requisicoa'] = $movimento->id;

        $this->response(array(
            'status' => '000 - sucesso',
            'cdretorno' => '000',
            'retorno' => $retorno,
        ));

    }

    function emitidosRetorno_post()
    {


        $datas = $this->post();


        $movimento = MovimentoCertificado::find($datas['id_requisicoa']);

        if (!$movimento) {
            return $this->response([
                'status' => '404 - Error',
                'cdretorno' => '404',
                'retorno' => 'Número de requisição invalido'], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $certificados_enviados = json_decode($movimento->datas_enviadas);

            $retorno = [];
            foreach ($certificados_enviados as $certificado) {
//                $retorno[] = str_pad($certificado->numero_certificado, 20, 0, STR_PAD_LEFT);

                if (in_array(str_pad($certificado->numero_certificado, 20, 0, STR_PAD_LEFT), array_column($datas['retorno'], 'numero_certificado'))) {
                    $retorno[] = $certificado->numero_certificado;
                }

            }

//            foreach ($datas['retorno'] as $ret) {
//                $retorno[] = $ret['numero_certificado'];
//            }


            $this->response([
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => 'Operação realizada com sucesso',
                'dados' => $retorno]);
        }


    }

    function pdf_post()
    {

        error_reporting(E_ERROR);
        $this->load->library('m_pdf');


        $datas = $this->post();

//        $this->response(array(
//            'status' => '009 - Atenção',
//            'cdretorno' => '009',
//            'message' => 'Estamos em manutenção por favor tente novamente mais tarde!!!'
//        ));

        $this->load->library('form_validation');
        $this->form_validation->set_data($datas);

        if ($this->form_validation->run('pdf') == false):
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '023',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        else:

            if (!$this->Model_key->get(['user_id' => $datas['idParceiro'], 'key' => $_SERVER['HTTP_X_API_KEY']])) {
                $this->response(array(
                    'status' => 'Acesso negado',
                    'cdretorno' => '098',
                    'message' => 'API KEY invalido para o parceiro, nome: ' . $datas['nmParceiro'] . ' id: ' . $datas['idParceiro']), REST_Controller::HTTP_FORBIDDEN);
            }

            $proposta['proposta'] = $this->Model_proposta->with_cotacao([
                'with' => [
                    ['relation' => 'segurado',
                        'with' => [
                            ['relation' => 'uf'],
                            ['relation' => 'rg_uf'],
                            ['relation' => 'profissao'],
                            ['relation' => 'ramoatividade'],
                            ['relation' => 'estadocivl'],
                        ]
                    ],
                    ['relation' => 'parceiro'],
                    ['relation' => 'veiculo',
                        'with' => [
                            ['relation' => 'fipe',
                                'with' => [
                                    ['relation' => 'valores'],
                                    ['relation' => 'contigencia'],
                                ]
                            ],
                            ['relation' => 'combustivel'],
                            ['relation' => 'utilizacao'],
                            ['relation' => 'proprietario'],
                        ]

                    ],
                    ['relation' => 'produtos',
                        'with' =>
                            ['relation' => 'produto',
                                'with' => [
                                    ['relation' => 'precos'],
                                    ['relation' => 'seguradoras',
                                        'with' => ['relation' => 'seguradora']
                                    ],
                                ]
                            ],
                    ],
                    ['relation' => 'corretor'],
                ]

            ])->with_forma_pagamento()->get($datas['idProposta']);

            $html = $this->load->view('pdf/proposta_view', $proposta, true);
            error_reporting(E_ERROR);


            $this->m_pdf->pdf->SetHTMLHeader($this->load->view('pdf/header_view', $proposta, true));
            $this->m_pdf->pdf->SetHTMLFooter($this->load->view('pdf/footer_view', $proposta, true));
            $this->m_pdf->pdf->AddPage('', // L - landscape, P - portrait
                '', '', '', '', 10, // margin_left
                10, // margin right
                25, // margin top
                15, // margin bottom
                5, // margin header
                6); // margin footer
            $this->m_pdf->pdf->SetProtection(['copy', 'print'], '', '@SAPpdf#2770');
            $this->m_pdf->pdf->WriteHTML($html);
//            $this->m_pdf->pdf->Output('pdfteste.pdf','S');

            $b64encode = chunk_split(base64_encode($this->m_pdf->pdf->Output('pdfteste.pdf', 'S')));
//            $html = gerarpdfb64(gerarhtml($proposta, $cotacao, $segurado, $veiculo, $corretor, $parcela, $produto, $parceiro, $proprietario));
//
////            header("Content-type: application/pdf");
////            echo base64_decode($html);


            $this->response(array(
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'idproposta' => $proposta['proposta']['idproposta'],
//                'idparceiro' => $parceiro['idparceiro'],
                'base64' => $b64encode,
            ));

//            $this->response(array(
//                $veiculo, $combustivel, $utilizacao  
//            ));

        endif;
    }


}
