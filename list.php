<?php
session_start();
include 'vendor/autoload.php';
 
$client = new Google_Client();
$client->setAuthConfig("oauth-credentials.json");
$client->addScope("https://www.googleapis.com/auth/drive");
 
// service yang akan digunakan adalah Google Drive
$service = new Google_Service_Drive($client);
// session_start(); //starts a session
// session_unset(); //flushes out all the contents previously set
 
// mengecek jika code auth sudah ada namun token access nya blm ada
if (isset($_GET['code'])) {
  // gunakan code auth untuk mendapatkan token accessnya
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  // simpan token ke session
  $_SESSION['upload_token'] = $token;
}
 
// mengecek jika token access nya blm ada
if (empty($_SESSION['upload_token'])){
    // lakukan login via oauth dan mendapatkan code auth
    $authUrl = $client->createAuthUrl();
    header("Location:".$authUrl);
 
} else {
    // jika token access sudah ada
    // print_r($_SESSION['upload_token']);
 
    // gunakan token access untuk mengakses layanan Google API service
    $client->setAccessToken($_SESSION['upload_token']);
    $client->getAccessToken();
 
    // membaca list file
    $folderId = "1TG8fF1xU5sBgtxqN57ahzx2uiBQL_B3O";
    $optParams = array(
        'pageSize' => 10,
        'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
        'q' => "'".$folderId."' in parents"
        );
    $results = $service->files->listFiles($optParams);
    // print_r($results);
    $id= "1vibrh4VHcXtJJDjmg-AZmjtG1-rw_F4r";
    $name="FIX LAPORAN CALL CENTER COVID 14 November 2021.docx";

    // menampilkan list file
    echo "<ul>";
    echo "<li><a href='download.php?id=".$id."'>".$name."</a></li>";
    // foreach ($results->getFiles() as $file) {
    //     echo "<li><a href='download.php?id=".$file->getID()."'>".$file->getName()."</a></li>";
    // }
    // echo "</ul>";
}
 
?>