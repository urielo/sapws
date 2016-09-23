<?php

function verifca_fields($datas, $corretor) {
    if ($corretor):
        if (!$datas['indPropVeic'] && !$datas['indCondutorVeic']):
            $verifyFields = 'cotacaoConPro';
        elseif (!$datas['indPropVeic']):
            $verifyFields = 'cotacaoPropri';
        elseif (!$datas['indCondutorVeic']):
            $verifyFields = 'cotacaoCond';
        else:
            $verifyFields = 'cotacaoSegurado';
        endif;
    else:
        if (!$datas['indPropVeic'] && !$datas['indCondutorVeic']):
            $verifyFields = 'cotacaoTodos';
        elseif (!$datas['indPropVeic']):
            $verifyFields = 'cotacaoCorrPropri';
        elseif (!$datas['indCondutorVeic']):
            $verifyFields = 'cotacaoCorrCond';
        else:
            $verifyFields = 'cotacaoCorreSegurado';
        endif;
    endif;
    return $verifyFields;
}

function correctDatas($datas) {
    $datasReady = $datas;
    if (count($datas) < 3):

        $datas = json_decode(key($datas));
        foreach ($datas as $k => $v):
            if (is_object($v)):
                $v = (array) $v;
                foreach ($v as $ko => $vo):
                    $datasReady[$k][$ko] = $vo;
                endforeach;
            else:
                $datasReady[$k] = $v;
            endif;
        endforeach;
    endif;

    return $datasReady;
}

function format($tipo, $string)
{
    if (empty($string) || strlen($string) < 1):
        return $string;
    else:
        switch ($tipo):
            case 'cpfcnpj':
                if (strlen($string) > 11):
                    $mask = "%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s";
                #91.805.050/0001-50
                #85.031.334/0001-85
                else:
                    $mask = "%s%s%s.%s%s%s.%s%s%s-%s%s";
                endif;
                break;
            case 'fone':
                if (strlen($string) <= 8):
                    $mask = "%s%s%s%s-%s%s%s%s";
                else:
                    $mask = "%s%s%s%s%s-%s%s%s%s";
                endif;
                break;
            case 'cep':
                $mask = "%s%s%s%s%s-%s%s%s";
                break;
            case 'placa':
                $string = strtoupper($string);
                $mask = "%s%s%s-%s%s%s%s";
                break;
        endswitch;

        return vsprintf($mask, str_split($string));
    endif;
}

function nomeCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"))
{
    /*
     * Exceptions in lower case are words you don't want converted
     * Exceptions all in upper case are any words you don't want converted to title case
     *   but should be converted to upper case, e.g.:
     *   king henry viii or king henry Viii should be King Henry VIII
     */
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    foreach ($delimiters as $dlnr => $delimiter) {
        $words = explode($delimiter, $string);
        $newwords = array();
        foreach ($words as $wordnr => $word) {
            if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtoupper($word, "UTF-8");
            } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtolower($word, "UTF-8");
            } elseif (!in_array($word, $exceptions)) {
                // convert to uppercase (non-utf8 only)
                $word = ucfirst($word);
            }
            array_push($newwords, $word);
        }
        $string = join($delimiter, $newwords);
    }//foreach
    return $string;
}

function jurosSimples($valor, $taxa, $parcelas) {
    $taxa = $taxa / 100;

    $m = $valor * (1 + $taxa * $parcelas);
    $valParcela = number_format($m / $parcelas, 2, ",", ".");

    return $valParcela;
}

function jurosComposto($valor, $taxa, $parcelas) {
    $taxa = $taxa / 100;
   return $valParcela = $valor * pow((1 + $taxa), $parcelas);

    return $juros = $valParcela - $valor;
    return $parcela = ($valor / $parcelas) + $juros ;
    $valParcela = number_format($valParcela / $parcelas, 2, ",", ".");

    return $valParcela;
}