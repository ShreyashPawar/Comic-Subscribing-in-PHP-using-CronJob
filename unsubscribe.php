<?php
    require_once __DIR__. '/dbCon.php';

    if (isset($_GET['token']) && !empty($_GET['token']))
    {
        $hashID = $_GET['token'];
        $query = $conn->prepare('SELECT * FROM data WHERE hashID = :hashID AND verify = :verify');
        $verify = 1;
        $query->bindParam(':hashID', $hashID,PDO::PARAM_STR);
        $query->bindParam(':verify', $verify,PDO::PARAM_INT);
        $query->execute();
        $rowCount = $query->rowCount();

        if ($rowCount > 0)
        {
            $upquery = $conn->prepare('DELETE FROM data WHERE hashID = :hashID');
            $upquery->bindParam(':hashID', $hashID,PDO::PARAM_STR);
            $upquery->execute();
            $upCount = $upquery->rowCount();

            if ($upCount > 0) {
                echo '
                        <script>alert("You have Unsubscribed to the Comic mails.");
                        window.location.href = "index.php";
                        </script>
                    ';
            }
            else
            {
                echo 'Unsubscribing Failed';
            }
        }
        else
        {
            echo 'User Not Found.';
        }
    }
