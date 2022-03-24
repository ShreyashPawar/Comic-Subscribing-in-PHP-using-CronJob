# rtlearn PHP ASSIGNMENT - PROBLEM STATEMENT 1
# Email a random XKCD challenge Solution
- This is a PHP application that accepts a user's email address and emails them a random XKCD comic image every five minutes.
# Live Demo Link
- **http://shreyash.axfree.com/shreyash_xkcd**
# Technologies Used
- HTML
- CSS
- PHP
- MySQL Database server.
# Files and Folders

- *dbCon.php* :
    - It contains all the initializations like sender's email address and database credentials.
    - It also establishes connection with the database. The credentials for the database are just dummy values.

- *index.php* :
    - This is the page where the user will provide its email to subscribe to the xkcd comic images.
    - This page also validates the email addresses of the users and checks if they are already present in the database if not it inserts the details of users in the database and sends a verification mail to the user on the provided email address.
    - While inserting it also sets the verify flag to 0 and generated a hashID for the user that is used for furrther funtionalities regarding that particular user.

- *index.css* :
    - This file is present in the css folder and it contains the styling details of the index page of the application.

- *verification.php* :
    - After clicking onto the verification link that contains the hashID the user will be redirected to this page and the verification takes place.
    - After the successful verification the verify flag will be set to 1 in the database.

- *mailing.php* :
    - This Script takes the json data provided by https://xkcd.com/ and the emails with the comic image provided in the json is send in the mail to the users that are verified in database.
    - The mail is sent every 5 minutes to the user with random images everytime as this script is scheduled in the cron jobs of the server.
    - This script also prevents DDoS Attacks as it has a time check feature that prohibits Spamming of mails. 

- *attachment folder* : 
    - In this folder the images provided by https://xkcd.com/ are downloaded and stored as they need to be send as an attachments in the same mail going from mailing.php file.
    - The image in the folder is also updated with every email send and the image sent in every mail are replaced by the existing ones.

- *unsubscribe.php* : 
    - The link for this page in provided with the mail sent every 5 minutes.
    - This page is used to unsubscribe the user from the comic mails as the user will get deleted from the database with the use of its hashID present in the link. 