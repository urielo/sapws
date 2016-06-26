<?php
include_once 'mpdf/mpdf.php';

function gerarpdfb64($html)
{

    $header = '<table width="800px" border="0" cellspacing="0px"><tr>
<th width= "200px"><img src="public/img/logo.png" width= "200px" style = "float:left;"></th>    
<th align="center" style ="font-size: 25px; font-family: sans-serif;  ">PROPOSTA</th></tr></table>	
</div><hr size="10" align="center" style="color: #000000; background-color: #000000; height: 1px;">';

    $footer = '
    <table width="100%" style="font-size: 8px;">
		<tr>
			<td style="font-size: 11px; border-bottom-width: 1px; border-bottom-color: black ;">SKYPROTECTION Tec. Informação Veic. Ltda</td>
		</tr>
		<tr>
			<td style="font-size: 11px;">CNPJ 17.241.995/0001-85 / SAC 11 27701601 (Atendimento emergencial 24hs F.0800 77 25 099).	</td>
		</tr>
	</table>';

    $mpdf = new mPDF("pt-BR", "A4");
    $mpdf->SetHTMLHeader($header);
    $mpdf->SetHTMLFooter($footer);


    $mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '', 10, // margin_left
        10, // margin right
        25, // margin top
        15, // margin bottom
        5, // margin header
        6); // margin footer

    $permisson = array('copy', 'print');
    $mpdf->SetProtection($permisson, '', '@SAPpdf2016');
    $mpdf->WriteHTML($html);
    $mpdf->Output('pdf/tmp.pdf', 'F');
    $response = chunk_split(base64_encode(file_get_contents('pdf/tmp.pdf')));
    unlink('pdf/tmp.pdf');
    return $response;
}

