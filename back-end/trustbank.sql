CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    account_number VARCHAR(20) UNIQUE NOT NULL,

    balance DECIMAL(15,2) DEFAULT 0.00,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    sender_account VARCHAR(20),

    receiver_account VARCHAR(20),

    amount DECIMAL(15,2) NOT NULL,

    type VARCHAR(20),

    reference VARCHAR(50),

    status VARCHAR(20) DEFAULT 'successful',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


