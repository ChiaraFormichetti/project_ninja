CREATE DATABASE primo_test;

CREATE TABLE address(
    id INT NOT NULL AUTO_INCREMENT,
    provincia VARCHAR(255) NOT NULL,
    stato VARCHAR(255) NOT NULL,
    FOREIGN KEY (id) REFERENCES users(indirizzo)
    

    );


    
    CREATE TABLE users(
    email VARCHAR(255) NOT NULL PRIMARY KEY ,
    nome VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    data_di_nascita DATE NOT NULL,
    telefono INT NOT NULL,
    indirizzo INT NOT NULL,
    FOREIGN KEY(indirizzo) 
    REFERENCES `address`(id) 
    ON DELETE CASCADE ON UPDATE CASCADE

    
    );
    INSERT INTO users (nome, surname, data_di_nascita, email, indirizzo VALUES
('Chiara','Formichetti','24/02/1999','chiara.formichetti@outlook.it', 23),
('Giordano','Conti','14/08/1996','giordano.conti@gmail.com',5),
('Francesca','Bruschi','22/12/1970','ltag@libero.it',9),
('Marco','Rossi','13/09/1992','marco.rossi@gmail.com',2));



INSERT INTO address (provincia, stato VALUES
('','Terni','Italia'),
('','Glasgow','Scozia'),
('','Roma','Italia'),
('','Berlino','Germania'));

