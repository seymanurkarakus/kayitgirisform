CREATE DATABASE kayitform;

USE kayitform;

CREATE TABLE kayit (
    id INT PRIMARY KEY,
    kullanici_adi VARCHAR(255) NOT NULL,
    sifre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefon VARCHAR (11) NOT NULL
);



