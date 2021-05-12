<?php
require_once "ArmaParser.php";
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

    <title>Slotlist-Generator by Isaac!</title>
</head>
<body>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
-->
<br><br>
<div class="container">
    <h1>Slotliste erstellen</h1>
    <h4>Nach dem <a href="https://tf133.de/forum/index.php?thread/1224-vorlage-missionsthread-nutzung-pflicht/" target="_blank">TF133 Muster</a></h4>
    <hr>
    Anleitung wie man den Generator benutzt, findet ihr im <a href="https://tf133.de/forum/index.php?thread/1381-slotlist-generator/&postID=7591#post7591" target="_blank"> Forum</a>.
    <hr>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label" for="file">(Nicht binarisierte) Mission.sqm hochladen</label>
            <input type="file" class="form-control" id="file" name="file" accept=".sqm" required/>
            <div id="missionsqmfile" class="form-text">Wir werden deine Mission nach allen spielbaren Slots durchsuchen!</div>
        </div>
        <button type="submit" name="formsubmit" class="btn btn-primary">Slotliste erstellen!</button>
    </form>
    <?php
    if (isset($_POST['formsubmit'])) {
        function validateForm() {
            $fileType = strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
            if ($fileType != "sqm") {
                return ['result' => 'false', 'message' => 'Falsche Dateiendung'];
            }
            if ($_FILES["file"]["size"] > 2000000) {
                return ['result' => 'false', 'message' => 'Datei zu groß'];
            }
        }
        $validation = validateForm();
        if (is_array($validation) and array_key_exists('result', $validation)) {
            $message = '<br><br><div class="alert alert-danger" role="alert">Fehler aufgetreten: '.$validation['message'].' Seite Neuladen!</div>';
            exit($message);
        }
        $handle = fopen($_FILES['file']['tmp_name'], "rb");
        if ($handle) {
            $tmpArray = [];
            $resultArray = [];
            while (($line = fgets($handle)) !== false) {
                $line = trim(strip_tags($line));
                $tmpArray[] = $line;
                if ($line == "isPlayer=1;" or $line == "isPlayable=1;") {
                    $resultArray[] = array_slice($tmpArray, -2, 2, true);
                }
            }
            fclose($handle);
            $ap = new ArmaParser();
            $slotliste = $ap->reformatData($resultArray);
            $table = $ap->createTable($slotliste);
            echo $table;
        } else {
            exit('<br><br><div class="alert alert-danger" role="alert">Es ist ein Fehler beim Lesen der Datei aufgetreten.</div>');
        }
    }
    ?>
    <br><br><br><br>
    <hr>
    <figure>
        <blockquote class="blockquote">
            <p>Mir geht es auf den Sack, immer diese Tabelle (Slotliste) im Forum per Hand zu machen!</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Ein aufgebrachter <cite title="Source Title">Isaac</cite>
        </figcaption>
    </figure>
    <hr>
    <p class="text-center text-muted">Erstellt durch <a href="https://tf133.de/index.php?user/135-isaac/" target="_blank">Isaac</a> | <a href="https://github.com/TFWIsaac/PHP-SQM-Parser" target="_blank">GitHub</a></p>
</div>
</body>
</html>