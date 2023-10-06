# Real-time WebSocket Technology Group/Public & One-to-One/Private Chat Application built with PHP and Ratchet Library
This project is a real-time chat application developed using PHP and WebSocket technology (using Ratchet Library). It provides a seamless and interactive platform for users to engage in real-time conversations, featuring two chat modes: Group/Public Chat & One-to-One/Private Chat. Built with WebSocket technology which allows a full-duplex bi-directional connection over a single TCP connection, my application provides a seamless platform for instant communication.

Frontend technologies used: jQuery, JavaScript, AJAX, Parsley JavaScript form validation library, and Bootstrap (responsive design).

## Screenshots:

***Group/Public Chat:***

![group-public-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/29181d84-a56f-4e29-a793-b41e470d9533)

***One-to-One/Private Chat:***

![one-to-one-private-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/cd0c52ca-1a42-4e38-8253-1a1c78a6908e)

***Profile Page:***

![profile-page](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/d74fc7ab-16a7-435e-8707-8008a1e9cb78)

***WebSocket Protocol:***

![websocket-connection](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/62bc916c-f6e9-4dfd-a322-3fba92fc4d01)

## Features:
1- Real-time Seamless Chat Messaging using WebSocket Technology (Ratchet Library).

2- Two Chat Modes: Group/Public Chat & One-to-One/Private Chat.

3- Real-time Push Notifications to show Read/Unread Messages Count.

4- Real-time User Online/Offline Status.

5- Using Ratchet PHP WebSocket Library.

6- Saving the Chat History for both Public & Private Chat Modes in a MySQL Database.

7- Multiple AJAX Requests.

8- Object-Oriented Programming.

9- Chat HTML Form Validation using Parsley JavaScript Library.

10- User Registration, Validation, Authentication, and Authorization.

11- Sending Registration Verification Code to emails using PHPMailer Library.

12- Playing different Notification Sounds when sending and receiving chat messages.

13- File Upload (profile image).

14- Regular Expression.

15- Responsive Design using Bootstrap.

## Application URLs:
1- **Group/Public Chat**: Engage in lively discussions with ALL the Chat Application users at http://localhost:8000/group_chat.php

2- **One-to-One/Private Chat**: Connect with individuals privately, ensuring confidential and personalized interactions. Chat with one particular, specific, targeted user of our Chat Application members at http://localhost:8000/private_chat.php

## Installation & Configuration:
1- Clone the project or download it.

2- Create a MySQL database named **\`chat_application\`**, then import the **[chat_application database SQL Dump File](<Database - chat_application/chat_application database - SQL Dump File - phpMyAdmin Export.sql>)** into your **\`chat_application\`** database.

3- Navigate to the database connection configuration file in [Database_connection.php](database/Database_connection.php) file and configure/edit/update the file with your MySQL database credentials and other configuration settings.

4- Navigate to the project root directory using the **`cd`** terminal command, and then start your PHP built-in Development Web Server by running the command: **`php -S localhost:8000`**.

5- From your terminal window (at the project root directory), start the WebSocket Server by running the command: **`php bin/server.php`**.

6- In your browser, go to http://localhost:8000 to login using one of the following ready-to-use account credentials:

> Email: **ahmed.yahya@test.com**, Password: **123456**

> Email: **fatma@test.com**, Password: **123456**

## Contribution:
Contributions to my Real-time WebSocket PHP Chat Application are most welcome! If you find any issues, have suggestions for improvements, or want to add new features, please open an issue or submit a pull request.
