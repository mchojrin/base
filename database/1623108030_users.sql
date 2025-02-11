DROP TABLE IF EXISTS users;

CREATE TABLE users (
	id INTEGER PRIMARY KEY AUTO_INCREMENT,
	photo VARCHAR(256),
	name VARCHAR(256) NOT NULL,
	email VARCHAR(256) NOT NULL UNIQUE,
	password VARCHAR(256) NOT NULL,
	role VARCHAR(256),
	permissions TEXT,
	oauth VARCHAR(256),
	hash VARCHAR(256) NOT NULL,
	date_create DATETIME DEFAULT CURRENT_TIMESTAMP,
	date_update DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
    (name, email, password, hash)
VALUES
    ('Administrator', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 'c4ca4238a0b923820dcc509a6f75849b');
