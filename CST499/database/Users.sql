CREATE TABLE User (
    UserId INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Address VARCHAR(255),
    Phone VARCHAR(20),
    SSN VARCHAR(11)
);

INSERT INTO `User` (Email, Password, FirstName, LastName, Address, Phone, SSN)
SELECT 'taylor.walston@student.uagc.edu', 'basic', 'Taylor', 'Walston', 'Home on the range', '6158675309', '5555555555'

SELECT * FROM `User`


CREATE TABLE Course
(   
    CourseId INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Description VARCHAR(1024) NOT NULL
);

INSERT INTO Course(Name, Description)
VALUES 
('Biology 1','You know you just want to cut things up'),
('Calculus 1','The start of the hard math classes that want to weed you out'),
('Chemistry 1','You can''t blow things up yet'),
('English 1','Make sure u speek like a college student'),
('Underwater Finger Painting','Favorite class of the hippy class'),

CREATE TABLE Semester
(
    SemesterId INT AUTO_INCREMENT PRIMARY KEY,
    Term VARCHAR(16) NOT NULL,
    `Year` VARCHAR(4) NOT NULL
);

INSERT INTO Semester (Term, `Year`)
VALUES ("Spring", "2025"),
("Fall", "2025")

CREATE TABLE Class (
    ClassId INT AUTO_INCREMENT PRIMARY KEY,
    CourseId INT NOT NULL,
    SemesterId INT NOT NULL,
    MaxEnrollment SMALLINT NOT NULL,
    FOREIGN KEY (CourseId) REFERENCES Course(CourseId),
    FOREIGN KEY (SemesterId) REFERENCES Semester(SemesterId)
);

-- Insert an instance of each class into each semester
INSERT INTO Class (CourseId, SemesterId, MaxEnrollment)
SELECT 
    c.CourseId, 
    s.SemesterId, 
    2 -- Using a ridiculously low number for testing purposes
FROM 
    Course c
CROSS JOIN 
    Semester s;

CREATE TABLE Enrollment (
    EnrollmentId INT AUTO_INCREMENT PRIMARY KEY,
    StudentId INT NOT NULL,
    ClassId INT NOT NULL,
    FOREIGN KEY (StudentId) REFERENCES User(UserId),
    FOREIGN KEY (ClassId) REFERENCES Class(ClassId)
);

CREATE TABLE WaitList (
    WaitListId INT AUTO_INCREMENT PRIMARY KEY,
    ClassId INT NOT NULL,
    StudentId INT NOT NULL,
    Sequence INT NOT NULL,
    FOREIGN KEY (ClassId) REFERENCES Class(ClassId),
    FOREIGN KEY (StudentId) REFERENCES User(UserId)
);

