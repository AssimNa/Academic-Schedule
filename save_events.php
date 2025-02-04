<?php
//// Configuration de la base de données
//$host = 'localhost';
//$dbname = 'mbrouk';
//$username = 'root';
//$password = ''; // Remplacez par votre mot de passe si nécessaire
//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
//// Debugging: Afficher les données reçues
//file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);
//
//try {
//    $title = isset($_POST['title']) ? $_POST['title'] : '';
//    $start = isset($_POST['start']) ? $_POST['start'] : '';
//    $end = isset($_POST['end']) ? $_POST['end'] : '';
//
//    if (!empty($title) && !empty($start) && !empty($end)) {
//        // Logique d'enregistrement dans la base de données
//        echo json_encode(['success' => true]);
//    } else {
//        echo json_encode(['success' => false, 'message' => 'Champs manquants']);
//    }
//} catch (Exception $e) {
//    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
//}
//
//?>
