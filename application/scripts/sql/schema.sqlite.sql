drop table if exists users;

CREATE TABLE users
(
    id INTEGER PRIMARY KEY,
    username character varying(255) NOT NULL UNIQUE,
    password character varying(32) NOT NULL,
    openid character varying(1000) NOT NULL,
    created timestamp with time zone NOT NULL
);

drop table if exists user_details;

CREATE TABLE user_details
(
    user_id int NOT NULL,
    key character varying(255) NOT NULL,
    value character varying(255),
    primary key(user_id, key)
);

drop table if exists sites;

CREATE TABLE sites
(
    id serial NOT NULL PRIMARY KEY,
    site character varying(2000) NOT NULL,
    time timestamp with time zone NOT NULL,
    trusted character varying(1000) NOT NULL,
    openid character varying(2000) NOT NULL
);

drop table if exists associations;

CREATE TABLE associations
(
    handle character varying(255) NOT NULL PRIMARY KEY,
    secret character varying(255) NOT NULL,
    mac_func character(16) NOT NULL,
    expires integer NOT NULL
);

insert into users (username, password, openid, created) VALUES ('testuser', 'a45adc8ea23392523a6431f16fbdea9d', 'http://localhost/?user=testuser', 2009);