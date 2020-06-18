# vhosts_manager

Simple vhosts manager for XAMPP

Copy vhosts folder
Create new database and user for that database, grant privileges for that user.
Create new table vhosts
```
CREATE TABLE `vhosts` (
  `domain` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vhosts` (`domain`, `path`) VALUES
('test1.local', 'C:/vhosts/test1.local'),
('test2.local', 'C:/vhosts/test2.local'),
('test3.local', 'C:/vhosts/test3.local');

ALTER TABLE `vhosts`
  ADD PRIMARY KEY (`domain`);
COMMIT;
```
Change connection settings in index.php
