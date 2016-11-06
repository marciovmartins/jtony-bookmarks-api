-- Table: users

-- DROP TABLE users;

CREATE TABLE users
(
  id serial NOT NULL,
  name VARCHAR(100),
  email VARCHAR(100),
  nick VARCHAR(30),
  dt_insert timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  dt_last_update timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  pass_prefix VARCHAR(10),
  password VARCHAR(60),
  active smallint DEFAULT 1,
  CONSTRAINT users_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


-- Table: admins

-- DROP TABLE admins;

CREATE TABLE admins
(
  id serial NOT NULL,
  name VARCHAR(100),
  email VARCHAR(100),
  nick VARCHAR(30),
  dt_insert timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  dt_last_update timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  pass_prefix VARCHAR(10),
  password VARCHAR(60),
  active smallint DEFAULT 1,
  CONSTRAINT admins_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


-- Table: bookmarks

-- DROP TABLE bookmarks;

CREATE TABLE bookmarks
(
  id serial NOT NULL,
  url VARCHAR(255),
  dt_insert timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  dt_last_update timestamp without time zone NOT NULL DEFAULT  CURRENT_TIMESTAMP,
  active smallint DEFAULT 1,
  id_user integer,
  CONSTRAINT bookmarks_pkey PRIMARY KEY (id),
  CONSTRAINT users_fkey FOREIGN KEY (id_user)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
