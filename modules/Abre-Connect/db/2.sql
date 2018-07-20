use abre;

DROP TABLE abre_connect_journal;

CREATE TABLE Abre_Connect_Journal (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  UsersID INT NOT NULL,
  Title VARCHAR(100) NOT NULL,
  Body TEXT,
  LastUpdated TIMESTAMP,
  FOREIGN KEY (UsersId) REFERENCES users(id)
);
