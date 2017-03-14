<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Illuminate\Database\Capsule\Manager as DB;

require APPPATH . './libraries/REST_Controller.php';

class Gerar extends REST_Controller
{
    protected $datas = [];

    protected $isJurida = false;
    protected $fipe_valor = 0;
    protected $max_valor_aceitacao = 0;
    protected $min_valor_aceitacao = 0;
    protected $max_idade_aceitacao = 0;
    protected $tipo_veiculo = 0;
    protected $comissao = 0;
    protected $desconto = 0;
    protected $valores_lmi_aceitacao = [];
    protected $idade_veiculo = 0;
    protected $produto_master;
    protected $produto_opcionais;
    protected $ids_produto = [];
    protected $tipo_servico = '';
    protected $produtos;
    protected $lmi = [];
    protected $premio = 0;
    protected $contigencia = 0;
    protected $categoria_fipe = 0;
    protected $valores_produtos = [];
    protected $primeira_parcela = 0;
    protected $parcelas = [];
    protected $formas_pagamentos = [];
    protected $segurado;
    protected $corretor;
    protected $cotacao;
    protected $parceiro;
    protected $retorno;
    protected $renova = [];
    protected $produtos_retorno = [];
    protected $cotacao_produtos = [];
    protected $veiculo;
    protected $apiKey;


    protected $proprietario;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('message_error');

        error_reporting(E_ERROR);

