-- ymlファイルでinitdb.d配下が指定されてるので、ここのsql実行される
DROP TABLE IF EXISTS members;
CREATE TABLE members
(
    id INT
    AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
    (255) NOT NULL,
    email VARCHAR
    (255) NOT NULL,
    password VARCHAR
    (255) NOT NULL,
    picture VARCHAR
    (255) NOT NULL,
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL
);