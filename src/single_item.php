<?php
require_once __DIR__ . '/environment.php';
$id = intval($_GET['id']);
$curlTarget = "https://fraser.stlouisfed.org/api/item/$id?limit=100&page=1&format=json";

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
    echo "<pre style='display:none'>";
    print_r($Object->records[0]);
    echo "</pre>";
    $data = $Object->records[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fraser Datatables</title>
    <link href="styles.css"  rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="canonical" href="/styles/single_item.css">

<body>
<div class='container'>
<h1>Fraser Item</h1>
<hr>
<h3>Title: <?= $data->relatedItem[0]->titleInfo[0]->title ?></h3>
<h4></h4>
<h4></h4>
    <div class='row'>

        <embed class='col-sm-8' src="<?= $data->location->pdfUrl[0] ?>" type="application/pdf" width="100%" height="800">
        <div class='col-sm-4' style="    border-radius: 5px;    border: solid 1px black;">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">About</button>
            <div class="collapse show" id="home-collapse" style=""> 
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small"> 
                    <li><span class="link-body-emphasis d-inline-flex text-decoration-none rounded"><strong>Date:</strong> <?= $data->originInfo->dateIssued[0] ?></span></li> 
                    <!-- <li><span class="link-body-emphasis d-inline-flex text-decoration-none rounded">Author: </span>
                
                    </li>  -->
                    <li><span class="link-body-emphasis d-inline-flex text-decoration-none rounded"><strong>Physical Description:</strong></span>
                        <?php 
                            if(isset($data->physicalDescription)){
                                echo "<ul>";
                                echo "<li><strong>Form:</strong> ".$data->physicalDescription->form."</li>";
                                echo "<li><strong>Extent:</strong> ".$data->physicalDescription->extent."</li>";
                                echo "<li><strong>Digital Origin:</strong> ".$data->physicalDescription->digitalOrigin."</li>";
                                echo "<li><strong>Internet Media Type:</strong> ".$data->physicalDescription->internetMediaType[0]."</li>";
                                echo "</ul>";
                            }
                        ?>
                    </li> 
                    
                    <li><span class="link-body-emphasis d-inline-flex text-decoration-none rounded"><strong>Language:</strong> <?= $data->language[0] ?> </span>
                
                    </li> 
                    <li><span class="link-body-emphasis d-inline-flex text-decoration-none rounded"><strong>Genre:</strong> 
                        <?php 
                            if(isset($data->genre) && is_array($data->genre)){
                                echo "<ul>";
                                foreach($data->genre as $genre){
                                    echo "<li>$genre</li>";
                                }
                                echo "</ul>";
                            }
                        ?>
                    </li> 
                    <li><strong>Access Condition:</strong> <?= $data->accessCondition ?></li>
                    
                </ul> 
            </div>
                
            
            
        </div>
    </div>
</div>
 <?php
    require_once('./partials/footer.php');
?>  
</body>
</html>