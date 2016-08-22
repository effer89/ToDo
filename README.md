## To Do List

**Database:**
1. Create your database 'todo'
2. Create the table with

```
CREATE TABLE task
    (
        id INT NOT NULL AUTO_INCREMENT,
        text text,
        done TINYINT(1) DEFAULT '0' NOT NULL,
        status INT DEFAULT '1' NOT NULL,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated TIMESTAMP NULL,
        PRIMARY KEY (id)
    )
    ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Note: if you need change the name of database, go to '/var/www/to_do/config/autoload/local.php' and change it

**Install:**
1. git clone https://github.com/effer89/ToDo.git
2. run composer install
3. Open on http://localhost/to_do/public  