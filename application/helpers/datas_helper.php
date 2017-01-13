<?php

function dataOrganizeProposta($datas)
{
    foreach ($datas as $key => $value):
        if (is_array($value)):
            foreach ($value as $K => $V):
                if ($V === ''):
                    $datas[$key][$K] = NULL;
                endif;
            endforeach;
        elseif ($value === ''):
            $datas[$key] = NULL;
        endif;
    endforeach;

    if (!isset($datas['veiculo'])):
        $datas['veiculo'] = $datas;
    elseif (isset($datas['veiculos'])):
        $datas['veiculo'] = $datas['veiculos'];
    endif;

    if (!$datas['indProprietVeic']):
        $return['proprietario']['proprcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
        $return['proprietario']['proprnomerazao'] = $datas['proprietario']["proprNomeRazao"];
        $return['proprietario']['proprdtnasc'] = $datas['proprietario']["proprDtNasci"];
        $return['proprietario']['proprcdsexo'] = $datas['proprietario']["proprCdSexo"];
        $return['proprietario']['proprcdestadocivil'] = $datas['proprietario']["proprCdEstCivl"];
        $return['proprietario']['proprcdprofiramoatividade'] = $datas['proprietario']["proprPrfoRamoAtivi"];
        $return['proprietario']['propremail'] = strtolower($datas['proprietario']["proprEmail"]);
        $return['proprietario']['proprdddcel'] = $datas['proprietario']["proprCelDdd"];
        $return['proprietario']['proprnmcel'] = $datas['proprietario']["proprCelNum"];
        $return['proprietario']['proprdddfone'] = $datas['proprietario']["proprFoneDdd"];
        $return['proprietario']['proprnmfone'] = $datas['proprietario']["proprFoneNum"];
        $return['proprietario']['proprnmend'] = $datas['proprietario']["proprEnd"];
        $return['proprietario']['proprnumero'] = $datas['proprietario']["proprEndNum"];
        $return['proprietario']['cdreldepsegurado'] = (isset($datas['proprietario']["proprCdRelDepSegurado"]) ? $datas['proprietario']["proprCdRelDepSegurado"] : null);
        $return['proprietario']['descreldepsegurado'] = (isset($datas['proprietario']["proprdescRelDepSegurado"]) ? $datas['proprietario']["proprdescRelDepSegurado"] : null);
        $return['proprietario']['proprendcomplet'] = $datas['proprietario']["proprEndCompl"];
        $return['proprietario']['proprcep'] = $datas['proprietario']["proprEndCep"];
        $return['proprietario']['proprnmcidade'] = $datas['proprietario']["proprEndCidade"];
        $return['proprietario']['proprcduf'] = $datas['proprietario']["proprEndCdUf"];
        $return['proprietario']['idtipocliente'] = 2;

        $return['veiculo']['propcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
    else:
        $return['veiculo']['propcpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    endif;

    if (!$datas['indCondutorVeic']):
        $return['condutor']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
        $return['condutor']['condnomerazao'] = $datas['condutor']["condutNomeRazao"];
        $return['condutor']['conddtnasc'] = $datas['condutor']["condutDtNasci"];
        $return['condutor']['condcdsexo'] = $datas['condutor']["condutCdSexo"];
        $return['condutor']['condcdestadocivil'] = $datas['condutor']["condutCdEstCivl"];
        $return['condutor']['condcdprofiramoatividade'] = $datas['condutor']["condutProfRamoAtivi"];
        $return['condutor']['idtipocliente'] = 3;

        $return['veiculo']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
    else:
        $return['veiculo']['condcpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    endif;


    #$return['proposta']['idparceiro'] = $datas["idParceiro"];
    $return['proposta']['idcotacao'] = $datas["cdCotacao"];
    $return['proposta']['idformapg'] = $datas["cdFormaPgt"];
    $return['proposta']['quantparc'] = $datas["qtParcela"];
    $return['proposta']['nmbandeira'] = $datas["nmBandeira"];
    $return['proposta']['numcartao'] = $datas["numCartao"];
    $return['proposta']['validadecartao'] = $datas["validadeCartao"];
    $return['proposta']['titularcartao'] = strtoupper($datas["titularCartao"]);
    $return['proposta']['dtvalidade'] = date('Y-m-d 00:00:00', strtotime('+ 30 days'));


    $return['perfil']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    $return['perfil']['ceppernoite'] = $datas['perfilSegurado']["cepPernoite"];
    $return['perfil']['garagempernoite'] = $datas['perfilSegurado']["garagemPernoite"];
    $return['perfil']['ceptrabalho'] = $datas['perfilSegurado']["cepTrabalho"];
    $return['perfil']['garagemtrabalho'] = $datas['perfilSegurado']["garagemTrabalho"];
    $return['perfil']['seguradoestuda'] = $datas['perfilSegurado']["seguradoEstuda"];
    $return['perfil']['garagemescola'] = $datas['perfilSegurado']["garagemEscola"];
    $return['perfil']['outrosmotoristasapolice'] = $datas['perfilSegurado']["outrosMotoristasApolice"];
    $return['perfil']['motoristajovem'] = $datas['perfilSegurado']["motoristaJovem"];
    $return['perfil']['seguradoacidenteultano'] = $datas['perfilSegurado']["seguradoAcidenteUltAno"];
    $return['perfil']['seguradoroubadoultano'] = $datas['perfilSegurado']["seguradoRoubadoUltAno"];
    $return['perfil']['renovaapolice'] = $datas['perfilSegurado']["renovaApolice"];
    $return['perfil']['renovaseguradora'] = $datas['perfilSegurado']["renovaSeguradora"];
    $return['perfil']['bonusapoliceultano'] = $datas['perfilSegurado']["bonusApoliceUltAno"];
    $return['perfil']['kmpordia'] = $datas['perfilSegurado']["kmPorDia"];
    $return['perfil']['outroscarros'] = $datas['perfilSegurado']["outrosCarros"];

    $return['segurado']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    $return['segurado']['clinomerazao'] = $datas['segurado']["segNomeRazao"];
    $return['segurado']['clidtnasc'] = $datas['segurado']["segDtNasci"];
    $return['segurado']['clicdsexo'] = $datas['segurado']["segCdSexo"];
    $return['segurado']['clicdestadocivil'] = $datas['segurado']["segCdEstCivl"];
    $return['segurado']['clicdprofiramoatividade'] = $datas['segurado']["segProfRamoAtivi"];
    $return['segurado']['bairro'] = $datas['segurado']["segBairro"];
    $return['segurado']['cliemail'] = strtolower($datas['segurado']["segEmail"]);
    ($datas['segurado']["segCelDdd"] != NULL ? $return['segurado']['clidddcel'] = $datas['segurado']["segCelDdd"] : NULL);
    ($datas['segurado']["segCelNum"] != NULL ? $return['segurado']['clinmcel'] = $datas['segurado']["segCelNum"] : NULL);
    ($datas['segurado']["segFoneDdd"] != NULL ? $return['segurado']['clidddfone'] = $datas['segurado']["segFoneDdd"] : NULL);
    ($datas['segurado']["segFoneNum"] != NULL ? $return['segurado']['clinmfone'] = $datas['segurado']["segFoneNum"] : NULL);
    $return['segurado']['clinmend'] = $datas['segurado']["segEnd"];
    $return['segurado']['clinumero'] = $datas['segurado']["segEndNum"];
    $return['segurado']['cliendcomplet'] = $datas['segurado']["segEndCompl"];
    $return['segurado']['clicep'] = $datas['segurado']["segEndCep"];
    $return['segurado']['clinmcidade'] = $datas['segurado']["segEndCidade"];
    $return['segurado']['clicduf'] = $datas['segurado']["segEndCdUf"];

    $return['segurado']['clinumrg'] = isset($datas['segurado']["segNumRg"]) ? $datas['segurado']["segNumRg"] : NULL;
    $return['segurado']['clidtemissaorg'] = isset($datas['segurado']["segDtEmissaoRg"]) ? $datas['segurado']["segDtEmissaoRg"] : NULL;
    $return['segurado']['cliemissorrg'] = isset($datas['segurado']["segEmissorRg"]) ? $datas['segurado']["segEmissorRg"] : NULL;
    $return['segurado']['clicdufemissaorg'] = isset($datas['segurado']["segCdUfRg"]) ? $datas['segurado']["segCdUfRg"] : NULL;

    $return['segurado']['idtipocliente'] = 1;

    $return['veiculo']['veicplaca'] = strtoupper($datas['veiculo']["veiPlaca"]);
    $return['veiculo']['veicmunicplaca'] = $datas['veiculo']["veiMunPlaca"];
    $return['veiculo']['veiccdufplaca'] = $datas['veiculo']["veiCdUfPlaca"];
    $return['veiculo']['veicrenavam'] = $datas['veiculo']["veiRenav"];
    $return['veiculo']['veicanorenavam'] = $datas['veiculo']["veiAnoRenav"];
    $return['veiculo']['veicchassi'] = strtoupper($datas['veiculo']["veiChassi"]);
    $return['veiculo']['veicchassiremar'] = $datas['veiculo']["veiIndChassiRema"];
    $return['veiculo']['veicleilao'] = $datas['veiculo']["veiIndLeilao"];
    $return['veiculo']['veicalienado'] = $datas['veiculo']["veiIndAlienado"];
    $return['veiculo']['veicacidentado'] = $datas['veiculo']["veiIndAcidentado"];
    $return['veiculo']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    $return['veiculo']['veianofab'] = $datas['veiculo']["veiAnoFab"];
    $return['veiculo']['veicor'] = $datas['veiculo']["veiCor"];
    $return['veiculo']['veiccdutilizaco'] = $datas['veiculo']["veiCdUtiliz"];

    $return['veiculo']['veiccodfipe'] = $datas['veiculo']["veiCodFipe"];
    $return['veiculo']['veicano'] = $datas['veiculo']["veiAno"];
    $return['veiculo']['veicautozero'] = $datas['veiculo']["veiIndZero"] == 0 ? 0 : 1;
    $return['veiculo']['veiccdveitipo'] = $datas['veiculo']["veiCdTipo"];
    $return['veiculo']['veictipocombus'] = $datas['veiculo']["veiCdCombust"];

    $return['cotacao']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];


    return $return;
}

function dataOrganizeCotacao($datas)
{
    foreach ($datas as $key => $value):
        if (is_array($value)):
            foreach ($value as $K => $V):
                if ($V == ''):
                    $datas[$key][$K] = NULL;
                endif;
            endforeach;
        elseif ($value == ''):
            $datas[$key] = NULL;
        endif;
    endforeach;

    if (!isset($datas['veiculo'])):
        $datas['veiculo'] = $datas;
    elseif (isset($datas['veiculos'])):
        $datas['veiculo'] = $datas['veiculos'];
    endif;


    if (!$datas['indProprietVeic']):
        $return['proprietario']['proprcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
        $return['proprietario']['proprnomerazao'] = $datas['proprietario']["proprNomeRazao"];
        $return['proprietario']['proprdtnasc'] = $datas['proprietario']["proprDtNasci"];
        $return['proprietario']['proprcdsexo'] = $datas['proprietario']["proprCdSexo"];
        $return['proprietario']['proprcdestadocivil'] = $datas['proprietario']["proprCdEstCivl"];
        $return['proprietario']['proprcdprofiramoatividade'] = $datas['proprietario']["proprPrfoRamoAtivi"];
        $return['proprietario']['propremail'] = strtolower($datas['proprietario']["proprEmail"]);
        $return['proprietario']['proprdddcel'] = $datas['proprietario']["proprCelDdd"];
        $return['proprietario']['proprnmcel'] = $datas['proprietario']["proprCelNum"];
        $return['proprietario']['cdreldepsegurado'] = (isset($datas['proprietario']["proprCdRelDepSegurado"]) ? $datas['proprietario']["proprCdRelDepSegurado"] : null);
        $return['proprietario']['descreldepsegurado'] = (isset($datas['proprietario']["proprdescRelDepSegurado"]) ? $datas['proprietario']["proprdescRelDepSegurado"] : null);
        $return['proprietario']['proprdddfone'] = $datas['proprietario']["proprFoneDdd"];
        $return['proprietario']['proprnmfone'] = $datas['proprietario']["proprFoneNum"];
        $return['proprietario']['proprnmend'] = $datas['proprietario']["proprEnd"];
        $return['proprietario']['proprnumero'] = $datas['proprietario']["proprEndNum"];
        $return['proprietario']['proprendcomplet'] = $datas['proprietario']["proprEndCompl"];
        $return['proprietario']['proprcep'] = $datas['proprietario']["proprEndCep"];
        $return['proprietario']['proprnmcidade'] = $datas['proprietario']["proprEndCidade"];
        $return['proprietario']['proprcduf'] = $datas['proprietario']["proprEndCdUf"];
        $return['proprietario']['idtipocliente'] = 2;
        $return['veiculo']['propcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
    else:
        $return['veiculo']['propcpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    endif;


    if (!$datas['indCondutorVeic']):
        $return['condutor']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
        $return['condutor']['condnomerazao'] = $datas['condutor']["condutNomeRazao"];
        $return['condutor']['conddtnasc'] = $datas['condutor']["condutDtNasci"];
        $return['condutor']['condcdsexo'] = $datas['condutor']["condutCdSexo"];
        $return['condutor']['condcdestadocivil'] = $datas['condutor']["condutCdEstCivl"];
        $return['condutor']['condcdprofiramoatividade'] = $datas['condutor']["condutProfRamoAtivi"];
        $return['condutor']['idtipocliente'] = 3;

        $return['veiculo']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
    else:
        $return['veiculo']['condcpfcnpj'] = $datas['segurado']["segCpfCnpj"];

    endif;


    if (isset($datas['segurado'])):
        $return['segurado']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['segurado']['clinomerazao'] = $datas['segurado']["segNomeRazao"];
        $return['segurado']['clidtnasc'] = $datas['segurado']["segDtNasci"];
        $return['segurado']['clicdsexo'] = $datas['segurado']["segCdSexo"];
        $return['segurado']['clicdestadocivil'] = $datas['segurado']["segCdEstCivl"];
        $return['segurado']['clicdprofiramoatividade'] = $datas['segurado']["segProfRamoAtivi"];
        $return['segurado']['cliemail'] = strtolower($datas['segurado']["segEmail"]);
        $return['segurado']['clidddcel'] = $datas['segurado']["segCelDdd"];
        $return['segurado']['clinmcel'] = $datas['segurado']["segCelNum"];
        $return['segurado']['clidddfone'] = $datas['segurado']["segFoneDdd"];
        $return['segurado']['clinmfone'] = $datas['segurado']["segFoneNum"];
        $return['segurado']['clinmend'] = $datas['segurado']["segEnd"];
        $return['segurado']['bairro'] = $datas['segurado']["segBairro"];
        $return['segurado']['clinumero'] = $datas['segurado']["segEndNum"];
        $return['segurado']['cliendcomplet'] = $datas['segurado']["segEndCompl"];
        $return['segurado']['clicep'] = $datas['segurado']["segEndCep"];
        $return['segurado']['clinmcidade'] = $datas['segurado']["segEndCidade"];
        $return['segurado']['clicduf'] = $datas['segurado']["segEndCdUf"];
        $return['segurado']['clinumrg'] = isset($datas['segurado']["segNumRg"]) ? $datas['segurado']["segNumRg"] : NULL;
        $return['segurado']['clidtemissaorg'] = isset($datas['segurado']["segDtEmissaoRg"]) ? $datas['segurado']["segDtEmissaoRg"] : NULL;
        $return['segurado']['cliemissorrg'] = isset($datas['segurado']["segEmissorRg"]) ? $datas['segurado']["segEmissorRg"] : NULL;
        $return['segurado']['clicdufemissaorg'] = isset($datas['segurado']["segCdUfRg"]) ? $datas['segurado']["segCdUfRg"] : NULL;
        $return['segurado']['idtipocliente'] = 1;
        $return['perfil']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['veiculo']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['cotacao']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    else:
        $return['perfil']['clicpfcnpj'] = NULL;
        $return['veiculo']['clicpfcnpj'] = NULL;
        $return['cotacao']['clicpfcnpj'] = NULL;
        $datas['indProprietVeic'] ? $return['veiculo']['condcpfcnpj'] = NULL : $return['veiculo']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
        $datas['indCondutorVeic'] ? $return['veiculo']['propcpfcnpj'] = NULL : $return['veiculo']['propcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
    endif;

    if (isset($datas['perfilSegurado'])):
        $return['perfil']['ceppernoite'] = $datas['perfilSegurado']["cepPernoite"];
        $return['perfil']['garagempernoite'] = $datas['perfilSegurado']["garagemPernoite"];
        $return['perfil']['ceptrabalho'] = $datas['perfilSegurado']["cepTrabalho"];
        $return['perfil']['garagemtrabalho'] = $datas['perfilSegurado']["garagemTrabalho"];
        $return['perfil']['seguradoestuda'] = $datas['perfilSegurado']["seguradoEstuda"];
        $return['perfil']['garagemescola'] = $datas['perfilSegurado']["garagemEscola"];
        $return['perfil']['outrosmotoristasapolice'] = $datas['perfilSegurado']["outrosMotoristasApolice"];
        $return['perfil']['motoristajovem'] = $datas['perfilSegurado']["motoristaJovem"];
        $return['perfil']['seguradoacidenteultano'] = $datas['perfilSegurado']["seguradoAcidenteUltAno"];
        $return['perfil']['seguradoroubadoultano'] = $datas['perfilSegurado']["seguradoRoubadoUltAno"];
        $return['perfil']['renovaapolice'] = $datas['perfilSegurado']["renovaApolice"];
        $return['perfil']['renovaseguradora'] = $datas['perfilSegurado']["renovaSeguradora"];
        $return['perfil']['bonusapoliceultano'] = $datas['perfilSegurado']["bonusApoliceUltAno"];
        $return['perfil']['kmpordia'] = $datas['perfilSegurado']["kmPorDia"];
        $return['perfil']['outroscarros'] = $datas['perfilSegurado']["outrosCarros"];
    endif;


    $return['veiculo']['veiccodfipe'] = $datas['veiculo']["veiCodFipe"];
    $return['veiculo']['veicano'] = $datas['veiculo']["veiAno"] == 0 ? 0 : $datas['veiculo']["veiAno"];
    $return['veiculo']['veicautozero'] = $datas['veiculo']["veiIndZero"] == 0 ? 0 : 1;
    $return['veiculo']['veiccdutilizaco'] = $datas['veiculo']["veiCdUtiliz"];
    $return['veiculo']['veiccdveitipo'] = $datas['veiculo']["veiCdTipo"];
    $return['veiculo']['veictipocombus'] = $datas['veiculo']["veiCdCombust"];
    $return['veiculo']['veicplaca'] = $datas['veiculo']["veiPlaca"] == NULL ? $datas['veiculo']["veiPlaca"] : strtoupper($datas['veiculo']["veiPlaca"]);
    $return['veiculo']['veicmunicplaca'] = $datas['veiculo']["veiMunPlaca"];
    $return['veiculo']['veiccdufplaca'] = $datas['veiculo']["veiCdUfPlaca"];
    $return['veiculo']['veicrenavam'] = $datas['veiculo']["veiRenav"];
    $return['veiculo']['veicanorenavam'] = $datas['veiculo']["veiAnoRenav"];
    $return['veiculo']['veicchassi'] = $datas['veiculo']["veiChassi"] == NULL ? $datas['veiculo']["veiChassi"] : strtoupper($datas['veiculo']["veiChassi"]);
    $return['veiculo']['veicchassiremar'] = $datas['veiculo']["veiIndChassiRema"];
    $return['veiculo']['veicleilao'] = $datas['veiculo']["veiIndLeilao"];
    $return['veiculo']['veicalienado'] = $datas['veiculo']["veiIndAlienado"];
    $return['veiculo']['veicacidentado'] = $datas['veiculo']["veiIndAcidentado"];
    $return['veiculo']['veianofab'] = $datas['veiculo']["veiAnoFab"];
    $return['veiculo']['veicor'] = $datas['veiculo']["veiCor"];

    $return['produto'] = $datas['produto'];

    $return['corretor']["corresusep"] = ($datas['corretor']["correSusep"] != null || !empty($datas['corretor']["correSusep"]) ? $datas['corretor']["correSusep"] : null);
    $return['corretor']["corrnomerazao"] = ($datas['corretor']["correNomeRazao"] != null || !empty($datas['corretor']["correNomeRazao"]) ? $datas['corretor']["correNomeRazao"] : null);
    $return['corretor']["corrcpfcnpj"] = ($datas['corretor']["correCpfCnpj"] != null || !empty($datas['corretor']["correCpfCnpj"]) ? $datas['corretor']["correCpfCnpj"] : null);
    $return['corretor']["corrdtnasc"] = ($datas['corretor']["correDtNasci"] != null || !empty($datas['corretor']["correDtNasci"]) ? $datas['corretor']["correDtNasci"] : null);
    $return['corretor']["corrcdsexo"] = ($datas['corretor']["correCdSexo"] != null || !empty($datas['corretor']["correCdSexo"]) ? $datas['corretor']["correCdSexo"] : null);
    $return['corretor']["corrcdestadocivil"] = ($datas['corretor']["correCdEstCivl"] != null || !empty($datas['corretor']["correCdEstCivl"]) ? $datas['corretor']["correCdEstCivl"] : null);
    $return['corretor']["corrcdprofiramoatividade"] = ($datas['corretor']["correProfRamoAtivi"] != null || !empty($datas['corretor']["correProfRamoAtivi"]) ? $datas['corretor']["correProfRamoAtivi"] : null);
    $return['corretor']["corremail"] = ($datas['corretor']["correEmail"] != null || !empty($datas['corretor']["correEmail"]) ? $datas['corretor']["correEmail"] : null);
    $return['corretor']["corrdddcel"] = ($datas['corretor']["correCelDdd"] != null || !empty($datas['corretor']["correCelDdd"]) ? $datas['corretor']["correCelDdd"] : null);
    $return['corretor']["corrnmcel"] = ($datas['corretor']["correCelNum"] != null || !empty($datas['corretor']["correCelNum"]) ? $datas['corretor']["correCelNum"] : null);
    $return['corretor']["corrdddfone"] = ($datas['corretor']["correFoneDdd"] != null || !empty($datas['corretor']["correFoneDdd"]) ? $datas['corretor']["correFoneDdd"] : null);
    $return['corretor']["corrnmfone"] = ($datas['corretor']["correFoneNum"] != null || !empty($datas['corretor']["correFoneNum"]) ? $datas['corretor']["correFoneNum"] : null);
    $return['corretor']["corrnmend"] = ($datas['corretor']["correEnd"] != null || !empty($datas['corretor']["correEnd"]) ? $datas['corretor']["correEnd"] : null);
    $return['corretor']["corrnumero"] = ($datas['corretor']["correEndNum"] != null || !empty($datas['corretor']["correEndNum"]) ? $datas['corretor']["correEndNum"] : null);
    $return['corretor']["correndcomplet"] = ($datas['corretor']["correEndCompl"] != null || !empty($datas['corretor']["correEndCompl"]) ? $datas['corretor']["correEndCompl"] : null);
    $return['corretor']["corrcep"] = ($datas['corretor']["correEndCep"] != null || !empty($datas['corretor']["correEndCep"]) ? $datas['corretor']["correEndCep"] : null);
    $return['corretor']["corrnmcidade"] = ($datas['corretor']["correEndCidade"] != null || !empty($datas['corretor']["correEndCidade"]) ? $datas['corretor']["correEndCidade"] : null);
    $return['corretor']["corrcduf"] = ($datas['corretor']["correEndCdUf"] != null || !empty($datas['corretor']["correEndCdUf"]) ? $datas['corretor']["correEndCdUf"] : null);
    $return['corretor']["idparceiro"] = $datas['idParceiro'];

    $return['cotacao']['comissao'] = $datas['comissao'] == null ? 0 : $datas['comissao'];
    $return['cotacao']['idparceiro'] = $datas['idParceiro'];
    $return['cotacao']['renova'] = isset($datas['renova']) ? $datas['renova'] : 0;



    return $return;
}

function real($numero)
{
    return number_format($numero, 2, ',', '.');
}

function floatN($numero)
{
    return (float)number_format($numero, 2, '.', '');
}

function aplicaComissao($valor, $comissao)
{
    if ($comissao > 0):
        $comissao = 1 - $comissao / 100;
        return (float)number_format($valor / $comissao, 2, '.', '');
    else:
        return (float)number_format($valor, 2, '.', '');
    endif;
}

function dataOrganize($datas)
{
    foreach ($datas as $key => $value):
        if (is_array($value)):
            foreach ($value as $K => $V):
                if ($V === ''):
                    $datas[$key][$K] = NULL;
                endif;
            endforeach;
        elseif ($value === ''):
            $datas[$key] = NULL;
        endif;
    endforeach;

    if (!isset($datas['veiculo'])):
        $datas['veiculo'] = $datas;
    elseif (isset($datas['veiculos'])):
        $datas['veiculo'] = $datas['veiculos'];
    endif;

    if (!$datas['indProprietVeic']):
        $return['proprietario']['proprcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
        $return['proprietario']['proprnomerazao'] = $datas['proprietario']["proprNomeRazao"];
        $return['proprietario']['proprdtnasc'] = $datas['proprietario']["proprDtNasci"];
        $return['proprietario']['proprcdsexo'] = $datas['proprietario']["proprCdSexo"];
        $return['proprietario']['proprcdestadocivil'] = $datas['proprietario']["proprCdEstCivl"];
        $return['proprietario']['proprcdprofiramoatividade'] = $datas['proprietario']["proprPrfoRamoAtivi"];
        $return['proprietario']['propremail'] = strtolower($datas['proprietario']["proprEmail"]);
        $return['proprietario']['proprdddcel'] = $datas['proprietario']["proprCelDdd"];
        $return['proprietario']['proprnmcel'] = $datas['proprietario']["proprCelNum"];
        $return['proprietario']['proprdddfone'] = $datas['proprietario']["proprFoneDdd"];
        $return['proprietario']['proprnmfone'] = $datas['proprietario']["proprFoneNum"];
        $return['proprietario']['proprnmend'] = $datas['proprietario']["proprEnd"];
        $return['proprietario']['proprnumero'] = $datas['proprietario']["proprEndNum"];
        $return['proprietario']['cdreldepsegurado'] = (isset($datas['proprietario']["proprCdRelDepSegurado"]) ? $datas['proprietario']["proprCdRelDepSegurado"] : null);
        $return['proprietario']['descreldepsegurado'] = (isset($datas['proprietario']["proprdescRelDepSegurado"]) ? $datas['proprietario']["proprdescRelDepSegurado"] : null);
        $return['proprietario']['proprendcomplet'] = $datas['proprietario']["proprEndCompl"];
        $return['proprietario']['proprcep'] = $datas['proprietario']["proprEndCep"];
        $return['proprietario']['proprnmcidade'] = $datas['proprietario']["proprEndCidade"];
        $return['proprietario']['proprcduf'] = $datas['proprietario']["proprEndCdUf"];
        $return['proprietario']['idtipocliente'] = 2;

        $return['veiculo']['propcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
    else:
        $return['veiculo']['propcpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    endif;

    if (!$datas['indCondutorVeic']):
        $return['condutor']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
        $return['condutor']['condnomerazao'] = $datas['condutor']["condutNomeRazao"];
        $return['condutor']['conddtnasc'] = $datas['condutor']["condutDtNasci"];
        $return['condutor']['condcdsexo'] = $datas['condutor']["condutCdSexo"];
        $return['condutor']['condcdestadocivil'] = $datas['condutor']["condutCdEstCivl"];
        $return['condutor']['condcdprofiramoatividade'] = $datas['condutor']["condutProfRamoAtivi"];
        $return['condutor']['idtipocliente'] = 3;

        $return['veiculo']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
    else:
        $return['veiculo']['condcpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    endif;


    #$return['proposta']['idparceiro'] = $datas["idParceiro"];
    $return['proposta']['idcotacao'] = $datas["cdCotacao"];
    $return['proposta']['idformapg'] = $datas["cdFormaPgt"];
    $return['proposta']['quantparc'] = $datas["qtParcela"];
    $return['proposta']['nmbandeira'] = $datas["nmBandeira"];
    $return['proposta']['numcartao'] = $datas["numCartao"];
    $return['proposta']['validadecartao'] = $datas["validadeCartao"];
    $return['proposta']['titularcartao'] = strtoupper($datas["titularCartao"]);
    $return['proposta']['dtvalidade'] = date('Y-m-d 00:00:00', strtotime('+ 30 days'));


    $return['perfil']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
    $return['perfil']['ceppernoite'] = $datas['perfilSegurado']["cepPernoite"];
    $return['perfil']['garagempernoite'] = $datas['perfilSegurado']["garagemPernoite"];
    $return['perfil']['ceptrabalho'] = $datas['perfilSegurado']["cepTrabalho"];
    $return['perfil']['garagemtrabalho'] = $datas['perfilSegurado']["garagemTrabalho"];
    $return['perfil']['seguradoestuda'] = $datas['perfilSegurado']["seguradoEstuda"];
    $return['perfil']['garagemescola'] = $datas['perfilSegurado']["garagemEscola"];
    $return['perfil']['outrosmotoristasapolice'] = $datas['perfilSegurado']["outrosMotoristasApolice"];
    $return['perfil']['motoristajovem'] = $datas['perfilSegurado']["motoristaJovem"];
    $return['perfil']['seguradoacidenteultano'] = $datas['perfilSegurado']["seguradoAcidenteUltAno"];
    $return['perfil']['seguradoroubadoultano'] = $datas['perfilSegurado']["seguradoRoubadoUltAno"];
    $return['perfil']['renovaapolice'] = $datas['perfilSegurado']["renovaApolice"];
    $return['perfil']['renovaseguradora'] = $datas['perfilSegurado']["renovaSeguradora"];
    $return['perfil']['bonusapoliceultano'] = $datas['perfilSegurado']["bonusApoliceUltAno"];
    $return['perfil']['kmpordia'] = $datas['perfilSegurado']["kmPorDia"];
    $return['perfil']['outroscarros'] = $datas['perfilSegurado']["outrosCarros"];



    if (isset($datas['segurado'])):
        $return['segurado']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['segurado']['clinomerazao'] = strtoupper($datas['segurado']["segNomeRazao"]);
        $return['segurado']['clidtnasc'] = $datas['segurado']["segDtNasci"];
        $return['segurado']['clicdsexo'] = $datas['segurado']["segCdSexo"];
        $return['segurado']['clicdestadocivil'] = $datas['segurado']["segCdEstCivl"];
        $return['segurado']['clicdprofiramoatividade'] = $datas['segurado']["segProfRamoAtivi"];
        $return['segurado']['cliemail'] = strtolower($datas['segurado']["segEmail"]);
        $return['segurado']['clidddcel'] = ($datas['segurado']["segCelDdd"] != NULL ? $datas['segurado']["segCelDdd"] : NULL);
        $return['segurado']['clinmcel'] = ($datas['segurado']["segCelNum"] != NULL ? $datas['segurado']["segCelNum"] : NULL);
        $return['segurado']['clidddfone'] = ($datas['segurado']["segFoneDdd"] != NULL ? $datas['segurado']["segFoneDdd"] : NULL);
        $return['segurado']['clinmfone'] = ($datas['segurado']["segFoneNum"] != NULL ? $datas['segurado']["segFoneNum"] : NULL);
        $return['segurado']['clinmend'] = strtoupper( $datas['segurado']["segEnd"]);
        $return['segurado']['clinumero'] = $datas['segurado']["segEndNum"];
        $return['segurado']['cliendcomplet'] = strtoupper($datas['segurado']["segEndCompl"]);
        $return['segurado']['clicep'] = $datas['segurado']["segEndCep"];
        $return['segurado']['clinmcidade'] = strtoupper($datas['segurado']["segEndCidade"]);
        $return['segurado']['clicduf'] = $datas['segurado']["segEndCdUf"];
        $return['segurado']['bairro'] = strtoupper($datas['segurado']["segBairro"]);
        $return['segurado']['clinumrg'] = isset($datas['segurado']["segNumRg"]) ? $datas['segurado']["segNumRg"] : NULL;
        $return['segurado']['clidtemissaorg'] = isset($datas['segurado']["segDtEmissaoRg"]) ? $datas['segurado']["segDtEmissaoRg"] : NULL;
        $return['segurado']['cliemissorrg'] = isset($datas['segurado']["segEmissorRg"]) ? $datas['segurado']["segEmissorRg"] : NULL;
        $return['segurado']['clicdufemissaorg'] = isset($datas['segurado']["segCdUfRg"]) ? $datas['segurado']["segCdUfRg"] : NULL;
        $return['perfil']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['veiculo']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];
        $return['cotacao']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];

    else:
        $return['perfil']['clicpfcnpj'] = NULL;
        $return['veiculo']['clicpfcnpj'] = NULL;
        $return['cotacao']['clicpfcnpj'] = NULL;
        $datas['indProprietVeic'] ? $return['veiculo']['condcpfcnpj'] = NULL : $return['veiculo']['condcpfcnpj'] = $datas['condutor']["condutCpfCnpj"];
        $datas['indCondutorVeic'] ? $return['veiculo']['propcpfcnpj'] = NULL : $return['veiculo']['propcpfcnpj'] = $datas['proprietario']["proprCpfCnpj"];
    endif;

    if (isset($datas['perfilSegurado'])):
        $return['perfil']['ceppernoite'] = $datas['perfilSegurado']["cepPernoite"];
        $return['perfil']['garagempernoite'] = $datas['perfilSegurado']["garagemPernoite"];
        $return['perfil']['ceptrabalho'] = $datas['perfilSegurado']["cepTrabalho"];
        $return['perfil']['garagemtrabalho'] = $datas['perfilSegurado']["garagemTrabalho"];
        $return['perfil']['seguradoestuda'] = $datas['perfilSegurado']["seguradoEstuda"];
        $return['perfil']['garagemescola'] = $datas['perfilSegurado']["garagemEscola"];
        $return['perfil']['outrosmotoristasapolice'] = $datas['perfilSegurado']["outrosMotoristasApolice"];
        $return['perfil']['motoristajovem'] = $datas['perfilSegurado']["motoristaJovem"];
        $return['perfil']['seguradoacidenteultano'] = $datas['perfilSegurado']["seguradoAcidenteUltAno"];
        $return['perfil']['seguradoroubadoultano'] = $datas['perfilSegurado']["seguradoRoubadoUltAno"];
        $return['perfil']['renovaapolice'] = $datas['perfilSegurado']["renovaApolice"];
        $return['perfil']['renovaseguradora'] = $datas['perfilSegurado']["renovaSeguradora"];
        $return['perfil']['bonusapoliceultano'] = $datas['perfilSegurado']["bonusApoliceUltAno"];
        $return['perfil']['kmpordia'] = $datas['perfilSegurado']["kmPorDia"];
        $return['perfil']['outroscarros'] = $datas['perfilSegurado']["outrosCarros"];
    endif;


    $return['veiculo']['veicplaca'] = strtoupper($datas['veiculo']["veiPlaca"]);
    $return['veiculo']['veicmunicplaca'] = $datas['veiculo']["veiMunPlaca"];
    $return['veiculo']['veiccdufplaca'] = $datas['veiculo']["veiCdUfPlaca"];
    $return['veiculo']['veicrenavam'] = $datas['veiculo']["veiRenav"];
    $return['veiculo']['veicanorenavam'] = $datas['veiculo']["veiAnoRenav"];
    $return['veiculo']['veicchassi'] = strtoupper($datas['veiculo']["veiChassi"]);
    $return['veiculo']['veicchassiremar'] = $datas['veiculo']["veiIndChassiRema"];
    $return['veiculo']['veicleilao'] = $datas['veiculo']["veiIndLeilao"];
    $return['veiculo']['veicalienado'] = $datas['veiculo']["veiIndAlienado"];
    $return['veiculo']['veicacidentado'] = $datas['veiculo']["veiIndAcidentado"];
    $return['veiculo']['veianofab'] = $datas['veiculo']["veiAnoFab"];
    $return['veiculo']['veicor'] = $datas['veiculo']["veiCor"];
    $return['veiculo']['veiccdutilizaco'] = $datas['veiculo']["veiCdUtiliz"];
    $return['veiculo']['veiccodfipe'] = $datas['veiculo']["veiCodFipe"];
    $return['veiculo']['veicano'] = $datas['veiculo']["veiAno"];
    $return['veiculo']['veicautozero'] = $datas['veiculo']["veiIndZero"] == 0 ? 0 : 1;
    $return['veiculo']['veiccdveitipo'] = $datas['veiculo']["veiCdTipo"];
    $return['veiculo']['veictipocombus'] = $datas['veiculo']["veiCdCombust"];

    $return['cotacao']['clicpfcnpj'] = $datas['segurado']["segCpfCnpj"];

    $return['produto'] = $datas['produto'];

    $return['corretor']["corresusep"] = ($datas['corretor']["correSusep"] != null || !empty($datas['corretor']["correSusep"]) ? $datas['corretor']["correSusep"] : null);
    $return['corretor']["corrnomerazao"] = ($datas['corretor']["correNomeRazao"] != null || !empty($datas['corretor']["correNomeRazao"]) ? strtoupper($datas['corretor']["correNomeRazao"]) : null);
    $return['corretor']["corrcpfcnpj"] = ($datas['corretor']["correCpfCnpj"] != null || !empty($datas['corretor']["correCpfCnpj"]) ? $datas['corretor']["correCpfCnpj"] : null);
    $return['corretor']["corrdtnasc"] = ($datas['corretor']["correDtNasci"] != null || !empty($datas['corretor']["correDtNasci"]) ? $datas['corretor']["correDtNasci"] : null);
    $return['corretor']["corrcdsexo"] = ($datas['corretor']["correCdSexo"] != null || !empty($datas['corretor']["correCdSexo"]) ? $datas['corretor']["correCdSexo"] : null);
    $return['corretor']["corrcdestadocivil"] = ($datas['corretor']["correCdEstCivl"] != null || !empty($datas['corretor']["correCdEstCivl"]) ? $datas['corretor']["correCdEstCivl"] : null);
    $return['corretor']["corrcdprofiramoatividade"] = ($datas['corretor']["correProfRamoAtivi"] != null || !empty($datas['corretor']["correProfRamoAtivi"]) ? $datas['corretor']["correProfRamoAtivi"] : null);
    $return['corretor']["corremail"] = ($datas['corretor']["correEmail"] != null || !empty($datas['corretor']["correEmail"]) ? $datas['corretor']["correEmail"] : null);
    $return['corretor']["corrdddcel"] = ($datas['corretor']["correCelDdd"] != null || !empty($datas['corretor']["correCelDdd"]) ? $datas['corretor']["correCelDdd"] : null);
    $return['corretor']["corrnmcel"] = ($datas['corretor']["correCelNum"] != null || !empty($datas['corretor']["correCelNum"]) ? $datas['corretor']["correCelNum"] : null);
    $return['corretor']["corrdddfone"] = ($datas['corretor']["correFoneDdd"] != null || !empty($datas['corretor']["correFoneDdd"]) ? $datas['corretor']["correFoneDdd"] : null);
    $return['corretor']["corrnmfone"] = ($datas['corretor']["correFoneNum"] != null || !empty($datas['corretor']["correFoneNum"]) ? $datas['corretor']["correFoneNum"] : null);
    $return['corretor']["corrnmend"] = ($datas['corretor']["correEnd"] != null || !empty($datas['corretor']["correEnd"]) ? $datas['corretor']["correEnd"] : null);
    $return['corretor']["corrnumero"] = ($datas['corretor']["correEndNum"] != null || !empty($datas['corretor']["correEndNum"]) ? $datas['corretor']["correEndNum"] : null);
    $return['corretor']["correndcomplet"] = ($datas['corretor']["correEndCompl"] != null || !empty($datas['corretor']["correEndCompl"]) ? $datas['corretor']["correEndCompl"] : null);
    $return['corretor']["corrcep"] = ($datas['corretor']["correEndCep"] != null || !empty($datas['corretor']["correEndCep"]) ? $datas['corretor']["correEndCep"] : null);
    $return['corretor']["corrnmcidade"] = ($datas['corretor']["correEndCidade"] != null || !empty($datas['corretor']["correEndCidade"]) ? $datas['corretor']["correEndCidade"] : null);
    $return['corretor']["corrcduf"] = ($datas['corretor']["correEndCdUf"] != null || !empty($datas['corretor']["correEndCdUf"]) ? $datas['corretor']["correEndCdUf"] : null);
    $return['corretor']["idparceiro"] = $datas['idParceiro'];

    $return['cotacao']['comissao'] = $datas['comissao'] == null ? 0 : $datas['comissao'];
    $return['cotacao']['idparceiro'] = $datas['idParceiro'];
    $return['cotacao']['renova'] = isset($datas['renova']) ? $datas['renova'] : 0;



    return $return;
}
