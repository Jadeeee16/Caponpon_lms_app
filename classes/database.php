<?php
    class database {
        function opencon(): PDO {
            return new PDO('mysql:host=localhost;
            dbname=lms_app',
            username: 'root',
            password: '');
 
            require_once('classes/database.php');
            $con = new database();
            $data = $con->opencon();
        }
 
        function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {
                $con = $this->opencon();
                try {
                $con->beginTransaction();
 
                // Insert into Users table
                $stmt = $con->prepare("INSERT INTO Users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);
 
                //Get the newly inserted user_id
                $userId = $con->lastInsertId();
 
                //Insert into users_pictures table
                $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?,?)");
                $stmt->execute([$userId, $profile_picture_path]);
 
                $con->commit();
                return $userId; //return user_id for further use (like inserting address)
            } catch(PDOException $e) {
                $con->rollBack();
                return false;
            }
 
        }
 
        function insertAddress($user_Id, $street, $barangay, $city, $province) {
            $con = $this->opencon();
            try {
                $con->beginTransaction();
 
                //Insert into Address table
                $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?,?,?,?)");
                $stmt->execute([$street, $barangay, $city, $province]);
 
                //Get the newly inserted address_id
                $addressId = $con->lastInsertId();
 
                //Link User and Address into Users_Address table
                $stmt = $con->prepare("INSERT INTO Users_Address (user_id, address_id) VALUES (?,?)");
                $stmt->execute([$user_Id, $addressId]);
 
                $con->commit();
                return true;
 
            } catch(PDOException $e) {
                $con->rollBack();
                return false;
            }
        }

        function loginUser($email, $password) {
        $con = $this->opencon();
        // Insert into User Table
        $stmt = $con->prepare("SELECT * FROM Users WHERE user_email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['user_password'])) {
        return $user;
        } else {
            return false;
        }
        }

    function authorUser($author_FN, $author_LN, $author_birthday, $author_nat) {
        $con = $this->opencon();
        try {
        $con->beginTransaction();
 
        // Insert into Users table
        $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?,?,?,?)");
        $stmt->execute([$author_FN, $author_LN, $author_birthday, $author_nat]);

        $authorId = $con->lastInsertId();

        $con->commit();
        return $authorId; 

    } catch(PDOException $e) {
                $con->rollBack();
                return false;
            }

    }

    function genreUser($genre_name) {
        $con = $this->opencon();
        try {
        $con->beginTransaction();

        // Insert into Users table
        $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)");
        $stmt->execute([$genre_name]);

        $genreId = $con->lastInsertId();

        $con->commit();
        return $genreId; 

    } catch(PDOException $e) {
                $con->rollBack();
                return false;
            }

    }

    function viewAuthors() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM Authors")
        ->fetchAll();
    }

    function viewAuthorsID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Authors WHERE author_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateAuthors($author_FN, $author_LN,$author_birthday, $author_nat,$author_id) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();

        $query = $con->prepare("UPDATE authors SET author_FN = ?, author_LN = ?, author_birthday = ?, author_nat = ? WHERE author_id = ?");
                                
        $query->execute([$author_FN, $author_LN,$author_birthday, $author_nat,$author_id]);

        $con->commit();
        return true;
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
    }

    function viewGenres() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM Genres")
        ->fetchAll();
    }

    function viewGenresID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Genres WHERE genre_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateGenres($genre_id, $genre_name) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();

        $query = $con->prepare("UPDATE Genres SET genre_name=? WHERE genre_id = ?");
                                
        $query->execute([$genre_id, $genre_name]);

        $con->commit();
        return true;
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
    }


}
   
?>