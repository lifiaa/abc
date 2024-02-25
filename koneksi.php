<?php
  $host = 'localhost';
  $user = 'root';
  $pass = '';
  $db = 'ukk_pengelolaan_laundry';
  
  $conn = mysqli_connect($host, $user, $pass, $db);
  $conn2 = mysqli_connect($host, $user, $pass, $db);
  if($conn){
    // echo 'Koneksi berhasil';
  }

  //mysqli_select_db($conn, $db)
?>