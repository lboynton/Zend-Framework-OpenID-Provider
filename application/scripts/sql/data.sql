-- empty tables first
delete from users;
delete from user_details;

-- insert test data
insert into users (username, password, openid, created, user_type) VALUES ('testuser', 'a45adc8ea23392523a6431f16fbdea9d', 'http://localhost/?user=testuser', '2009-01-01 00:00:00+00', 'member');
insert into user_details (user_id, key, value) VALUES ((select id from users where username = 'testuser'), 'email', 'test@test.com');
