# Real-time WebSocket Technology Group & One-to-One/Private Chat Application built with PHP and Ratchet Library
This project is a real-time chat application developed using PHP and WebSocket technology (using Ratchet Library). It provides a seamless and interactive platform for users to engage in real-time conversations, featuring two chat modes: Group/Public Chat & One-to-One/Private Chat. Built with PHP and WebSocket technology, my application provides a seamless platform for instant communication.
Frontend technologies used: jQuery, JavaScript, AJAX and Bootstrap (responsive design).

## Screenshots:

***Group/Public Chat:***

![group-public-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/29181d84-a56f-4e29-a793-b41e470d9533)

***One-to-One/Private Chat:***

![one-to-one-private-chat](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/01d8d728-39df-4d87-91a0-576206b44d91)

***Profile Page:***

![profile-page](https://github.com/AhmedYahyaE/realtime-websocket-php-chat-application/assets/118033266/d74fc7ab-16a7-435e-8707-8008a1e9cb78)

## Features:
1- MVC design pattern, Routing System, Service Container, Middlewares, Pagination Class, Entry Point/Script file (index.php) for the whole application, ...

2- User Registration, Authentication and Authorization.

3- Login System (Session Management).

4- Both Server-side and Client-side Validation.

5- Admin Panel for managing blog users, posts and comments.

6- User profile management.

7- Create, update, and delete blog posts.

8- Categories and tags for organizing blog posts. Also, commenting system for blog posts.

9- CRUD Operations.

10- User Roles and Permissions.

11- File Upload.

12- Using .htaccess Apache configuration file.

13- Regular Expression.

14- Responsive / Mobile first Design.

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
