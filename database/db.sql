
CREATE TYPE user_status AS ENUM ('active', 'inactive');
CREATE TYPE login_status AS ENUM ('login', 'logout');

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_status user_status NOT NULL,
    user_created_on TIMESTAMP NOT NULL,
    user_login_status login_status
);

CREATE TABLE chatrooms (
    room_id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    msg VARCHAR(800) NOT NULL,
    created_on TIMESTAMP NOT NULL
);
