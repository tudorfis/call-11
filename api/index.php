<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

echo "test"
;
var_dump($_POST); 
var_dump($_GET);
die();


include 'config.php'; 

$conn = mysqli_connect( $host, $user, $pass, $db );

$getMethod = $_GET['method'];

$api = new Api( $conn );
if ( isset($getMethod) ) {
    $api->{$getMethod}();
}

echo '<pre>';
var_dump($_POST);
die();

class Api {
    protected $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function login() {
        $email = $_GET['params']['email'];
        $password = $_GET['params']['password'];

        $query = "select * from users where email = '". $email ."' and password = '". $password ."'";
        $result = mysqli_query( $this->conn, $query );
    
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode( array( 'token' => $row['token'] ) );
            return;
        }

        echo json_encode( array( 'token' => '' ) );
    }

    public function checkLogin() {
        $token = $_GET['params']['token'];

        $query = "select * from users where token = '". $token ."'";
        $result = mysqli_query( $this->conn, $query );
    
        if ($result->num_rows > 0) {
            echo json_encode( array( 'valid' => true ) );
        }
        else {
            echo json_encode( array( 'valid' => false ) );
        }
    }
}
