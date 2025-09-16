<?php
require_once __DIR__ . '/environment.php';
$id = intval($_GET['id']);
$curlTarget = "https://fraser.stlouisfed.org/api/subject/$id/records?limit=100&page=1&format=json";

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
    <title>Fraser Subject Details for  <?= $Object->records[0]->subject->topic[0]->topic ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <?php
        require_once('./partials/header.php');
    ?> 
    <div class='container'>
    <h1>Records for  <?= $Object->records[0]->subject->topic[0]->topic ?> (ID: <?= $id ?>)</h1>
    <hr>
    <h3># of Records: <?= $Object->total ?></h3>

    <div class='row' style="padding:20px">
    <?php foreach($Object->records as $record){ ?>
            <div class="card" style="width: 19rem;margin-left:10px;margin-top:10px">
                <div class="card-body" style="padding-bottom: 50px;">
                    <h5 class="card-title"><a href='./single_item.php?id=<?= $record->recordInfo->recordIdentifier[0]??'0' ?>'><?= $record->titleInfo[0]->title ?></a></h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
                      <?php
                            if(isset($record->subject->topic) && !empty($record->subject->topic)){
                                echo "<em>Topics</em>:";
                                echo "<ul>";
                                foreach($record->subject->topic as $topic){
                                    echo "<li>".$topic->topic."</li>";
                                }
                                echo "</ul>";
                            }
                        ?>
                        <em>Type</em>: <strong><?= $record->typeOfResource??"Unknown" ?></strong><br> 
                    <p class="card-text overflow-y-scroll overflow-x-hidden" style="height:300px"> 
                        <em>Abstract</em>: <?php if(isset($record->abstract[0]) && !empty($record->abstract[0])){ echo strip_tags($record->abstract[0]); }else{ echo"N/A"; } ?>
                    </p>
                    <?php
                        if(strpos($record->location->pdfUrl[0]??$record->location->url[0],'.pdf') !== false){
                            echo '<svg style="position:absolute;bottom:5px;left:-100px;height: 50px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                        <path d="M 87.822 26.164 v 58.519 c 0 2.937 -2.381 5.317 -5.317 5.317 H 23.344 c -2.937 0 -5.317 -2.381 -5.317 -5.317 V 71.549 V 5.317 C 18.027 2.381 20.407 0 23.344 0 h 38.315 C 69.928 0.135 87.822 16.011 87.822 26.164 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(226,226,226); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 18.027 36.039 h 47.67 c 3.81 0 6.899 3.089 6.899 6.899 v 21.713 c 0 3.81 -3.089 6.899 -6.899 6.899 H 9.076 c -3.81 0 -6.899 -3.089 -6.899 -6.899 V 29.14" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(241,86,66); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 2.178 29.14 c 0 3.81 3.089 6.899 6.899 6.899 h 8.95 V 22.653 h -8.95 c -3.81 0 -6.899 3.089 -6.899 6.899" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(190,64,48); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 22.105 44.938 h -7.292 c -0.829 0 -1.5 0.671 -1.5 1.5 v 9.914 v 6.79 c 0 0.828 0.671 1.5 1.5 1.5 s 1.5 -0.672 1.5 -1.5 v -5.29 h 5.792 c 2.534 0 4.596 -2.062 4.596 -4.596 v -3.723 C 26.702 46.999 24.64 44.938 22.105 44.938 z M 23.702 53.256 c 0 0.88 -0.716 1.596 -1.596 1.596 h -5.792 v -6.914 h 5.792 c 0.88 0 1.596 0.716 1.596 1.596 V 53.256 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 39.866 44.938 h -6.973 c -0.829 0 -1.5 0.671 -1.5 1.5 v 16.704 c 0 0.828 0.671 1.5 1.5 1.5 h 6.973 c 2.71 0 4.915 -2.205 4.915 -4.915 v -9.875 C 44.781 47.142 42.576 44.938 39.866 44.938 z M 41.781 59.727 c 0 1.056 -0.859 1.915 -1.915 1.915 h -5.473 V 47.938 h 5.473 c 1.056 0 1.915 0.858 1.915 1.914 V 59.727 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 61.718 44.938 H 51.33 c -0.828 0 -1.5 0.671 -1.5 1.5 v 16.704 c 0 0.828 0.672 1.5 1.5 1.5 s 1.5 -0.672 1.5 -1.5 v -6.853 h 5.304 c 0.828 0 1.5 -0.672 1.5 -1.5 s -0.672 -1.5 -1.5 -1.5 H 52.83 v -5.352 h 8.888 c 0.828 0 1.5 -0.672 1.5 -1.5 C 63.218 45.609 62.546 44.938 61.718 44.938 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                        <path d="M 61.659 0 h 6.662 c 2.826 0 5.536 1.123 7.534 3.121 l 8.847 8.847 c 1.998 1.998 3.121 4.708 3.121 7.534 v 6.662 c 0 -3.419 -2.772 -6.19 -6.19 -6.19 h -7.866 c -3.268 0 -5.917 -2.649 -5.917 -5.917 c 0 0 0 -7.866 0 -7.866 v 0 C 67.849 2.772 65.078 0 61.659 0 C 61.659 0 61.659 0 61.659 0 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(183,183,183); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                                    </g>
                                </svg>';
                            echo '<a style="position: absolute;bottom:5px;left:55px;" href="'.$record->location->pdfUrl[0].'" target="_blank" class="card-link">Download Link</a>';
                        }else{
                            echo '<a style="position: absolute;bottom:5px;left:55px;" href="'.$record->location->url[0].'" target="_blank" class="card-link">Visit Link</a>';
                        }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>