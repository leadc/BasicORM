<?php
    namespace BasicORM\MTOP;
    use \simplexml_load_string;


    class MTOP{

        /**
         * Consulta los datos de un vehículo en MTOP y deuvelve un mensaje de error en caso de haberlo o el vehículo en un objeto tipo SimpleXMLElement
         */
        public static function ConsultarDatosVehiculo($matricula){
            // Consulta al mtop por la matrícula
            $xml = self::ConsultarMatricula($matricula);
            // Verifico si devolvió algún mensaje de error
            $mensajeError = self::ObtenerError($xml);
            //echo ($mensajeError);
            if($mensajeError  != ''){
                // Devolvió un mensaje de error
                return $mensajeError->__toString();
            }else{
                // No devolvió nungún mensaje de error
                // Obtengo los datos del vehículo
                $vehiculo = self::ObtenerDatosVehiculo($xml);
                return $vehiculo;
            }
        }

        /**
         * Consulta los datos de una matrícula en MTOP y devulve los resultados
         * Devuelve un objeto SimpleXMLElement con la respuesta del WS
         * Cuando la respuesta tiene error existe al menos un $xml->xpath('//envoy:MensajeError')
         * Cuando no hay error los datos del vehículo pueden obtenerse como $xml->xpath('//envoy:DatosVehiculo:PaisISO') por ejemplo
         */
        public static function ConsultarMatricula($matricula){
            $xml_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="WebService">'.
                            '<soapenv:Header/>'.
                            '<soapenv:Body>'.
                            '<web:WSVehiculosITV.PORMATRICULA>'.
                                '<web:Paisiso>UY</web:Paisiso>'.
                                '<web:Matricula>'.$matricula.'</web:Matricula>'.
                            '</web:WSVehiculosITV.PORMATRICULA>'.
                            '</soapenv:Body>'.
                        '</soapenv:Envelope>';

            $response = self::sendXmlOverPost('https://wsdnt.mtop.gub.uy/WSITV-PROD/aWSVehiculosITV.aspx?wsdl',$xml_request);
            $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response[0]);
            $xml = simplexml_load_string($clean_xml);
            $xml->registerXPathNamespace('MTOP', 'WebService');
            return $xml;
        }

        /**
         * Devuelve el mensaje de error de la respuesta del WS de MTOP
         * Devuelve vacío en caso de no haber error
         */
        public static function ObtenerError($respuesta_mtop){
            $errores = $respuesta_mtop->xpath("//MTOP:MensajesErrorTL.MensajesErrorTLItem");
            if(count($errores) > 0){
                return $errores[0]->MensajeError;
            }
            return '';
        }

        /**
         * Obtiene los datos de un vehículo desde una respuesta del WS de MTOP
         * Devuelve un objeto SimpleXMLElement con los datos del vehículo o false en caso de error
         */
        public static function ObtenerDatosVehiculo($respuesta_mtop){
            $vehiculos = $respuesta_mtop->xpath("//MTOP:Datovehiculo");
            if(count($vehiculos) > 0){
                return $vehiculos[0];
            }
            return false;
        }
    
        /**
         * Sends an XML Post
         */
        private static function sendXmlOverPost($url, $xml) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
        
            // For xml, change the content-type.
            curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned
            curl_setopt ($ch, CURLOPT_CAINFO, "C:/php/ext/cacert.pem");
            // Send to remote and return data to caller.
            $result = curl_exec($ch);
            $error = curl_error($ch);
            $errorNro = curl_errno($ch);
            curl_close($ch);
            return [$result,$error,$errorNro];
        }

    }

?>