        date_default_timezone_set('America/Sao_Paulo');

    }


    /**
     * @return arrayidproduto
     */


    function cotacao_post()
    {

        DB::beginTransaction();

        try {
            $this->apiKey = $_SERVER['HTTP_X_API_KEY'];
            $this->datas = $this->post();
            $this->validaParceiro();            
            $this->setTipoPessoa();
            $this->setTipoServico('cotacao');
            $this->load->library('form_validation');
            $this->setDatas();
            $this->setDesconto();
            $this->setParamsVeiculoValidacao();
            $this->setProdutos();
            $this->setAceitacaoSeguradora();
            $this->setComissao();
            $this->setProdutoValores();
            $this->setFormasPagamento();
            $this->setParcelas();
            $this->setSegurado();
            $this->setCorretor();

            $cotacao = new Cotacoes;
            $veiculo = $this->datas['veiculo'];
            $cotacao->validade = date('Y-m-d', strtotime('+30 days'));
            $cotacao->premio = $this->premio;
            $cotacao->comissao = $this->comissao;
            $cotacao->idparceiro = $this->parceiro->idparceiro;
            $cotacao->idcorretor = $this->corretor->idcorretor;
            $cotacao->segurado_id = $this->segurado->id;
            $cotacao->renova = $this->renova;
            $cotacao->code_fipe = $veiculo['veiccodfipe'];
            $cotacao->ano_veiculo = $veiculo['veicano'];
            $cotacao->combustivel_id = $veiculo['veictipocombus'];
            $cotacao->tipo_veiculo_id = $veiculo['veiccdveitipo'];
            $cotacao->ind_veiculo_zero = $veiculo['veicautozero'];
            $cotacao->idstatus = 9;
            $cotacao->save();

            foreach ($this->cotacao_produtos as $cotacao_p) {
                $cotacao->produtos()->create($cotacao_p);
            }
            $cotacao->save();

            $response = [
                'cdCotacao' => $cotacao->idcotacao,
                'idparceiro' => $cotacao->idparceiro,
                'validade' => $cotacao->validade,
                'premio' => $this->premio,
                'produto' => $this->produtos_retorno,
                'formapagamento' => $this->parcelas['formapagamento']

            ];


            DB::commit();

            $this->response(array(
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => $response,
            ));

        } catch (Exception $e) {
            DB::rollBack();

            $this->response(['status' => 'Error',
                'cdretorno' => '513',
                'message' => 'Error ao gerar a cotação, porfavor contact o administrador!', $e], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);


        }


    }

    function proposta_post()
    {

        $this->apiKey = $_SERVER['HTTP_X_API_KEY'];
        $this->datas = $this->post();
        $this->validaParceiro();
        $this->setTipoPessoa();
        $this->setTipoServico('proposta');
        $this->load->library('form_validation');
        $this->setDatas();
        $this->setFormasPagamento();
        $this->setProdutoProposta();
        $this->premio = $this->cotacao->premio;
        $this->renova = $this->cotacao->renova;
        $this->setParcela();
        $this->setSegurado();
        $this->setVeiculo();

        DB::beginTransaction();
        try {
            $this->cotacao->segurado_id = $this->segurado->id;
            $this->cotacao->save();
            $proposta = new Propostas();
            $proposta->idcotacao = $this->cotacao->idcotacao;
            $proposta->idformapg = $this->formas_pagamentos->idformapgto;
            $proposta->quantparc = $this->datas[$this->tipo_servico]['quantparc'];
            $proposta->dtvalidade = date('Y-m-d', strtotime('+30 days'));
            $proposta->idstatus = 10;
            $proposta->nmbandeira = $this->datas[$this->tipo_servico]['nmbandeira'];
            $proposta->numcartao = $this->datas[$this->tipo_servico]['numcartao'];
            $proposta->validadecartao = $this->datas[$this->tipo_servico]['validadecartao'];
            $proposta->premiototal = $this->premio;
            $proposta->primeiraparc = $this->parcelas['formapagamento']['primeira'];
            $proposta->demaisparc = $this->parcelas['formapagamento']['demais'];
            $proposta->titularcartao = $this->datas[$this->tipo_servico]['titularcartao'];
            $proposta->cvvcartao = $this->datas[$this->tipo_servico]['cvvcartao'];
            $proposta->veiculo_id = $this->veiculo->veicid;
            $proposta->save();


            $response = [
                'premioTotal' => $this->premio,
                'idproposta' => $proposta->idproposta,
                'idcotacao' => $proposta->idcotacao,
                'dtvalidade' => $proposta->dtvalidade,
                'idparceiro' => $this->datas[$this->tipo_servico]['idparceiro'],
                'produto' => $this->produtos_retorno,
                'formapagamento' => $this->parcelas['formapagamento'],
            ];

            DB::commit();

            $this->response(array(
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => $response,
            ));


        } catch (Exception $e) {
            DB::rollBack();
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => ['proposta' => 'Error ao cadastrar o proposta!'],
                'error' => $e
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);


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
        //        

        $this->load->library('form_validation');
        $this->form_validation->set_data($datas);

        if ($this->form_validation->run('pdf') == false):
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '023',
                'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        else:

            try {
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

                ])->with_veiculo([
                    'with' => [
                        ['relation' => 'fipe',
                            'with' => [
                                ['relation' => 'valores'],
                                ['relation' => 'contigencia'],
                            ]
                        ],
                        ['relation' => 'combustivel'],
                        ['relation' => 'utilizacao'],
                        ['relation' => 'proprietario']
                    ]])->with_forma_pagamento()->get($datas['idProposta']);

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


                $b64encode = chunk_split(base64_encode($this->m_pdf->pdf->Output('pdfteste.pdf', 'S')));


                $this->response(array(
                    'status' => '000 - sucesso',
                    'cdretorno' => '000',
                    'idproposta' => $proposta['proposta']['idproposta'],
                //                'idparceiro' => $parceiro['idparceiro'],
                    'base64' => $b64encode,
                ));


            } catch (Exception $e) {
                $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '023',
                    'message' => $e), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }


            //            $this->response(array(
            //                $veiculo, $combustivel, $utilizacao  
            //            ));

        endif;
    }

    protected function validaParceiro(){
        $parceiro = Parceiros::with('keys')->find($this->datas['idParceiro']);
        
        if(!$parceiro){
            $this->response(array(
                    'cdretorno' => '045',
                    'status' => 'Error',
                    'message' => 'idParceiro inválido!'), REST_Controller::HTTP_BAD_REQUEST);
        } else if($parceiro->keys->key != $this->apiKey){
            $this->response(array(
                    'cdretorno' => '045',
                    'status' => 'Error',
                    'message' => 'API-KEY inválida para este idParceiro!'), REST_Controller::HTTP_BAD_REQUEST);
        }
        $this->parceiro = $parceiro;
        
    }
    protected function setDatas()
    {

        
        /*begin - validações de from*/
        
        $pessoa = $this->isJurida ? 'PJ' : '';
        $segurado_validacao = 'segurado' . ucfirst($this->tipo_servico) . $pessoa;
        $validacoes = [
            'proposta' => [
                ['validacao' => 'proposta', 'cod_error' => '023', 'key' => 'proposta'],
                ['validacao' => $segurado_validacao, 'cod_error' => '013', 'key' => 'segurado'],
                ['validacao' => 'veiculoProposta', 'cod_error' => '013', 'key' => 'veiculo'],
            ],
            'cotacao' => [
                ['validacao' => 'cotacao', 'cod_error' => '023', 'key' => ''],
                ['validacao' => $segurado_validacao, 'cod_error' => '013', 'key' => 'segurado'],
                ['validacao' => 'veiculoCotacao', 'cod_error' => '023', 'key' => 'veiculo'],
                ['validacao' => 'corretor', 'cod_error' => '023', 'key' => 'corretor'],
            ],
        ];

        foreach ($validacoes[$this->tipo_servico] as $validacao) {
            $datas = isset($this->datas[$validacao['key']]) ? $this->datas[$validacao['key']] : $this->datas;

            $this->form_validation->reset_validation();

            $this->form_validation->set_data($datas);

            if ($this->form_validation->run($validacao['validacao']) == false) {
                $this->response(array(
                    'cdretorno' => $validacao['cod_error'],
                    'status' => 'Error',
                    'message' => $this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        /*end - validações de from*/

        /*begin - validações DB*/

        $parceiro = $this->parceiro;

        $this->datas = dataOrganize($this->datas);


        $fipe_ano = FipeAnoValor::where('codefipe', $this->datas['veiculo']['veiccodfipe'])
            ->where('ano', $this->datas['veiculo']['veicano'])
            ->where('idcombustivel', $this->datas['veiculo']['veictipocombus'])
            ->first();

        $fipe = Fipes::find($this->datas['veiculo']['veiccodfipe']);
        $cotacao = '';
        $id_cotacao = 0;
        if ($this->tipo_servico == 'proposta') {
            $id_cotacao = $this->datas[$this->tipo_servico]['idcotacao'];
            $ids_parceiro = $this->datas[$this->tipo_servico]['idparceiro'] == 99 ? [99, 2, 1, 3] : [$this->datas[$this->tipo_servico]['idparceiro']];
            $cotacao = Cotacoes::whereIn('idparceiro', $ids_parceiro)
                ->where('idcotacao', $this->datas[$this->tipo_servico]['idcotacao'])->first();
            $this->cotacao = $cotacao;

        }


        $validacoes_db = [

            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '013', 'verifica' => $fipe, 'message' => 'Veiculo: Fipe invalido'],
            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '010', 'verifica' => ($fipe->idstatus != 29), 'message' => 'Veiculo: Não tem aceitação para esse veiculo'],
            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '010', 'verifica' => ($fipe->tipoveiculo_id == $this->datas['veiculo']['veiccdveitipo']), 'message' => 'Veiculo: Essa fipe não é para esse tipo de veículo!'],
            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '013', 'verifica' => $fipe_ano, 'message' => 'Veiculo: Combustivel invalido para esse Fipe/Ano'],
            ['validacao' => 'veiculo', 'tipo' => 'proposta', 'cod_error' => '013', 'verifica' => TipoUtilizacaoVeic::find($this->datas['veiculo']['veiccdutilizaco']), 'message' => 'Veiculo: Tipo de utilização inválida!'],
            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '013', 'verifica' => TipoVeiculos::find($this->datas['veiculo']['veiccdveitipo']), 'message' => 'Veiculo: Tipo de veículo inválida!'],
            ['validacao' => 'veiculo', 'tipo' => 'todos', 'cod_error' => '013',
                'verifica' => FipeAnoValor::where('codefipe', $this->datas['veiculo']['veiccodfipe'])->where('ano', $this->datas['veiculo']['veicano'])->first(),
                'message' => 'Veiculo: Ano Veiculo invalido'],
            ['validacao' => 'cotacao', 'tipo' => 'proposta', 'cod_error' => '015', 'verifica' => $cotacao, 'message' => 'Cotacao Nº: ' . $id_cotacao . ' Inválido!'],
            ['validacao' => 'cotacao', 'tipo' => 'proposta', 'cod_error' => '015', 'verifica' => $cotacao->idstatus == 9, 'message' => 'Já existe uma proposta para essa cotação']


        ];


        foreach ($validacoes_db as $validacao) {
            if ($validacao['tipo'] == 'todos' && !$validacao['verifica']) {
                $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => $validacao['validacao'],
                    'message' => [$validacao['cod_error'] => $validacao['message']]
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            } elseif ($validacao['tipo'] == $this->tipo_servico && !$validacao['verifica']) {
                $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => $validacao['validacao'],
                    'message' => [$validacao['cod_error'] => $validacao['message']]
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        /*end - validações DB*/

        $this->contigencia = Contingencia::where('ind_idstatus_fipe', $fipe->idstatus)->first()->valor;
        $this->fipe_valor = $fipe_ano->valor;
        $this->parceiro = $parceiro;


    }

    protected function setProdutos()
    {

        foreach ($this->datas['produto'] as $produto) {
            $this->ids_produto[] = $produto['idProduto'];
            if (isset($produto['valorLmiProduto']) && $produto['valorLmiProduto'] > 0) {
                $this->lmi[$produto['idProduto']] = $produto['valorLmiProduto'];
            }
        }

        $produtos = Produtos::with('precoproduto', 'combos')->whereIn('idproduto', $this->ids_produto)->where('tipoproduto', 'master')->get();


        if ($produtos->count() > 1) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '040',
                'message' => array(
                    'produtos' => 'Cotacao só aceita um produto master',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);

        } elseif ($produtos->count() < 1) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '040',
                'message' => array(
                    'produtos' => 'Cotacao exige contratação do produto Seguro AUTOPRATICO Roubo e Furto',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        }

        $this->produto_master = $produtos->first();

        if ($this->produto_master->codstatus == 2) {
            $this->response(array(
                'status' => 'Atenção',
                'cdretorno' => '009',
                'message' => "O Produto {$this->produto_master->nomeproduto} não está ativo NO MOMENTO, refaça a sua  cotação SEM ESTA COBERTURA. Em breve ofereceremos novamente esta cobertura opcional.",
            ), REST_Controller::HTTP_BAD_REQUEST);
        }

        $this->produto_master->desconto = $this->desconto;
        $this->produtos[$this->produto_master->idproduto] = $this->produto_master;
        $opcionais_aceito = $this->produto_master->combos->where('tipo_veiculo_id', $this->tipo_veiculo)->pluck('idprodutomaster', 'idprodutoopcional');
        $this->valores_produtos = array_merge($this->valores_produtos, $this->produto_master->precoproduto->toArray());


        foreach ($this->ids_produto as $key => $id) {
            if (!isset($opcionais_aceito[$id])) {
                unset($this->ids_produto[$key]);
            }
        }

        $this->produto_opcionais = Produtos::with('precoproduto')->whereIn('idproduto', $this->ids_produto)->where('tipoproduto', '!=', 'master')->get();

        foreach ($this->produto_opcionais as $produto) {

            if ($produto->codstatus == 2) {
                $this->response(array(
                    'status' => 'Atenção',
                    'cdretorno' => '009',
                    'message' => "O Produto {$produto->nomeproduto} não está ativo NO MOMENTO, refaça a sua  cotação SEM ESTA COBERTURA. Em breve ofereceremos novamente esta cobertura opcional.",
                ), REST_Controller::HTTP_BAD_REQUEST);
            }

            $this->valores_lmi_aceitacao = array_merge($this->valores_lmi_aceitacao, $produto->precoproduto->where('lmiproduto', '>', 0)->pluck('lmiproduto')->toArray());

            if (isset($this->lmi[$produto->idproduto]) && !in_array($this->lmi[$produto->idproduto], $this->valores_lmi_aceitacao)) {
                $this->response(array(
                    'status' => 'Error',
                    'cdretorno' => '009',
                    'message' => "O Produto {$produto->idproduto} - {$produto->nomeproduto} só aceita lmi 50000, 100000 ou 200000",
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
            $produto->desconto = 0;

            $this->produtos[$produto->idproduto] = $produto;
            $this->valores_produtos = array_merge($this->valores_produtos, $produto->precoproduto->toArray());

        }

    }

    protected function setParamsVeiculoValidacao()
    {
        $ano = date('Y');

        if ($this->datas['veiculo']['veicano'] == 0 || $this->datas['veiculo']['veicano'] > $ano) {
            $this->idade_veiculo = 0;
        } else {
            $this->idade_veiculo = $ano - $this->datas['veiculo']['veicano'];
        }
        $this->tipo_veiculo = $this->datas['veiculo']['veiccdveitipo'];

    }

    protected function setTipoPessoa()
    {

        if (strlen($this->datas['segurado']['segCpfCnpj']) > 11) {
            $this->isJurida = true;
        }

    }

    protected function setAceitacaoSeguradora()
    {

        $this->max_valor_aceitacao = $this->produto_master->precoproduto->max('vlrfipemaximo');
        $this->max_idade_aceitacao = $this->produto_master->precoproduto->max('idadeaceitamax');
        $this->min_valor_aceitacao = $this->produto_master->precoproduto->min('vlrfipeminimo');

        if ($this->fipe_valor > $this->max_valor_aceitacao) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Esse produto não aceita item com valor fipe superior a R$ ' . real($this->max_valor_aceitacao),
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else if ($this->fipe_valor < $this->min_valor_aceitacao) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Esse produto não aceita item com valor fipe inferior a R$ ' . real($this->min_valor_aceitacao),
                )
            ), REST_Controller::HTTP_BAD_REQUEST);

        } else if ($this->idade_veiculo > $this->max_idade_aceitacao) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Não tem aceitação para veiculos com idade acima de ' . $this->max_idade_aceitacao . ' anos invalido',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);

        }


    }

    protected function setComissao($comissao = NULL)
    {
        if ($comissao != NULL) {
            $this->comissao = $comissao;
        }

        $this->comissao = $this->datas[$this->tipo_servico]['comissao'];

    }

    protected function setDesconto()
    {
        if ($this->datas[$this->tipo_servico]['renova'] == 1) {
            $this->desconto = Descontos::where('tipo', 'renova')->first()->valor;
        }
        $this->renova = $this->datas[$this->tipo_servico]['renova'];

    }

    protected function setTipoServico($servico)
    {
        $this->tipo_servico = $servico;
    }

    protected function setProdutoValores()
    {

        $valor_fipe = $this->fipe_valor;
        $tipo = $this->tipo_veiculo;
        $idade = $this->idade_veiculo;
        $categoria = $this->categoria_fipe;
        $comissao = $this->comissao;
        $contigencia = $this->contigencia;
        $produtos = [];
        $cotacao_produto = [];


        foreach ($this->valores_produtos as $key => $valor) {
            $lmi = $this->lmi[$valor['idproduto']];

            if (!$valor['idcategoria'] == $categoria) {
                $lmi = 0;
                $valor['lmiproduto'] = 0;
                $categoria = null;
            }

            $produt_ = [];


            if (between($valor_fipe, $valor['vlrfipemaximo'], $valor['vlrfipeminimo']) && $valor['idtipoveiculo'] == $tipo && $idade < $valor['idadeaceitamax'] && $valor['idcategoria'] == $categoria && $valor['lmiproduto'] == $lmi) {

                if ($valor['idproduto'] == 1) {
                    $valor['premioliquidoproduto'] += $contigencia;
                }

                $valor['premioliquidoproduto'] -= $this->produtos[$valor['idproduto']]->desconto;
                $this->valores_produtos[$key]['premioliquidoproduto'] = aplicaComissao($valor['premioliquidoproduto'], $comissao);
                $this->premio += $this->valores_produtos[$key]['premioliquidoproduto'];
                $this->primeira_parcela += $this->valores_produtos[$key]['vlrminprimparc'];
                $produt_ = $this->valores_produtos[$key];
                $this->produtos[$valor['idproduto']]->valor = (object)$this->valores_produtos[$key];

            } else if ($valor['vlrfipeminimo'] == $tipo && $valor['vlrfipemaximo'] == null && $valor['vlrfipeminimo'] == null && $idade < $valor['idadeaceitamax']) {
                $this->valores_produtos[$key]['premioliquidoproduto'] = aplicaComissao($valor[$key]['premioliquidoproduto'], $comissao);
                $this->premio += $this->valores_produtos[$key]['premioliquidoproduto'];
                $this->primeira_parcela += $this->valores_produtos[$key]['vlrminprimparc'];
                $produt_ = $this->valores_produtos[$key];
                $this->produtos[$valor['idproduto']]->valor = (object)$this->valores_produtos[$key];


            }

            if (count($produt_) > 0) {
                $produtos[] = [
                    "idproduto" => $produt_['idproduto'],
                    "nomeproduto" => $produt_['nomeproduto'],
                    "caractproduto" => $this->produtos[$produt_['idproduto']]->cractproduto,
                    "porcentindenizfipe" => $produt_['porcentfipepremio'],
                    "ind_exige_vistoria" => $this->produtos[$produt_['idproduto']]->ind_exige_vistoria,
                    "ind_exige_rastreador" => $produt_['indobrigrastreador'],
                    "indexigenciavistoria" => $this->produtos[$produt_['idproduto']]->ind_exige_vistoria,
                    "indobrigrastreador" => $produt_['indobrigrastreador'],
                    "premioliquidoproduto" => $produt_['premioliquidoproduto'],
                ];

                $cotacao_produto[] = [
                    'idproduto' => $produt_['idproduto'],
                    'idprecoproduto' => $produt_['idprecoproduto'],
                    'premioliquidoproduto' => $produt_['premioliquidoproduto'],
                    'dtcreate' => date('Y-m-d H:i:s'),

                ];

            }

        }

        if ($this->premio == 0) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '005',
                'message' => 'Produtos não encontrado',
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->cotacao_produtos = $cotacao_produto;
            $this->produtos_retorno = $produtos;
        }

    }

    protected function setFormasPagamento()
    {
        if ($this->tipo_servico == 'cotacao') {
            $this->formas_pagamentos = FormaPagamento::all();
        } elseif ($this->tipo_servico == 'proposta') {
            $this->formas_pagamentos = FormaPagamento::find($this->datas[$this->tipo_servico]['idformapg']);
            if (!$this->formas_pagamentos) {
                $this->response(['cdretorno' => '015',
                    'status' => 'Error',
                    'message' => 'Forma de pagamento invalida!'], REST_Controller::HTTP_BAD_REQUEST);
            }
        }

    }

    protected function setParcelas()
    {
        $parcelas = [];
        $premio = $this->premio;
        $c = 0;
        foreach ($this->formas_pagamentos as $forma) {
            $juros = $forma->taxamesjuros;
            for ($i = 1; $i <= $forma->numparcsemjuros; $i++) {
                $parcelas['formapagamento'][$c]['parcela']['tipo'] = $forma->descformapgto;
                $parcelas['formapagamento'][$c]['parcela']['quantidade'] = $i;
                $parcelas['formapagamento'][$c]['parcela']['primeira'] = floatN($premio / $i);
                $parcelas['formapagamento'][$c]['parcela']['demais'] = ($i == 1) ? 0 : floatN($premio / $i);
                $parcelas['formapagamento'][$c]['parcela']['juros'] = 0;
                $parcelas['formapagamento'][$c]['parcela']['total'] = floatN($premio);

                $c++;
            }
            for ($i = $forma->numparcsemjuros + 1; $i <= $forma->nummaxparc; $i++) {

                $primeira = jurosComposto($premio, $juros, $i);
                $demais = $primeira;
                $premio_ = $primeira * $i;
                if ($primeira < $this->primeira_parcela && $this->renova == 0 && $forma->idformapgto == 2) {
                    $primeira = $this->primeira_parcela;
                    $demais = ($premio_ - $primeira) / ($i - 1);
                }
                $parcelas['formapagamento'][$c]['parcela']['tipo'] = $forma->descformapgto;
                $parcelas['formapagamento'][$c]['parcela']['quantidade'] = $i;
                $parcelas['formapagamento'][$c]['parcela']['primeira'] = floatN($primeira);
                $parcelas['formapagamento'][$c]['parcela']['demais'] = floatN($demais);
                $parcelas['formapagamento'][$c]['parcela']['juros'] = $juros;
                $parcelas['formapagamento'][$c]['parcela']['total'] = floatN($premio_);

                $c++;
            }
        }
        $this->parcelas = $parcelas;
        $this->premio = floatN($this->premio);

    }

    protected function setParcela()
    {
        $quantidade = $this->datas[$this->tipo_servico]['quantparc'];
        $forma = $this->formas_pagamentos;
        $semjuros = $forma->numparcsemjuros;
        $juros = $quantidade > $semjuros ? $forma->taxamesjuros : 0;
        $premio = $this->premio;

        $primeira = $quantidade > $semjuros ? jurosComposto($premio, $juros, $quantidade) : $premio / $quantidade;
        $demais = $quantidade == 1 ? 0 : $primeira;
        $premio_ = $primeira * $quantidade;
        if ($primeira < $this->primeira_parcela && $this->renova == 0 && $forma->idformapgto == 2) {
            $primeira = $this->primeira_parcela;
            $demais = ($premio_ - $primeira) / ($quantidade - 1);
        }
        $parcelas['formapagamento']['tipo'] = $forma->descformapgto;
        $parcelas['formapagamento']['quantidade'] = $quantidade;
        $parcelas['formapagamento']['primeira'] = floatN($primeira);
        $parcelas['formapagamento']['demais'] = floatN($demais);
        $parcelas['formapagamento']['juros'] = $juros;
        $this->premio = floatN($premio_);

        $this->parcelas = $parcelas;
        $this->premio = floatN($this->premio);


    }

    protected function setSegurado()
    {
        try {
            DB::beginTransaction();
            $segurado = pullOutEmpty($this->datas['segurado']);
            $segurado_up = $segurado;
            unset($segurado_up['clicpfcnpj']);
            $segurado_db = Segurado::firstOrCreate(['clicpfcnpj' => $segurado['clicpfcnpj']]);
            if(count($segurado_up)>0){
                $segurado_db->update($segurado);
            }
            $this->segurado = Segurado::where('clicpfcnpj',$segurado_db->clicpfcnpj)->first();
            DB::commit();
        } catch (Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => ['Segurado' => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);


        }
    }

    protected function setCorretor()
    {
        try {
            $corretor = pullOutEmpty($this->datas['corretor']);
            $this->corretor = Corretores::firstOrCreate(['corrcpfcnpj' => $corretor['corrcpfcnpj']]);
            $this->corretor->update($corretor);

        } catch (Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => ['Corretor' => 'Erro ao cadastrar : ' . $e->errorInfo[2]]), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);


        }
    }

    protected function setProdutoProposta()
    {
        $produtos = $this->cotacao->produtos;
        $produto_ = [];
        foreach ($produtos as $produto) {
            $preco = $produto->produto->precoproduto()->where('idprecoproduto', $produto->idprecoproduto)->first();
            $this->primeira_parcela += $preco->vlrminprimparc;
            $produto_[] = [
                "idproduto" => $produto->idproduto,
                "nomeproduto" => $preco->nomeproduto,
                "caractproduto" => $produto->produto->caractproduto,
                "porcentindenizfipe" => $preco->porcentfipepremio,
                "ind_exige_vistoria" => $produto->produto->ind_exige_vistoria,
                "ind_exige_rastreador" => $preco->indobrigrastreador,
                "indexigenciavistoria" => $produto->produto->ind_exige_vistoria,
                "indobrigrastreador" => $preco->indobrigrastreador,
            ];
        }

        $this->produtos_retorno = $produto_;

    }

    protected function setVeiculo()
    {
        $veiculo_ = $this->datas['veiculo'];
        $cotacao = $this->cotacao;

        

        $veiculo = Veiculos::where('veicplaca', $veiculo_['veicplaca'])
            ->orWhere('veicrenavam', $veiculo_['veicrenavam'])
            ->orWhere('veicchassi', $veiculo_['veicchassi'])
            ->get();
            
        if($veiculo->count() > 1 ){
            $msg = [];
            foreach ($veiculo as $veic ){
                if($veic->veicplaca == $veiculo_['veicplaca']){
                    $msg[]='Placa: ' .$veiculo_['veicplaca'] .' cadastrada em veículo diferente';
                } elseif($veic->veicrenavam == $veiculo_['veicrenavam']){
                    $msg[]='Renavam: ' .$veiculo_['veicrenavam'] .' cadastrado em veículo diferente';
                    
                } elseif($veic->veicchassi == $veiculo_['veicchassi']){
                    $msg[]='Chassi: ' .$veiculo_['veicchassi'] .' cadastrado em veículo diferente';
                }                
            }
            $message = 'Veículo - '. implode(', ',$msg).'!'; 

        }elseif($veiculo->count() == 1) {
            $veiculo = $veiculo->first();
        } else {
            $veiculo = new Veiculos();            
        }

        if ($veiculo && $veiculo->idstatus == 10) {
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => ['veiculo' => 'Existe uma proposta em aberto pra esse veiculo']
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        DB::beginTransaction();
        try {

            $veiculo->veiccodfipe = $cotacao->code_fipe;
            $veiculo->veicano = $cotacao->ano_veiculo;
            $veiculo->veicautozero = $cotacao->ind_veiculo_zero;
            $veiculo->veiccdveitipo = $cotacao->tipo_veiculo_id;
            $veiculo->veictipocombus = $cotacao->combustivel_id;
            $veiculo->veiccdutilizaco = $veiculo_['veiccdutilizaco'];
            $veiculo->veicplaca = $veiculo_['veicplaca'];
            $veiculo->veicmunicplaca = $veiculo_['veicmunicplaca'];
            $veiculo->veiccdufplaca = $veiculo_['veiccdufplaca'];
            $veiculo->veicrenavam = $veiculo_['veicrenavam'];
            $veiculo->veicanorenavam = $veiculo_['veicanorenavam'];
            $veiculo->veicchassi = $veiculo_['veicchassi'];
            $veiculo->veicchassiremar = $veiculo_['veicchassiremar'];
            $veiculo->veicleilao = $veiculo_['veicleilao'];
            $veiculo->veicalienado = $veiculo_['veicalienado'];
            $veiculo->veicacidentado = $veiculo_['veicacidentado'];
            $veiculo->nome_proprietario = $veiculo_['nome_proprietario'];
            $veiculo->clicpfcnpj = $this->segurado->clicpfcnpj;
            $veiculo->propcpfcnpj = $veiculo_['propcpfcnpj'];
            $veiculo->idstatus = 10;
            $veiculo->veianofab = $veiculo_['veianofab'];
            $veiculo->veicor = $veiculo_['veicor'];
            $veiculo->save();
            $this->veiculo = $veiculo;
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $this->response(array(
                'status' => 'Error',
                'cdretorno' => '013',
                'message' => ['veiculo' => 'Error ao cadastrar o veiculo!'],
                'error' => $e
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }


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
                //return $check['opcionais'];
                foreach ($check['opcionais'] as $opcional) {
                    $opcionais[] = $opcional['idprodutoopcional'];
                }

            } elseif ($check['tipoproduto'] == 'master' && $master == true) {
                unset($produto[$key]);
                //unset($prodcheck[$key]);
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
            ->where(array('codefipe' => $veiculo['veiccodfipe'], 'ano' => $veiculo['veicano']))
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

        if (!Fipes::where('idstatus', '!=', 29)->where('codefipe', $veiculo['veiccodfipe'])->first()):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Não tem aceitação para esse veiculo',
                )
            ), REST_Controller::HTTP_BAD_REQUEST);
        endif;

        if (!$valorfipe):
            return $this->response(array(
                'status' => 'Error',
                'cdretorno' => '010',
                'message' => array(
                    'veiculo' => 'Fipe ou ano do modelo do veiculo invalido Ano: ' . $veiculo['veicano'],
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
        $idade = ($veiculo['veicano'] == 0 ? $veiculo['veicano'] : date('Y') - $veiculo['veicano']);
        $comissao = $datas['cotacao']['comissao'];
        $renova = $datas[$tipo]['renova'];
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

        //            elseif ($produtodb['idtipoveiculo'] != $veiculo['veiccdveitipo']):
        //                return $this->response(array(
        //                    'status' => 'Error',
        //                    'cdretorno' => '009',
        //                    'message' => "O Tipo de veículo {$veiculo['veiccdveitipo']} é inválido para o produto {$idproduto} - {$produtodb['nomeproduto']}",
        //                ), REST_Controller::HTTP_BAD_REQUEST);


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

                    /*aplicando desconto*/
                    if ($produtodb['tipoproduto'] == 'master' && $renova == 1) {
                        $preco['premioliquidoproduto'] = $preco['premioliquidoproduto'] - Descontos::where('tipo', 'renova')->first()->valor;
                    }

                    if ($valorfipe >= $preco['vlrfipeminimo'] && $valorfipe <= $preco['vlrfipemaximo'] && $preco['idcategoria'] == ($preco['idcategoria'] == $categoria['idcategoria'] ? $categoria['idcategoria'] && $preco['lmiproduto'] == $prolmi : null) && $idade <= max($maxidade) && $preco['idtipoveiculo'] == $tipoveiculo):

                        if ($idproduto == 1) {

                            $preco['premioliquidoproduto'] = $preco['premioliquidoproduto'] + $contigencia;

                        }
                        $preco['premioliquidoproduto'] = aplicaComissao($preco['premioliquidoproduto'], $comissao);


                        $produtos['cotacaoproduto'][$i]['premioliquidoproduto'] = $preco['premioliquidoproduto'];

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
                        $produtos['cotacaoproduto'][$i]['premioliquidoproduto'] = $preco['premioliquidoproduto'];
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

            if ($menorparcela > $parcelaj && $proposta['idformapg'] == 2 && $renova == 0):
        //               
                $valor_juros_total = $parcelaj * $proposta['quantparc'];
                $parcelaj = ($valor_juros_total - $menorparcela) / ($proposta['quantparc'] - 1);
                $produtos['premioTotal'] = $valor_juros_total;
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
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['total'] = $premio;

                            $c++;
                        endfor;
                    elseif ($key == 'nummaxparc'):
                        for ($i = $parc; $i <= $val; $i++):

                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['tipo'] = $tipo;
                            $produtos['parcelamento']['formapagamento'][$c]['parcela']['quantidade'] = $i;

                            if (jurosComposto($premio, $juros, $i) < $menorparcela && $idforma == 2 && $renova == 0):
                                $valor_juros_total = floatN(jurosComposto($premio, $juros, $i) * $i);
                                $parcelajuros = floatN(($valor_juros_total - $menorparcela) / ($i - 1));
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['primeira'] = floatN($menorparcela);
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = $parcelajuros;
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['total'] = $valor_juros_total;
                            else:
                                $valor_juros_total = floatN(jurosComposto($premio, $juros, $i) * $i);
                                $parcelajuros = floatN(($valor_juros_total - $menorparcela) / ($i - 1));
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['primeira'] = $parcelajuros;
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['demais'] = $parcelajuros;
                                $produtos['parcelamento']['formapagamento'][$c]['parcela']['total'] = $valor_juros_total;

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


        $cotacao = $this->Model_cotacao->with_produtos(['with' =>
            ['relation' => 'produto',
                'with' => [
                    ['relation' => 'precos'],
                    ['relation' => 'seguradoras',
                        'with' => ['relation' => 'seguradora']
                    ],
                ]
            ]])->with_veiculo()->get(['idcotacao' => $datas["cdCotacao"]]);


        //        $this->veiculodb($datas, 'proposta');


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
                    $pessoa = Proprietario::firstOrCreate($datas);

                    $this->proprietario = $pessoa->id;

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

            $replace = ['veiccodfipe', 'veicano', 'veictipocombus', 'veicautozero', 'veiccdveitipo'];

            foreach ($veiculo as $key => $value) {
                if (in_array($key, $replace)) {

                    $veiculo[$key] = $cotacao->veiculo->{$key};

                }
            }


            if (count($veiculos)) {
                $veiculo['dtupdate'] = date('Y-m-d H:i:s');

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
                            $veic->veiccdveitipo == $cotacao->veiculo->veiccdveitipo
                        ) {

                            /*
                             * Verifica se o veiculo é o mesmo da cotacao
                             */

                            if ($veic->veicid != $cotacao->veicid) {
                                /*
                                 * Verifica se o veiculo da cotacao está vinculado a outras cotações
                                 */

                                if (count(Cotacoes::where('veicid', $cotacao->veicid)->where('idcotacao', '<>', $cotacao->idcotacao)->get()) == 0) {
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
                                            'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador'],
                                            'error' => $e
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
                                            'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador'],
                                            'error' => $e
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
                                        'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador'],
                                        'error' => $e
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
                        'message' => ['veiculo' => 'Ao atualizar por favor contate o administrador'],
                        'error' => $e
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
