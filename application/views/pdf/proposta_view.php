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

<table class="table-bordered pdf-table">

    <tbody class="">
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

    <tr class="pdf-table-tr-title">
        <td><h3>DADOS DA PROPOSTA</h3></td>
    </tr>
    </tbody>
</table>
<div class="container">


</div>
</body>
</html>