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
