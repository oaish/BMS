CREATE TABLE Staff (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    Type VARCHAR(100) NOT NULL
)

INSERT INTO Staff VALUES(DEFAULT, 'admin', '1234', 'Admin'), (DEFAULT, 'teller', 'abcd', 'Bank Teller');

CREATE TABLE Users
(
    UserID   INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50)  NOT NULL,
    Email    VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Phone    BIGINT       NOT NULL,
    DOB      VARCHAR(20)  NOT NULL,
    Status   VARCHAR(20)  NOT NULL DEFAULT 'inactive'
);

CREATE TABLE Accounts
(
    AccountID   INT AUTO_INCREMENT PRIMARY KEY,
    UserID      INT            NOT NULL,
    AccountNo   BIGINT         NOT NULL,
    AccountType VARCHAR(50)    NOT NULL,
    CardNumber  VARCHAR(255)   NOT NULL,
    CVV         INT            NOT NULL,
    ExpiryDate  VARCHAR(20)    NOT NULL,
    Balance     DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (UserID) REFERENCES Users (UserID),
    UNIQUE (CardNumber, AccountNo)
);

CREATE TABLE Loans (
    LoanId INT PRIMARY KEY AUTO_INCREMENT,  
    UserId INT,                             
    LoanType VARCHAR(20) NOT NULL,
    LoanAmount DECIMAL(10, 2) NOT NULL,     
    InterestRate DECIMAL(5, 2) NOT NULL,    
    RepaymentPeriod INT NOT NULL,           
    MonthlyInstallment DECIMAL(10, 2) NOT NULL,  
    LoanStatus VARCHAR(20) NOT NULL,        
    StartDate DATE NOT NULL,            
    EndDate DATE NOT NULL,              
    CONSTRAINT FK_UserId FOREIGN KEY (UserId) REFERENCES Users(UserId)  
);

CREATE TABLE Transactions
(
    TransactionID   INT AUTO_INCREMENT PRIMARY KEY,
    FromAccountNo   BIGINT         NOT NULL,
    ToAccountNo     BIGINT         NOT NULL,
    Amount          DECIMAL(10, 2) NOT NULL,
    TransactionType VARCHAR(50)    NOT NULL,
    TransactionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE PayRequests
(
    PayRequestID  INT AUTO_INCREMENT PRIMARY KEY,
    FromAccountNo BIGINT         NOT NULL,
    ToAccountNo   BIGINT         NOT NULL,
    Amount        DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Beneficiary
(
    BID           INT AUTO_INCREMENT PRIMARY KEY,
    MainAccountNo BIGINT NOT NULL,
    AccountNo     BIGINT NOT NULL
);