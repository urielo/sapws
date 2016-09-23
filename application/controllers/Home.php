<?php

/**
 * Home { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('my_ajust');
//        $this->load->helper('pdfgerator');
        $this->load->helper('datas');
        $this->load->helper('message_error');
        $this->load->library('m_pdf');
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index()
    {
        $this->load->view('home/inc/header_view');
        $this->load->view('home/home_view');
        $this->load->view('home/inc/footer_view');
    }

    /**
     * @return object
     */
    public function pdf()
    {
        $proposta['proposta'] = $this->Model_proposta->with_cotacao([
            'with' => [
                ['relation' => 'segurado',
                    'with' => [
                        ['relation' => 'uf'],
                        ['relation' => 'rg_uf'],
                        ['relation' => 'profissao'],
                        ['relation' => 'ramoatividade'],
                        ['relation' => 'estadocivl'],
                    ]
                ],
                ['relation' => 'parceiro'],
                ['relation' => 'veiculo',
                    'with' => [
                        ['relation' => 'fipe',
                            'with' => [
                                ['relation' => 'valores'],
                                ['relation' => 'contigencia'],
                            ]
                        ],
                        ['relation' => 'combustivel'],
                        ['relation' => 'utilizacao'],
                        ['relation' => 'proprietario'],
                    ]

                ],
                ['relation' => 'produtos',
                    'with' =>
                        ['relation' => 'produto',
                            'with' => [
                                ['relation' => 'precos'],
                                ['relation' => 'seguradoras' ,
                                    'with'=> ['relation'=>'seguradora']
                                ],
                            ]
                        ],
                ],
                ['relation' => 'corretor'],
            ]

        ])->with_forma_pagamento()->get(398);
       
        $html = $this->load->view('pdf/proposta_view',$proposta,true);


        $this->m_pdf->pdf->SetHTMLHeader($this->load->view('pdf/header_view',$proposta,true));
        $this->m_pdf->pdf->SetHTMLFooter($this->load->view('pdf/footer_view',$proposta,true));
        $this->m_pdf->pdf->AddPage('', // L - landscape, P - portrait
            '', '', '', '', 10, // margin_left
            10, // margin right
            25, // margin top
            15, // margin bottom
            5, // margin header
            6); // margin footer

        $this->m_pdf->pdf->SetProtection(['copy', 'print'], '', '@SAPpdf#2770');
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output('pdfteste.pdf','I');
        
//        $b64encode = chunk_split(base64_encode($pdf));
//        header("Content-Type: application/pdf");
//        header("Content-Disposition: inline; filename=\"" . 'pdfteste.pdf' . "\";");
//
//        echo base64_decode($b64encode);

//        echo $this->load->view('pdf/header_view',$proposta,true);
    }

    /**
     * @return object
     */
    public function pdfweb()
    {
        $proposta['proposta'] = $this->Model_proposta->with_cotacao([
            'with' => [
                ['relation' => 'segurado',
                    'with' => [
                        ['relation' => 'uf'],
                        ['relation' => 'rg_uf'],
                        ['relation' => 'profissao'],
                        ['relation' => 'ramoatividade'],
                        ['relation' => 'estadocivl'],
                    ]
                ],
                ['relation' => 'parceiro'],
                ['relation' => 'veiculo',
                    'with' => [
                        ['relation' => 'fipe',
                            'with' => ['relation' => 'valores']
                        ],
                        ['relation' => 'combustivel'],
                        ['relation' => 'utilizacao'],
                    ]

                ],
                ['relation' => 'produtos',
                    'with' =>
                        ['relation' => 'produto',
                            'with' => [
                                ['relation' => 'precos'],
                                ['relation' => 'seguradoras'],
                            ]
                        ],
                ],
                ['relation' => 'corretor'],
            ]

        ])->get(382);
        $this->load->view('pdf/proposta_view',$proposta);
    }

    public function juros(){
        echo jurosComposto(1196,2.50,12);
    }
}

    
