<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    require_once __DIR__ . '/environment.php';


    /*
            Subjects are standardized terms to describe record topics in FRASER. They
        can be of the type topic, geographic, name or titleInfo. However, note that no
        subject record is a name record – instead, authors are assigned as name
        subjects. Note that when requesting subject metadata, the key
        corresponding to the name of the subject will be the type of subject (that is,
        either topic, geographic or titleInfo).
    */


    function returnSubjectRecordBasedOnType($record){
        $typeKeys = array('topic', 'geographic', 'titleInfo');
        foreach ($typeKeys as $key) {
            if (property_exists($record, $key)) {
                $type = $key;
                $label = is_array($record->{$key}) ? ($record->{$key}[0] ?? null) : $record->{$key};
                break;
            }
        }

        // recordIdentifier is always an array; take first for convenience
        $creationDate = $record->recordInfo->recordCreationDate ?? 'Creation Date Missing';
        $id = $record->recordInfo->recordIdentifier[0] ?? 'Record ID Missing';

        // urls are arrays; take first
        $htmlUrl = $record->location->url[0] ?? null;
        $apiUrl  = $record->location->apiUrl[0] ?? 'API URL Missing';

        return array(
            $label,    // e.g., "Accounting"
            $id,            
            $htmlUrl,
            $creationDate,            
            $apiUrl,
            $type     // "topic" | "geographic" | "titleInfo"
        );
    }



    switch($_GET['type']){

        case 'subjects':
            $params = 'limit='.intval($_POST['length']).'&page='.((intval($_POST['start'])/10)+1);
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
                //"curl_info"=>print_r($info ,true),
                "draw"=> intval($_POST['draw']??0),
                "recordsTotal"=> $Object->total,
                "length"=>$Object->limit,
                "data"=>[]
            );

            foreach($Object->records as $record){
                $displayRecord = returnSubjectRecordBasedOnType($record);
                array_push( $returnedData['data'],$displayRecord);
            }
            $returnedData['recordsFiltered'] = $Object->total;
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($returnedData);
            break;

        case 'themes':

            $params = 'limit='.intval($_POST['length']).'&page='.((intval($_POST['start'])/10)+1);
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fraser.stlouisfed.org/api/theme?format=json&'.$params,
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
                "originalPayload"=>$Object,
                "curl_info"=>print_r($info ,true)
            );
            foreach($Object->records as $record){

                if(isset($record->location->url[0])){
                    $clickableUrl = $record->location->url[0];
                }else{
                    $clickableUrl = "Missing Clickable url";
                }

                array_push($returnedData['data'],array(
                    $record->titleInfo[0]->title??"Topic Missing",
                    $record->recordInfo->recordIdentifier[0]??"Record ID Missing",
                    $clickableUrl,
                    $record->recordInfo->recordUpdatedDate??'Update Date Missing',
                    $record->location->apiUrl[0]??'API URL Missing',
                    $record->recordInfo->recordType??"Type Missing"
                ));
            }
            $returnedData['recordsFiltered'] = $Object->total;
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($returnedData);
            break;


            case 'authors':


                break;


        default:


    }
?>