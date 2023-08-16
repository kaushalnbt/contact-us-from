CREATE TABLE contactus_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(30) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email VARCHAR(40) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    ip_address INT(11) NOT NULL,
    submission_time TIMESTAMP NOT NULL
);
