-- Creates tables

drop table if exists users;

CREATE TABLE users
(
    id serial NOT NULL PRIMARY KEY,
    username character varying(255) NOT NULL UNIQUE,
    password character varying(32) NOT NULL,
    openid character varying(1000) NOT NULL,
    --name character varying(255),
    --nickname character varying(255),
    --email character varying(255),
    created timestamp with time zone NOT NULL
    --dob date,
    --gender character varying(6),
    --postcode character varying(20),
    --country character varying(255),
    --language character varying(255),
    --timezone character varying(255),
    --user_type character varying(10),
);

ALTER TABLE users OWNER TO openid2;

drop table if exists user_details;

CREATE TABLE user_details
(
    user_id int NOT NULL,
    key character varying(255) NOT NULL,
    value character varying(255),
    primary key(user_id, key)
);

ALTER TABLE users_details OWNER TO openid2;

drop table if exists sites;

CREATE TABLE sites
(
    id serial NOT NULL PRIMARY KEY,
    site character varying(2000) NOT NULL,
    time timestamp with time zone NOT NULL,
    trusted character varying(1000) NOT NULL,
    openid character varying(2000) NOT NULL
);

ALTER TABLE sites OWNER TO openid2;

drop table if exists associations;

CREATE TABLE associations
(
    handle character varying(255) NOT NULL PRIMARY KEY,
    secret character varying(255) NOT NULL,
    mac_func character(16) NOT NULL,
    expires integer NOT NULL
);

ALTER TABLE associations OWNER TO openid2;
