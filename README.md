# Real-time WebSocket Technology Group & One-to-One/Private Chat Application built with PHP and Ratchet Library
This project is a real-time chat application developed using PHP and WebSocket technology (using Ratchet Library). It provides a seamless and interactive platform for users to engage in real-time conversations, featuring two chat modes: Group/Public Chat & One-to-One/Private Chat. Built with PHP and WebSocket technology, my application provides a seamless platform for instant communication.
Frontend technologies used: jQuery, JavaScript, AJAX, Parsley JavaScript form validation library, and Bootstrap (responsive design).

## Screenshots:

***Group/Public Chat:***

![group-public-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/29181d84-a56f-4e29-a793-b41e470d9533)

***One-to-One/Private Chat:***

![one-to-one-private-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/7b251f66-dd47-4695-8e08-c1c65b0dff79)

***Profile Page:***

![profile-page](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/d74fc7ab-16a7-435e-8707-8008a1e9cb78)

***WebSocket Protocol:***

![websocket-connection](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/62bc916c-f6e9-4dfd-a322-3fba92fc4d01)

## Features:
1- Real-time Seamless Chat Messaging using WebSocket Technology.

2- Two Chat Modes: Group/Public Chat & One-to-One/Private Chat.

3- Real-time Push Notifications to show Read/Unread Messages Count.

4- Real-time User Online/Offline Status.

5- Using Ratchet PHP WebSocket Library.

6- Saving the Chat History for both Public & Private Chat Modes in a MySQL Database.

7- Object-Oriented Programming.

8- Chat HTML Form Validation using Parsley JavaScript Library.

9- User Registration, Validation, Authentication, and Authorization.

10- Sending Registration Verification Code to emails using PHPMailer Library.

11- File Upload (profile image).

12- Regular Expression.

13- Responsive Design using Bootstrap.

## Application URLs:
1- **Frontend**: The public-facing website can be accessed at https://www.your-domain-example.com/blog. This is where regular customers/users/members can view/read blog posts, add posts, comment on them, and interact with the website, .... The frontend URL is typically accessible to all visitors of the website. Replace https://www.your-domain-example.com/ with the actual domain name or localhost address where you have deployed the application.

2- **Admin Panel**: The Admin Panel for managing the blog is available at https://www.your-domain-example.com/blog/admin/login or https://www.your-domain-example.com/blog/admin. This is a secure area accessible only to authorized administrators. It provides access to the administrative functionalities of the blog application. It is designed for authorized users with administrative privileges to manage the blog posts, comments, and user accounts. Only authenticated administrators can access the admin panel. The Admin Panel URL is protected and restricted to a specific set of users. Again, make sure to replace https://www.your-domain-example.com/ with the appropriate domain name or localhost address.

## Application Routes:
All the application routes are defined in the [Application Routes](App/index.php) file.

## Installation & Configuration:
1- Clone the project or download it.

2- Create a MySQL database named **\`blog\`** and import the database schema from [blog database - PhpMyAdmin Export.sql](<Database - blog/blog database - SQL Dump File - PhpMyAdmin Export.sql>) SQL Dump file. Navigate to '**`Database - blog`**/**`blog database - SQL Dump File - PhpMyAdmin Export.sql`**' SQL Dump file.

3- Navigate to the database connection configuration file in [config.php](config.php) file and configure/edit/update the file with your MySQL database credentials and other configuration settings.

4- Note: ***Apache*** Web Server must be used to serve this project in order for the application routing system and '***.htaccess***' file to work properly! Place the project inside your *'**htdocs***' folder.

5- In your browser, go to http://127.0.0.1/blog/ (**Frontend**) and http://127.0.0.1/blog/admin or http://127.0.0.1/blog/admin/login (**Admin Panel**). N.B. All the application routes are defined in the [Application Routes](App/index.php) file.

6- Here are the ready-to-use registered user account credentials you can readily use (for both **Frontend** and **Admin Panel**):

> **Email**: **ahmed.yahya@gmail.com**, **Password**: **123456**

## Contribution:
Contributions to my plain PHP/MySQL MVC OOP Blog application are most welcome! If you find any issues or have suggestions for improvements or want to add new features, please open an issue or submit a pull request.
