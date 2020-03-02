<?php

$response = array();  
if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $postPassword = $_POST['password'];
    
    if ($stmt = $con->prepare("SELECT id, name, email, password FROM users WHERE username = ?")) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $email, $password);
            $stmt->fetch();
            if (password_verify($postPassword, $password)) {
                $user = array(  
                    'id'=>$id,   
                    'name'=>$name,
                    'username'=>$username,
                    'email'=>$email
                );  
            
                $response['error'] = false;   
                $response['message'] = 'Logget ind!';   
                $response['user'] = $user;
                
            } else {
                $response['error'] = true;   
                $response['message'] = 'Forkert kodeord!';
            }
        } else {
            $response['error'] = true;   
            $response['message'] = 'Forkert brugernavn eller kodeord';
        }
    
        $stmt->close();
    }
} else {
    $response['error'] = true;   
    $response['message'] = 'Alle felter skal udfyldes!';
}

echo json_encode($response); 
?>