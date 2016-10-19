<!doctype html>
<html lang="pt-br">
<head>

    <title>Seguro Auto Pratico</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/pdf.css"/>

    <script src="<?= base_url() ?>assets/js/jquery.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>


</head>
<body class="">

<table class="pdf-table">

    <tbody>
    <!--    Inicio dados Corretor e Parceiro -->
    <tr>
        <td class="pdf-table-td-top">
            <table class="pdf-table-td-table">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>CORRETOR:</b></td>
                                <td class="pdf-table-td-content"><?= nomeCase($proposta['cotacao']['corretor']['corrnomerazao']) ?></td>
                                <td class="pdf-table-td-title"><b>SUSEP:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['corretor']['corresusep'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title">FONE:</td>
                                <td class="pdf-table-td-content"> (<?= $proposta['cotacao']['corretor']['corrdddcel'] ?>
                                    ) <?= format('fone', $proposta['cotacao']['corretor']['corrnmcel']) ?></td>
                                <td class="pdf-table-td-title">EMAIL:</td>
                                <td class="pdf-table-td-content"> <?= $proposta['cotacao']['corretor']['corremail'] ?></td>
                                <td class="pdf-table-td-title">PARCEIRO:</td>
                                <td class="pdf-table-td-content"> <?= nomeCase($proposta['cotacao']['parceiro']['nomerazao']) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <!--    Fim dados Corretor e Parceiro -->

    <!--    Inicio dados Proposta -->
    <tr class="pdf-table-tr-title">
        <td><h4>DADOS DA PROPOSTA</h4></td>
    </tr>

    <tr>
        <td class="pdf-table-td">
            <table class="pdf-table-td-table">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>REF. COTAÇÃO Nº:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['idcotacao'] ?></td>
                                <td class="pdf-table-td-title"><b>DATA EMISSÃO:</b></td>
                                <td class="pdf-table-td-content"><?= date("d/m/Y H:i:s",
                                        strtotime($proposta['dtcreate'])) ?>
                                </td>
                                <td class="pdf-table-td-title"><b>VALIDADE:</b></td>
                                <td class="pdf-table-td-content"><?= date("d/m/Y H:i:s",
                                        strtotime($proposta['dtvalidade'])) ?>
                                </td>
                                <td class="pdf-table-td-title"><b>VIGÊNCIA:</b></td>
                                <td class="pdf-table-td-content">ANUAL</td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <!--    Fim dados Proposta -->

    <!--    Inicio dados Proponente -->

    <tr class="pdf-table-tr-title">
        <td><h4>DADOS DO PROPONENTE</h4></td>
    </tr>

    <tr>
        <td class="pdf-table-td">
            <table class="pdf-table-td-table">
                <?php if (strlen($proposta['cotacao']['segurado']['clicpfcnpj']) > 11) { ?>

                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>RAZÃO SOCIAL:</b></td>
                                    <td class="pdf-table-td-content"><?= nomeCase($proposta['cotacao']['segurado']['clinomerazao']) ?></td>
                                    <td class="pdf-table-td-title"><b>CNPJ:</b></td>
                                    <td class="pdf-table-td-content"><?= format('cpfcnpj', $proposta['cotacao']['segurado']['clicpfcnpj']) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>DATA ABERTURA:</b></td>
                                    <td class="pdf-table-td-content"><?= date("d/m/Y",
                                            strtotime($proposta['cotacao']['segurado']['clidtnasc'])) ?>
                                    </td>
                                    <td class="pdf-table-td-title"><b>RAMO DE ATIVIDADE:</b></td>
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['ramoatividade']['nome_atividade'] ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                <?php } else { ?>

                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>NOME:</b></td>
                                    <td class="pdf-table-td-content"><?= nomeCase($proposta['cotacao']['segurado']['clinomerazao']) ?></td>
                                    <td class="pdf-table-td-title"><b>CPF:</b></td>
                                    <td class="pdf-table-td-content"><?= format('cpfcnpj', $proposta['cotacao']['segurado']['clicpfcnpj']) ?>
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>RG:</b></td>
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['clinumrg'] ?></td>
                                    <td class="pdf-table-td-title"><b>ORGÂO EMISSOR:</b></td>
                                    <td class="pdf-table-td-content"><?= strtoupper($proposta['cotacao']['segurado']['cliemissorrg']) ?>
                                        -<?= $proposta['cotacao']['segurado']['rg_uf']['nm_uf'] ?></td>
                                    <td class="pdf-table-td-title"><b>DATA EMISSAO:</b></td>
                                    <td class="pdf-table-td-content"><?= date("d/m/Y",
                                            strtotime($proposta['cotacao']['segurado']['clidtemissaorg'])) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>DATA NASCIMENTO:</b></td>
                                    <td class="pdf-table-td-content"><?= date("d/m/Y",
                                            strtotime($proposta['cotacao']['segurado']['clidtnasc'])) ?>
                                    </td>
                                    <td class="pdf-table-td-title"><b>ESTADO CIVIL:</b></td>
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['estadocivl']['nmestadocivil'] ?></td>
                                    <td class="pdf-table-td-title"><b>SEXO:</b></td>
                                    <td class="pdf-table-td-content"><?= ($proposta['cotacao']['segurado']['clicdsexo'] == 1 ? 'Masculino' : 'Feminino') ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>PROFISSÃO:</b></td>
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['profissao']['nm_ocupacao'] ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                <?php } ?>

                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>ENDEREÇO:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['clinmend'] ?></td>
                                <td class="pdf-table-td-title"><b>CIDADE:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['clinmcidade'] ?></td>
                                <td class="pdf-table-td-title"><b>UF:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['uf']['nm_uf'] ?></td>

                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>NUMERO:</b></td>
                                <td class="pdf-table-td-content"
                                    width="50px"><?= $proposta['cotacao']['segurado']['clinumero'] ?></td>
                                <?php if (!empty($proposta['cotacao']['segurado']['cliendcomplet'])): ?>
                                    <td class="pdf-table-td-title"><b>COMPLEMENTO:</b></td>
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['cliendcomplet'] ?></td>
                                <?php endif; ?>
                                <td class="pdf-table-td-title"><b>CEP:</b></td>
                                <td class="pdf-table-td-content"><?= format('cep', $proposta['cotacao']['segurado']['clicep']) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <?php if (strlen($proposta['cotacao']['segurado']['clidddfone']) < 8): ?>
                                    <td class="pdf-table-td-title"><b>TELEFONE:</b></td>
                                    <td class="pdf-table-td-content">
                                        (<?= $proposta['cotacao']['segurado']['clidddfone'] ?>) <?= format('fone',
                                            $proposta['cotacao']['segurado']['clinmfone']) ?>
                                    </td>
                                <?php endif; ?>

                                <?php if (strlen($proposta['cotacao']['segurado']['clinmcel']) < 8): ?>
                                    <td class="pdf-table-td-title"><b>CELULAR:</b></td>
                                    <td class="pdf-table-td-content">
                                        (<?= $proposta['cotacao']['segurado']['clidddcel'] ?>) <?= format('fone',
                                            $proposta['cotacao']['segurado']['clinmcel']) ?>
                                    </td>
                                <?php endif; ?>

                                <td class="pdf-table-td-title"><b>EMAIL:</b></td>
                                <td class="pdf-table-td-content"> <?= $proposta['cotacao']['segurado']['cliemail'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <!--    Fim dados Proponente -->

    <!--    Inicio dados veiculos -->

    <tr class="pdf-table-tr-title">
        <td><h4>DADOS DO VEÍCULO</h4></td>
    </tr>
    <?php foreach ($proposta['cotacao']['veiculo']['fipe']['valores'] as $valor) {
        if ($proposta['cotacao']['veiculo']['veicano'] == $valor['ano'] && $proposta['cotacao']['veiculo']['veictipocombus'] == $valor['idcombustivel']) {
            $proposta['cotacao']['veiculo']['fipe']['valores'] = $valor;
        }
    } ?>
    <tr>
        <td class="pdf-table-td">
            <table class="pdf-table-td-table">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>VEICULO:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['fipe']['marca'] ?>
                                    / <?= $proposta['cotacao']['veiculo']['fipe']['modelo'] ?></td>
                                <td class="pdf-table-td-title"><b>FIPE:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['fipe']['codefipe'] ?></td>

                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>VALOR:</b></td>
                                <td class="pdf-table-td-content">
                                    R$ <?= real($proposta['cotacao']['veiculo']['fipe']['valores']['valor']) ?></td>
                                <td class="pdf-table-td-title"><b>ANO MODELO:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['fipe']['valores']['ano'] ?></td>
                                <td class="pdf-table-td-title"><b>ANO FABRICAÇÃO:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['veianofab'] ?></td>
                                <td class="pdf-table-td-title"><b>ZERO KM?</b></td>
                                <td class="pdf-table-td-content"><?= ($proposta['cotacao']['veiculo']['veicautozero'] == 1 ? 'Sim' : 'Não') ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>CHASSI:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['veicchassi'] ?></td>
                                <td class="pdf-table-td-title"><b>PLACA:</b></td>
                                <td class="pdf-table-td-content"><?= format("placa", $proposta['cotacao']['veiculo']['veicplaca']) ?></td>
                                <td class="pdf-table-td-title"><b>RENAVAM:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['veicrenavam'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>COMBUSTIVEL:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['combustivel']['nmcomb'] ?></td>
                                <td class="pdf-table-td-title"><b>UTILIZAÇÃO:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['utilizacao']['descutilveiculo'] ?></td>
                                <td class="pdf-table-td-title"><b>COR:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['veiculo']['veicor'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>PROPRIETARIO:</b></td>
                                <td class="pdf-table-td-content"><?= ($proposta['cotacao']['veiculo']['propcpfcnpj'] == $proposta['cotacao']['segurado']['clicpfcnpj'] ? nomeCase($proposta['cotacao']['segurado']['clinomerazao']) : nomeCase($proposta['cotacao']['veiculo']['proprietario']['proprnomerazao'])) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>


    <!--    Fim dados veiculos -->


    <!--    Inicio dados Coberturas e serviços -->

    <tr class="pdf-table-tr-title">
        <td><h4>COBERTURAS E SERVIÇOS CONTRATADOS</h4></td>
    </tr>
    <?php $pt = count($proposta['cotacao']['produtos']) ?>
    <?php $premio = 0; ?>
    <?php foreach ($proposta['cotacao']['produtos'] as $produto) :
        $key = array_search($produto['idprecoproduto'], array_column($produto['produto']['precos'], 'idprecoproduto'));
        $produto['produto']['precos'] = $produto['produto']['precos'][$key]; ?>
        <tr>
            <td class="pdf-table-td<?= ($pt != 1 ? '-produtos' : '') ?>">
                <table class="pdf-table-td-table">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>PRODUTO:</b></td>
                                    <td class="pdf-table-td-content"><?= $produto['produto']['nomeproduto'] ?>
                                        (<?= $produto['produto']['descproduto'] ?>)
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="pdf-table-td-content-p">
                            <li><?= $produto['produto']['caractproduto'] ?></li>
                        </td>
                    </tr>
                    <tr>
                        <td class="pdf-table-td-content-p">
                            <li><?= $produto['produto']['cobertura'] ?></li>
                        </td>
                    </tr>

                    <tr class="pdf-table-td-subtitle">
                        <td></td>
                    </tr>

                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <?php if ($produto['produto']['precos']['lmiproduto'] != null && $produto['produto']['precos']['lmiproduto'] != 0): ?>
                                        <td class="pdf-table-td-title"><b>LMI (limite maximo de indenização):</b></td>
                                        <td class="pdf-table-td-content">
                                            R$ <?= real($produto['produto']['precos']['lmiproduto']) ?></td>
                                    <?php endif; ?>
                                    <?php if ($produto['produto']['precos']['porcentfipepremio'] != null && $produto['produto']['precos']['porcentfipepremio'] != 0): ?>
                                        <td class="pdf-table-td-title"><b>% INDENIZAÇÃO FIPE:</b></td>
                                        <td class="pdf-table-td-content"><?= $produto['produto']['precos']['porcentfipepremio'] ?>
                                            %
                                        </td>
                                    <?php endif; ?>
                                    <td class="pdf-table-td-title"><b>PREMIO:</b></td>
                                    <td class="pdf-table-td-content">R$ <?php
                                        $valor = aplicaComissao(
                                            ($produto['produto']['idproduto'] == 1 ?
                                                $produto['produto']['precos']['premioliquidoproduto'] + $proposta['cotacao']['veiculo']['fipe']['contigencia']['valor'] :
                                                $produto['produto']['precos']['premioliquidoproduto'])
                                            , $proposta['cotacao']['comissao']);

                                        $premio = $valor + $premio;
                                        echo real($valor) ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>EXIGÊNCIAS:</b></td>
                                    <td class="pdf-table-td-content">VISTORIA
                                        PRÉVIA? <?= ($produto['produto']['ind_exige_vistoria'] ? 'Sim' : 'Não') ?> /
                                        DISPOSITIVO
                                        ANTI-FURTO? <?= ($produto['produto']['ind_exige_rastreador'] ? 'Sim' : 'Não') ?> </td>
                                    <td class="pdf-table-td-content"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <!--                    INICIO LOOP-->
                    <?php foreach ($produto['produto']['seguradoras'] as $seguradora): ?>
                        <?php $seguradoras[$seguradora['idseguradora']] = $seguradora['seguradora'] ?>
                    <?php endforeach; ?>
                    <!--                    FIM LOOP-->


                </table>
            </td>
        </tr>
        <?php if ($pt != 1) : ?>
        <tr class="pdf-table-tr-produto">
            <td></td>
        </tr>
        <?php $pt = $pt - 1 ?>

    <?php endif; ?>
    <?php endforeach; ?>
    <!--    Fim dados Coberturas e serviços -->


    <!--    Inicio condições de pagamento -->
    <tr class="pdf-table-tr-title">
        <td><h4>CONDIÇÕES DE PAGAMENTO</h4></td>
    </tr>
    <?php $proposta['forma_pagamento']['taxamesjuros'] = str_replace('.', ',', $proposta['forma_pagamento']['taxamesjuros']) ?>
    <tr>
        <td class="pdf-table-td">
            <table class="pdf-table-td-table">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>PREMIO AVISTA:</b></td>
                                <td class="pdf-table-td-content"> R$ <?= real($premio) ?></td>
                                <td class="pdf-table-td-title"><b>PREMIO EM <?= $proposta['quantparc'] ?>X:</b></td>
                                <td class="pdf-table-td-content"> R$ <?= real($proposta['premiototal']) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>FORMA DE PAGAMENTO:</b></td>
                                <td class="pdf-table-td-content">
                                    <?php if ($proposta['forma_pagamento']['idformapgto'] == 1): ?>
                                        <?= $proposta['quantparc'] ?>X de
                                        R$ <?= real($proposta['primeiraparc']) ?> <?= ($proposta['quantparc'] > $proposta['forma_pagamento']['descformapgto'] ? "com {$proposta['forma_pagamento']['taxamesjuros']}%  de juros " : '') ?>
                                        no <?= $proposta['forma_pagamento']['descformapgto'] ?>
                                    <?php else: ?>

                                        <?php if ($proposta['quantparc'] == 1): ?>
                                            <?= $proposta['quantparc'] ?> X de R$
                                            <?= real($proposta['primeiraparc']) ?> no <?= $proposta['forma_pagamento']['descformapgto'] ?>
                                        <?php else: ?>

                                            <?php if ($proposta['primeiraparc'] == $proposta['demaisparc']): ?>
                                                <?= $proposta['quantparc'] ?>X de R$ <?= real($proposta['primeiraparc']) ?>
                                            <?php else: $proposta['quantparc'] = $proposta['quantparc'] - 1 ?>
                                                1ª de R$ <?= real($proposta['primeiraparc']) ?> e mais <?= $proposta['quantparc'] ?> de R$ <?= real($proposta['demaisparc']) ?>
                                            <?php endif; ?>

                                            <?= ($proposta['quantparc'] > $proposta['forma_pagamento']['descformapgto'] ? "com {$proposta['forma_pagamento']['taxamesjuros']}%  de juros " : '') ?>
                                            no <?= $proposta['forma_pagamento']['descformapgto'] ?>
                                        <?php endif; ?>


                                    <?php endif; ?>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>


            </table>
        </td>
    </tr>
    <!--    Fim condições de pagamento -->

    <tr class="pdf-table-tr-title">
        <td><h4>CONSIDERAÇÕES IMPORTANTES</h4></td>
    </tr>

    </tbody>
</table>

<div class="texto-legal">

    <p>O “Seguro AUTOPRATICO” (Seguro contra roubo e furto) tem como base um fator de ajuste aplicado
        sobre o valor do veículo referência que constava na tabela FIPE na data de contratação do seguro, do site
        <a href="">www.fipe.org.br</a>. Este produto Seguro AUTOPRATICO é a união de uma apólice de Seguro Roubo e Furto
        emitida por
        <b>QBE Brasil Seguros S/A   -  CNPJ: 96.348.677/0001-94 </b>(Código SUSEP: 594-1) – Seguro ROUBO e FURTO
         Processo
        SUSEP n° 15414.901946/2014-12, ou por <b>USEBENS Seguros S.A. CNPJ N. 09.180.505/0001-50 </b> (Código SUSEP:
        367-1) –
        Seguro ROUBO e FURTO - Processo SUSEP n° 15414.90.2028/2013-1, que exigem o monitoramento continuo por sistema
        anti-furto da empresa SKYPROTECTION Tec. Inf. Veic. Ltda. A SKYPROTECTION é empresa homologada a prestar esse
        monitoramento, a comercialilzar o combo (monitoramento mais seguro) e a cobrar este combo diretamente dos
        clientes finais, sempre considerando a comercialização por intermédio de um corretor de Seguros conforme norma
        do setor. <br>
        Estão cobertos os prejuízos, previstos nos termos de suas condições gerais das respectivas seguradoras,
        devidamente comprovados e respeitados os riscos excluídos, decorrentes de Roubo ou Furto Total, seguidos da não
        localização do veículo devidamente atestada pela SKYPROTECTION Tec. Inf. Veic. Ltda., no período estipulado na
        apólice/certificado. Serão elegíveis à contratação do seguro apenas os veículos que, no momento da adesão,
        adquirirem sistema de rastreamento/monitoramento veicular SKYPROTECTION, sendo que o início da cobertura do
        seguro se dará após a devida instalação e ativação do sistema, desde que a instalação seja feita no prazo máximo
        de até 15 dias a contar da data de entrega desta proposta. <br>
        Apólices de RCF serão emitidas pela <b>Nobre Seguradora do Brasil S/A, CNPJ: 85.031.334/0001-85</b>, Registro
        SUSEP:
        575-4. Apólices de Perda Total por Colisão serão emitidas pela <b>QBE Brasil Seguros S/A</b>.
        As indenizações, caso ocorram serão sempre exclusivamente arcadas por parte da Seguradora que emitir a Apólice.
        A cobrança tanto do equipamento anti furto como do serviço de monitoramento necessário para a emissão das
        apólices, assim como o próprio premio do seguro será unificada e de responsabilidade da SKYPROTECTION Tec. Inf.
        Veic. Ltda. <br>
        A inadimplência da primeira parcela do seguro implica na recusa desta Proposta de Adesão.
        O não cumprimento das ações declaradas nesta proposta poderá acarretar a perda de direito à indenização do
        seguro. <br>
        Ao termino deste contrato é obrigatória a devolução do equipamento anti-furto para a SKYPROTECTION. Para a
        desinstalação do rastreador haverá um custo no valor de R$ 199,00 (cento e noventa e nove reais) se realizada
        dentro do primeiro ano de contrato e de R$ 100,00 (cem reais) para os demais casos. </p>

    <p> As condições dos serviços e produtos aqui contratados assim como as
        Condições Gerais completas de seu Seguro encontram-se disponíveis para consulta nos respectivos sites das
        seguradoras e também por facilidade replicadas no site <a href="">www.seguroautopratico.com.br/contratos</a>,
        motivo pelo qual
        informo ser desnecessário o envio das Condições Gerais impressas e que estou ciente de que, caso as necessite,
        poderei requisitá-las em sua Central de Atendimento ou descarrega-las no site/endereço acima. - O Segurado,
        declara ainda ciência e concorda que tanto o contrato de prestação de serviços de monitoramento como a(s)
        Apólice(s) de Seguro será(ão) disponibilizada(s) por meio eletrônico, por email ou no(s) site(s) da(s)
        Seguradora(s), no prazo legal. - As informações acima foram fornecidas pelo Proponente (mesmo que não
        preenchidas de próprio punho) e são levadas em consideração pela Seguradora para o cálculo do prêmio de seguro
        para possível aceitação do risco. Em razão disso, o Proponente declara que todas as informações previstas na
        presente proposta são verdadeiras e foram prestadas de boa-fé, assumindo total responsabilidade pela sua
        exatidão, sob pena de prejudicar sua eventual indenização. - Antes da assinatura da presente proposta de seguro,
        o Proponente declara já ter tomado conhecimento prévio das particularidades dos serviços de monitoramento
        indissociáveis e necessários para a presente condição comercial assim como das Condições Gerais que regem os
        Seguros incluidos, especialmente das cláusulas restritivas e/ou limitativas de direitos, autorizando a
        Seguradora a emitir Apólice/Certificado em caso de aceitação do risco. - A aceitação do seguro está sujeita à
        análise do risco, dentro do prazo legal. - A presente proposta, juntamente com o contrato de prestação de
        serviço de monitoramento e as Condições Gerais, Apólice/Certificado de Seguro, são partes integrante do contrato
        de Seguro, sendo as informações ora prestadas, fundamentais para a precificação e subscrição do risco. O
        adiantamento do prêmio do sistema anti furto e das mensalidades de monitoramento e de seguro não vincula a
        presente proposta, sendo facultado às Seguradoras, dentro do prazo de 15 (quinze) dias, recusá-la ou aceitá-la.
        Em caso de recusa, o prêmio pago, a título de adiantamento, será devolvido, através de crébito em conta corrente
        do Proponente, a ser oportunamente fornecida.</p>

    <p>Na ocorrência de sinistro, o Segurado que estiver em mora no momento da ocorrência, ficará sujeito às penalidades
        impostas pelas Condições Gerais. <b>O Segurado declara estar ciente que o inadimplemento de qualquer parcela por
            mais de 5 (cinco) dias do seu vencimento implica no cancelamento da apólice</b>, sendo facultado a
        Seguradora o
        exercício de referida prerrogativa, que quando exercido será formalmente comunicado ao Segurado. <br>
        É facultado ao Segurado o direito de arrependimento no prazo de 07 (sete) dias corridos, contados da contratação
        do seguro, de acordo com o Código de Defesa do Consumidor, devendo manifestá-lo através do telefone (11)
        27701601 ou por email para : <a href="">sac@seguroautopratico.com.br</a>.<br>
        As condições contratuais/regulamento deste produto protocolizadas pelas Sociedades Seguradoras Parceiras junto à
        SUSEP poderão ser consultadas no endereço eletrônico <a href=""><b>www.susep.gov.br</b></a>, de acordo com o
        número de processo
        constante da apólice/proposta. O registro desses planos na Susep, não implica por parte da Autarquia, incentivo
        ou recomendação à sua comercialização. - O Proponente poderá consultar a situação cadastral da Seguradora, do
        Produto contratado e do seu corretor, no site da Susep (<a href="">www.susep.gov.br</a>), por meio do número de
        seu registro na
        Susep, nome completo, CNPJ ou CPF. “Em atendimento à Lei 12.741/12 informamos que incidem as alíquotas de 0,65%
        de PIS/Pasep e de 4% de COFINS sobre os prêmios de seguros. “A FRAUDE CONTRA SEGUROS É CRIME DENUNCIE, (21)
        2253-1177 OU 181 - <a href="">www.fenaseg.org.br</a>.”</p>

    <p>O Segurado está ciente e autoriza a inclusão de todos os dados e informações relacionadas ao presente seguro,
        assim como de todos os eventuais sinistros e ocorrências referentes ao mesmo, em banco de dados, aos quais a
        seguradora poderá recorrer para análise de riscos atuais e futuros e na liquidação de processos de
        sinistros.</p>

    <h4>Canais de Comunicação</h4>

    <ul>
        <li>
            Aviso de Sinistro em Caso de Roubo e Furto:
        </li>
        <p> Em caso de Roubo ou Furto do veículo o segurado deve comunicar o sinistro imediatamente para a central de
            rastreamento no Fone 0800 77 25099 e/ou no fone (11) 27701601; que irá tentar localizar o veículo. Caso o
            veículo não seja recuperado, o Segurado ou um de seus representantes deverá encaminhar para a Seguradora,
            conforme informações constantes na respectiva apólice, os documentos relacionados nas Condições Gerais do
            produto.</p>
<!--        <li>-->
<!--            Aviso de Sinistro em casos de sinistro com Terceiros (para os Proponentes que optarem por esta cobertura):-->
<!--        </li>-->
<!--        <p> Opção online NOBRE SEGUROS: Acesse o site <a href="">www.nobre.com.br</a> e clique na opção “COMUNICAR UM-->
<!--            SINISTRO” e-->
<!--            proceda conforme instruções detalhadas nas telas.<br>-->
<!--            - Atendimento Sinistro NOBRE: Ligue para 4007-1115 capitais, regiões metropolitanas e grandes cidades ou-->
<!--            0800 16 3020 demais localidades de segunda à sexta-feira das 8:30h às 20:00h e aos sábados das 8:30h às-->
<!--            17:30h.<br>-->
<!--            - Central de Atendimento : Tel: 55 (11) 5069-1177 E-mail: <a href="">cacc@nobre.com.br</a></p>-->
<!---->
<!--        <li>-->
            Aviso de Sinistro em casos de sinistro de Perda Total por colisão (para os Proponentes que optarem por esta
            cobertura):
        </li>
        <p> Em caso de PT por colisão do veículo do segurado, este deve comunicar o sinistro para a QBE Seguradora, e
            enviar através da Caixa Postal nº (29217) CEP: 04561-970 – São Paulo/SP, os documentos relacionados nas
            Condições Gerais, disponíveis através do site <a href="">www.qbe.com.br</a></p>
        <li>
            Assistência Veicular:
        </li>
        <p> Caso a sua apólice tenha sido emitida pela QBE Seguros, as Informações sobre exclusões, limites de serviços
            e intervenções dos planos de Assistência Veicular contratado, conforme expresso na Apólice de Seguro, podem
            ser consultadas a qualquer tempo nas Condições Gerais disponíveis no site <a href="">www.qbe.com.br</a>.
            Prestador
            Assistência Auto: USS Soluções Gerenciadas LTDA - CNPJ: 01.979.936/0001-79 - Central de Atendimento: 0800
            723 2886 </p>
    </ul>

    <p><b>Observação</b> As coberturas contratadas tanto na apólice de PT por colisão, como de RCF-V (ambas
        opcionalmente
        contratadas de forma acessória ao Seguro contra roubo e furto), como na apólice mestre de Seguro contra roubo e
        furto (Seguro AUTOPRATICO) não compreendem e tampouco se confundem, com a cobertura total, bem como a
        indenização integral do veículo, cujo conceito faz parte do glossário constante das Condições Gerais. As
        coberturas de Danos Corporais e Danos Materiais cujos conceitos distintos fazem parte do glossário constante das
        inclusas Condições Gerais, não compreendem e tampouco se confundem com a cobertura de Danos Morais.</p>

    <p><b>Declaração</b> Declaro estar ciente e de acordo, sob pena de perda de direito de cobertura, conforme
        previsto no artigo 766 do Código Civil, que: Todas as informações aqui prestadas são verdadeiras e completas,
        fazendo parte da proposta de seguro. O veículo objeto do seguro não será conduzido por pessoa inabilitada.
        As garantias previstas no contrato só serão devidas se o veículo estiver devidamente regularizado e legalizado
        junto às autoridades competentes. O Segurado esá ciente e concorda que é o responsável pela autenticidade do
        veiculo e de sua documentação e ainda que o corretor indicado na proposta é seu representante legal neste
        contrato. <br>
        O Segurado obriga−se a comunicar imediatamente a SKYPROTECTION, por escrito, para o email:
        <a href="">sac@seguroautopratico.com.br</a>, qualquer alteração nas condições estabelecidas no contrato de
        seguro assim como no
        meu cadastro ou nos meus dados de contato como fone e email.</p>
</div>

<table class="table-data">
    <tr>
        <td class="td-title-local">Local:</td>
        <td class="td-content-local"></td>
        <td class="td-title-data">Data Emissão:</td>
        <td class="td-content-data"></td>
    </tr>
</table>
<div class="espaco">

</div>
<div class="assinatura-segurado">
    <div><?=nomeCase($proposta['cotacao']['segurado']['clinomerazao'])?></div>
</div>


<div class="assinatura-div">


<table class="table-assinatura">


    <tr>
        <td>
            <table class="table-assinatura-table">
                <tr>
                    <td>Estipulante</td>
                </tr>
                <tr>
                    <td><img class="logo" src="<?= base_url() ?>assets/img/logo-skyprotection.png" alt=""></td>
                </tr>
                <tr>
                    <td>SKYPROTECTION TEC. INF. VEICULAR<br>
                        CNPJ 17.241.995/0001-85
                    </td>
                </tr>
                <tr>
                    <td><img class="assinatura" src="<?= base_url() ?>assets/img/assinatura-sky.png" alt=""></td>

                </tr>
                <tr>
                    <td>Luciano Ladeira <br>
                        CEO
                    </td>
                </tr>
            </table>
        </td>

        <td>
            <table class="table-assinatura-table">
                <tr>
                    <td>Garantia: Roubo ou Furto e PT Colisão</td>
                </tr>

                <tr>
                    <td><img class="logo" src="<?= base_url() ?>assets/img/logo-qbe.png" alt=""></td>
                </tr>

                <tr>
                    <td>QBE Brasil Seguros S/A
                        <br>
                        CNPJ: 96.348.677/0001-94
                    </td>
                </tr>

                <tr>
                    <td><img class="assinatura" src="<?= base_url() ?>assets/img/assinatura-qbe.png" alt=""></td>

                </tr>
                <tr>
                    <td>Raphael Swierczynsk <br>
                        CEO
                    </td>
                </tr>
            </table>
        </td>

        <td>
            <table class="table-assinatura-table">
                <tr>
                    <td>Garantia: Roubo ou Furto </td>
                </tr>

                <tr>
                    <td><img class="logo" src="<?= base_url() ?>assets/img/logo-usebens.png" alt=""></td>
                </tr>

                <tr>
                    <td>QBE Brasil Seguros S/A
                        <br>
                        CNPJ: 96.348.677/0001-94
                    </td>
                </tr>

                <tr>
                    <td><img class="assinatura" src="<?= base_url() ?>assets/img/assinatura-usebens.png" alt=""></td>
                </tr>
                <tr>
                    <td>USEBENS SEGUROS S/A</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>
</body>
</html>