<?php 

$response = array();  
if(isset($_POST['name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $allreadyTaken = false;

    do {
        if ($stmt = $con->prepare("SELECT id FROM users WHERE username = ?")) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $response['error'] = true;   
                $response['message'] = 'Brugernavn er allerede registreret!';
                $allreadyTaken = true;
                break;
            }
        }

        if ($stmt = $con->prepare("SELECT id FROM users WHERE email = ?")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $response['error'] = true;   
                $response['message'] = 'Email er allerede registreret!';
                $allreadyTaken = true;
                break;
            }
        }
        
        break;
    } while(true);

    if($allreadyTaken == false) {
        $stmt = $con->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $username, $email, $password);
        $stmt->execute();

        $response['error'] = false;   
        $response['message'] = 'Registrering godkendt!';
    }
    
    $stmt->close();
} else {
    $response['error'] = true;   
    $response['message'] = 'Alle felter skal udfyldes!';
}

echo json_encode($response); 
?>