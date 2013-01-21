<? php
/ **
 * MoIP - MoIP módulo de pagamento
 *
 * @ Título Magento -> módulo de pagamento personalizado para MoIP (Brasil)
 * @ Gateway de Pagamento categoria
 * @ Pacote O2TI_Moip
 * @ Autor O2ti solucoes web Ldta
 * @ Copyright Copyright (c) 2010 MoIP Pagamentos S / A
 * @ Licença Autorizado o USO POR ritmo indeterminado
 * /
classe  O2TI_Moip_StandardController  estende  Mage_Core_Controller_Front_Action  {
    protegidos  $ _order ;
    público  função  GetOrder ()  {
        se  ( $ this -> _order  ==  nulo )  {
        }
        retornar  $ this -> _order ;
    }
    público  função  getStandard ()  {
        voltar  Mago :: getSingleton ( 'MoIP / standard' );
    }
    protegido  função  _expireAjax ()  {
        se  ( ! Mago :: getSingleton ( 'checkout / sessão' ) -> GetQuote () -> hasItems ())  {
            $ This -> getResponse () -> setHeader ( 'HTTP/1.1' ,  '403 Sessão expirada ' );
            saída ;
        }
    }
    público  função  redirectAction ()  {
        $ Session  =  Mago :: getSingleton ( 'checkout / sessão' );
        Padrão $  =  $ this -> getStandard ();
        $ Campos  =  $ session -> getMoIPFields ();
        $ Campos [ 'id_transacao' ]  =  Mago :: getSingleton ( 'checkout / sessão' ) -> getLastRealOrderId ();
        PgtoArray $  =  $ session -> getPgtoArray ();
        $ Api  =  Mago :: getModel ( 'MoIP / api' );
        $ Api -> setAmbiente ( padrão $ -> getConfigData ( 'ambiente' ));
        $ Xml  =  $ api -> generateXML ( $ campos ,  $ pgtoArray );
        Mago :: registo ( 'xml' ,  $ xml );
							Formapgto $  =  $ api -> generateforma ( $ campos ,  $ pgtoArray );
							Mago :: registo ( 'formapgto' ,  $ formapgto );
							Formapg $  =  $ api -> generateformapg ( $ campos ,  $ pgtoArray );
							Mago :: registo ( 'formapg' ,  $ formapg );
        $ Token  =  $ api -> getToken ( $ xml );
        $ Session -> setMoipStandardQuoteId ( $ session -> getQuoteId ());
        Mago :: registo ( 'token' ,  $ token [ 'token' ]);
							Mago :: registo ( 'Erro' ,  $ token [ 'Erro' ]);
        Mago :: registo ( 'StatusPgdireto' ,  $ token [ 'pgdireto_status' ]);
        $ This -> loadLayout ();
	$ This -> GetLayout () -> getBlock ( 'root' ) -> setTemplate ( 'page/1column.phtml' );
	$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('moip/standard_redirect'));      
        $ This -> renderLayout ();
        $ Session -> unsQuoteId ();
    }
    / **
     * QUANDO UM Cliente Cancelar o Pagamento da MoIP
     * /
    público  função  cancelAction ()  {
        $ Session  =  Mago :: getSingleton ( 'checkout / sessão' );
        $ Session -> setQuoteId ( $ session -> getMoipStandardQuoteId ( verdadeiro ));

        se  ( $ session -> getLastRealOrderId ())  {
            $ Order  =  Mago :: getModel ( "vendas / encomendas" ) -> loadByIncrementId ( $ session -> getLastRealOrderId ());
            se  ( pedido de US $ -> getId ())  {
                $ Order -> cancel () -> save ();
            }
        }
        $ This -> _redirect ( 'checkout / carrinho' );
    }
    / **
     * QUANDO HÁ Retorno Para O Módulo. A information da Ordem Neste Momento los E Variáveis ​​via POST. No entanto, Guia Você Nao quer "processar" o Pedido ATÉ o obter uma Validação fazer IPN
     * /
    público  função  successAction ()  {
        Padrão $  =  $ this -> getStandard ();
        $ Order  =  Mago :: getModel ( "vendas / Ordem );
        $ Session  =  Mago :: getSingleton ( 'checkout / sessão' );
        se  ( ! $ this -> getRequest () -> isPost ())  {
            $ Session -> setQuoteId ( $ session -> getMoipStandardQuoteId ( verdadeiro ));
            / **
             * Definir uma citação Como inativos APOS uma Volta do Módulo
             * /
            Mago :: getSingleton ( 'checkout / sessão' ) -> GetQuote () -> setIsActive ( falso ) -> save ();
            / **
             * Enviar e-mail de Confirmação para o Cliente
             * /
            $ Order -> carga ( Mago :: getSingleton ( 'checkout / sessão' ) -> getLastOrderId ());
            se  ( pedido de US $ -> getId ())  {
            }
            / **
             * Faz o redirecionamento para à Tela de Compra efetuada
             * /
            $ This -> _redirect ( 'checkout / OnePage / sucesso' ,  matriz ( '_Secure'  =>  verdadeiro ));
        }  mais  {
            $ Data  =  $ this -> getRequest () -> getPost ();
            / **
             * Efetua uma mudança de status fazer
             * /
            $ Login  =  $ padrão -> getConfigData ( 'conta_moip' );
            $ Order -> loadByIncrementId ( ereg_replace ( $ login ,  "" ,  $ dados [ 'id_transacao' ]));
            / *
              const STATE_NEW = 'novo';
              const STATE_PROCESSING = "processamento";
              const STATE_COMPLETE = 'completo';
              const STATE_CLOSED = 'fechado';
              const STATE_CANCELED = 'cancelado';
              const STATE_HOLDED = 'holded';
             * /
 interruptor  ( $ dados [ 'status_pagamento' ])  {
            caso  1 :
               			se ( $ _SERVER [ 'SERVER_ADDR' ]  =  "208.82.206.66" ) {
                $state = Mage_Sales_Model_Order::STATE_PROCESSING;
                $ Status  =  "processamento" ;
                $ Comentário  =  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
/ / Iniciá Geração da fatura
				$ Fatura  =  pedido de US $ -> prepareInvoice ();							
				se  ( $ this -> getStandard () -> canCapture ())
					{
						$ Factura -> registo () -> captura ();
					}									
				Mago :: getModel ( 'core / resource_transaction' )
				-> addObject ( $ factura )
				-> addObject ( factura $ -> GetOrder ())
				-> save ();
				$ Factura -> sendEmail ();
				$ Factura -> setEmailSent ( verdadeiro );
				$ Factura -> salvar ();
/ / Encerra Geração da fatura! Salve o tricolor paulista!
			}
			mais  {
				 $ Estado  =  Mage_Sales_Model_Order :: STATE_CANCELED ;
                $ Status  =  'cancelado' ;
                $ Comentário  =  "Tentativa de Fraude não MoIP Retorno" ;
				$ Order -> cancel ();
				}
                quebrar ;
            caso  2 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_HOLDED ;
                $ Status  =  'holded' ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
                quebrar ;
            caso  3 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_HOLDED ;
                $ Status  =  'holded' ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
				$ Comentário  =.  "Reimprimir boleto https://www.moip.com.br/Boleto.do?id ="  . $ dados [ 'cod_moip' ];
                quebrar ;
            caso  4 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_PROCESSING ;
                $ Status  =  "processamento" ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
                quebrar ;
            caso  5 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_CANCELED ;
                $ Status  =  'cancelado' ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
				$ Comentário  =.  "Motivo:" . utf8_encode ( $ dados [ 'classificacao' ]);
		$ Order -> cancel ();
                quebrar ;
            caso  6 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_HOLDED ;
                $ Status  =  'holded' ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
                quebrar ;
            caso  7 :
                $ Estado  =  Mage_Sales_Model_Order :: STATE_PROCESSING ;
                $ Status  =  "processamento" ;
                $ Comentário  =.  $ this -> getStatusPagamentoMoip ( $ dados [ 'status_pagamento' ]);
				$ Comentário  =.  "ID MOIP"  . $ dados [ 'cod_moip' ];
                quebrar ;
            }
            $ Order -> SetState ( $ estado ,  $ status ,  $ comentário ,  notificado $  =  verdade ,  includeComment $  =  verdadeiro );
            $ Order -> salvar ();
            $ Order -> carga ( Mago :: getSingleton ( 'checkout / sessão' ) -> getLastOrderId ());
            se  ( pedido de US $ -> getId ())  {
            }
            
            Zend_Debug :: despejo ( "Pagamento atualizado" );
        }
    }
    privadas  função  getStatusPagamentoMoip ( $ param )  {
        $ Status  =  "" ;
        interruptor  ( $ param )  {
        caso  1 :
            $ Status  =  "Autorizado" ;
            quebrar ;
        caso  2 :
            $ Status  =  "Iniciado" ;
            quebrar ;
        caso  3 :
            $ Status  =  "Impresso Boleto" ;
            quebrar ;
        caso  4 :
            $ Status  =  "Concluído" ;
            quebrar ;
        caso  5 :
            $ Status  =  "Cancelado" ;
            quebrar ;
        caso  6 :
            $ Status  =  "Em Análise" ;
            quebrar ;
        caso  7 :
            $ Status  =  "Estornado" ;
            quebrar ;
        }
        voltar  $ status ;
    }




}
