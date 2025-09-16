<?php
require_once __DIR__ . '/environment.php';
$id = intval($_GET['id']);
$curlTarget = "https://fraser.stlouisfed.org/api/theme/$id/records?format=json";

 $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $curlTarget,
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fraser Theme Details for  <?= $Object->records[0]->subject->topic[0]->topic ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class='container'>
    <h1>Records for  <?= $Object->records[0]->subject->topic[0]->topic ?> (ID: <?= $id ?>)</h1>
    <hr>
    <h3># of Records: <?= $Object->total ?></h3>

    <div class='row' style="padding:20px">
    <?php foreach($Object->records as $record){ ?>
            <div class="card" style="width: 19rem;margin-left:10px;margin-top:10px">
                <div class="card-body">
                    <h5 class="card-title"><?= $record->titleInfo[0]->title ?></h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
                    <p class="card-text overflow-y-scroll overflow-x-hidden" style="height:300px">
                        <em>Type</em>: <strong><?= $record->typeOfResource??"Unknown" ?></strong><br>  
                        <em>Abstract</em>: <?= $record->abstract[0]??"N/A" ?>
                    </p>
                    <a href="<?= $record->location->pdfUrl[0]??$record->location->url[0] ?>" target="_blank" class="card-link">File Link</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
