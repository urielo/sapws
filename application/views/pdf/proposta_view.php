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
        <td class="pdf-table-td">
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
                                <td class="pdf-table-td-title"><b>PROPOSTA Nº:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['idproposta'] ?></td>
                                <td class="pdf-table-td-title"><b>PROPOSTA EMISSÃO:</b></td>
                                <td class="pdf-table-td-content"><?= date("d/m/Y H:i:s",
                                        strtotime($proposta['dtcreate'])) ?>
                                </td>
                                <td class="pdf-table-td-title"><b>VALIDADE:</b></td>
                                <td class="pdf-table-td-content"><?= date("d/m/Y H:i:s",
                                        strtotime($proposta['dtvalidade'])) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>TIPO DE VIGÊNCIA:</b></td>
                                <td class="pdf-table-td-content">ANUAL</td>
                                <td class="pdf-table-td-title"><b>REF. COTAÇÃO Nº:</b></td>
                                <td class="pdf-table-td-content"><?= $proposta['cotacao']['idcotacao'] ?></td>
                                <td class="pdf-table-td-title"><b>COTAÇÃO EMISSÃO:</b></td>
                                <td class="pdf-table-td-content"><?= date("d/m/Y H:i:s",
                                        strtotime($proposta['cotacao']['dtcreate'])) ?>
                                </td>
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
                                    <td class="pdf-table-td-content"><?= $proposta['cotacao']['segurado']['cliemissorrg'] ?>
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
                                    <td class="pdf-table-td-content"><?= $produto['produto']['idproduto'] . ' / ' . $produto['produto']['nomeproduto'] ?></td>
                                    <td class="pdf-table-td-title"><b>DESCRIÇÃO:</b></td>
                                    <td class="pdf-table-td-content"><?= $produto['produto']['descproduto'] ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="pdf-table-td-subtitle">
                        <td>COBERTURA</td>
                    </tr>
                    <tr>
                        <td class="pdf-table-td-content"><?= $produto['produto']['caractproduto'] ?></td>
                    </tr>

                    <tr class="pdf-table-td-subtitle">
                        <td>ELEGIBILIDADE</td>
                    </tr>

                    <tr>
                        <td class="pdf-table-td-content"><p><?= $produto['produto']['cobertura'] ?></p></td>
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

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>PREMIO:</b></td>
                                    <td class="pdf-table-td-content">R$ <?php
                                        $valor = aplicaComissao(
                                            ($produto['produto']['idproduto'] == 1 ?
                                                $produto['produto']['precos']['premioliquidoproduto'] + $proposta['cotacao']['veiculo']['fipe']['contigencia']['valor'] :
                                                $produto['produto']['precos']['premioliquidoproduto'])
                                            , $proposta['cotacao']['comissao']);

                                        $premio = $valor + $premio;
                                        echo real($valor) ?></td>
                                    <td class="pdf-table-td-title"><b>CUSTO DISP. ANTI-FURTO:</b></td>
                                    <td class="pdf-table-td-content">N/A</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="pdf-table-td-subtitle">
                        <td>
                            EXIGÊNCIAS
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>VISTORIA PRÉVIA?</b></td>
                                    <td class="pdf-table-td-content"><?= ($produto['produto']['ind_exige_vistoria'] ? 'Sim' : 'Não') ?></td>
                                    <td class="pdf-table-td-title"><b>DISPOSITIVO ANTI-FURTO?</b></td>
                                    <td class="pdf-table-td-content"><?= ($produto['produto']['ind_exige_rastreador'] ? 'Sim' : 'Não') ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <?php if (isset($produto['produto']['seguradoras'])): ?>
                        <tr class="pdf-table-td-subtitle">
                            <td>
                                SEGURADORA(S) CONTRATADA(S) PARA ESTE PRODUTO
                            </td>
                        </tr>

                        <!--                    INICIO LOOP-->
                        <?php foreach ($produto['produto']['seguradoras'] as $seguradora): ?>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td class="pdf-table-td-title"><b>SEGURADORA:</b></td>
                                            <td class="pdf-table-td-content"><?= $seguradora['seguradora']['segnome'] ?></td>
                                            <td class="pdf-table-td-title"><b>REGISTRO DO PRODUTO NA SUSEP:</b></td>
                                            <td class="pdf-table-td-content"><?= $seguradora['prdotudo_susep'] ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php $seguradoras[$seguradora['idseguradora']] = $seguradora['seguradora'] ?>
                        <?php endforeach; endif; ?>
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

    <!--    Inicio seguradoras -->

    <tr class="pdf-table-tr-title">
        <td><h4>SEGURADORAS RESPONSAVÉS PELAS APÓLICES</h4></td>
    </tr>

    <tr>
        <td class="pdf-table-td">
            <table class="pdf-table-td-table">

                <?php foreach ($seguradoras as $seg): ?>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="pdf-table-td-title"><b>SEGURADORA:</b></td>
                                    <td class="pdf-table-td-content"><?= $seg['segnome'] ?></td>
                                    <td class="pdf-table-td-title"><b>CNPJ:</b></td>
                                    <td class="pdf-table-td-content"><?= format('cpfcnpj', $seg['segcnpj']) ?></td>
                                    <td class="pdf-table-td-title"><b>SUSEP:</b></td>
                                    <td class="pdf-table-td-content"><?= $seg['segcodsusep'] ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="pdf-table-td-title"><b>FONE DE ACIONAMENTO EM CASO DE SINISTRO:</b></td>
                                <td class="pdf-table-td-content">0800 7725 099</td>

                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!--    FIm seguradoras -->

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

    </tbody>
</table>
<div class="container">


</div>
</body>
</html>