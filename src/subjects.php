<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fraser Datatables</title>
    <link href="styles.css"  rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.3.4/b-3.2.5/b-html5-3.2.5/b-print-3.2.5/datatables.min.css" rel="stylesheet" integrity="sha384-kxJoVyqlxMGAlO8XEWgqVGC68Owtq0W1dZipJ9U9EkyACWvBE2EuxXjTc1vana1M" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.3.4/b-3.2.5/b-html5-3.2.5/b-print-3.2.5/datatables.min.js" integrity="sha384-G3KEtWG7bu74BoULNNDXUMCdQXlnnNp4/d8PcQNmbNv+onYugqwsNGv78t/sLKDX" crossorigin="anonymous"></script>
</head>

<body>
    <?php
        require_once('./partials/header.php');
    ?> 
<div class="container">
    <h1>Subjects</h1>
        <table id="fraserTable" class="table table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>ID</th>
                    <th>Details</th>
                    <th>Creation Date</th>
                    <th>apiUrl</th>
                    <th>recordType</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
</div>
    <script>
        new DataTable('#fraserTable', {
            processing: true,
            serverSide: true,
            ordering: false,
            stateSave: true,
            stateSaveCallback: function (settings, data) {
                localStorage.setItem(
                    'DataTables_' + settings.sInstance,
                    JSON.stringify(data)
                );
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            layout: {
                topStart: 'pageLength',
                topEnd: [{ 
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                }],
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            ajax: {
                url: './api.php?type=subjects',
                type: 'POST'
            },
            columnDefs:[
                {
                    targets:[2],
                    data: function (row, type, val, meta) {
                        console.log(row, type, val, meta)
                        return "<a href='./subject_details.php?id="+row[1]+"'>Items Under Subject</a>";
                    }

                },
            ]
        });
    </script>
    <?php
        require_once('./partials/footer.php');
    ?>  
</body>
</html>