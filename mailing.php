<?php
    require_once __DIR__ . '/dbCon.php';
    $nowTime = time();

    $query = $conn->prepare('SELECT time_check FROM check_time');
    $query->execute();
    $rowCount = $query->rowCount();


    if ($rowCount > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $pastTime = $row['time_check'];
           

            if(($nowTime - $pastTime) >= (5*60)){  

                $xkcd_json_url = 'http://xkcd.com/info.0.json';
                $data = file_get_contents($xkcd_json_url);
                $json_data = json_decode($data, true);
                $totalCount = $json_data['num'];

                $rand = rand(1, $totalCount);


                $new_url = 'https://xkcd.com/' . $rand . '/info.0.json';
                $data = file_get_contents($new_url);
                $json_data = json_decode($data, true);
                $img = $json_data['img'];
                $path = 'attachment/img.png';


                file_put_contents($path, file_get_contents($img));


                $subject = $json_data['safe_title'];
                
                $query = $conn->prepare('SELECT * FROM data WHERE verify = :verify');
                $verify = 1;
                $query->bindParam(':verify', $verify,PDO::PARAM_INT);
                $query->execute();
                $rowCount = $query->rowCount();

                if ($rowCount >0) {
                    
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $hashID = $row['hashID'];
                        $to = $row['email'];
                        $unsub = $servername.'/shreyash_xkcd/unsubscribe.php?token='.$hashID;
                        $html = '
                            <html>
                                <head>
                                    <title>XKCD Emoticon</title>
                                </head>
                                <body>
                                    <center><h2 style="color:#0e1c26;" >HELLO,</h2>
                                    <h3>Enjoy your Comic </h1></center>
                                    <center>
                                        <h1 style="color:#3d3529;">' . $json_data['title'] . '</h1>
                                        <img src="' . $img . '" alt="' . $json_data['alt'] . '">
                                        <a href='.$unsub.'><h3>Unsubscribe XKCD</h3></a></center><br/>
                                    <p>With regards </p>
                                    <p>Xkcd Team</p>
                                    <h6 align="center">This is a system generated mail.Please do not reply</h6>
                                </body>
                            </html>
                        ';

                        $headers ='From: '.$email_address;

                        $semi_rand = md5(time());
                        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

                        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

                        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n"."Content-Transfer-Encoding: 7bit\n\n" . $html . "\n\n";
                        
                        

                        if (!empty($path))
                        {
                            if (file_exists($path))
                            {
                                $message .= "--{$mime_boundary}\n";
                                $fp = @fopen($path, 'rb');
                                $data = @fread($fp, filesize($path));

                                @fclose($fp);
                                $data = chunk_split(base64_encode($data));
                                $message .= "Content-Type: application/octet-stream; name=\"" . basename($path) . "\"\n"."Content-Description: " . basename($path) . "\n"."Content-Disposition: attachment;\n" . " filename=\"" . basename($path) . "\"; size=" . filesize($path) . ";\n"."Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                            }
                            else
                            {
                                echo 'File Not Found';
                            }
                        }
                        else
                        {
                            echo 'Image Url Empty';
                        }

                        $message .= "--{$mime_boundary}--";

                        $mail_result = mail($to, $subject, $message, $headers);

                    }
                }

                $upQuery = $conn->prepare('UPDATE check_time SET time_check = :nowtime WHERE time_check = :pasttime');
                $upQuery->bindParam(':nowtime',$nowTime,PDO::PARAM_INT);
                $upQuery->bindParam(':pasttime',$pastTime,PDO::PARAM_INT);
                $upQuery->execute();
            }
            else
                echo 'Spamming is Prohibited.';

        }
}
    else
        echo 'No time to check with.';

    
