<?php
class O2TI_Moip_Model_Api {
	const TOKEN_TEST = "3UNGHOJCLVXZVOYF85JGILKALZSROU2O";
	const KEY_TEST = "VX2MOP4AEXFQYHBYIWT0GINNVXFZO9TJCKJ6AWDR";
	const TOKEN_PROD = "FEE5P78NA6RZAHBNH3GLMWZFWRE7IU3D";
	const KEY_PROD = "Y8DIATTADUNVOSXKDN0JVDAQ1KU7UPJHEGPM7SBA";
    private $ambiente = null;
    private $conta_moip = null;
    public function getContaMoip() {
        return $this->conta_moip;
    }
    public function setContaMoip($conta_moip) {
        $this->conta_moip = $conta_moip;
    }
    public function getAmbiente() {
        return $this->ambiente;
    }
    public function setAmbiente($ambiente) {
        $this->ambiente = $ambiente;
    }
public function generateformapg($data, $pgto) {
$formapg = $pgto['forma_pagamento'];
return $formapg;
}
public function generateforma($data, $pgto) {
if ($pgto['forma_pagamento'] == "DebitoBancario") {
$formapgto .= "\"Forma\": \"DebitoBancario\",\"Instituicao\":";
$formapgto .= "\"".$pgto['debito_instituicao']."\"";
            }
            $standard = Mage::getModel('moip/standard');
if ($pgto['forma_pagamento'] == "BoletoBancario") {
$formapgto .= "\"Forma\": \"BoletoBancario\"";
            }	
		if ($pgto['credito_parcelamento'] <> "") {
                    $pgto['credito_parcelamento'] = explode("|", $pgto['credito_parcelamento']);
                    $parcelamento = "\"2\"";                  
                }
if($pgto['tipoderecebimento'] =="0")
{
		$tipoderecebimento = "Parcelado";
}
else
{
	 $tipoderecebimento = "AVista";	
}                
  if ($pgto['parcelamento'] == "0")
  {
  	$numeropar = "1";
  	}
  else{
  	$numeropar = $pgto['credito_parcelamento'][0];
  	}        
  if ($pgto['forma_pagamento'] == "CartaoCredito") {
$formapgto .= "\"Forma\": \"".$pgto['forma_pagamento']."\",
        \"Instituicao\": \"".$pgto['credito_instituicao']."\",
        \"Parcelas\": \"".$numeropar."\",
        \"Recebimento\": \"".$tipoderecebimento."\",
        \"CartaoCredito\": {
            \"Numero\": \"".$pgto['credito_numero']."\",
            \"Expiracao\": \"".$pgto['credito_expiracao_mes'] . '/' . $pgto['credito_expiracao_ano']."\",
            \"CodigoSeguranca\": \"".$pgto['credito_codigo_seguranca']."\",
            \"Portador\": {
                \"Nome\": \"".$pgto['credito_portador_nome']."\",
                \"DataNascimento\": \"".$pgto['credito_portador_nascimento']."\",
                \"Telefone\": \"".$pgto['credito_portador_telefone']."\",
                \"Identidade\": \"".$pgto['credito_portador_cpf']."\"
            }}";
}
return $formapgto;
}
    public function generateXML($data, $pgto) {
	$standard = Mage::getSingleton('moip/standard');
    $parcelamento = $standard->getInfoParcelamento();
	if ($parcelamento['ate1'] <= 12)
	{
		$parcelamento1 = "<Parcelamento><MinimoParcelas>1</MinimoParcelas><MaximoParcelas>".$parcelamento['ate1']."</MaximoParcelas><Juros>".$parcelamento['juros1']."</Juros></Parcelamento>";
	$parcldoze = $parcelamento['ate1']+1;
while ($parcldoze <= "12")
	{
	$parcelamento2 = "<Parcelamento><MinimoParcelas>".$parcelamento['ate1']."</MinimoParcelas><MaximoParcelas>".$parcldoze."</MaximoParcelas><Juros>1.99</Juros></Parcelamento>";
		$parcldoze++;
	}
}
else {$parcelamento1 = "<Parcelamento><MinimoParcelas>1</MinimoParcelas><MaximoParcelas>12</MaximoParcelas><Juros>1.99</Juros></Parcelamento>";}
if($parcelamento['de2'] != ""){
$valorcompra = $data['valor'];
$descont = $parcelamento['de2']/100;
$valorcompra = $valorcompra - ($valorcompra*$descont);
}
if ($pgto['forma_pagamento'] == "BoletoBancario") {
                if($parcelamento['de2'] != ""){
$valorcompra = $data['valor'];
$descont = $parcelamento['de2']/100;
$valorcompra = $valorcompra - ($valorcompra*$descont);
}
else
{
$valorcompra = $data['valor'];
}
            }
else
{
$valorcompra = $data['valor'];
}


if($pgto['comissionamento']!= "0")
{
	if($pgto['comissionamento']!= "0")
{
$comissionamentotaxa = "<PagadorTaxa>
<LoginMoIP>".$pgto['logincomissionamento']."</LoginMoIP>
</PagadorTaxa>";
}
$comissionamento = "<Comissoes>
<Comissionamento>
<Razao>Pagamento do pedido #".$data['id_transacao']." a ".$pgto['apelido']."</Razao>
<Comissionado>
<LoginMoIP>".$pgto['logincomissionamento']."</LoginMoIP>
</Comissionado>
<ValorPercentual>".$pgto['porc_comissionamento'].".0</ValorPercentual>
".$comissionamentotaxa."
</Comissionamento>
</Comissoes>";
}
/*$bla  = date('Y-m-d h:i:s');*/
$xml = "<EnviarInstrucao>
<InstrucaoUnica TipoValidacao=\"Transparente\">
<Razao>Pagamento do pedido #".$data['id_transacao']." a ".$pgto['apelido']."</Razao>
<Recebedor>
<LoginMoIP>".$pgto['conta_moip']."</LoginMoIP>
<Apelido>".$pgto['apelido']."</Apelido>
</Recebedor>
".$comissionamento."
<Valores>
<Valor Moeda=\"BRL\">".$valorcompra."</Valor>
</Valores>
<IdProprio>".$pgto['conta_moip']."".$data['id_transacao']."</IdProprio>
<Pagador>
<Nome>".$data['pagador_nome']."</Nome>
<Email>".$data['pagador_email']."</Email>
<IdPagador>".$data['pagador_email']."</IdPagador>
<EnderecoCobranca>
<Logradouro>".$data['pagador_logradouro']."</Logradouro>
<Numero>0".$data['pagador_complemento']."</Numero>
<Complemento>".$data['pagador_complemento']."</Complemento>
<Bairro>-".$data['pagador_bairro']."</Bairro>
<Cidade>".$data['pagador_cidade']."</Cidade>
<Estado>".$data['pagador_estado']."</Estado>
<Pais>BRA</Pais>
<CEP>".$data['pagador_cep']."</CEP>
<TelefoneFixo>".$data['pagador_telefone']."</TelefoneFixo>
</EnderecoCobranca>
</Pagador>
<Parcelamentos>".$parcelamento2."".$parcelamento1."</Parcelamentos>
<DataVencimento>2013-01-01T02:33:48.703-02:00</DataVencimento>
<Boleto>
       <DiasExpiracao Tipo=\"Corridos\">1</DiasExpiracao>
 </Boleto>
 </InstrucaoUnica>
 </EnviarInstrucao>
";
return $xml;
    }
    public function hasPagamentoDireto() {
        if ($this->getAmbiente() == "teste") {
    		$url = "https://www.moip.com.br/ws/alpha/ChecarPagamentoDireto/" . $this->conta_moip;
			$header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_TEST . ":" . O2TI_Moip_Model_Api::KEY_TEST);
		}
        else {
            $url = "https://www.moip.com.br/ws/alpha/ChecarPagamentoDireto/" . $this->conta_moip;
			$header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_PROD . ":" . O2TI_Moip_Model_Api::KEY_PROD);
		}
        $httpClient = new Zend_Http_Client($url);
        $httpClient->setHeaders($header);
        $responseMoIP = $httpClient->request('GET');
        $res = simplexml_load_string($responseMoIP->getBody());
        return $res;
    }

    public function getParcelamento($valor) {
        $standard = Mage::getSingleton('moip/standard');
        $parcelamento = $standard->getInfoParcelamento();
        $result = array();
        if ($parcelamento['ate1'] > 0) {
            $result1 = $this->getRequestParcelamento($valor, $parcelamento['juros1'], $parcelamento['ate1']);
            foreach ($result1 as $k => $v) {
                if ($k >= $parcelamento['de1'])
                    $result[$k] = $v;
            }
        }
        if ($parcelamento['ate1'] < 13) {
            $result1 = $this->getRequestParcelamento($valor, "1.99", "12");
            foreach ($result1 as $k => $v) {
                if ($k > $parcelamento['ate1'])
                    $result[$k] = $v;
            }
        }
        return $result;
    }
    public function getRequestParcelamento($valor, $juros, $parcelas) {
    
    $standard = Mage::getModel('moip/standard');
    $nummaxparcelamax = $standard->getConfigData('nummaxparcelamax');
    $valorminimo = $standard->getConfigData('valorminimoparcela');
				if($valorminimo != ""){    
    $valorminimopg = (int) ($valor/$valorminimo);
 }
else {
	  $valorminimopg = (int) ($valor/5);
}		    	
    	if($nummaxparcelamax != "")
	        	{
					    	if($valorminimopg <= $nummaxparcelamax)
    						{
	        			$novaparcelas = $valorminimopg;
	        	}
	      			else {
	        		$novaparcelas = $nummaxparcelamax;	        		
	        		}
	      			}
	   else {
	   	if($valorminimopg <= "12")
	   	{
	      $novaparcelas = $valorminimopg;
    	}	
    	else{
	      		$novaparcelas = "12";
	      		}
	    }    		
		 if ($this->getAmbiente() == "teste") {
	    		$url = "https://desenvolvedor.moip.com.br/sandbox/ws/alpha/ChecarValoresParcelamento/elisei/" . $novaparcelas . "/" . $juros . "/" . $valor;
				  $header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_TEST . ":" . O2TI_Moip_Model_Api::KEY_TEST);
			}
	  else {	        
	      $url = "https://www.moip.com.br/ws/alpha/ChecarValoresParcelamento/elisei/" . $novaparcelas . "/" . $juros . "/" . $valor;
			    $header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_PROD . ":" . O2TI_Moip_Model_Api::KEY_PROD);
			}
			
        $httpClient = new Zend_Http_Client($url);
        $httpClient->setHeaders($header);
        $responseMoIP = $httpClient->request('GET');

        $res = simplexml_load_string($responseMoIP->getBody());

        $result = array();
        $i = 1;
        foreach ($res as $resposta) {
            foreach ($resposta as $data) {
                if ($data->getName() == "ValorDaParcela") {
                    $result[$i]['total'] = $data->attributes()->Total;
                    $result[$i]['valor'] = $data->attributes()->Valor;
                    $i++;
                }
            }
        }

        return $result;
    }
    /**
     * Solicita token pelo MoIP depois do XML informado
     * @access public
     * @param String $xml
     * @return Array
     */
    public function getToken($xml) {
	
		if ($this->getAmbiente() == "teste") { 
	    		$url = "https://desenvolvedor.moip.com.br/sandbox/ws/alpha/EnviarInstrucao/Unica";
				$header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_TEST . ":" . O2TI_Moip_Model_Api::KEY_TEST);
			}
	        else {
	            $url = "https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica";
				$header = "Authorization: Basic " . base64_encode(O2TI_Moip_Model_Api::TOKEN_PROD . ":" . O2TI_Moip_Model_Api::KEY_PROD);
			}
        $httpClient = new Zend_Http_Client($url);
        $httpClient->setHeaders($header);
        $httpClient->setRawData($xml);
        $responseMoIP = $httpClient->request('POST');
        $res = simplexml_load_string($responseMoIP->getBody());
        $pgdireto_status = "";
        $pgdireto_mensagem = "";
        $status = "";
        $moipToken = "";
        $pgdireto_codigoretorno = "";
		$erro = "";
        if ($res) {
			if($res->Resposta->Erro)
				$erro = $res->Resposta->Erro;
            foreach ($res->children() as $child) {
                foreach ($child as $c) {
                    if ($c->getName() == 'Token')
                        $moipToken = $c;

                    if ($c->getName() == "Status")
                        $status = $c;
					
                    foreach ($c as $pgdireto) {
                        if ($pgdireto->getName() == "Status")
                            $pgdireto_status = $pgdireto;

                        if ($pgdireto->getName() == "Mensagem")
                            $pgdireto_mensagem = $pgdireto;

                        if ($pgdireto->getName() == "CodigoRetorno")
                            $pgdireto_codigoretorno = $pgdireto;
                    }
                }
            }
        }

		$result = array();
		$result['erro'] = $erro;
        if ($status == "Sucesso") {      
            $result['status'] = $status;
            $result['token'] = $moipToken;
            $result['pgdireto_status'] = $pgdireto_status;
            $result['pgdireto_mensagem'] = $pgdireto_mensagem;
            $result['pgdireto_codigoretorno'] = $pgdireto_codigoretorno;
		
		}
        else {
			$result['status'] = $status;
            $result['token'] = "";
            $result['pgdireto_status'] = "";
            $result['pgdireto_mensagem'] = "";
            $result['pgdireto_codigoretorno'] = "";
		}
        return $result;
    }
    public function generateUrl($token) {
        if ($this->getAmbiente() == "teste")
            $url = $token.$pgto['forma_pagamento'];
        else
            $url = $token.$pgto['forma_pagamento'];
        return $url;
    }

  public function generatemeip($formapgto) {
       
            $meiopg2 = $formapgto;
      
        return $meiopg2 ;
    }

public function generatemeiopago($formapg) {
            $meiopgpg = $formapg;
        return $meiopgpg;
    }

}