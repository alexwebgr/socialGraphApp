# Task Social Graph
This task is designed to give people an idea of how a developer would approach software development problems.

Key points of interest would be

* the structure the database
* the structure of the code
* whether or not the solution is extensible

### Problem Description
The purpose of this task is to create a method of examining a social network.
* Each person listed has one or more connections within the group.
* Come up with a data structure to store and query the information found in the JSON file found at /data.json.
* Create a public API in the language of choice which allows for three basic operations to be
executed for a certain person
    * **Direct friends:** Return those people who are directly connected to the chosen person.
    * **Friends of friends:** Return those who are two steps away, but not directly connected to the chosen person.

The API can be exposed as public functions, a REST-endpoint, a command line interface,whatever fits the chosen technology stack best.

### Requirements
* Object oriented design
* Provide information on how to setup and use the solution

### Suggestions
* VCS should be used

## Implementation

### Prerequisites
1. mysql server - tested on 5.6.12
2. php - tested on 5.4.12 with the following extensions enabled
    * php_mysql
    * php_pdo_mysql
3. modern browser - tested on
    * firefox 27
    * chrome 33
    * Internet Explorer 11

### Installation
1. clone repo under your server web root. Let's assume http://localhost/socialGraph
2. run http://localhost/socialGraph/api/db/createDB
3. run http://localhost/socialGraph/api/user/insertUsers
4. run http://localhost/socialGraph/api/user/insertFriends

### Run
1. go to http://localhost/socialGraph
2. follow on screen instructions
3. have fun !

### Feedback
Comments, criticism, praise are all welcomed !
Create an issue or send me an email at email@alex-web.gr
