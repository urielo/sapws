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
                "numero_certificado" =>str_pad($certificado->id, 20, 0, STR_PAD_LEFT) ,
                "ano" => $certificado->proposta->cotacao->veiculo->veicano,
                "combustivel" => $certificado->proposta->cotacao->veiculo->combustivel->sigla,
                "numero_capitalizacao" => NULL,
                "numero_serie_sorteio" => NULL,
                "chassi" => $certificado->proposta->cotacao->veiculo->veicchassi,
                "renavam" => $certificado->proposta->cotacao->veiculo->veicrenavam,
                "placa" => $certificado->proposta->cotacao->veiculo->veicplaca,
                "marca" => $certificado->proposta->cotacao->veiculo->fipe->marca,
                "modelo" => $certificado->proposta->cotacao->veiculo->fipe->modelo,
                "cod_fipe" => $certificado->proposta->cotacao->veiculo->fipe->codefipe,
                "cor" => $certificado->proposta->cotacao->veiculo->veicor,
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
                "endereco" => $certificado->proposta->cotacao->segurado->clinmend,
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
                $dados['coberturas'][] = [
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


            $retorno[] = array_merge($dados, $assitencias);
        }

        $movimento = MovimentoCertificado::create([
            'datas_enviadas' => json_encode($retorno),
            'dt_envio' => date('Y-m-d H:i:s'),
            'status_id' => 26,
            'tipo_envio' => 'emitidos'
        ]);

        $retorno['id_lote'] = $movimento->id;

        $this->response([
            'status' => '000 - sucesso',
            'cdretorno' => '000',
            'retorno' => $retorno,
        ]);

    }

    function emitidosRetorno_post()
    {


        $datas = $this->post();


        $movimento = MovimentoCertificado::find($datas['id_lote']);

        if (!$movimento) {
            return $this->response([
                'status' => '404 - Error',
                'cdretorno' => '404',
                'retorno' => 'Número de requisição invalido'], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $certificados_enviados = json_decode($movimento->datas_enviadas);

            $aceitos = [];
            $nao_aceitos=[];
            $nao_retorno=[];

            foreach ($datas['retorno'] as $key => $data_retorno){
                if($data_retorno['status'] == 1){
                    $nao_aceitos[] = (int) $data_retorno['numero_certificado'];
                }
            }

            foreach ($certificados_enviados as $certificado) {

                if (in_array(str_pad((int) $certificado->numero_certificado, 20, 0, STR_PAD_LEFT), array_column($datas['retorno'], 'numero_certificado'))) {
                    $aceitos[] = (int) $certificado->numero_certificado;
                } else {
                    $nao_retorno[] = $certificado->numero_certificado;
                }

            }


            $this->response([
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => (count($nao_retorno) >= 1 ? 'Operação realizada com sucesso! Porém alguns dos certificados enviados não teve retorno, checar o parâmentro retnornado "nao_reternou" ' : 'Operação realizada com sucesso'),
                'nao_retornou' => $nao_retorno]);
        }


    }




}
