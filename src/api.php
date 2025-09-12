<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    require_once __DIR__ . '/environment.php';
    $params = 'limit='.intval($_POST['length']).'&page='.(intval($_POST['start'])/10);

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://fraser.stlouisfed.org/api/subject?format=json&'.$params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'X-API-Key: '.API_KEY,
        'accept: application/json'
        ),
    ));
    $info = curl_getinfo($curl);
    $response = curl_exec($curl);
    curl_close($curl);
    $Object = json_decode($response);
    $returnedData = array(
        "draw"=> intval($_POST['draw']??0),
        "recordsTotal"=> $Object->total,
        "length"=>$Object->limit,
        "data"=>[],
        "curl_info"=>print_r($info ,true)
    );

    foreach($Object->records as $record){

        if(isset($record->location->url)){
            $clickableUrl = $record->location->url;
        }else{
            $clickableUrl = "Missing Clickable url";
        }

        array_push($returnedData['data'],array(
            $record->topic??$record->geographic??$record->titleInfo??"Topic Missing",
            $record->recordInfo->recordIdentifier[0]??"Record ID Missing",
            $clickableUrl,
            $record->recordInfo->recordCreationDate??'Creation Date Missing',
            $record->location->apiUrl[0]??'API URL Missing',
            $record->recordInfo->recordType??"Type Missing"
        ));
    }
    $returnedData['recordsFiltered'] = $Object->total;
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($returnedData);
?>