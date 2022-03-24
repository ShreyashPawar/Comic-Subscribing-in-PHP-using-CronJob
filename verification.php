<?php
    require_once __DIR__. '/dbCon.php';

    if (isset($_GET['token'])) {
        $hashID  = $_GET['token'];

        $query = $conn->prepare('SELECT * FROM data WHERE hashID = :hashID');
        $query->bindParam(':hashID', $hashID, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        if ($rowCount > 0) {
            $upquery = $conn->prepare('UPDATE data SET verify = 1  WHERE hashID = :hashID');
            $upquery->bindParam(':hashID',$hashID,PDO::PARAM_STR);
            $upquery->execute();

            $upCount = $upquery->rowCount();
            if ($upCount >0) {
                echo '<script>alert("Your email is now verified.\nYour comic image mails will start within 5 minutes.");window.location.href = "index.php";</script>';
            }
            else {
                if ($upCount==0) {
                    echo '<script>alert("This email is already verified")</script>';
                }
                else {
                    echo '<script>alert("Failed to Verify")</script>';
                }
            }
        }
        else {
            echo '<script>alert("Email not found")</script>';
        }


    }
