<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'refreshSession':
            include '../protected/refreshSession.php';
            break;
        case 'loadHead':
            $headContents = file_get_contents('../../resources/headElements.html');
            echo $headContents;
            break;
        case 'getTranslations':
            $csvFile = '../translation/locales.csv';
            $csvData = file_get_contents($csvFile);
            echo $csvData;
            break;
        case 'fetchPreferred':
            include '../protected/fetchPrefs.php';
            break;
        default:
            break;
    }
} else {
   
}
?>