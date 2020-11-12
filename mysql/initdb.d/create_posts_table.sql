DROP TABLE IF EXISTS posts;
CREATE TABLE posts
(
    id INT
    AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    member_id INT NOT NULL,
    reply_message_id INT NOT NULL,
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL
);
