<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . './libraries/REST_Controller.php';

class Gerar extends REST_Controller
{
    protected $_insert_id = '';

    function __construct()
    {
        parent::__construct();
//        $this->load->helper('my_ajust');
//        $this->load->helper('pdfgerator');
//        $this->load->helper('datas');
        $this->load->helper('message_error');

        error_reporting(E_ERROR);

        date_default_timezone_set('America/Sao_Paulo');
    }


    /**
     * @return array
     */


    function cotacao_post()
    {

        $this->_insert_id = 11154;

        $datas = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data($datas);

        if ($this->form_validation->run('cotacao') == false):
            $this->response(array(
                'cdretorno' => '023',
                'status' => 'Error',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        else:
            if (!$this->Model_key->get(['user_id' => $datas['idParceiro'], 'key' => $_SERVER['HTTP_X_API_KEY']])) {
                $this->response(array(
                    'status' => 'Acesso negado',
                    'cdretorno' => '098',
                    'message' => 'API KEY invalido para o parceiro, nome: ' . $datas['nmParceiro'] . ' id: ' . $datas['idParceiro']), REST_Controller::HTTP_FORBIDDEN);
            }

            $this->form_validation->reset_validation();

            $this->validadb($datas);

            $produto = $this->getProdutoParcPremio($datas, 'cotacao');

//            $this->response(dataOrganize($datas));
            /*
             * Tratando dados do segurado e inserindo no banco
             */

            if (isset($datas['segurado']) && strlen($datas['segurado']['segCpfCnpj']) > 0):

                if (strlen($datas['segurado']['segCpfCnpj']) > 11):
                    $validacao = 'CotacaoPJ';
                else:
                    $validacao = 'Cotacao';
                endif;
                $this->valida_pessoas('segurado', $validacao, $datas);

            endif;


            /*
             * Tratando dados do proprietario e inserindo no banco.
             */

//            if (!$datas['indProprietVeic']):
//
//                $proprietario = $this->valida_pessoas('proprietario', 'Cotacao', $datas);
//                $datas['proprietario']['proprCpfCnpj'] = $proprietario->id;
////                $datas['proprietario']['proprCpfCnpj'] = $this->pessoadb($datas, 'Cotacao', 'proprietario');
//            endif;
//
//            /*
//             * Tratando dados do condutor e inserindo no banco
//             */
//
//            if (!$datas['indCondutorVeic']):
//
//                $condutor = $this->valida_pessoas('condutor', 'Cotacao', $datas);
//                $datas['condutor']["condutCpfCnpj"] = $condutor->id;
////                $datas['condutor']["condutCpfCnpj"] = $this->pessoadb($datas, 'Cotacao', 'condutor');
//            endif;

            /*
             * Tratando dados do veiculo e inserindo no banco
             */

            $veiculo = $this->veiculo($datas, 'Cotacao');
//            $veicid = $this->veiculodb($datas, 'Cotacao');


            /*
             * Tratando dados do cotacao e inserindo no banco
             */

            $cotacao = $this->cotacaodb($datas, $produto, $veiculo->veicid);

            /*
             * Preparando retorno
             */


            $result = array_merge($cotacao, ['produto' => $produto['produto'], 'premio' => $produto['premio']], $produto['parcelamento']);


            /*
             *  Retorno
             */

            $this->response(array(
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => $result,
            ));

        endif;
    }

    function proposta_post()
    {

        $this->_insert_id = 11154;
        $datas = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data($datas);

        if ($this->form_validation->run('proposta') == false):
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

            $this->form_validation->reset_validation();


            /*
             * Tratando dados do cotacao e e froma de pagamento inserindo no banco
             */

            /*
             * Tratando dados do segurado e inserindo no banco
             */
            if (strlen($datas['segurado']['segCpfCnpj']) > 11):
                $validacao = 'PropostaPJ';
            else:
                $validacao = 'PropostaPF';
            endif;
            $this->valida_pessoas('segurado', $validacao, $datas);


            /*
             * Tratando dados do proprietario e inserindo no banco.
             */


            if (!$datas['indProprietVeic']):

                $proprietario = $this->valida_pessoas('proprietario', 'Proposta', $datas);
                $datas['proprietario']['proprCpfCnpj'] = $proprietario->id;
//                $datas['proprietario']['proprCpfCnpj'] = $this->pessoadb($datas, 'Cotacao', 'proprietario');
            endif;

            /*
             * Tratando dados do condutor e inserindo no banco
             */
            if (!$datas['indCondutorVeic']):
                $condutor = $this->valida_pessoas('condutor', 'Proposta', $datas);
                $datas['condutor']["condutCpfCnpj"] = $condutor->id;
//                $datas['condutor']["condutCpfCnpj"] = $this->pessoadb($datas, 'Cotacao', 'condutor');
            endif;

            /*
             * Tratando dados do veiculo e inserindo no banco
             */


            /*
             * Enviando resposta
             */

            $result = $this->propostadb($datas);


            $this->response(array(
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => $result,));


        endif;
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

    protected function getProdutoParcPremio($datas, $tipo)
    {


        $datas = $tipo == 'cotacao' ? dataOrganizeCotacao($datas) : $datas;

        $veiculo = $datas['veiculo'];


        $produto = $datas['produto'];
        $prodcheck = $produto;
        $master = false;

        foreach ($prodcheck as $key => $pro) {
            $pro['idProduto'];
            $check = $this->Model_produto->with_opcionais()->get($pro['idProduto']);
            if ($check['tipoproduto'] == 'master' && $master == false) {
                unset($prodcheck[$key]);
                $master = true;
                $produto[$key]['master'] = true;
//                return $check['opcionais'];
                foreach ($check['opcionais'] as $opcional) {
                    $opcionais[] = $opcional['idprodutoopcional'];
                }

            } elseif ($check['tipoproduto'] == 'master' && $master == true) {
                unset($produto[$key]);
//                unset($prodcheck[$key]);
            }
        }
        $menorparcela = 0;
        $prodcheck = $produto;

        if ($master) {
            foreach ($prodcheck as $key => $pro) {
                if (!in_array($pro['idProduto'], $opcionais) && !$pro['master']) {
                    unset($produto[$key]);
                }
            }
        } else {
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '040',
                'message' => array(
                    'produtos' => 'Cotacao exige contratação do produto Seguro AUTOPRATICO Roubo e Furto',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        }

        $ano = $veiculo['veicautozero'] == 1 ? 0 : $veiculo['veicano'];
        $valorfipe = $this->Model_fipeanovalor
            ->fields('valor')
            ->where(array('codefipe' => $veiculo['veiccodfipe'], 'ano' => $ano))
            ->with_fipe(['with' => ['relation' => 'contigencia']])->get();
        $contigencia = $valorfipe['fipe']['contigencia']['valor'];

        $maxidade = max($this->Model_produto_seguradora->fields('idade_aceitacao_max')->get_all());
        $maxvalor = max($this->Model_produto_seguradora->fields('valor_aceitacao_max')->get_all());
        $minvalor = min($this->Model_produto_seguradora->fields('valor_aceitacao_min')->get_all());


        if ($ano != 0 && date('Y') - $ano > max($maxidade)):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Não tem aceitação para veiculos com idade acima de ' . $maxidade . ' anos invalido',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;

        if (!$valorfipe):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Fipe ou ano do modelo do veiculo invalido',
                )
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        else:
            $valorfipe = $valorfipe['valor'];
        endif;

        if ($valorfipe > max($maxvalor)):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Esse produto não aceita item com valor fipe superior a R$ ' . real(max($maxvalor)),
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        elseif ($valorfipe < min($minvalor)):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Esse produto não aceita item com valor fipe inferior a R$ ' . real(min($minvalor)),
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;

        $tipoveiculo = $veiculo['veiccdveitipo'];
        $idade = date('Y') - $veiculo['veicano'];
        $comissao = $datas['cotacao']['comissao'];
        $categoria = $this->Model_fipecategoria->get(['codefipe' => $veiculo['veiccodfipe'], 'idseguradora' => 2]);
        $i = 0;

        /* Iniciando calculo e separando os produtos */

        foreach ($produto as $k => $v):
            $idproduto = $produto[$k]['idProduto'];
            $prolmi = $produto[$k]['valorLmiProduto'];
            $produtodb = $this->Model_produto->with_precos()->get($idproduto);
            $precos = $produtodb['precos'];


            if (!$produtodb):
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '009',
                    'message' => "O Produto {$idproduto} - {$produtodb['nomeproduto']}  é inválido",
                ), REST_Controller::HTTP_BAD_REQUEST);

            elseif ($produtodb['idtipoveiculo'] != $veiculo['veiccdveitipo']):
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '009',
                    'message' => "O Tipo de veículo {$veiculo['veiccdveitipo']} é inválido para o produto {$idproduto} - {$produtodb['nomeproduto']}",
                ), REST_Controller::HTTP_BAD_REQUEST);


