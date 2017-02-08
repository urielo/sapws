<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$anomin = date('Y') - 18 . date('md');
$anovei = date('Y') + 2;
$anorena = date('Y') + 1;

$config = array(
    'cotacao' => array(
        array('field' => 'idParceiro', 'label' => 'Codigo do Parceiro', 'rules' => 'trim|required|max_length[10]|integer'),
        array('field' => 'nmParceiro', 'label' => 'Nome do Parceiro', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'renova', 'label' => 'Renova', 'rules' => 'trim|max_length[1]|integer|less_than[2]'),
        array('field' => 'indProprietVeic', 'label' => 'Indicação do proprietario do veículo', 'rules' => 'trim|max_length[1]|less_than[2]'),
        array('field' => 'indCondutorVeic', 'label' => 'Indicação do condutor do veículo', 'rules' => 'trim|max_length[1]|less_than[2]'),
        array('field' => 'comissao', 'label' => 'comissao', 'rules' => 'trim|required|integer|max_length[2]'),
    ),
    'veiculoCotacao' => array(
        array('field' => 'veiCodFipe', 'label' => 'Codigo fipe', 'rules' => 'trim|required|max_length[10]'),
        array('field' => 'veiAno', 'label' => 'Ano do modelo do veículo', 'rules' => "trim|required|integer|min_length[1]|max_length[4]|less_than[{$anovei}]"),
        array('field' => 'veiAnoFab', 'label' => 'Ano de fabricação do veículo', 'rules' => "trim|integer|min_length[4]|max_length[4]|less_than[{$anovei}]"),
        array('field' => 'veiIndZero', 'label' => 'Indicação de veiculo 0KM', 'rules' => 'trim|required|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiCdUtiliz', 'label' => 'Utilização do veículo', 'rules' => 'trim|integer|max_length[1]|greater_than[0]'),
        array('field' => 'veiCdTipo', 'label' => 'Tipo de veículo', 'rules' => 'trim|required|integer|max_length[5]'),
        array('field' => 'veiCdCombust', 'label' => 'Tipo de Combustivél', 'rules' => 'trim|required|integer'),
        array('field' => 'veiPlaca', 'label' => 'Placa', 'rules' => 'trim|max_length[7]|min_length[7]|alpha_numeric'),
        array('field' => 'veiMunPlaca', 'label' => 'Municipio da placa', 'rules' => 'trim|max_length[100]'),
        array('field' => 'veiCor', 'label' => 'Cor do veiculo', 'rules' => 'trim|max_length[50]'),
        array('field' => 'veiCdUfPlaca', 'label' => 'Estado da placa', 'rules' => 'trim|integer|max_length[3]'),
        array('field' => 'veiRenav', 'label' => 'Renavan', 'rules' => 'trim|max_length[11]|numeric|min_length[11]|max_length[11]'),
        array('field' => 'veiAnoRenav', 'label' => 'Ano do Renavan', 'rules' => "trim|integer|min_length[4]|max_length[4]|less_than[{$anorena}]"),
        array('field' => 'veiChassi', 'label' => 'Chassis', 'rules' => 'trim|min_length[17]|max_length[17]|alpha_numeric'),
        array('field' => 'veiIndChassiRema', 'label' => 'Indicador de chassis remarcado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndLeilao', 'label' => 'Indicador de veiculo de leilão', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndAcidentado', 'label' => 'Indicador de veiculo acidentado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndAlienado', 'label' => 'Indicador de veiculo alienado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
    ),
    'seguradoCotacao' => array(
        array('field' => 'segNomeRazao', 'label' => 'Segurado: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segCpfCnpj', 'label' => 'Segurado: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[14]|numeric|required'),
        array('field' => 'segDtNasci', 'label' => 'Segurado: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'segCdSexo', 'label' => 'Segurado: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'segCdEstCivl', 'label' => 'Segurado: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'segProfRamoAtivi', 'label' => 'Segurado: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
        array('field' => 'segEmail', 'label' => 'Segurado: Email', 'rules' => 'trim|valid_email'),
        array('field' => 'segCelDdd', 'label' => 'Segurado: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segCelNum', 'label' => 'Segurado: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'segFoneDdd', 'label' => 'Segurado: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segFoneNum', 'label' => 'Segurado: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'segEnd', 'label' => 'Segurado: Logradouro', 'rules' => 'trim'),
        array('field' => 'segEndNum', 'label' => 'Segurado: Numero do endereço', 'rules' => 'trim|max_length[10]'),
        array('field' => 'segBairro', 'label' => 'Segurado: Bairro', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndCompl', 'label' => 'Segurado: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segEndCep', 'label' => 'Segurado: CEP', 'rules' => 'trim|max_length[8]|min_length[8]|numeric'),
        array('field' => 'segEndCidade', 'label' => 'Segurado: Cidade', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndCdUf', 'label' => 'Segurado: Estado', 'rules' => 'trim|integer|max_length[5]'),
        array('field' => 'segNumRg', 'label' => 'Segurado: Numero do RG', 'rules' => 'trim|numeric'),
        array('field' => 'segDtEmissaoRg', 'label' => 'Segurado: Data emissão do RG', 'rules' => 'trim|numeric|min_length[8]'),
        array('field' => 'segEmissorRg', 'label' => 'Segurado: Orgão emissor do RG', 'rules' => 'trim|alpha'),
        array('field' => 'segCdUfRg', 'label' => 'Segurado: Estado de emissão do RG', 'rules' => 'trim|integer'),
    ),
    'seguradoCotacaoPJ' => array(
        array('field' => 'segNomeRazao', 'label' => 'Segurado: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segCpfCnpj', 'label' => 'Segurado: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[14]|numeric|required'),
        array('field' => 'segDtNasci', 'label' => 'Segurado: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[18000000]'),
        array('field' => 'segCdSexo', 'label' => 'Segurado: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'segCdEstCivl', 'label' => 'Segurado: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'segProfRamoAtivi', 'label' => 'Segurado: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
        array('field' => 'segEmail', 'label' => 'Segurado: Email', 'rules' => 'trim|valid_email'),
        array('field' => 'segCelDdd', 'label' => 'Segurado: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segCelNum', 'label' => 'Segurado: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'segFoneDdd', 'label' => 'Segurado: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segFoneNum', 'label' => 'Segurado: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'segEnd', 'label' => 'Segurado: Logradouro', 'rules' => 'trim'),
        array('field' => 'segBairro', 'label' => 'Segurado: Bairro', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndNum', 'label' => 'Segurado: Numero do endereço', 'rules' => 'trim|max_length[10]'),
        array('field' => 'segEndCompl', 'label' => 'Segurado: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segEndCep', 'label' => 'Segurado: CEP', 'rules' => 'trim|max_length[8]|min_length[8]|numeric'),
        array('field' => 'segEndCidade', 'label' => 'Segurado: Cidade', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndCdUf', 'label' => 'Segurado: Estado', 'rules' => 'trim|integer|max_length[5]'),
    ),
    'corretor' => array(
        array('field' => 'correCodPlataforma', 'label' => 'Corretor: Codigo da corretora na plataforma', 'rules' => 'trim|max_length[20]|integer'),
        array('field' => 'correSusep', 'label' => 'Corretor: Numero da SUSEP', 'rules' => 'trim|max_length[20]|numeric'),
        array('field' => 'correNomeRazao', 'label' => 'Corretor: Nome ou razão social', 'rules' => 'trim|required'),
        array('field' => 'correCpfCnpj', 'label' => 'Corretor: CPF ou CNPJ', 'rules' => 'trim|required|min_length[11]|max_length[14]|numeric'),
        array('field' => 'correDtNasci', 'label' => 'Corretor: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'correCdSexo', 'label' => 'Corretor: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'correCdEstCivl', 'label' => 'Corretor: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'correProfRamoAtivi', 'label' => 'Corretor: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
        array('field' => 'correEmail', 'label' => 'Corretor: Email', 'rules' => 'trim|required|valid_email'),
        array('field' => 'correCelDdd', 'label' => 'Corretor: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'correCelNum', 'label' => 'Corretor: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer'),
        array('field' => 'correFoneDdd', 'label' => 'Corretor: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'correFoneNum', 'label' => 'Corretor: Numero do telefone', 'rules' => 'trim|max_length[9]|min_length[8]|integer'),
        array('field' => 'correEnd', 'label' => 'Corretor: Logradouro', 'rules' => 'trim'),
        array('field' => 'correEndNum', 'label' => 'Corretor: Numero do endereço', 'rules' => 'trim|max_length[10]'),
        array('field' => 'correEndCompl', 'label' => 'Corretor: Complement', 'rules' => 'trim|max_length[255]'),
        array('field' => 'correEndCep', 'label' => 'Corretor: CEP', 'rules' => 'trim|max_length[8]|min_length[8]|numeric'),
        array('field' => 'correEndCidade', 'label' => 'Corretor: Cidade', 'rules' => 'trim|max_length[255]'),
        array('field' => 'correEndCdUf', 'label' => 'Corretor: Estado', 'rules' => 'trim|integer|max_length[5]'),
    ),
    'condutorCotacao' => array(
        array('field' => 'condutNomeRazao', 'label' => 'Condutor: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'condutCpfCnpj', 'label' => 'Condutor: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[11]|numeric'),
        array('field' => 'condutDtNasci', 'label' => 'Condutor: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'condutCdSexo', 'label' => 'Condutor: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'condutCdEstCivl', 'label' => 'Condutor: Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'condutProfRamoAtivi', 'label' => 'Condutor: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
    ),
    'proprietarioCotacao' => array(
        array('field' => 'proprNomeRazao', 'label' => 'Propritario do veículo: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'proprCpfCnpj', 'label' => 'Propritario do veículo: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[14]|numeric'),
        array('field' => 'proprDtNasci', 'label' => 'Propritario do veículo: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'proprCdSexo', 'label' => 'Propritario do veículo: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'proprCdEstCivl', 'label' => 'Propritario do veículo: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'proprPrfoRamoAtivi', 'label' => 'Propritario do veículo: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
        array('field' => 'proprEmail', 'label' => 'Propritario do veículo: Email', 'rules' => 'trim|valid_email'),
        array('field' => 'proprCelDdd', 'label' => 'Propritario do veículo: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'proprCelNum', 'label' => 'Propritario do veículo: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'proprFoneDdd', 'label' => 'Propritario do veículo: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'proprFoneNum', 'label' => 'Propritario do veículo: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'proprEnd', 'label' => 'Propritario do veículo: Logradouro', 'rules' => 'trim'),
        array('field' => 'proprEndNum', 'label' => 'Propritario do veículo: Numero do endereço', 'rules' => 'trim|max_length[10]'),
        array('field' => 'proprEndCompl', 'label' => 'Propritario do veículo: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'proprEndCep', 'label' => 'Propritario do veículo: CEP', 'rules' => 'trim|max_length[8]|numeric'),
        array('field' => 'proprEndCidade', 'label' => 'Propritario do veículo: Cidade', 'rules' => 'trim|max_length[20]'),
        array('field' => 'proprEndCdUf', 'label' => 'Propritario do veículo: Estado', 'rules' => 'trim|integer|max_length[5]'),
    ),
    /*
     * PORPOSTA VALIDAÇÃO
     */
    'proposta' => array(
        array('field' => 'idParceiro', 'label' => 'Id do parceiro', 'rules' => 'trim|required|max_length[10]|integer'),
        array('field' => 'nmParceiro', 'label' => 'Nome do parceiro', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'indProprietVeic', 'label' => 'Indicador do proprietario do veiculo', 'rules' => 'trim|required|max_length[1]'),
        array('field' => 'indCondutorVeic', 'label' => 'Indicador do condutor do veiculo', 'rules' => 'trim|max_length[1]'),
        array('field' => 'cdCotacao', 'label' => 'Codigo da cotação', 'rules' => 'trim|required|integer|max_length[50]'),
        array('field' => 'qtParcela', 'label' => 'Quantidade de parcelas', 'rules' => 'trim|required|integer|max_length[3]'),
        array('field' => 'cdFormaPgt', 'label' => 'Codigo da forma de pagamento', 'rules' => 'trim|required|numeric|max_length[2]'),
        array('field' => 'nmBandeira', 'label' => 'Bandeira do Cartão', 'rules' => 'trim|max_length[60]'),
        array('field' => 'titularCartao', 'label' => 'Nome do titular do cartão', 'rules' => 'trim'),
        array('field' => 'validadeCartao', 'label' => 'Validade do Cartão', 'rules' => 'trim|numeric|max_length[6]|greater_than[' . date("Ym", strtotime("-1 month")) . ']'),
        array('field' => 'numCartao', 'label' => 'Numero do Cartão', 'rules' => 'trim|numeric|max_length[16]|min_length[16]'),
    ),
    'veiculoProposta' => array(
        array('field' => 'veiCodFipe', 'label' => 'Codigo fipe', 'rules' => 'trim|required|max_length[10]'),
        array('field' => 'veiAno', 'label' => 'Ano do modelo do veiculo', 'rules' => "trim|required|integer|min_length[1]|max_length[4]|less_than[{$anovei}]"),
        array('field' => 'veiAnoFab', 'label' => 'Ano de fabricação do veículo', 'rules' => "trim|integer|min_length[4]|max_length[4]|less_than[{$anovei}]"),
        array('field' => 'veiIndZero', 'label' => 'Indicação de veiculo 0KM', 'rules' => 'trim|required|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiCdUtiliz', 'label' => 'Utilização do veículo', 'rules' => 'trim|required|integer|max_length[1]|greater_than[0]'),
        array('field' => 'veiCdTipo', 'label' => 'Tipo de veículo', 'rules' => 'trim|required|integer|max_length[5]'),
        array('field' => 'veiCdCombust', 'label' => 'Tipo de Combustivél', 'rules' => 'trim|required|integer'),
        array('field' => 'veiPlaca', 'label' => 'Placa', 'rules' => 'trim|required|max_length[7]|min_length[7]|alpha_numeric'),
        array('field' => 'veiCor', 'label' => 'Municipio da placa', 'rules' => 'trim|max_length[50]'),
        array('field' => 'veiMunPlaca', 'label' => 'Cor do veiculo', 'rules' => 'trim|max_length[50]'),
        array('field' => 'veiCdUfPlaca', 'label' => 'Estado da placa', 'rules' => 'trim|required|integer|max_length[3]'),
        array('field' => 'veiRenav', 'label' => 'Renavan', 'rules' => 'trim|required|max_length[11]|numeric|min_length[11]'),
        array('field' => 'veiAnoRenav', 'label' => 'Ano do Renavan', 'rules' => "trim|integer|min_length[4]|max_length[4]|less_than[{$anorena}]"),
        array('field' => 'veiChassi', 'label' => 'Chassis', 'rules' => 'trim|required|min_length[17]|max_length[17]|alpha_numeric'),
        array('field' => 'veiIndChassiRema', 'label' => 'Indicador de chassis remarcado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndLeilao', 'label' => 'Indicador de veiculo de leilão', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndAcidentado', 'label' => 'Indicador de veiculo acidentado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
        array('field' => 'veiIndAlienado', 'label' => 'Indicador de veiculo alienado', 'rules' => 'trim|integer|max_length[1]|less_than[2]'),
    ),
    'seguradoProposta' => array(
        array('field' => 'segNomeRazao', 'label' => 'Segurado: Nome ou razão social', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'segCpfCnpj', 'label' => 'Segurado: CPF ou CNPJ', 'rules' => 'trim|required|min_length[11]|max_length[14]|numeric'),
        array('field' => 'segDtNasci', 'label' => 'Segurado: Data de nascimento', 'rules' => 'trim|required|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'segCdSexo', 'label' => 'Segurado: Sexo', 'rules' => 'trim|required|max_length[1]|numeric|less_than[3]'),
        array('field' => 'segCdEstCivl', 'label' => 'Segurado: Estado Civil', 'rules' => 'trim|required|max_length[3]|integer'),
        array('field' => 'segProfRamoAtivi', 'label' => 'Segurado: Profissao ou ramo de atividade comercial', 'rules' => 'trim|required|max_length[10]|integer'),
        array('field' => 'segEmail', 'label' => 'Segurado: Email', 'rules' => 'trim|required|valid_email|required'),
        array('field' => 'segCelDdd', 'label' => 'Segurado: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segCelNum', 'label' => 'Segurado: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'segFoneDdd', 'label' => 'Segurado: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segFoneNum', 'label' => 'Segurado: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'segEnd', 'label' => 'Segurado: Logradouro', 'rules' => 'trim'),
        array('field' => 'segEndNum', 'label' => 'Segurado: Numero do endereço', 'rules' => 'trim|required|max_length[10]'),
        array('field' => 'segEndCompl', 'label' => 'Segurado: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segBairro', 'label' => 'Segurado: Bairro', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndCep', 'label' => 'Segurado: CEP', 'rules' => 'trim|required|max_length[8]|min_length[8]|numeric'),
        array('field' => 'segEndCidade', 'label' => 'Segurado: Cidade', 'rules' => 'trim|required|max_length[255]'),
        array('field' => 'segEndCdUf', 'label' => 'Segurado: Estado', 'rules' => 'trim|integer|required|max_length[5]'),
        array('field' => 'segNumRg', 'label' => 'Segurado: Numero do RG', 'rules' => 'trim|numeric'),
        array('field' => 'segDtEmissaoRg', 'label' => 'Segurado: Data emissão do RG', 'rules' => 'trim|numeric|min_length[8]'),
        array('field' => 'segEmissorRg', 'label' => 'Segurado: Orgão emissor do RG', 'rules' => 'trim|alpha'),
        array('field' => 'segCdUfRg', 'label' => 'Segurado: Estado de emissão do RG', 'rules' => 'trim|integer'),
    ),
    'seguradoPropostaPJ' => array(
        array('field' => 'segNomeRazao', 'label' => 'Segurado: Nome ou razão social', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'segCpfCnpj', 'label' => 'Segurado: CPF ou CNPJ', 'rules' => 'trim|required|min_length[11]|max_length[14]|numeric'),
        array('field' => 'segDtNasci', 'label' => 'Segurado: Data de nascimento', 'rules' => 'trim|required|max_length[8]|min_length[8]|integer'),
        array('field' => 'segCdSexo', 'label' => 'Segurado: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'segCdEstCivl', 'label' => 'Segurado: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'segProfRamoAtivi', 'label' => 'Segurado: Profissao ou ramo de atividade comercial', 'rules' => 'trim|required|max_length[10]|integer'),
        array('field' => 'segEmail', 'label' => 'Segurado: Email', 'rules' => 'trim|required|valid_email|required'),
        array('field' => 'segCelDdd', 'label' => 'Segurado: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segCelNum', 'label' => 'Segurado: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'segFoneDdd', 'label' => 'Segurado: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'segFoneNum', 'label' => 'Segurado: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'segEnd', 'label' => 'Segurado: Logradouro', 'rules' => 'trim'),
        array('field' => 'segEndNum', 'label' => 'Segurado: Numero do endereço', 'rules' => 'trim|required|max_length[10]'),
        array('field' => 'segEndCompl', 'label' => 'Segurado: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'segEndCep', 'label' => 'Segurado: CEP', 'rules' => 'trim|required|max_length[8]|min_length[8]|numeric'),
        array('field' => 'segEndCidade', 'label' => 'Segurado: Cidade', 'rules' => 'trim|required|max_length[255]'),
        array('field' => 'segBairro', 'label' => 'Segurado: Bairro', 'rules' => 'trim|max_length[255]'),
        array('field' => 'segEndCdUf', 'label' => 'Segurado: Estado', 'rules' => 'trim|integer|required|max_length[5]'),
        array('field' => 'segNumRg', 'label' => 'Segurado: Numero do RG', 'rules' => 'trim|numeric'),
        array('field' => 'segDtEmissaoRg', 'label' => 'Segurado: Data emissão do RG', 'rules' => 'trim|numeric|min_length[8]'),
        array('field' => 'segEmissorRg', 'label' => 'Segurado: Orgão emissor do RG', 'rules' => 'trim|alpha'),
        array('field' => 'segCdUfRg', 'label' => 'Segurado: Estado de emissão do RG', 'rules' => 'trim|integer'),
    ),
    
    
    'condutorProposta' => array(
        array('field' => 'condutNomeRazao', 'label' => 'Condutor: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'condutCpfCnpj', 'label' => 'Condutor: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[11]|numeric'),
        array('field' => 'condutDtNasci', 'label' => 'Condutor: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'condutCdSexo', 'label' => 'Condutor: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'condutCdEstCivl', 'label' => 'Condutor: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'condutProfRamoAtivi', 'label' => 'Condutor: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
    ),
    'proprietarioProposta' => array(
        array('field' => 'proprNomeRazao', 'label' => 'Proprietário: Nome ou razão social', 'rules' => 'trim|max_length[50]'),
        array('field' => 'proprCpfCnpj', 'label' => 'Proprietário: CPF ou CNPJ', 'rules' => 'trim|min_length[11]|max_length[14]|numeric'),
        array('field' => 'proprDtNasci', 'label' => 'Proprietário: Data de nascimento', 'rules' => 'trim|max_length[8]|min_length[8]|integer|greater_than[19000000]|less_than[' . $anomin . ']'),
        array('field' => 'proprCdSexo', 'label' => 'Proprietário: Sexo', 'rules' => 'trim|max_length[1]|numeric|less_than[3]'),
        array('field' => 'proprCdEstCivl', 'label' => 'Proprietário: Estado Civil', 'rules' => 'trim|max_length[3]|integer'),
        array('field' => 'proprPrfoRamoAtivi', 'label' => 'Proprietário: Profissao ou ramo de atividade comercial', 'rules' => 'trim|max_length[10]|integer'),
        array('field' => 'proprEmail', 'label' => 'Proprietário: Email', 'rules' => 'trim|valid_email'),
        array('field' => 'proprCelDdd', 'label' => 'Proprietário: DDD do celular', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'proprCelNum', 'label' => 'Proprietário: Numero do celular', 'rules' => 'trim|max_length[9]|min_length[8]|integer|greater_than[40000000]'),
        array('field' => 'proprFoneDdd', 'label' => 'Proprietário: DDD do telefone', 'rules' => 'trim|max_length[2]|integer|greater_than[10]|less_than[100]'),
        array('field' => 'proprFoneNum', 'label' => 'Proprietário: Numero do telefone', 'rules' => 'trim|max_length[8]|integer'),
        array('field' => 'proprEnd', 'label' => 'Proprietário: Logradouro', 'rules' => 'trim'),
        array('field' => 'proprEndNum', 'label' => 'Proprietário: Numero do endereço', 'rules' => 'trim|max_length[10]'),
        array('field' => 'proprEndCompl', 'label' => 'Proprietário: Complemento', 'rules' => 'trim|max_length[50]'),
        array('field' => 'proprEndCep', 'label' => 'Proprietário: CEP', 'rules' => 'trim|max_length[8]|numeric'),
        array('field' => 'proprEndCidade', 'label' => 'Proprietário: Cidade', 'rules' => 'trim|max_length[20]'),
        array('field' => 'proprEndCdUf', 'label' => 'Proprietário: Estado', 'rules' => 'trim|integer|max_length[5]'),
    ),
    'pdf' => array(
        array('field' => 'idParceiro', 'label' => 'ID do parceiro', 'rules' => 'trim|required|max_length[10]|integer'),
        array('field' => 'nmParceiro', 'label' => 'Nome do Parceiro', 'rules' => 'trim|required|max_length[50]'),
        array('field' => 'idProposta', 'label' => 'Numero da Proposta', 'rules' => 'trim|required|integer'),
    ),
    
    'email' => [
        ['emails', 'label' => 'emails', 'rules' => 'trim|valid_email']
    ]
);


