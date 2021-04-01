Hi, welcome to the Group 20 BAPERS Implementation Project!

This README text file will contain detials about the folder structure and other important details.

====================================================================================================

Web Server Installation instructions

====================================================================================================

Firstly, if you are planning to run this software on your own local machine, you must have a local web server installed such as XAMPP.

Here's a link to install this web server for free... https://www.apachefriends.org/index.html

Secondly, once the download has finished, navigate yourself to the xampp/htdocs directory and extract this web application in that directory.

Once done, open your control XAMPP control panel (on Windows, you can easily search for it at the bottom if it installed successfully) and start Apache and MySQL.

Afterwards, open a web browser and simply enter this in the search bar "localhost/BAPERS/" and you will be taken to the login page of the web application we have created.

====================================================================================================

Folder Structure

====================================================================================================

For our implementation, we as a team have decided to use PHP and approach this project with a web application.

All of the main pages are placed in the root folder.

All of the PHP files that includes form submission to run are placed in the "php" folder. The subfolder called "task" contains all the php files needed to manage the tasks
and the "phpmailer" includes all of the SMTP details we have used to do the SMTP extra feature.

The "report_templates" directory contains the templates for the reports to be generated.

The "js" directory contains all of the JavaScript files we have used throughout the whole software.

The "images" directory contains images that we have used or planned to use in this project.

The "email_templates" are the templates used to create the email and are sent using SMTP.

The "db" should contain an SQL file which we exported from our backup database function. You can use this to import into your own local database which you can access by clicking admin
on MySQL in the XAMPP Control Panel. This also means that the database exported will be placed here and the database to be restored is also taken from this directory.

The "css" folder contains mroe folders with CSS and SCSS files which are used for styling the web pages.

====================================================================================================

That's all, thank you for your time. I can gladly say we all had fun doing this project!

====================================================================================================