<?php
global $conn;
session_start();
include 'db.php';
include 'email.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'load') {
    $query = "SELECT title,e.id As event_id, u.id as user_id,start_event, end_event,name,e.affected_to as affected_id FROM events e JOIN user u ON e.affected_to = u.id  WHERE u.id = " .$_SESSION['id']  ." ;";
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin')
        $query = "SELECT title,e.id As event_id, u.id as user_id,start_event, end_event,name,e.affected_to as affected_id FROM events e JOIN user u ON e.affected_to = u.id;";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
            $data[] = [
                'id' => $row['event_id'],
                'title' => $row['title'] ."--" .$row['name'],
                'start' => $row['start_event'],
                'end' => $row['end_event'],
                'affected_to' => $row['affected_id']
            ];
        } else {
            $data[] = [
                'id' => $row['event_id'],
                'title' => $row['title'],
                'start' => $row['start_event'],
                'end' => $row['end_event'],
                'affected_to' => $row['affected_id']
            ];
        }

    }
    echo json_encode($data);
}

if ($action == 'insert') {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $affected_to = $_POST['affected_to'];
    $query = "INSERT INTO events (title, start_event, end_event,affected_to	) VALUES ('$title', '$start', '$end', '$affected_to')";
    $conn->query($query);
    $query = "SELECT email,name FROM `user` WHERE id = " . $affected_to;
    $result = $conn->query($query);
    $row = $result->fetch_assoc(); 
    $user_name = $row['name'];
    $user_email = $row['email'];
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){

        $university_name = "ESTSB";
        $recipient_name = $user_name;
        $sender_name = "Assim Naim";
        $course_title = $title;
        $start_date = $start;
        $end_date = $end;
        $message = "
            <p>Bonjour $recipient_name,</p>
            <p>Nous souhaitons vous informer qu’un nouveau cours vous a été attribué par l’administration. Veuillez trouver ci-dessous les détails :</p>
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Titre du cours :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$course_title</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Date de début :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$start_date</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Date de fin :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$end_date</td>
                </tr>
            </table>
            <p>Pour toute question ou information complémentaire, n’hésitez pas à nous contacter.</p>
            <p>Cordialement,</p>
            <p><strong>$sender_name</strong><br>
            $university_name</p>
        ";
        sendEmail($user_email, "Affectation d'un cours à votre agenda - ".$title, $message);
    } else {
        $message = "
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <p>Bonjour Admin,</p>
        
            <p>Un utilisateur a ajouté un nouvel agenda. Voici les détails :</p>
        
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Nom de l'utilisateur :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$user_name</td>
                </tr>
                                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Email de l'utilisateur :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$user_email</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Titre de l'agenda :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$title</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Debut de cour  :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$start</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Fin de cour:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$end</td>
                </tr>
            </table>
        
            <p>Merci de prendre connaissance de cette mise à jour et de prendre les mesures nécessaires.</p>
        
            <p>Cordialement,</p>
            <p><strong>Assim Naim</strong><br>ESTSB</p>
        </body>
    
        ";
        sendEmail("assim3188@gmail.com", "Un prof a ajouté un nouvel agenda - ".$title, $message);
    }

    echo 'Event Inserted';
}

if ($action == 'update') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $affected_to = $_POST['affected_to'];



    $query = "UPDATE events SET title='$title', start_event='$start', end_event='$end' WHERE id='$id'";
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
        $query = "UPDATE events SET title='$title', start_event='$start', end_event='$end',  affected_to='$affected_to' WHERE id='$id'";

    }
    $conn->query($query);


    $query = "SELECT email,name FROM `user` WHERE id = " . $affected_to;
    $result = $conn->query($query);
    $row = $result->fetch_assoc(); 
    $user_name = $row['name'];
    $user_email = $row['email'];
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){

        $university_name = "ESTSB";
        $recipient_name = $row['name'];
        $sender_name =  $user_name;
        $course_title = $title;
        $start_date = $start;
        $end_date = $end;
        $message = "
            <p>Bonjour $recipient_name,</p>
            <p>Nous souhaitons vous informer qu’un nouveau cours vous a été attribué par l’administration. Veuillez trouver ci-dessous les détails :</p>
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Titre du cours :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$course_title</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Date de début :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$start_date</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Date de fin :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$end_date</td>
                </tr>
            </table>
            <p>Pour toute question ou information complémentaire, n’hésitez pas à nous contacter.</p>
            <p>Cordialement,</p>
            <p><strong>$sender_name</strong><br>
            $university_name</p>
        ";
        sendEmail($user_email, "Affectation d'un cours à votre agenda - ".$title, $message);

    }else {
        $message = "
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <p>Bonjour Admin,</p>
        
            <p>Un utilisateur a modifiée un agenda. Voici les détails :</p>
        
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Nom de l'utilisateur :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$user_name</td>
                </tr>
                                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Email de l'utilisateur :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$user_email</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Titre de l'agenda :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$title</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Debut de cour  :</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$start</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>Fin de cour:</strong></td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$end</td>
                </tr>
            </table>
        
            <p>Merci de prendre connaissance de cette mise à jour et de prendre les mesures nécessaires.</p>
        
            <p>Cordialement,</p>
            <p><strong>Assim Naim</strong><br>ESTSB</p>
        </body>
    
        ";
        sendEmail("assim3188@gmail.com", "Un prof a modifiée un agenda - ".$title, $message);
    }
    echo 'Event Updated';
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $query = "DELETE FROM events WHERE id='$id'";
    $conn->query($query);
    echo 'Event Deleted';
}
?>
