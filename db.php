<?php

$host = 'localhost'; // Database host
$user = 'root';      // Database username
$pass = '';          // Database password
$dbname = 'mbrouk'; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




//Titre de l'evenenement
//try{
//    // Connexion à la base de données
//    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//    // Récupérer les données envoyées en POST
//    $title = isset($_POST['title']) ? $_POST['title'] : '';
//    $start = isset($_POST['start']) ? $_POST['start'] : '';
//    $end = isset($_POST['end']) ? $_POST['end'] : '';
//
//    if (!empty($title) && !empty($start) && !empty($end)) {
//        // Insérer les données dans la table
//        $sql = "INSERT INTO events (title, start, end) VALUES (:title, :start, :end)";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute([
//            ':title' => $title,
//            ':start' => $start,
//            ':end' => $end
//        ]);
//
//        // Réponse JSON
//        echo json_encode(['success' => true]);
//    } else {
//        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
//    }
//} catch (PDOException $e) {
//    // Gestion des erreurs
//    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
//}



?>