function gerarhtml($proposta, $cotacao, $segurado, $veiculo, $corretor, $parcela, $produto, $parceiro, $proprietario)
{


    $segurado['clicdsexo'] = 1 ? $segurado['clicdsexo'] = "Masculino" : $segurado['clicdsexo'] = "Feminino";


    $html1 = "<html>
            <head>
            <style>
            table {              
            }
            h3 {
            
            }
            
            td {
                font-size:12px;
                border: 0px solid black;
                
            }
            th {
               
               text-align: right;
            }
            h3 {
            font-style: oblique;
            font-size:16px;
            }
            div.arabic{
                font-size:10px;
                text-align: justify;
                letter-spacing: 0;
            }
            div.arabic2{
                padding-top: 150px;
                font-size:12px;
                text-align: center;
                letter-spacing: 0;
            }
            div.arabic3{
                padding-top: 100px;
                font-size:12px;
                text-align: center;
                letter-spacing: 0;
            }
           
            </style>
            </head>
            <body>
            <table  style=\"border: 0px solid black; border-collapse: collapse;\" width=\"100%\">
		
  

        <tr>
			<td style=\"border-bottom-width: 0px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>CORRETOR:</b></td>
						<td>{$corretor['corrnomerazao']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>SUSEP:</b></td>
						<td>{$corretor['corresusep']}</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>FONE:</b></td>
						<td>({$corretor['corrdddcel']}) " . format('fone', $corretor['corrnmcel']) . "</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>EMAIL:</b></td>
						<td>{$corretor['corremail']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>PARCEIRO:</b></td>
						<td>{$parceiro['nomerazao']}</td>
					</tr>
                    </table></td></tr>
					
				</table>
			</td>
		</tr>
        
        <br>
		<tr>
				<td style=\"border-bottom-width: 2px; border-bottom-color: black; text-align: right;\"><h3>DADOS DA PROPOSTA</h3></td>
		</tr>
        <tr>
			<td style=\"border-bottom-width: 1px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
                    <tr>
                        <td>
                            <table width=\"100%\">
                                <tr>
                                    <td style=\" width: 1px; white-space: nowrap;\"><b>PROPOSTA Nº:</b></td>
                                    <td>{$proposta['idproposta']}</td>
                                    <td style=\" width: 1px; white-space: nowrap;\"><b>PROPOSTA EMISSÃO:</b></td>
                                    <td>" . date("d/m/Y H:i:s", strtotime($proposta['dtcreate'])) . "</td>
                                    <td style=\" width: 1px; white-space: nowrap;\"><b>VALIDADE:</b></td>
                                    <td>" . date("d/m/Y H:i:s", strtotime($proposta['dtvalidade'])) . "</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width=\"100%\">
                            <tr>
                                <td style=\" width: 1px; white-space: nowrap;\"><b>TIPO DE VIGÊNCIA:</b></td>
                                <td>ANUAL</td>
                                <td style=\" width: 1px; white-space: nowrap;\"><b>REF. COTAÇÃO Nº:</b></td>
                                <td>{$cotacao['idcotacao']}</td>
                                <td style=\" width: 1px; white-space: nowrap;\"><b>COTAÇÃO EMISSÃO:</b></td>
                                <td>" . date("d/m/Y G:i:s", strtotime($cotacao['dtcreate'])) . "</td>
                            </tr>
                            </table>
                        </td>
                    </tr>
					
					
				</table>
			</td>
		</tr>
       
        <tr><td><br><td></tr>
		<tr>
				<td style=\"border-bottom-width: 2px; border-bottom-color: black; text-align: right;\"><h3>DADOS DO PROPONENTE</h3></td>
		</tr>
        <tr>
			<td style=\"border-bottom-width: 1px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>NOME:</b></td>
						<td>{$segurado['clinomerazao']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>CPF/CNPJ:</b></td>
						<td>" . format('cpfcnpj', $segurado['clicpfcnpj']) . "</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>DATA NASCIMENTO:</b></td>
						<td>" . date("d/m/Y", strtotime($segurado['clidtnasc'])) . "</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>ESTADO CIVIL:</b></td>
						<td>{$segurado['clicdestadocivil']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>SEXO:</b></td>
						<td>{$segurado['clicdsexo']}</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td  style=\" width: 1px; white-space: nowrap;\"><b>PROFISSAO/ATIVIDADE:</b></td>
						<td>{$segurado['clicdprofiramoatividade']}</td>
					</tr>
                    </table></td></tr>
                    <tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>ENDEREÇO:</b></td>
						<td>{$segurado['clinmend']}</td>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>CIDADE:</b></td>
						<td>{$segurado['clinmcidade']}</td>				
                        <td style=\" width: 1px; white-space: nowrap;\"><b>UF:</b></td>
						<td>{$segurado['clicduf']}</td>
						
					</tr>
                    </table></td></tr>
                    <tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>NUMERO:</b></td>
						<td width=\"50px\">{$segurado['clinumero']}</td>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>COMPLEMENTO:</b></td>
						<td>{$segurado['cliendcomplet']}</td>                            
						<td style=\" width: 1px; white-space: nowrap;\"><b>CEP:</b></td>
						<td>" . format('cep', $segurado['clicep']) . "</td>
					</tr>
                    </table></td></tr>
                    <tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>TELEFONE:</b></td>
						<td>({$segurado['clidddfone']})" . format('fone', $segurado['clinmfone']) . "</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>CELULAR:</b></td>
						<td>({$segurado['clidddcel']}) " . format('fone', $segurado['clinmcel']) . "</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>EMAIL:</b></td>
						<td>{$segurado['cliemail']}</td>
					</tr>
                    </table></td></tr>
					
				</table>
			</td>
		</tr>
        <tr><td><br><td></tr>
		<tr>
				<td style=\"border-bottom-width: 2px; border-bottom-color: black; text-align: right;\"><h3>PRODUTO</h3></td>
		</tr>
        <tr>
			<td style=\"border-bottom-width: 1px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
                        ";
    

    foreach ($produto as $k => $v):
        $produto[$k]['precoproduto']['indobrigrastreador'] ? $produto[$k]['precoproduto']['indobrigrastreador'] = "SIM" : $produto[$k]['precoproduto']['indobrigrastreador'] = "NÃO";
        $produto[$k]['produto']['indexigenciavistoria'] ? $produto[$k]['produto']['indexigenciavistoria'] = "SIM" : $produto[$k]['produto']['indexigenciavistoria'] = "NÃO";
        $html2 = $html2 . "
                
					<tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\"width: 1px; white-space: nowrap;\"><b>SEGURADORA:</b></td>
                            <td>{$produto[$k]['seguradora']['segnome']}</td>
                            <td style=\"width: 1px; white-space: nowrap;\"><b>CNPJ:</b></td>
                            <td>" . format('cpfcnpj', $produto[$k]['seguradora']['segcnpj']) . "</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>SUSEP:</b></td>
                            <td>{$produto[$k]['seguradora']['segcodsusep']}</td>
                        </tr>
                        </table></td>
                    </tr>
					
                    <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>PRODUTO:</b></td>
                            <td>{$produto[$k]['produto']['nomeproduto']}</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>CODIGO PRODUTO:</b></td>
                            <td>{$produto[$k]['produto']['idproduto']}</td>
                        </tr>
                        </table></td>
                    </tr>
                     <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>PROCESSO SUSEP PRODUTO:</b></td>
                            <td>{$produto[$k]['produto']['procsusepproduto']}</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>RAMO:</b></td>
                            <td>{$produto[$k]['produto']['codramoproduto']}</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>IDENIZAÇÃO FIPE:</b></td>
                            <td>{$produto[$k]['produto']['porcentindenizfipe']}%</td>
                        </tr>
                        </table></td>
                    </tr>
					
                    <!--<tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                           
                            <td style=\" width: 1px; white-space: nowrap;\"><b>PREMIO:</b></td>
                            <td> R$ " . number_format($produto[$k]['precoproduto']['premioliquidoproduto'], 2, ",", ".") . "</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>FONE ACIONAMENTO:</b></td>
                            <td></td>
                        </tr>
                        </table></td>
                    </tr> -->
					
                    <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>EXIGE VISTORIA?</b></td>
                            <td>{$produto[$k]['produto']['indexigenciavistoria']}</td>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>EXIGE INSTALAÇÃO RASTREADOR?</b></td>
                            <td>{$produto[$k]['precoproduto']['indobrigrastreador']}</td>
                        </tr>
                        </table></td>
                    </tr>
                    
					
                    <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap;\"><b>DESCRIÇÃO:</b></td>
                            <td>{$produto[$k]['produto']['descproduto']}</td>
                        </tr>
                        </table></td>
                    </tr>
                    
					
                    <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap; text-align: center;\"><b>CARACTERISTICA</b></td>

                        </tr>
                        <tr>
                            <td style=\"text-align: justify; font-size: 10px;\">{$produto[$k]['precoproduto']['caractproduto']}</td>
                        </tr>
                        </table></td>
                    </tr>
					
                    <tr>
						<td><table width=\"100%\" style=\"border: 0px solid black; border-collapse: collapse;\">
                        <tr>
                            <td style=\" width: 1px; white-space: nowrap; text-align: center ;\"><b>COBERTURA</b></td>                          
                        </tr>
                        <tr>
                            <td style=\"text-align: justify; font-size: 10px;\">{$produto[$k]['produto']['cobertura']}</td>
                        </tr>
                        </table></td>
                    </tr>
					
                   

                    	<tr><td><br><td></tr>
                    
				";



    endforeach;
    $veiculo['veicautozero'] ? $veiculo['veicautozero'] = "Novo" : $veiculo['veicautozero'] = "Usado";

    $html3 = "</table>
			</td>
		</tr>
        
        <tr><td><br><td></tr>
		<tr>
				<td style=\"border-bottom-width: 2px; border-bottom-color: black; text-align: right;\"><h3>DADOS DO VEICULO</h3></td>
		</tr>
        <tr>
			<td style=\"border-bottom-width: 1px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>VEICULO:</b></td>
						<td>{$veiculo['modelo']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>FIPE:</b></td>
						<td>{$veiculo['veiccodfipe']}</td>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>VALOR:</b></td>
                        <td> R$" . number_format($veiculo['lmi'], 2, ",", ".") . "</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>MARCA:</b></td>
						<td>{$veiculo['marca']}</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>ANO MODELO:</b></td>
						<td>{$veiculo['veicano']}</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>ANO FABRICAÇÃO:</b></td>
						<td>{$veiculo['veianofab']}</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>ZERO KM:</b></td>
						<td>{$veiculo['veicautozero']}</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>CHASSI:</b></td>
						<td>{$veiculo['veicchassi']}</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>PLACA:</b></td>
						<td>" . format("placa", $veiculo['veicplaca']) . "</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>RENAVAM:</b></td>
						<td>{$veiculo['veicrenavam']}</td>
					</tr>
                    </table></td></tr>
					<tr><td><table width=\"100%\"><tr>
                        <td  style=\" width: 1px; white-space: nowrap;\"><b>COMBUSTIVEL:</b></td>
						<td>{$veiculo['veictipocombus']}</td>
                        <td  style=\" width: 1px; white-space: nowrap;\"><b>UTILIZAÇÃO:</b></td>
						<td>{$veiculo['veiccdutilizaco']}</td>
                        <td  style=\" width: 1px; white-space: nowrap;\"><b>COR:</b></td>
						<td>{$veiculo['veicor']}</td>
					</tr>
                    </table></td></tr>
                                        
                    <tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>PROPRIETARIO:</b></td>
						<td>{$proprietario['clinomerazao']}</td>
						
					</tr>
                    </table></td></tr>
                                  					
				</table>
			</td>
		</tr>
                       <tr><td><br><td></tr>
<tr><td style=\"border-bottom-width: 2px; border-bottom-color: black; text-align: right;\">
                <h3>CONDIÇÕES DE PAGAMENTO</h3>
                </td></tr>
        <tr>
			<td style=\"border-bottom-width: 1px; border-bottom-color: black; border-collapse: collapse;\">
				<table width=\"100%\" style=\"border: 1px solid black; border-collapse: collapse;\">
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>PREMIO AVISTA:</b></td>
						<td> R$" . number_format($cotacao['premio'], 2, ',', '.') . "</td>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>PREMIO EM {$parcela['quantidade']}X:</b></td>
						<td> R$" . number_format($parcela['premio'], 2, ',', '.') . "</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>FORMA DE PAGAMENTO:</b></td>
						<td>{$parcela['formapagamento']}</td>
					</tr>
                    </table></td></tr>
                    
					<tr><td><table width=\"100%\"><tr>
                        <td style=\" width: 1px; white-space: nowrap;\"><b>PRIMEIRA PARCELA DE:</b></td>
						<td>R$ " . number_format($parcela['primeira'], 2, ',', '.') . "</td>
						<td style=\" width: 1px; white-space: nowrap;\" ><b>DEMAIS PARCELAS:</b></td>
						<td>R$ " . number_format($parcela['demais'], 2, ',', '.') . "</td>
						<td style=\" width: 1px; white-space: nowrap;\"><b>JUROS:</b></td>
						<td>{$parcela['juros']}%</td>
					</tr></table></td></tr>
      
					
				</table>
			</td>
		</tr>
        
	</table>
    

           <div class=\"arabic\"> <p style=\"text-align: justify; letter-spacing: 0; \" >   <h4 style=\"font-size:12px;\">COBERTURA/CONSIDERAÇÕES IMPORTANTES</h4>
            <b>COBERTURA:</b> No caso do produto “Seguro AUTOPRATICO” (Seguro contra roubo e furto) contratado com base num fator de ajuste escolhido, aplicado sobre o valor do veículo referência que constava na tabela FIPE na data de contratação do seguro, do site <a href=\" www.fipe.org.br\">www.fipe.org.br</a> . Este produto Seguro AUTOPRATICO é a união de uma apólice de Seguro contra Roubo e Furto a ser emitida por uma das Seguradoras parceiras que exigem o monitoramento continuo por sistema anti-furto (rastreador GSM/GPS) da empresa SKYPROTECTION Tec. Inf. Veic. Ltda. A SKYPROTECTION é empresa homologada a prestar esse monitoramento, a comercialilzar o combo (monitoramento mais seguro) e a cobrar este combo diretamente dos clientes finais, sempre considerando a comercialização por intermédio de um corretor de Seguros conforme norma do setor. As indenizações, caso ocorram serão sempre exclusivamente arcadas por parte da Seguradora que emitir a Apólice e que consta na presente proposta. Estão cobertos por este produto, os prejuízos, previstos nos termos de suas condições gerais das respectivas seguradoras, devidamente comprovados e respeitados os riscos excluídos, decorrentes de Roubo ou Furto Total, seguidos da não localização do veículo devidamente atestada pela SKYPROTECTION Tec. Inf. Veic. Ltda., empresa de rastreamento/monitoramento veicular, no período estipulado na apólice/certificado. Serão elegíveis à contratação do seguro apenas os veículos que, no momento da adesão, adquirirem sistema de rastreamento/monitoramento veicular SKYPROTECTION, sendo que o início da cobertura do seguro se dará após a devida instalação e ativação do sistema. 
A cobrança tanto do equipamento anti furto como do serviço de monitoramento continuo e recorrente por todo período de cobertura e necessário para a emissão das apólice de custo reduzida das respectivas seguradoras (conforme previsto nos registros de referidos seguros junto a SUSEP), assim como o próprio custo do seguro será unificada e de responsabilidade da SKYPROTECTION Tec. Inf. Veic. Ltda, que poderá parcelar a cobrança da forma acertada com o Segurado e que poderá diferir da forma de parcelamento e do valor do premio isolado apenas da parcela do Seguro que poderá constar das respectivas apólices.
<br><br>
<b>DECLARAÇÕES IMPORTANTES E OBSERVAÇÕES:</b> - As condições dos serviços e produtos aqui contratados assim como as Condições Gerais completas de seu Seguro encontram-se disponíveis para consulta nos respectivos sites das seguradoras definidas no campo especifico acima e também por facilidade replicadas no site <a href=\"www.seguroautopratico.com.br/contratos\">www.seguroautopratico.com.br/contratos</a>, motivo pelo qual informo ser desnecessário o envio das Condições Gerais impressas e que estou ciente de que, caso as necessite, poderei requisitá-las em sua Central de Atendimento ou descarrega-las no site/endereço acima. - O Segurado,  declara ainda ciência e concorda que tanto o contrato de prestação de serviços de monitoramento como a(s) Apólice(s) de Seguro será)ão) disponibilizada(s) por meio eletrônico, por email ou no(s) site(s) da(s) Seguradora(s), no prazo legal. - As informações acima foram fornecidas pelo Proponente (mesmo que não preenchidas de próprio punho) e são levadas em consideração pela Seguradora para o cálculo do prêmio de seguro para possível aceitação do risco. Em razão disso, o Proponente declara que todas as informações previstas na presente proposta são verdadeiras e foram prestadas de boa-fé, assumindo total responsabilidade pela sua exatidão, sob pena de prejudicar sua eventual indenização. - Antes da assinatura da presente proposta de seguro, o Proponente declara já ter tomado conhecimento prévio das particularidades dos serviços de monitoramento indissociáveis e necessários para a presente condição comercial assim como das Condições Gerais que regem os Seguros incluidos, especialmente das cláusulas restritivas e/ou limitativas de direitos, autorizando a Seguradora a emitir Apólice/Certificado em caso de aceitação do risco. - A aceitação do seguro está sujeita à análise do risco, dentro do prazo legal. - A presente proposta, juntamente com o contrato de prestação de serviço de monitoramento e as Condições Gerais, Apólice/Certificado de Seguro, são partes integrante do contrato de Seguro, sendo as informações ora prestadas, fundamentais para a precificação e subscrição do risco. O adiantamento do prêmio do sistema anti furto e das mensalidades de monitoramento e de seguro não vincula a presente proposta, sendo facultado às Seguradoras, dentro do prazo de 15 (quinze) dias, recusá-la ou aceitá-la. Em caso de recusa, o prêmio pago, a título de adiantamento, será devolvido, através de crébito em conta corrente do Proponente, a ser oportunamente fornecida.
Na ocorrência de sinistro, o Segurado que estiver em mora no momento da ocorrência, ficará sujeito às penalidades impostas pelas Condições Gerais. O Segurado declara estar ciente que o inadimplemento de qualquer parcela por mais de 5 (cinco) dias do seu vencimento implica no cancelamento da apólice, sendo facultado a Seguradora o exercício de referida prerrogativa, que quando exercido será formalmente comunicado ao Segurado. É facultado ao Segurado o direito de arrependimento no prazo de 07 (sete) dias corridos, contados da contratação do seguro, de acordo com o Código de Defesa do Consumidor, devendo manifestá-lo através do telefone (11) 27701601 ou por email para : <a href=\"mailto:sac2@seguroautopratico.com.br\">sac2@seguroautopratico.com.br</a>. - As condições contratuais/regulamento deste produto protocolizadas pelas Sociedades Seguradoras Parceiras junto à SUSEP poderão ser consultadas no endereço eletrônico www.susep.gov.br, de acordo com o número de processo constante da apólice/proposta. - O registro desses planos na Susep, não implica por parte da Autarquia, incentivo ou recomendação à sua comercialização. - O Proponente poderá consultar a situação cadastral da Seguradora, do Produto contratado e do seu corretor, no site da Susep (www.susep.gov.br), por meio do número de seu registro na Susep, nome completo, CNPJ ou CPF.
<br><br>
Todos os valores constantes nesta proposta são expressos em reais (R$). Qualquer importância oferecida pelo proponente junto com a proposta tem natureza de adiantamento, a ser devolvida no caso de não aceitação do risco. 
<br><br>
<b>Importante</b>. Canais de Comunicação:
- Aviso de Sinistro On-Line NOBRE SEGUROS: Acesse nosso site <a href=\"www.nobre.com.br\">www.nobre.com.br</a> e clique na opção “COMUNICAR UM SINISTRO” e proceda conforme instruções detalhadas nas telas.
- Atendimento Sinistro NOBRE: Ligue para 4007-1115 capitais, regiões metropolitanas e grandes cidades ou 0800 16 3020 demais localidades de segunda à sexta-feira das 8:30h às 20:00h e aos sábados das 8:30h às 17:30h.
- Central de Atendimento : Tel: 55 (11) 5069-1177 E-mail: <a href=\"mailto:cacc@nobre.com.br\">cacc@nobre.com.br</a>
<br><br>
<b>Observação</b> As coberturas contratadas na tanto na apólice de RCF-V (opcionalmente contratada de forma acessória ao Seguro contra roubo e furto), como na apólice mestre de Seguro contra roubo e furto (Seguro AUTOPRATICO) não compreendem e tampouco se confundem, com a cobertura total, bem como a indenização integral do veículo, cujo conceito faz parte do glossário constante das Condições Gerais. As coberturas de Danos Corporais e Danos Materiais cujos conceitos distintos fazem parte do glossário constante das inclusas Condições Gerais, não compreendem e tampouco se confundem com a cobertura de Danos Morais.
<br><br>
<b>Declaração</b> Declaro estar ciente e de acordo, sob pena de perda de direito de cobertura, conforme previsto no artigo 766 do Código Civil, que: Todas as informações aqui prestadas são verdadeiras e completas, fazendo parte da proposta de seguro. O veículo objeto do seguro não será conduzido por pessoa inabilitada. 
As garantias previstas no contrato só serão devidas se o veículo estiver devidamente regularizado e legalizado junto às autoridades competentes. O Segurado esá ciente e concorda que é o responsável pela autenticidade do veiculo e de sua documentação e ainda que o corretor indicado na proposta é seu representante legal neste contrato. 
O Segurado obriga−se a comunicar imediatamente a SKYPROTECTION, por escrito, para o email: <a href=\"mailto:sac2@seguroautopratico.com.br\">sac2@seguroautopratico.com.br</a>, qualquer alteração nas condições estabelecidas no contrato de seguro assim como no meu cadastro ou nos meus dados de contato como fone e email. 


            </p></div><p>
            <div class=\"arabic2\">
                                                    <table width=\"100%\">
                                                        <tr>
                                                            <td width=\"100%\" style=\" font-size:20px ; text-align: center; border-bottom-width: 1px ; border-color: black;\"></td>
                                                            <td width=\"10%\" style=\" cellpadding: 0px; font-size: 30px ; text-align: left; border-bottom-width: 0px ; border-color: black;\">,</td>
                                                            
                                                            <td  width=\"100%\" style=\" cellpadding: 0px; font-style:italic; font-size:30px ; text-align: center; border-bottom-width: 1px ; border-color: black;\">" . date('d / m / Y') . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td width=\"100%\" style=\" font-size:30px ; text-align: center;\">LOCAL</td>
                                                              <td width=\"10%\" style=\" cellpadding: 0px; font-size: 30px ; \"></td>
                                                            
                                                            <td  width=\"100%\" style=\" cellpadding: 0px; font-size:30px ; text-align: center; \">DATA</td>
                                                        </tr>
                                                       
                                                    </table>

            </p></div>
            
            <div class=\"arabic3\">
                                                    <table width=\"100%\">
                                                        <tr>
                                                            <td width=\"100%\" style=\" font-size:20px ; text-align: center; border-bottom-width: 1px ; border-color: black;\"></td>
                                                            <td width=\"10%\" style=\" cellpadding: 0px; font-size: 30px ; text-align: left; border-bottom-width: 0px ; border-color: black;\"></td>
                                                            
                                                            <td  width=\"100%\" style=\" cellpadding: 0px; font-style:italic; font-size:30px ; text-align: center; border-bottom-width: 1px ; border-color: black;\"></td>
                                                        </tr>
                                                        <tr>
                                                            <td width=\"100%\" style=\" font-size:30px ; text-align: center;\">" . strtoupper($corretor['corrnomerazao']) . "</td>
                                                              <td width=\"10%\" style=\" cellpadding: 0px; font-size: 30px ; \"></td>
                                                            
                                                            <td  width=\"100%\" style=\" cellpadding: 0px;  font-size:30px ; text-align: center; \">" . strtoupper($segurado['clinomerazao']) . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td width=\"100%\" style=\" font-size:20px ; font-style:italic; text-align: center;\">" . strtoupper('corretor') . "</td>
                                                              <td width=\"10%\" style=\" cellpadding: 0px; font-size: 30px ; \"></td>
                                                            
                                                            <td  width=\"100%\" style=\" cellpadding: 0px; font-style:italic; font-size:20px ; text-align: center; \">" . strtoupper('proponente') . "</td>
                                                        </tr>
                                                       
                                                    </table>

            </p></div>
            


    </body></html>
";

    $html = $html1 . $html2 . $html3;
    return $html;
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
