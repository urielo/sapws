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
//        $this->load->model('Custo_produto');
//        $this->load->library('m_pdf');
        date_default_timezone_set('America/Sao_Paulo');
    }


    public function emitidos_get()
    {
//        $_SERVER;

        $certificados = Certificados::where('status_id', 28)->get();

        $retorno = [];

        foreach ($certificados as $certificado) {

            $retorno['apolices'][$certificado->id] = [
                "apolice" => $certificado->id,
                "nome" => $certificado->proposta->cotacao->segurado->clinomerazao,
                "cnpjcpf" => $certificado->proposta->cotacao->segurado->clicpfcnpj,
                "aniversario" => date('Y-m-d', strtotime($certificado->proposta->cotacao->segurado->clidtnasc)),
                "cod_dispositivo" => 'SKYPROTECTION',
                "descricao" => 'SKYPROTECTION',
                "cep" => format('cep', $certificado->proposta->cotacao->segurado->clicep),
                "endereco" => $certificado->proposta->cotacao->segurado->clinmend,
                "numero" => $certificado->proposta->cotacao->segurado->clinumero,
                "complemento" => $certificado->proposta->cotacao->segurado->cliendcomplet,
                "cidade" => $certificado->proposta->cotacao->segurado->clinmcidade,
                "bairro" => '',
                "uf" => $certificado->proposta->cotacao->segurado->uf->nm_uf,
                "cod_fipe" => $certificado->proposta->cotacao->veiculo->veiccodfipe,
                "valor" => $certificado->proposta->cotacao->veiculo->fipe_ano_valor()
                    ->where('ano', $certificado->proposta->cotacao->veiculo->veicano)
                    ->where('idcombustivel', $certificado->proposta->cotacao->veiculo->veictipocombus)->first()->valor,
                "marca_veiculo" => $certificado->proposta->cotacao->veiculo->fipe->marca,
                "modelo_veiculo" => $certificado->proposta->cotacao->veiculo->fipe->modelo,
                "placa" => $certificado->proposta->cotacao->veiculo->veicplaca,
                "cor" => $certificado->proposta->cotacao->veiculo->veicor,
                "utilizacao_veiculo" => '',
                "ano_modelo" => $certificado->proposta->cotacao->veiculo->veicano,
                "renavan" => $certificado->proposta->cotacao->veiculo->veicrenavam,
                "chassi" => $certificado->proposta->cotacao->veiculo->veicchassi,
                "n_corretor" => 'LOGICA SEGUROS',
                "geracao" => date('Y-m-d', strtotime($certificado->dt_inicio_virgencia)),
                "carga" => date('Y-m-d'),
                "capitalizacao" => '',
                "lote" => '',
                "est_civil" => $certificado->proposta->cotacao->segurado->estadocivil->sigla,
                "0km" => ($certificado->proposta->cotacao->veiculo->veicautozero == 1 ? 'S' : 'N'),
                "tipo" => $certificado->proposta->cotacao->veiculo->veiccdveitipo,
                "combustivel" => $certificado->proposta->cotacao->veiculo->combustivel->sigla,
            ];

            foreach ($certificado->custos as $custo) {
                if ($custo->seguradora_produto()->where('idseguradora', $custo->idseguradora)->first()->id_produto_seguradora < 1000) {
                    $retorno['apolices'][$certificado->id]['coberturas'][] = [
                        'CodigoCobertura' => $custo->seguradora_produto()->where('idseguradora', $custo->idseguradora)->first()->id_produto_seguradora,
                        'Premio' => $custo->custo_anual
                    ];
                } else {
                    $retorno['apolices'][$certificado->id]['assistencia'][] = [
                        'CodigoAssistencia' => $custo->seguradora_produto()->where('idseguradora', $custo->idseguradora)->first()->id_produto_seguradora,
                        'Premio' => $custo->custo_anual
                    ];
                }

            }
            $certificado->movimento()->create([
                'dt_carga'=>date('Y-m-d H:i:s'),
                'status_id'=>26,
            ]);
        }

        foreach ($certificados as $certificado) {
            $certificado->status_id = 26;
            $certificado->save();

        }
        $this->response([$retorno]);


    }

}
