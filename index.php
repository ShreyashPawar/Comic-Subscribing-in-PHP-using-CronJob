<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Subscribe</title>
</head>
<body class="body" align = "center" style="margin:10%;">
    <h1>Subscribe to Get Comic Images</h1>
    <div class="form" align="center">
    <form action="" method="post">
        <div>
            <label>Provide Email to subscribe</label><br>
            <input class="email" type="email" name="email" placeholder="example@example.com">
        </div>
        <div style = "color:red; font-size:13px;"><?php echo $err; ?></div>
        <div><button class="button" type="submit" name="subscribe">Subscribe</button></div>
    </form>
</div>
</body>
</html>


<?php
    require_once __DIR__.'/dbCon.php';
    $err='';
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $mail = testInput($_POST['email']);
        if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)===false) {
            $hashID = password_hash($email,PASSWORD_DEFAULT);

            $query = $conn->prepare('SELECT email FROM data WHERE email = :email');
            $query->bindParam(':email', $mail, PDO::PARAM_STR);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);

            $rowCount = $query->rowCount();

            if ($rowCount > 0 ) {
                echo '<script>alert("This email is already registered")</script>';
            }
            else {
                $to = $mail;
                $subject = 'Verify email for XKCD';
                $url = $servername.'/shreyash_xkcd/verification.php?token='.$hashID;
                $msg ='
                            <html>
                            <head>
                                <title>Subscription Email</title>
                            </head>
                            <body style="background-color:cyan;" align="center">
                                <h1>Hi there !! </h1>
                                <h3>Please verify your email by clicking the link below <br> <br>
                                <a target="_blank" href='.$url.'>Click below link to Verify</a> </h3>
                                <h5>After successful verification of your email, you will recieve emails from us every five minutes with XKCD images.<br> You can unsuscribe anytime you want.</p></h5>
                                <h6>This is a system generated mail.Please do not reply</h6>
                            </body>
                        </html>
                    ';
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .=  'From: '.$email_address. "\r\n";
                mail($to, $subject, $msg, $headers);

                
                $verify = 0;

                $query = $conn->prepare('INSERT INTO data (email, hashID, verify) VALUES (:email, :hashID, :verify)');
                $query->bindParam(':email', $mail,PDO::PARAM_STR);
                $query->bindParam(':hashID', $hashID,PDO::PARAM_STR);
                $query->bindParam(':verify', $verify,PDO::PARAM_INT);
                $query->execute();
                echo '<script>alert("Mail sent. Please Verify your mail.")</script>';
            }
        }
        else {
            $err = 'Enter a valid email.';
        }
        
    }

    function testInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data,FILTER_SANITIZE_STRING);
        return $data;
    }
