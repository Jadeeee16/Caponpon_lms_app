<?php

class database { 
    function opencon(): PDO{
        return new PDO('mysql:host=localhost; dbname=lms_app',
        username: 'root',
        password: '');
    }
}

require_once('classes/database.php');
$con = new database();

$data = $con->opencon();

function $con->signupUser($firstname, $latname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {

    $con = $this->opencon();
    try {
        $con->beginTransaction();

        // Insert into Users Table
        $stmt = $con->prepare("INSERT INTO Users(user_FN, user_LN, user_birthday, user_sex, uer_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstname, $lastname, $borthday, $sex, $email, $phone, $username, $password]);

        // Get the newly inserted user_id
        $userId = $con->lastInsertId();

        // Insert into users_pictures table
        $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUS (?, ?)");
        $stmt->execute([$userId, $profile_picture_path]);

        $con->commit();
        return $userId; // return user_id for further use (like inserting address)
    } catch (PDOExceptiion$e) {
        $con->rollBack();

    }

    $con = $this->opencon();
    try {
        $con->beginTransaction();

        // Insert into Users Table
        $stmt = $con->prepare("INSERT INTO Address(ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
        $stmt->execute([$steet, $barangay, Scity, $province]);

        // Get the newly inserted address_id
        $addre4ssId = $con->lastInsertId();
        
    }
}

?>