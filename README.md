IntNet Project
======

[![Code Climate](https://codeclimate.com/github/markusos/intnet_project/badges/gpa.svg)](https://codeclimate.com/github/markusos/intnet_project)

This is a project for the course [DD2390 Internet Programming](http://www.kth.se/student/kurser/kurs/DD2390?l=en "DD2390"). The project consists of a message board web site and an Android app. Implemented functionality includes: creating accounts, adding new messages and commenting existing ones, following friends, etc...

The project is implemented using PHP with and MySQL database for storage.

## Setup ##

- Install project dependencies by running `npm install` in the in the web folder.
- Build the js using grunt, run `grunt` in the web folder.
- Create a database with the name 'intnet'.
- Configure the db user name and password in '/api/config.ini'-file.
- Open 'setup.php' in your browser to create all the needed database tables. Remove the file from production env.
- Configure the 'domain' variable in the android app to connect to the right domain.
