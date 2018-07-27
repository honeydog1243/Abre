CREATE TABLE Abre_Connect_Journal_Attachment (
	ID INT AUTO_INCREMENT PRIMARY KEY,
    UniqueID varchar(36) NOT NULL,
    JournalID INT NOT NULL,
    FileName varchar(100) NOT NULL,
    FileLocation varchar(200) NOT NULL,
    LastUpdated TIMESTAMP,
    FOREIGN KEY (JournalID) REFERENCES Abre_Connect_Journal(ID)
);
