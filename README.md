## To Do List

**Database:**
1. Create your database 'todo'<br />
2. Create the table with<br />

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

Note: if you need change the name of database, go to '/var/www/to_do/config/autoload/local.php' and change it<br />
<br />
**Install:**<br />
1. git clone https://github.com/effer89/ToDo.git<br />
2. run composer install<br />
3. Open on http://localhost/to_do/public<br />