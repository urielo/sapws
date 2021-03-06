<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . './libraries/REST_Controller.php';

use Illuminate\Support\Facades\DB;

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


        try {


            $certificados = Certificados::with('proposta.cotacao','custos')->whereIn('status_id', [1,30])->get();
            $retorno = [];
            $assitencia = [];



            if (!$certificados->isEmpty()) {
                foreach ($certificados as $certificado) {

                    $dados = [
                        "tipo_arquivo" => 2,
                        "numero_certificado" => str_pad($certificado->id, 20, 0, STR_PAD_LEFT),
                        "ano" => $certificado->proposta->veiculo->veicano,
                        "combustivel" => $certificado->proposta->veiculo->combustivel->sigla,
                        "numero_capitalizacao" => NULL,
                        "numero_serie_sorteio" => NULL,
                        "chassi" => $certificado->proposta->veiculo->veicchassi,
                        "renavam" => $certificado->proposta->veiculo->veicrenavam,
                        "placa" => $certificado->proposta->veiculo->veicplaca,
                        "marca" => $certificado->proposta->veiculo->fipe->marca,
                        "modelo" => $certificado->proposta->veiculo->fipe->modelo,
                        "cod_fipe" => $certificado->proposta->veiculo->fipe->codefipe,
                        "cor" => $certificado->proposta->veiculo->veicor,
                        "valor" => $certificado->proposta->veiculo->fipe
                            ->anovalor()
                            ->where('ano', $certificado->proposta->veiculo->veicano)
                            ->where('idcombustivel', $certificado->proposta->veiculo->veictipocombus)
                            ->first()->valor,
                        "zero_km" => ($certificado->proposta->veiculo->veicautozero ? 'Y' : 'N'),
                        "dono_veiculo" => ($certificado->proposta->veiculo->propcpfcnpj != $certificado->proposta->veiculo->clicpfcnpj ? 'N' : 'Y'),
                        "nome" => $certificado->proposta->cotacao->segurado->clinomerazao,
                        "cpf_cnpj" => $certificado->proposta->cotacao->segurado->clicpfcnpj,
                        "cep" => $certificado->proposta->cotacao->segurado->clicep,
                        "endereco" => $certificado->proposta->cotacao->segurado->clinmend,
                        "numero" => $certificado->proposta->cotacao->segurado->clinumero,
                        "complemento" => ($certificado->proposta->cotacao->segurado->cliendcomplet) == null ? 'Nao informado' : $certificado->proposta->cotacao->segurado->cliendcomplet,
                        "bairro" => ($certificado->proposta->cotacao->segurado->bairro) == null ? 'Nao informado': $certificado->proposta->cotacao->segurado->bairro,
                        "estado" => $certificado->proposta->cotacao->segurado->clicduf,
                        "cidade" => $certificado->proposta->cotacao->segurado->clinmcidade,
                        "data_nascimento" => date('Y-m-d', strtotime($certificado->proposta->cotacao->segurado->clidtnasc)),
                        "estado_civil" => $certificado->proposta->cotacao->segurado->estadocivil->sigla,
                        "valor_mensal_seguro" => $certificado->custo_mensal->sum('custo_mensal'),

                        "categoria" => $certificado->proposta->veiculo->tipo->codigo,
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
                        "possui_assistencia" => (in_array(1004, $assitencia) ? 'S' : 'N'),
                        "assistencia_reparo_vidro" => (in_array(1000, $assitencia) ? 'S' : 'N'),
                        "assistencia_reparo_parachoque" => (in_array(1001, $assitencia) ? 'S' : 'N'),
                        "assistencia_reparo_pintura" => (in_array(1002, $assitencia) ? 'S' : 'N'),
                        "assistencia_residencial" => (in_array(1003, $assitencia) ? 'S' : 'N'),
                        "assistencia_pt_colisao" => (in_array(238, $assitencia) ? 'S' : 'N'),
                    ];


                    $retorno['retorno'][] = array_merge($dados, $assitencias);
                }

                $movimento = MovimentoCertificado::create([
                    'datas_enviadas' => json_encode($retorno),
                    'dt_envio' => date('Y-m-d H:i:s'),
                    'status_id' => 26,
                    'tipo_envio' => 'emitidos'
                ]);
                Certificados::where('status_id', 1)->update(['status_id' => 30]);


                $retorno = array_merge([
                    'status' => '000 - sucesso',
                    'cdretorno' => '000',
                ], $retorno);
                $retorno['id_lote'] = $movimento->id;

                $this->response($retorno);
            } else {
                $this->response([
                    'status' => '000 - sucesso',
                    'cdretorno' => '000',
                    'retorno' => 'Não há certificados emitidos tente novamente mais tarde!!!'
                ]);
            }


        } catch (Exception $e) {


            $this->response([
                'status' => '405 - erro',
                'cdretorno' => '405',
                'retorno' => 'Ocorreu um erro interno, tente novamente mais tarde se caso o erro pesistir contate o suporte do sistema'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        }


    }

    function emitidasRetorno_post()
    {


        $datas = $this->post();


        $movimento = MovimentoCertificado::find($datas['id_lote']);

        if (!$movimento) {
            return $this->response([
                'status' => '404 - Error',
                'cdretorno' => '404',
                'retorno' => 'Número de lote invalido'], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $movimento->dt_retorno = date('Y-m-d H:i:s');
            $movimento->datas_recebidas = json_encode($datas);
            $movimento->status_id = 27;
            $movimento->save();

            $certificados_enviados = json_decode($movimento->datas_enviadas);

            $aceitos = [];
            $nao_aceitos = [];
            $nao_retorno = [];

            foreach ($datas['retorno'] as $key => $data_retorno) {
                if ($data_retorno['status'] == 1) {
                    $nao_aceitos[] = (int)$data_retorno['numero_certificado'];
                }
            }

            foreach ($certificados_enviados->retorno as $certificado) {

                if (in_array(str_pad((int)$certificado->numero_certificado, 20, 0, STR_PAD_LEFT), array_column($datas['retorno'], 'numero_certificado'))) {
                    $aceitos[] = (int)$certificado->numero_certificado;
                } else {
                    $nao_retorno[] = (int)$certificado->numero_certificado;
                }

            }

            Certificados::whereIn('id', $aceitos)->update(['status_id'=> 31]);
            Certificados::whereIn('id', $nao_aceitos)->update(['status_id'=> 32]);
            Certificados::whereIn('id', $nao_retorno)->update(['status_id'=> 33]);


            $this->response([
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => (count($nao_retorno) >= 1 ? 'Operação realizada com sucesso! Porém alguns dos certificados enviados não teve retorno, checar o parâmentro retnornado "nao_reternou" ' : 'Operação realizada com sucesso'),
                'nao_retornou' => $nao_retorno]);
        }


    }

    public function cancelados_get()
    {


        try {

            $certificados = Certificados::with('apolice.cancelamento.motivo')->where('status_id', 40)->get();
            $retorno = [];

            if (!$certificados->isEmpty()) {
                foreach ($certificados as $certificado) {

                    $retorno['retorno'][] = [
                        "tipo_arquivo" => 4,
                        "numero_certificado" => str_pad($certificado->id, 20, 0, STR_PAD_LEFT),
                        "cpf_cnpj" => $certificado->proposta->cotacao->segurado->clicpfcnpj,
                        "data_cancelamento" => date('Y-m-d', strtotime($certificado->apolice->cancelamento->created_at)),
                        "tipo_cancelamento" => $certificado->apolice->cancelamento->motivo->tipo,
                        "motivo_cancelamento" => $certificado->apolice->cancelamento->motivo->cod_motivo,
                    ];
                }

                $movimento = MovimentoCertificado::create([
                    'datas_enviadas' => json_encode($retorno),
                    'dt_envio' => date('Y-m-d H:i:s'),
                    'status_id' => 26,
                    'tipo_envio' => 'cancelados'
                ]);


                Certificados::where('status_id', 29)->update(['status_id' => 30]);
                $retorno = array_merge([
                    'status' => '000 - sucesso',
                    'cdretorno' => '000',
                ], $retorno);
                $retorno['id_lote'] = $movimento->id;

                $this->response($retorno);
            } else {
                $this->response([
                    'status' => '000 - sucesso',
                    'cdretorno' => '000',
                    'retorno' => 'Não há certificados emitidos tente novamente mais tarde!!!'
                ]);
            }


        } catch (Exception $e) {


            $this->response([
                'status' => '405 - erro',
                'cdretorno' => '405',
                'retorno' => 'Ocorreu um erro interno, tente novamente mais tarde se caso o erro pesistir contate o suporte do sistema',
                'exception' => $e
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        }


    }

    public function canceladosRetorno_post()
    {
        
        $datas = $this->post();


        $movimento = MovimentoCertificado::find($datas['id_lote']);

        if (!$movimento) {
            return $this->response([
                'status' => '404 - Error',
                'cdretorno' => '404',
                'retorno' => 'Número de lote invalido'], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $movimento->dt_retorno = date('Y-m-d H:i:s');
            $movimento->datas_recebidas = json_encode($datas);
            $movimento->status_id = 27;
            $movimento->save();

            $certificados_enviados = json_decode($movimento->datas_enviadas);

            $aceitos = [];
            $nao_aceitos = [];
            $nao_retorno = [];

            foreach ($datas['retorno'] as $key => $data_retorno) {
                if ($data_retorno['status'] == 1) {
                    $nao_aceitos[] = (int)$data_retorno['numero_certificado'];
                }
            }

            foreach ($certificados_enviados->retorno as $certificado) {

                if (in_array(str_pad((int)$certificado->numero_certificado, 20, 0, STR_PAD_LEFT), array_column($datas['retorno'], 'numero_certificado'))) {
                    $aceitos[] = (int)$certificado->numero_certificado;
                } else {
                    $nao_retorno[] = (int) $certificado->numero_certificado;
                }

            }

            Certificados::whereIn('id', $aceitos)->update(['status_id'=> 42]);
            Certificados::whereIn('id', $nao_aceitos)->update(['status_id'=> 43]);
            Certificados::whereIn('id', $nao_retorno)->update(['status_id'=> 44]);


            $this->response([
                'status' => '000 - sucesso',
                'cdretorno' => '000',
                'retorno' => (count($nao_retorno) >= 1 ? 'Operação realizada com sucesso! Porém alguns dos certificados enviados não teve retorno, checar o parâmentro retnornado "nao_reternou" ' : 'Operação realizada com sucesso'),
                'nao_retornou' => $nao_retorno]);
        }
    }

}
