use abre;

CREATE TABLE Abre_Connect_Journal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  users_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  body TEXT,
  FOREIGN KEY (users_id) REFERENCES users(id)
);

