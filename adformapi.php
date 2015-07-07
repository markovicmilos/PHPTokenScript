<?php

class AdformAPI {

    private $baseUrl = 'https://api.adform.com/Services';

    private $loginUrl = '/Security/Login';

    private $getDataExportUrl = '/DataExport/DataExportResult?DataExportName=ApiHyperoptic';

    public function login($username, $password) {
        $url = $this->baseUrl . $this->loginUrl;
        $params = json_encode(array('UserName' => $username, 'Password' => $password));
        $response = $this->_makePOSTRequest($url, $params);
        $response = json_decode($response, true);

        if (empty($response['Ticket'])) {
            throw new \Exception('Invalid response');
        }

        var_dump($response);
        return $response['Ticket'];
    }

    public function getExportData($ticket) {
        $url = $this->baseUrl . $this->getDataExportUrl;
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Ticket: '. $ticket,
        ));

        $output = curl_exec($ch);
        var_dump($output);
        return $output;
    }

    private function _makePOSTRequest($url, $json_data) {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($json_data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}

$api = new AdformAPI();
$ticket = $api->login('api-hyperoptic', 'q2bUF.6.M');
$exportData = $api->getExportData($ticket);