            elseif ($produtodb['codstatus'] == 2):
                return $this->response(array(
                    'status' => 'Atenção',
                    'cdretorno' => '009',
                    'message' => "O Produto {$produtodb['nomeproduto']} não está ativo NO MOMENTO, refaça a sua  cotação SEM ESTA COBERTURA. Em breve ofereceremos novamente esta cobertura opcional.",
                ), REST_Controller::HTTP_BAD_REQUEST);

            elseif ($produtodb['tipodeseguro'] == 'RCF' && $prolmi != 50000 && $prolmi != 100000 && $prolmi != 200000):
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '009',
                    'message' => "O Produto {$idproduto} - {$produtodb['nomeproduto']} só aceita lmi 50000, 100000 ou 200000",
                ), REST_Controller::HTTP_BAD_REQUEST);
            else:

                unset($produtodb["idtipoveiculo"]);
                unset($produtodb["vlrfipemaximo"]);
                unset($produtodb["vlrfipeminimo"]);
                unset($produtodb["qtdemaxparcelas"]);
                unset($produtodb["indtabprecofipe"]);
                unset($produtodb["indtabprecocategorianobre"]);
                unset($produtodb["numparcelsemjuros"]);
                unset($produtodb["jurosmesparcelamento"]);
                unset($produtodb["idstatus"]);
                unset($produtodb["idseguradora"]);
                unset($produtodb["procsusepproduto"]);
                unset($produtodb["codramoproduto"]);
                unset($produtodb["cobertura"]);
                unset($produtodb["descproduto"]);
                unset($produtodb["precos"]);

                foreach ($precos as $preco):

                    if ($valorfipe >= $preco['vlrfipeminimo'] && $valorfipe <= $preco['vlrfipemaximo'] && $preco['idcategoria'] == ($preco['idcategoria'] == $categoria['idcategoria'] ? $categoria['idcategoria'] && $preco['lmiproduto'] == $prolmi : null) && $idade <= max($maxidade) && $preco['idtipoveiculo'] == $tipoveiculo):

                        if ($idproduto == 1) {

                            $preco['premioliquidoproduto'] = $preco['premioliquidoproduto'] + $contigencia;

                        }
                        $preco['premioliquidoproduto'] = aplicaComissao($preco['premioliquidoproduto'], $comissao);

                        $produtos['produto'][$i] = $produtodb;
                        $produtos['produto'][$i]['indexigenciavistoria'] = $produtodb['ind_exige_vistoria'];
                        $produtos['produto'][$i]['caractproduto'] = $preco['caractproduto'];
                        $produtos['produto'][$i]['nomeproduto'] = $preco['nomeproduto'];
                        $produtos['produto'][$i]['indobrigrastreador'] = $produtodb['ind_exige_rastreador'];

                        $produtos['produto'][$i]['premioliquidoproduto'] = floatN($preco['premioliquidoproduto']);

                        $produtos['premio'] = floatN($produtos['premio'] + $preco['premioliquidoproduto']);

                        $produtos['cotacaoproduto'][$i]['idprecoproduto'] = $preco['idprecoproduto'];
                        $produtos['cotacaoproduto'][$i]['idproduto'] = $idproduto;

                        $menorparcela = $menorparcela + $preco['vlrminprimparc'];

                        if ($tipo != 'cotacao'):
                            unset($produtos['produto'][$i]['premioliquidoproduto']);
                            unset($produtos['cotacaoproduto']);
                        endif;


                    elseif ($preco['idtipoveiculo'] == $tipoveiculo && $preco['vlrfipeminimo'] == null && $preco['vlrfipemaximo'] == null && $idade <= $preco['idadeaceitamax']):
                        $preco['premioliquidoproduto'] = aplicaComissao($preco['premioliquidoproduto'], $comissao);
                        $produtos['produto'][$i] = $produtodb;
                        $produtos['produto'][$i]['caractproduto'] = $preco['caractproduto'];
                        $produtos['produto'][$i]['nomeproduto'] = $preco['nomeproduto'];
                        $produtos['produto'][$i]['indobrigrastreador'] = $preco['indobrigrastreador'];

                        $produtos['produto'][$i]['premioliquidoproduto'] = floatN($preco['premioliquidoproduto']);

                        $produtos['premio'] = floatN($produtos['premio'] + $preco['premioliquidoproduto']);

                        $produtos['cotacaoproduto'][$i]['idprecoproduto'] = $preco['idprecoproduto'];
                        $produtos['cotacaoproduto'][$i]['idproduto'] = $idproduto;

                        $menorparcela = $menorparcela + $preco['vlrminprimparc'];

                        if ($tipo != 'cotacao'):
                            unset($produtos['produto'][$i]['premioliquidoproduto']);
                            unset($produtos['cotacaoproduto']);

                        endif;
                    endif;
                endforeach;
            endif;


            $i++;
        endforeach;
