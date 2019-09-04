<?php

  ini_set('max_execution_time', 5);

  function web_services($metodo, $params) {

    $wsdl = 'https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService?WSDL';
    $options = array(
        'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
        'actor'=>'http://action.trm.services.generic.action.superfinanciera.nexura.sc.com.co/',
        'style'=>SOAP_RPC,
        'use'=>SOAP_ENCODED,
        'soap_version'=>SOAP_1_1, //version 1 obligatorio
        'cache_wsdl'=>WSDL_CACHE_NONE,
        'connection_timeout'=>5,
        'trace'=>true,
        'encoding'=>'UTF-8',
        'exceptions'=>false,
        'location' => 'https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService', //endpoint
        'typemap' =>
          [ //namespace
            "type_ns"  => "http://action.trm.services.generic.action.superfinanciera.nexura.sc.com.co/",
            "type_name" => "WebServiceTRMReference.TCRMServicesInterface",
            "to_xml"  => "some_funktion_name"
          ],
        'stream_context' =>stream_context_create([
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          ]
        ])
        //'proxy_port'     => 8080,
        //'local_cert' => '/www/cer_superfinanciera.cer'
    );
    $nroError = '';
    $msjError = '';
    $result = '';
    try {
      $soap = new SoapClient( $wsdl, $options );
      $data = $soap->$metodo( $params );
      return $data->return;
    }
    catch(SoapFault $e) {
      echo (string)$e->faultcode.'<br>';
      echo (string)$e->getMessage().'<br>';
    }
  }

  $r = web_services( 'queryTCRM', [ 'tcrmQueryAssociatedDate' => date( 'Y-m-d' ) ] );

  if ( $r->success == true ){
    header('Content-Type: application/json');
    echo json_encode($r);
}

?>
