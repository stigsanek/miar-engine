/* PostgreSQL fixtures */

DROP TABLE IF EXISTS
users,
accesses;

CREATE TABLE accesses (
  access_id serial NOT NULL PRIMARY KEY,
  user_role text NOT NULL
);

INSERT INTO accesses (user_role) VALUES
('admin'),
('user'),
('guest');

CREATE TABLE users (
  user_id serial NOT NULL PRIMARY KEY,
  user_login character varying(255) NOT NULL,
  pass character varying(255) NOT NULL,
  access_id integer NOT NULL REFERENCES accesses (access_id),
  first_name character varying(255) NOT NULL,
  last_name character varying(255) NOT NULL,
  pass_status integer NOT NULL
);

INSERT INTO users (user_login, pass, access_id, first_name, last_name, pass_status) VALUES
('admin', '$2y$10$KjmWsHscQwUnwUbAy0D2fe8ErN47.O3mX0ymj9Jdk.yL325dFz4Km', 1, 'First Name', 'Last Name', 0),
('user', '$2y$10$KjmWsHscQwUnwUbAy0D2fe8ErN47.O3mX0ymj9Jdk.yL325dFz4Km', 2, 'First Name', 'Last Name', 0),
('guest', '$2y$10$KjmWsHscQwUnwUbAy0D2fe8ErN47.O3mX0ymj9Jdk.yL325dFz4Km', 3, 'First Name', 'Last Name', 0);