//        return $produtos;

        if (!isset($produtos['premio']) || $produtos['premio'] == 0):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '005',
                'message' => (count($produtos) > 0 ? $produtos : 'Produtos não encontrado'),
            ));
        endif;

        /* Iniciando parcelamento */
        $premio = $produtos['premio'];
        if ($tipo == 'proposta'):


            $proposta = $datas['proposta'];
            $parcela = $this->Model_parcela->get($proposta['idformapg']);
            $parcelaj = $proposta['quantparc'] > $parcela['numparcsemjuros'] ? jurosComposto($premio, $parcela['taxamesjuros'], $proposta['quantparc']) : floatN($premio / $proposta['quantparc']);

//            $premio = $proposta['quantparc'] > $parcela['numparcsemjuros'] ? floatN($premio + ($premio * ($parcela['taxamesjuros'] / 100))) : $premio;
            $parcela['taxamesjuros'] = $proposta['quantparc'] > $parcela['numparcsemjuros'] ? $parcela['taxamesjuros'] : 0;
            unset($produtos['premio']);

            if ($menorparcela > $parcelaj && $proposta['idformapg'] == 2):
                $parcelaj = jurosComposto(($premio - $menorparcela), $parcela['taxamesjuros'], ($proposta['quantparc'] - 1));
                $produtos['premioTotal'] = $menorparcela + ($parcelaj * ($proposta['quantparc'] - 1));
                $produtos['formapagamento']['tipo'] = $parcela['descformapgto'];
                $produtos['formapagamento']['quantidade'] = $proposta['quantparc'];
                $produtos['formapagamento']['primeira'] = floatN($menorparcela);
                $produtos['formapagamento']['demais'] = $parcelaj;
                $produtos['formapagamento']['juros'] = $parcela['taxamesjuros'];

            else:
                $produtos['premioTotal'] = $parcelaj * $proposta['quantparc'];
                $produtos['formapagamento']['tipo'] = $parcela['descformapgto'];
                $produtos['formapagamento']['quantidade'] = $proposta['quantparc'];
                $produtos['formapagamento']['primeira'] = $parcelaj;
                $produtos['formapagamento']['demais'] = $proposta['quantparc'] == 1 ? 0 : $parcelaj;
                $produtos['formapagamento']['juros'] = 0;

            endif;
        else:

            $c = 0;
            $parcela = $this->Model_parcela->get_all();


            foreach ($parcela as $k => $v):

                foreach ($v as $key => $val) :

                    $tipo = $parcela[$k]['descformapgto'];
                    $idforma = $parcela[$k]['idformapgto'];
                    $juros = $parcela[$k]['taxamesjuros'];
                    $parc = $parcela[$k]['numparcsemjuros'] + 1;


                    if ($key == 'numparcsemjuros'):
                        for ($i = 1; $i <= $val; $i++):
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['tipo'] = $tipo;
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['quantidade'] = $i;
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['primeira'] = floatN($premio / $i);
                            if ($i == 1):
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = 0;
                            else:
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = floatN($premio / $i);
                            endif;
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['juros'] = 0;
                            $c++;
                        endfor;
                    elseif ($key == 'nummaxparc'):
                        for ($i = $parc; $i <= $val; $i++):

                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['tipo'] = $tipo;
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['quantidade'] = $i;

                            if (jurosComposto($premio, $juros, $i) < $menorparcela && $idforma == 2):
                                $parcelajuros = jurosComposto(($premio - $menorparcela), $juros, ($i - 1));
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['primeira'] = floatN($menorparcela);
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = $parcelajuros;
                            else:
                                $parcelajuros = jurosComposto($premio, $juros, $i);
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['primeira'] = $parcelajuros;
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = $parcelajuros;
                            endif;


                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['juros'] = $juros;
                            $c++;
                        endfor;
                    endif;
                endforeach;
            endforeach;

        endif;


        return $produtos;
    }

    protected function cotacaodb($datas, $produto, $veicid)
    {

        $idcorretor = $this->valida_pessoas('corretor', '', $datas);

//        $idcorretor = $this->corretordb($datas, 'corretor');

        $datas = dataOrganizeCotacao($datas);

        /*
         * Tratando dados do corretor e inserindo no banco
         */
        $datas['cotacao']['idcorretor'] = $idcorretor->idcorretor;


        $datas['cotacao']['veicid'] = $veicid;
        $datas['cotacao']['premio'] = $produto['premio'];

        $cotacaoid = $this->Model_cotacao->insert($datas['cotacao']);
        if (!$cotacaoid):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '003',
                'message' => array('cotacao' => 'Error ao cadastrar')
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        endif;

        foreach ($produto['cotacaoproduto'] as $k => $v):
            $produto['cotacaoproduto'][$k]['idcotacao'] = $cotacaoid;
            $this->Model_cotacaoproduto->insert($produto['cotacaoproduto'][$k]);
        endforeach;

        $data = $this->Model_cotacao->get($cotacaoid);


        $retorno['cdCotacao'] = $data['idcotacao'];
        $retorno['idparceiro'] = $data['idparceiro'];
        $retorno['validade'] = $data['dtvalidade'];


        return $retorno;
    }

    protected function veiculodb($datas, $validacao)
    {


        $this->form_validation->reset_validation();
        $this->form_validation->set_data((isset($datas['veiculo']) ? $datas['veiculo'] : $datas));
        $validacao == 'Cotacao' ? $datas = dataOrganizeCotacao($datas) : $datas = dataOrganizeProposta($datas);
        $veiculo = $datas['veiculo'];

//        if ($veiculo['veicchassiremar']):
//            return $this->response(array(
//                'status' => 'Error',
//                'cdretorno' => '019',
//                'message' => array('veiculo' => 'Não há aceitação pra veiculo com chassi remarcado')
//            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
//        elseif ($veiculo['veicleilao']):
//            return $this->response(array(
//                'status' => 'Error',
//                'cdretorno' => '019',
//                'message' => array('veiculo' => 'Não há aceitação pra veiculo oriundo de leilão')
//            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
//        elseif ($veiculo['veicacidentado']):
//            return $this->response(array(
//                'status' => 'Error',
//                'cdretorno' => '019',
//                'message' => array('veiculo' => 'Não há aceitação pra veiculo acidentado')
//            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
//        endif;


        if ($this->form_validation->run('veiculo' . ucfirst($validacao)) == false):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '023',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        elseif (ucfirst($validacao) == 'Cotacao'):

            $wherec = "idstatus != '10' AND veicplaca = '{$veiculo['veicplaca']}' "
                . "OR idstatus != '10' AND veicrenavam = '{$veiculo['veicrenavam']}' "
                . "OR idstatus != '10' AND veicchassi = '{$veiculo['veicchassi']}'";
            $dbveiculo = $this->Model_veiculo->where($wherec, null, null, FALSE, FALSE, TRUE)->get_all();

            $wherep = "idstatus = '10' AND veicplaca = '{$veiculo['veicplaca']}' "
                . "OR idstatus = '10' AND veicrenavam = '{$veiculo['veicrenavam']}' "
                . "OR idstatus = '10' AND veicchassi = '{$veiculo['veicchassi']}'";
            $dbveiculop = $this->Model_veiculo->where($wherep, null, null, FALSE, FALSE, TRUE)->get_all();

            if ($dbveiculop):

                if (count($dbveiculop) == 1):
                    return $dbveiculop[0]['veicid'];
                elseif (count($dbveiculop) > 1):

                    foreach ($dbveiculop as $veic):
                        if (strtoupper($veic['veicplaca']) == strtoupper($veiculo['veicplaca'])):
                            $msg = $msg . "Placa {$veiculo['veicplaca']} já está cadastrada em outro veiculo. ";
                        elseif (strtoupper($veic['veicchassi']) == strtoupper($veiculo['veicchassi'])):
                            $msg = $msg . "Chassi {$veiculo['veicchassi']} já está cadastrado em outro veiculo. ";
                        elseif ($veic['veicrenavam'] == $veiculo['veicrenavam']):
                            $msg = $msg . "Renavam {$veiculo['veicrenavam']} já está cadastrado em outro veiculo. ";
                        endif;
                    endforeach;

                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => array('veiculo' => $msg)
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;

            elseif (!$dbveiculo):
                $idvei = $this->Model_veiculo->insert($veiculo);
                if (!$idvei):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => array('veiculo' => 'Error ao cadastrar')
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                else:
                    return $idvei;
                endif;
            elseif (count($dbveiculo) == 1):

                if (!$this->Model_veiculo->update($veiculo, $dbveiculo[0]['veicid'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => array('veiculo' => 'Error ao atualizar')
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                else:
                    return $dbveiculo[0]['veicid'];
                endif;

            elseif (count($dbveiculo) > 1):
                foreach ($dbveiculo as $veic):
                    if (strtoupper($veic['veicplaca']) == strtoupper($veiculo['veicplaca'])):
                        $msg = $msg . "Placa {$veiculo['veicplaca']} já está cadastrada em outro veiculo. ";
                    elseif (strtoupper($veic['veicchassi']) == strtoupper($veiculo['veicchassi'])):
                        $msg = $msg . "Chassi {$veiculo['veicchassi']} já está cadastrado em outro veiculo. ";
                    elseif ($veic['veicrenavam'] == $veiculo['veicrenavam']):
                        $msg = $msg . "Renavam {$veiculo['veicrenavam']} já está cadastrado em outro veiculo. ";
                    endif;
                endforeach;

                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => array('veiculo' => $msg)
                ), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        /* Vieculo Proposta */
        else:

            $msg = '';
            $wherev = "veiccodfipe = '{$veiculo['veiccodfipe']}' AND "
                . "veicano = {$veiculo['veicano']} AND "
                . "veicautozero = {$veiculo['veicautozero']} AND "
                . "veiccdveitipo = {$veiculo['veiccdveitipo']} AND "
                . "veictipocombus = {$veiculo['veictipocombus']} AND "

                . "veicplaca = '{$veiculo['veicplaca']}' OR "
                . "veiccodfipe = '{$veiculo['veiccodfipe']}' AND "
                . "veicano = {$veiculo['veicano']} AND "
                . "veicautozero = {$veiculo['veicautozero']} AND "
                . "veiccdveitipo = {$veiculo['veiccdveitipo']} AND "
                . "veictipocombus = {$veiculo['veictipocombus']} AND "

                . "veicrenavam = '{$veiculo['veicrenavam']}' OR "
                . "veiccodfipe = '{$veiculo['veiccodfipe']}' AND "
                . "veicano = {$veiculo['veicano']} AND "
                . "veicautozero = {$veiculo['veicautozero']} AND "
                . "veiccdveitipo = {$veiculo['veiccdveitipo']} AND "
                . "veictipocombus = {$veiculo['veictipocombus']} AND "
                . "veicchassi = '{$veiculo['veicchassi']}' ";

            $dbveiculo = $this->Model_veiculo->where($wherev, null, null, FALSE, FALSE, TRUE)->get_all();

            unset($veiculo['veiccodfipe']);
            unset($veiculo['veicano']);
            unset($veiculo['veicautozero']);
            unset($veiculo['veiccdveitipo']);
            unset($veiculo['veictipocombus']);


            if ($dbveiculo && count($dbveiculo) == 1):
                $this->Model_cotacao->update(['veicid' => $dbveiculo[0]['veicid']], $datas['proposta']['idcotacao']);
                $idveic['veicid'] = $dbveiculo[0]['veicid'];

            elseif (count($dbveiculo) > 1):
                foreach ($dbveiculo as $veic):
                    if (strtoupper($veic['veicplaca']) == strtoupper($veiculo['veicplaca'])):
                        $msg = $msg . "Placa {$veiculo['veicplaca']} já está cadastrada em outro veiculo. ";
                    elseif (strtoupper($veic['veicchassi']) == strtoupper($veiculo['veicchassi'])):
                        $msg = $msg . "Chassi {$veiculo['veicchassi']} já está cadastrado em outro veiculo. ";
                    elseif ($veic['veicrenavam'] == $veiculo['veicrenavam']):
                        $msg = $msg . "Renavam {$veiculo['veicrenavam']} já está cadastrado em outro veiculo. ";
                    endif;
                endforeach;
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => array('veiculo' => 'Proposta: ' . $msg)
                ), REST_Controller::HTTP_BAD_REQUEST);
            else:
                $idveic = $this->Model_cotacao->get(['idcotacao' => $datas['proposta']['idcotacao']]);
            endif;

            $veiculo['idstatus'] = '10';
            $cotacao = $this->Model_cotacao->get(['veicid' => $idveic['veicid'], 'idstatus' => 10]);

            if ($cotacao):

                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => array('veiculo' => 'Existe uma proposta em aberto pra esse veiculo')
                ), REST_Controller::HTTP_BAD_REQUEST);

            else:

                $where = "veicid != {$idveic['veicid']} AND veicplaca = '{$veiculo['veicplaca']}' "
                    . "OR veicid != {$idveic['veicid']} AND veicrenavam = '{$veiculo['veicrenavam']}' "
                    . "OR veicid != {$idveic['veicid']} AND veicchassi = '{$veiculo['veicchassi']}'";
                $dbveiculo = $this->Model_veiculo->where($where, null, null, FALSE, FALSE, TRUE)->get_all();

                if ($dbveiculo):
                    foreach ($dbveiculo as $veic):
                        if (strtoupper($veic['veicplaca']) == strtoupper($veiculo['veicplaca'])):
                            $msg = $msg . "Placa {$veiculo['veicplaca']} já está cadastrada em outro veiculo. ";
                        elseif (strtoupper($veic['veicchassi']) == strtoupper($veiculo['veicchassi'])):
                            $msg = $msg . "Chassi {$veiculo['veicchassi']} já está cadastrado em outro veiculo. ";
                        elseif ($veic['veicrenavam'] == $veiculo['veicrenavam']):
                            $msg = $msg . "Renavam {$veiculo['veicrenavam']} já está cadastrado em outro veiculo. ";
                        endif;
                    endforeach;
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => array('veiculo' => 'Proposta: ' . $msg)
                    ), REST_Controller::HTTP_BAD_REQUEST);

                elseif (!$this->Model_veiculo->update($veiculo, $idveic['veicid'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => array('veiculo' => 'Error ao atualizar')
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                endif;
            endif;
        endif;
    }

    protected function propostadb($datas)
    {

        $where = ($datas['idParceiro'] == 99 ? ['idcotacao' => $datas["cdCotacao"], 'idparceiro' => $datas["idParceiro"]] : ['idcotacao' => $datas["cdCotacao"]]);
        $cotacao = $this->Model_cotacao->with_produtos(['with' =>
            ['relation' => 'produto',
                'with' => [
                    ['relation' => 'precos'],
                    ['relation' => 'seguradoras',
                        'with' => ['relation' => 'seguradora']
                    ],
                ]
            ]])->with_veiculo()->get($where);


        if (!$cotacao):
            return $this->response(array(
                'status' => 'Error',
                'message' => "Cotacao Nº: {$datas["cdCotacao"]} Inválido!",
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;


//        $this->veiculodb($datas, 'proposta');

        $this->veiculo($datas, 'Proposta');

        $datas = dataOrganizeProposta($datas);

        if ($cotacao['idstatus'] == 10):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'Existe uma proposta ativa para essa cotação',
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;


        $forma = $this->Model_parcela->get($datas['proposta']['idformapg']);
        $datas['veiculo'] = $cotacao['veiculo'];


        if ($cotacao['dtvalidade'] < date('Y-m-d 00:00:00')):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'cotacao vencida',
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;
        $datas['cotacao']['idstatus'] = 10;
        if (!$this->Model_cotacao->update($datas['cotacao'], $datas['proposta']['idcotacao'])):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'cotacao ao atualizar',
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        endif;


        if (!$cotacao):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'Codigo da cotação invalido',
            ), REST_Controller::HTTP_BAD_REQUEST);
        elseif (!$forma):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'Codigo da forma de pagamento invalido',), REST_Controller::HTTP_BAD_REQUEST);

        elseif ($datas['qtParcela'] > $forma['nummaxparc']):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => 'Quantidade de parcelaa invalida',), REST_Controller::HTTP_BAD_REQUEST);
        endif;

        $datas['cotacao']['comissao'] = $cotacao['comissao'];

        /* produdos*/

        foreach ($cotacao['produtos'] as $k => $produto):
            $key = array_search($produto['idprecoproduto'], array_column($produto['produto']['precos'], 'idprecoproduto'));
            $datas['produto'][$k]['idProduto'] = $produto['idproduto'];
            $datas['produto'][$k]['valorLmiProduto'] = $produto['produto']['precos'][$key]['lmiproduto'];
        endforeach;


        $retorno = $this->getProdutoParcPremio($datas, 'proposta');

        $datas['proposta']['premiototal'] = $retorno['premioTotal'];
        $datas['proposta']['primeiraparc'] = $retorno['formapagamento']['primeira'];
        $datas['proposta']['demaisparc'] = $retorno['formapagamento']['demais'];


        $idproposta = $this->Model_proposta->insert($datas['proposta']);
        if (!$idproposta):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array('proposta' => 'Error ao cadastrar')
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        endif;

        $proposta = $this->Model_proposta->fields('idproposta, idcotacao, dtvalidade')->get($idproposta);


        $parceiro = array('idparceiro' => $cotacao['idparceiro']);

        $return = array_merge($retorno, $proposta, $parceiro);

        return $return;
    }

    protected function corretordb($datas, $tipoValidacao)
    {

        $this->form_validation->reset_validation();

        if (!isset($datas['corretor'])):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array('corretor' => 'Deve-se passar o objeto corretor.'),
            ), REST_Controller::HTTP_BAD_REQUEST);
        else:

            $corretor = dataOrganizeCotacao($datas);
            $corretor = $corretor['corretor'];

            $corretorid = $this->Model_corretor->fields('idcorretor')->where('corrcpfcnpj', (empty($corretor['corrcpfcnpj']) ? '' : $corretor['corrcpfcnpj']))->get();
            if ($corretorid):
                $this->Model_corretor->update($corretor, $corretorid['idcorretor']);
                return $corretorid['idcorretor'];
            else:

                $corretor['corrcpfcnpj'] = (string)$corretor['corrcpfcnpj'];

                foreach ($datas['corretor'] as $key => $value):

                    if ($key == 'correCelDdd'):
                        $datas['corretor'][$key] = (strlen($value) < 2 ? NULL : $value);
                    elseif ($key == 'correCelNum'):
                        $datas['corretor'][$key] = (strlen($value) < 8 ? NULL : $value);
                    elseif ($key == 'correFoneDdd'):
                        $datas['corretor'][$key] = (strlen($value) < 2 ? NULL : $value);
                    elseif ($key == 'correFoneNum'):
                        $datas['corretor'][$key] = (strlen($value) < 8 ? NULL : $value);
                    elseif ($key == 'correSusep'):
                        $datas['corretor'][$key] = (strlen($value) < 3 ? NULL : $value);

                    endif;
                endforeach;

                $this->form_validation->set_data($datas['corretor']);
                if ($this->form_validation->run($tipoValidacao) == false):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
                elseif (!$this->Model_corretor->fields('idcorretor')->where(array('corrcpfcnpj' => $corretor['corrcpfcnpj']))->get()):
                    $corretorid = $this->Model_corretor->insert($corretor);
                    if (!$corretorid):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'message' => array('corretor' => 'Error ao cadastrar')
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    else :
                        return $corretorid;
                    endif;
                endif;
            endif;
        endif;
    }

    /*
     * Cliente  insert & Update* 
     */

    protected function pessoadb($datas, $validacao, $tipoCliente)
    {
        $validacao = ucfirst($validacao);
        $model = $tipoCliente == 'segurado' ? 'Model_cliente' : 'Model_' . $tipoCliente;
        $this->form_validation->reset_validation();

        if (!isset($datas[$tipoCliente])):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array($tipoCliente => "Deve-se passar o objeto {$tipoCliente}."),
            ), REST_Controller::HTTP_BAD_REQUEST);
        else:
            $validacao == 'Proposta' ? $pessoa = dataOrganizeProposta($datas) : $pessoa = dataOrganizeCotacao($datas);
            $pessoa = $pessoa[$tipoCliente];
            $verficar = $tipoCliente == 'segurado' ? $this->Model_cliente->get($pessoa['clicpfcnpj']) : FALSE;
            $tipoValidacao = $tipoCliente . $validacao;

            $this->form_validation->set_data($datas[$tipoCliente]);

            if ($this->form_validation->run($tipoValidacao) == false):
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
            elseif (!$verficar):
                $pessoa['dtcreate'] = date('Y-m-d H:i:s');
                if ($tipoCliente == 'segurado'):
                    $id = $this->{$model}->insert($pessoa);
                    if (!$id):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'message' => array($pessoa => 'Error ao cadastrar')
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    else:
                        return $id;
                    endif;
                else:
                    $cpfcnpj = $tipoCliente == 'proprietario' ? 'proprcpfcnpj' : 'condcpfcnpj';
                    $pessoadb = $this->{$model}->get([$cpfcnpj => $pessoa[$cpfcnpj]]);
                    if (!$pessoadb):
                        $id = $this->{$model}->insert($pessoa);
                        if (!$id):
                            return $this->response(array(
                                'status' => 'Error',
                                'cdretorno' => '013',
                                'message' => array($pessoa => 'Error ao cadastrar')
                            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        else:
                            return $id;
                        endif;
                    else:
                        return $pessoadb['id'];
                    endif;
                endif;
            else:
                if ($validacao == 'Proposta' && $tipoCliente == 'segurado'):
                    if (!$this->Model_cliente->update($pessoa, $pessoa['clicpfcnpj'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'message' => array($tipoCliente => 'Error ao atualizar')
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    endif;
                elseif ($validacao == 'Cotacao' && $tipoCliente == 'segurado'):
                    if (!$this->Model_cliente->update($pessoa, $pessoa['clicpfcnpj'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'message' => array($tipoCliente => 'Error ao atualizar')
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    endif;
                endif;
            endif;
        endif;
    }

    protected function validadb($datas)
    {
        /* CODE FIPE  */
        $data = isset($datas['veiculo']) ? $datas['veiculo'] : $datas;
        if (!$this->Model_fipeanovalor->where(array('codefipe' => $data['veiCodFipe']))->get()):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array('veiculo' => 'Fipe invalido')
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        elseif (!$this->Model_fipeanovalor->where(array('codefipe' => $data['veiCodFipe'], 'ano' => $data['veiAno']))->get()):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array('veiculo' => 'Ano Veiculo invalido')
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        elseif (!$this->Model_fipeanovalor->where(array('codefipe' => $data['veiCodFipe'], 'ano' => $data['veiAno'], 'idcombustivel' => $data['veiCdCombust']))->get()):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => array('veiculo' => 'Combustivel invalido para esse Fipe/Ano')
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        endif;


        if (isset($datas['segurado'])):

            if (isset($datas['segurado']['segCdEstCivl'])):
                if (!$this->Model_estadocivil->get($datas['segurado']['segCdEstCivl'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'segurado' => 'Segurado: Codigo do estado civil invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['segurado']['segEndCdUf'])):
                if (!$this->Model_uf->get($datas['segurado']['segEndCdUf'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'segurado' => 'Segurado: Codgio do estado invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['segurado']['segProfRamoAtivi'])):
                if (strlen($datas['segurado']['segCpfCnpj']) > 11):
                    if (!$this->Model_ramoatividade->get($datas['segurado']['segProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'segurado' => 'Segurado: Codigo do ramo de atividade invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                else:
                    if (!$this->Model_profissao->get($datas['segurado']['segProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'segurado' => 'Segurado: Codigo da profissão invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                endif;
            endif;
        endif;

        if (isset($datas['corretor'])):

            if (isset($datas['corretor']['correCdEstCivl'])):
                if (!$this->Model_estadocivil->get($datas['corretor']['correCdEstCivl'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'corretor' => 'Corretor: Codigo do estado civil  invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['corretor']['correEndCdUf'])):
                if (!$this->Model_uf->get($datas['corretor']['correEndCdUf'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'corretor' => 'Corretor: Codgio do estado invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['corretor']['correProfRamoAtivi'])):
                if (strlen($datas['corretor']['correCpfCnpj']) > 11):
                    if (!$this->Model_ramoatividade->get($datas['corretor']['correProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'corretor' => 'Corretor: Codigo do ramo de atividade invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                else:
                    if (!$this->Model_profissao->get($datas['corretor']['correProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'corretor' => 'Corretor: Codigo da profissão invalido invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                endif;
            endif;
        endif;

        if (isset($datas['proprietario'])):

            if (isset($datas['proprietario']['proprCdEstCivl'])):
                if (!$this->Model_estadocivil->get($datas['proprietario']['proprCdEstCivl'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'proprietario' => 'Proprietario do veiculo: Codigo do estado civil  invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['proprietario']['proprEndCdUf'])):
                if (!$this->Model_uf->get($datas['proprietario']['proprEndCdUf'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'proprietario' => 'Proprietario do veiculo: Codgio do estado invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['proprietario']['proprProfRamoAtivi'])):
                if (strlen($datas['proprietario']['proprCpfCnpj']) > 11):
                    if (!$this->Model_ramoatividade->get($datas['proprietario']['proprProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'proprietario' => 'Proprietario do veiculo: Codigo do ramo de atividade invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                else:
                    if (!$this->Model_profissao->get($datas['proprietario']['proprProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'proprietario' => 'Proprietario do veiculo: Codigo da profissão invalido invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                endif;
            endif;
        endif;

        if (isset($datas['condutor'])):

            if (isset($datas['condutor']['condutCdEstCivl'])):
                if (!$this->Model_estadocivil->get($datas['condutor']['condutCdEstCivl'])):
                    return $this->response(array(
                        'status' => 'Error', 'cdretorno' => '013',
                        'condutor' => 'Condutor do veiculo: Codigo do estado civil  invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['condutor']['condutEndCdUf'])):
                if (!$this->Model_uf->get($datas['condutor']['condutEndCdUf'])):
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'condutor' => 'Condutor do veiculo: Codgio do estado invalido',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                endif;
            endif;

            if (isset($datas['condutor']['condutProfRamoAtivi'])):
                if (strlen($datas['condutor']['condutCpfCnpj']) > 11):
                    if (!$this->Model_ramoatividade->get($datas['condutor']['condutProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'condutor' => 'Condutor do veiculo: Codigo do ramo de atividade invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                else:
                    if (!$this->Model_profissao->get($datas['condutor']['condutProfRamoAtivi'])):
                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'condutor' => 'Condutor do veiculo: Codigo da profissão invalido invalido',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    endif;
                endif;
            endif;
        endif;
        /* VEICULO */

        if (isset($datas['veiCdCombust'])):
            if (!$this->Model_tipoveiculo->get($datas['veiCdTipo'])):
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => 'Codigo tipo de combustivel invalido',
                ), REST_Controller::HTTP_BAD_REQUEST);
            endif;
        endif;
    }

    protected function record_db($pessoa, $datas)
    {

        foreach ($datas as $key => $value) {
            if ($value == null || empty($value) || $value == '') {
                unset($datas[$key]);
            }
        }
        $pessoa_msg = $pessoa;

        switch ($pessoa) {
            case 'segurado':

                try {
                    $pessoa = Segurado::firstOrCreate(['clicpfcnpj' => $datas['clicpfcnpj']]);

                } catch (Illuminate\Database\QueryException $e) {

                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => [$pessoa_msg => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_BAD_REQUEST);
//                    return $e;
                }
                break;
            case 'proprietario':

                try {
                    $pessoa = Proprietario::firstOrCreate(['proprcpfcnpj' => $datas['proprcpfcnpj']]);

                } catch (Illuminate\Database\QueryException $e) {
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => [$pessoa_msg => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_BAD_REQUEST);
                }


                break;
            case 'condutor':
                try {
                    $pessoa = Condutor::firstOrCreate(['condcpfcnpj' => $datas['condcpfcnpj']]);

                } catch (Illuminate\Database\QueryException $e) {
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => [$pessoa_msg => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_BAD_REQUEST);
                }

                break;
            case 'corretor':
                try {
                    $pessoa = Corretores::firstOrCreate(['corrcpfcnpj' => $datas['corrcpfcnpj']]);

                } catch (Illuminate\Database\QueryException $e) {
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => [$pessoa_msg => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_BAD_REQUEST);
                }
                break;
            default :
                return false;
        }

        try {
            $pessoa->update($datas);
        } catch (Illuminate\Database\QueryException $e) {
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => [$pessoa_msg => 'Erro ao atualizar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_BAD_REQUEST);
        }


        return $pessoa;

    }

    protected function veiculo($datas, $validacao)
    {

        $this->form_validation->reset_validation();
        $this->form_validation->set_data((isset($datas['veiculo']) ? $datas['veiculo'] : $datas));
        $datas = dataOrganize($datas);


        if ($this->form_validation->run('veiculo' . ucfirst($validacao)) == false) {
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '023',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($validacao == 'Cotacao') {
            $datas = $datas['veiculo'];


            $create = [
                "veiccodfipe" => $datas['veiccodfipe'],
                "veicano" => $datas['veicano'],
                "veictipocombus" => $datas['veictipocombus'],
                "clicpfcnpj" => $datas['clicpfcnpj'],
                "veicautozero" => $datas['veicautozero'],
                "veiccdutilizaco" => $datas['veiccdutilizaco'],
                "veiccdveitipo" => $datas['veiccdveitipo'],
            ];


            try {
                $veiculo = Veiculos::firstOrCreate($create);
            } catch (Illuminate\Database\QueryException $e) {
                return $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '013',
                    'message' => ['veiculo' => 'Ao gravar por favor contate o administrador']
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $veiculo;


        } elseif ($validacao == 'Proposta') {
            $veiculo = $datas['veiculo'];


            $cotacao = Cotacoes::find($datas['proposta']['idcotacao']);


            $veiculos = Veiculos::
            where('veicplaca', $veiculo['veicplaca'])->
            orWhere("veicrenavam", $veiculo['veicrenavam'])->
            orWhere("veicchassi", $veiculo['veicchassi'])->
            get();

            $replace = ['veiccodfipe', 'veicano', 'veictipocombus', 'veicautozero', 'veiccdveitipo', 'veiccdutilizaco'];

            foreach ($veiculo as $key => $value) {
                if (in_array($key, $replace)) {

                    $veiculo[$key] = $cotacao->veiculo->{$key};

                }
            }
            $veiculo['idstatus'] = 10;

            if (count($veiculos)) {
                $veiculo['dtupdate']= date('Y-m-d H:i:s');

                foreach ($veiculos as $veic) {

                    /*
                     * Verifica se a placa, renavam ou chassi está em um veículo com proposta ativa
                     * */
                    if ($veic->veicplaca == $veiculo['veicplaca'] &&
                        $veic->veicrenavam == $veiculo['veicrenavam'] &&
                        $veic->veicchassi == $veiculo['veicchassi'] && $veic->idstatus == 10 ||
                        $veic->veicplaca == $veiculo['veicplaca'] && $veic->idstatus == 10 ||
                        $veic->veicrenavam == $veiculo['veicrenavam'] && $veic->idstatus == 10 ||
                        $veic->veicchassi == $veiculo['veicchassi'] && $veic->idstatus == 10
                    ) {

                        return $this->response(array(
                            'status' => 'Error',
                            'cdretorno' => '013',
                            'message' => ['veiculo' => 'Existe uma proposta em aberto pra esse veiculo']
                        ), REST_Controller::HTTP_BAD_REQUEST);


                        /*
                         * Verifica se existe um veiculo com placa, renavam ou chassi sem está vinculado a uma proposta
                         */

                    } elseif (
                        $veic->veicplaca == $veiculo['veicplaca'] &&
                        $veic->veicrenavam == $veiculo['veicrenavam'] &&
                        $veic->veicchassi == $veiculo['veicchassi'] && $veic->idstatus != 10 ||
                        $veic->veicplaca == $veiculo['veicplaca'] && $veic->idstatus != 10 ||
                        $veic->veicrenavam == $veiculo['veicrenavam'] && $veic->idstatus != 10 ||
                        $veic->veicchassi == $veiculo['veicchassi'] && $veic->idstatus != 10
                    ) {

                        /*
                         * Verifica se o veiculo tem os mesmos paramentros do que está da cotacao
                         */

                        if ($veic->veiccodfipe == $cotacao->veiculo->veiccodfipe &&
                            $veic->veicano == $cotacao->veiculo->veicano &&
                            $veic->veictipocombus == $cotacao->veiculo->veictipocombus &&
                            $veic->veicautozero == $cotacao->veiculo->veicautozero &&
                            $veic->veiccdutilizaco == $cotacao->veiculo->veiccdutilizaco &&
                            $veic->veiccdveitipo == $cotacao->veiculo->veiccdveitipo
                        ) {

                            /*
                             * Verifica se o veiculo é o mesmo da cotacao
                             */

                            if ($veic->veicid != $cotacao->veicid) {
                                /*
                                 * Verifica se o veiculo da cotacao está vinculado a outras cotações
                                 */

                                if (count(Cotacoes::whereIn('veicid', $cotacao->veicid)->where('idcotacao', '<>', $cotacao->idcotacao)->get()) == 0) {
                                    $destroy_id = $cotacao->veicid;
                                    try {
                                        $cotacao->veicid = $veic->veicid;
                                        $cotacao->save();
                                        $cotacao->veiculo->update($veiculo);
                                        Veiculos::destroy($destroy_id);
                                    } catch (Illuminate\Database\QueryException $e) {
                                        return $this->response(array(
                                            'status' => 'Error',
                                            'cdretorno' => '013',
                                            'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador']
                                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                                    }

                                } else {

                                    try {
                                        $cotacao->veicid = $veic->veicid;
                                        $cotacao->save();
                                        $cotacao->veiculo->update($veiculo);


                                    } catch (Illuminate\Database\QueryException $e) {
                                        return $this->response(array(
                                            'status' => 'Error',
                                            'cdretorno' => '013',
                                            'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador']
                                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                                    }

                                }

                            } else {
//                                return $this->response(['status 3'=>$cotacao->veiculo]);

                                try {
                                    $cotacao->veiculo->update($veiculo);


                                } catch (Illuminate\Database\QueryException $e) {
                                    return $this->response(array(
                                        'status' => 'Error',
                                        'cdretorno' => '013',
                                        'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador']
                                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                                }

                            }


                        } else {

                            //placa cadastrada em outro veiculo
                            $msg = '';
                            if ($veic->veicplaca == $veiculo['veicplaca']) {
                                $msg = 'Placa: ' . $veiculo['veicplaca'];
                            } elseif ($veic->veicrenavam == $veiculo['veicrenavam']) {
                                $msg = 'Renavam: ' . $veiculo['veicrenavam'];

                            } elseif ($veic->veicchassi == $veiculo['veicchassi']) {
                                $msg = 'Chassi: ' . $veiculo['veicchassi'];

                            }


                            return $this->response(array(
                                'status' => 'Error',
                                'cdretorno' => '013',
                                'message' => ['veiculo' => 'Proposta: ' . $msg . ' já está cadastrado em outro veiculo.']
                            ), REST_Controller::HTTP_BAD_REQUEST);

                        }


                    }
                }


            } else {

//                return $this->response(['status 6'=>$veiculo]);


                try {
                    $veic = Veiculos::create($veiculo);
                    $cotacao->veicid = $veic->veicid;
                    $cotacao->save();


                } catch (Illuminate\Database\QueryException $e) {
                    return $this->response(array(
                        'status' => 'Error',
                        'cdretorno' => '013',
                        'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador']
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }


            }

            return $cotacao->veiculo;
        }


    }

    protected function valida_pessoas($pessoa, $tipo_validacao, $datas)
    {
        $this->form_validation->reset_validation();

        $this->form_validation->set_data($datas[$pessoa]);

        $datas = dataOrganize($datas);

        if ($this->form_validation->run($pessoa . $tipo_validacao) == false):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '023',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        else:
            return $this->record_db($pessoa, $datas[$pessoa]);
        endif;


    }


}
