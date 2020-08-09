-- SET FOREIGN_KEY_CHECKS = 0;
-- Drop APPs tables
DROP TABLE IF EXISTS gre;
DROP TABLE IF EXISTS gresubject;
DROP TABLE IF EXISTS toefl;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS recRating;
DROP TABLE IF EXISTS recLet;
DROP TABLE IF EXISTS application;
DROP TABLE IF EXISTS applicant;
-- More drops
DROP TABLE IF EXISTS inbox;
DROP TABLE IF EXISTS messaging;
DROP TABLE IF EXISTS form1;
DROP TABLE IF EXISTS takes;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS teaches CASCADE;
DROP TABLE IF EXISTS faculty;
DROP TABLE IF EXISTS advises;
DROP TABLE IF EXISTS GS;
DROP TABLE IF EXISTS allusers;
DROP TABLE IF EXISTS rtf;

-- SET FOREIGN_KEY_CHECKS = 0;

-- All users, identified by acctype as different user previlages
-- 1 = admin, 2 = GS, 3 = CAC, 4 = registrar, 5 = faculty reviewer
-- 6 = faculty advisor, 7 = instructor, 8 = students, 9 = alumni

create table allusers(
    ID int auto_increment,
    password varchar(20),
    acctype int,
    type varchar(20),
    fname varchar(40),
    lname varchar(40),
    email varchar(45),
    address varchar(80),
    city varchar(20),
    state varchar(20),
    birthdate DATE,
    picture varchar(60),
    SSN varchar(9) UNIQUE,
    semester varchar(11),
    year int,
    primary key(ID)
);

create table students (
    SID int,
    form1pass int,
    registrationHold int,
    thesis int,
    foreign key(SID) references allusers(ID),
    primary key(SID)
);

create table courses (
    CID varchar(10),
    start_time varchar(5),
    end_time varchar(5),
    credits INT(4),
    semester varchar(11),
    title varchar(30),
    day varchar(1),
    prerequisite1Id varchar(10),
    prerequisite2Id varchar(10),
    p1num int(11),
    p1dep varchar(45),
    p2num int(11),
    p2dep varchar(45),
    cnum int (11),
    department varchar(45),
    capacity int, 
    primary key(CID, semester)
);

create table form1 (
    f1_id int(11),
    f1_cnum int(11),
    f1_dep varchar(45),
    foreign key(f1_id) REFERENCES allusers(ID),
    primary key(f1_id, f1_cnum, f1_dep)
);

create table takes (
    grade varchar(2),
    semester varchar(11),
    courseID varchar(10),
    studentID int,
    cnum int(11),
    cdept varchar(45),
    foreign key(studentID) references allusers(ID),
    foreign key(courseID, semester) references courses(CID, semester),
    primary key(courseID, studentID, semester)
);

create table faculty (
    FID int,
    primary key(FID),
    FOREIGN key(FID) references allusers(ID)
);

create table advises (
    f_id int(11),
    s_id int(11),
    gradstatus int(1),
    foreign key(f_id) REFERENCES allusers(ID),
    foreign key(s_id) REFERENCES allusers(ID),
    primary key(f_id, s_id)
);

create table GS (
    GSID int,
    primary key(GSID),
    FOREIGN key(GSID) references allusers(ID)
);

create table teaches (
    facultyID int,
    semester varchar(11),
    courseID varchar(10),
    primary key(facultyID, semester, courseID),
    foreign key(facultyID) references faculty(FID)
);

create table messaging(
    mid int(11) AUTO_INCREMENT,
    subject varchar(45),
    email varchar(45),
    body TEXT,
    timestamp DATETIME,
    primary key(mid)
);

create table inbox(
    inboxID int,
    sender int,
    selfrecord int,
    msg_ID int(11),
    foreign key(inboxID) references allusers(ID),
    foreign key(sender) references allusers(ID),
    foreign key(selfrecord) references allusers(ID),
    foreign key(msg_ID) references messaging(mid),
    primary key(inboxID, msg_ID)
);

-- Apps Exclusive Tables:
-- Application and Applicant
CREATE TABLE applicant (
    uid VARCHAR(8),
    username VARCHAR(15) UNIQUE,
    password VARCHAR(40),
    fname VARCHAR(32) DEFAULT NULL,
    lname VARCHAR(32) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    dobirth DATE DEFAULT NULL,
    ssn VARCHAR(9) UNIQUE,
    address VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (uid)
);

CREATE TABLE application (
    aid VARCHAR(8),
    uid VARCHAR(8),
    trReced BOOLEAN,
    transcript VARCHAR(64),
    applyfor VARCHAR(3),
    priorDegree1 VARCHAR(2),
    pd1Place VARCHAR(64),
    pd1GPA VARCHAR(5),
    priorDegree2 VARCHAR(2),
    pd2Place VARCHAR(64),
    pd2GPA VARCHAR(5),
    priorExp VARCHAR(256),
    interest VARCHAR(256),
    admitYear YEAR,
    admitSemes VARCHAR(7),
    complete BOOLEAN,
    submit BOOLEAN,
    finaldeci INT(1),
    recomAdv int,
    reason VARCHAR(1),
    dateSubmission DATETIME,
    email1 VARCHAR(100),
    email2 VARCHAR(100),
    email3 VARCHAR(100),
    recName1 VARCHAR(64),
    recName2 VARCHAR(64),
    recName3 VARCHAR(64),
    PRIMARY KEY (aid),
    FOREIGN KEY (uid) REFERENCES applicant(uid),
    FOREIGN KEY (recomAdv) REFERENCES allusers(ID)
);

-- Exams
CREATE TABLE gre (
    aid VARCHAR(8),
    verbalscore INT(4),
    quantscore INT(4),
    year YEAR,
    PRIMARY KEY (aid),
    FOREIGN KEY (aid) REFERENCES application(aid)
);

CREATE TABLE gresubject (
    aid VARCHAR(8),
    subject VARCHAR(12),
    score INT(4),
    year YEAR,
    PRIMARY KEY (aid),
    FOREIGN KEY (aid) REFERENCES application(aid)
);

CREATE TABLE toefl (
    aid VARCHAR(8),
    score INT(4),
    year YEAR,
    PRIMARY KEY (aid),
    FOREIGN KEY (aid) REFERENCES application(aid)
);

-- Review & Recommendation
CREATE TABLE review (
    date DATETIME,
    sid int,
    aid VARCHAR(8),
    rating INT(1),
    comments VARCHAR(255),
    courseDef VARCHAR(255),
    PRIMARY KEY (sid, aid),
    FOREIGN KEY (aid) REFERENCES application(aid),
    FOREIGN KEY (sid) REFERENCES allusers(ID)
);

CREATE TABLE recLet (
    recid VARCHAR(8),
    aid VARCHAR(8),
    date DATETIME,
    recName VARCHAR(64),
    recLink VARCHAR(1000),
    PRIMARY KEY (recid, aid),
    FOREIGN KEY (aid) REFERENCES application(aid)
);

CREATE TABLE recRating (
    recid VARCHAR(8),
    sid int,
    rating INT(1),
    generic BOOLEAN,
    credible BOOLEAN,
    PRIMARY KEY (recid, sid),
    FOREIGN KEY (recid) REFERENCES recLet(recid),
    FOREIGN KEY (sid) REFERENCES allusers(ID)
);

CREATE TABLE rtf (
sid int,
cid varchar(10),
sem varchar(11),
reason varchar(200)

);


-- Applicants Insert
INSERT INTO applicant VALUES ('15555555', 'johnl', '1234', 'John', 'Lennon', 'wxyowen@gwu.edu' ,'2000-01-01', '111111111', '111 23rd ST NW RM 111');
INSERT INTO applicant VALUES ('66666666', 'ringos', '1234', 'Ringo', 'Starr', 'wxyowen@gwu.edu' ,'1999-01-01', '222111111', '212 22nd ST NW RM 222');
INSERT INTO applicant VALUES ('00001234', 'louisa', '9876', 'Louis', 'Armstrong', 'wxyowen@gwu.edu' ,'1995-10-10', '555111111', '515 21st ST NW RM 555');
INSERT INTO applicant VALUES ('00001235', 'arethaf', '9876', 'Aretha', 'Franklin', 'wxyowen@gwu.edu' ,'1993-05-05', '666111111', '616 20th ST NW RM 666');
INSERT INTO applicant VALUES ('00001236', 'carloss', '0000', 'Carlos', 'Santana', 'wxyowen@gwu.edu' ,'1993-06-06', '777111111', '717 27th ST S RM 777');
INSERT INTO applicant VALUES ('00001237', 'wxyowen', '1234', 'Owen', 'Wu', 'wxyowen@gwu.edu' ,'1998-10-01', '779112112', '616 23rd ST S RM 777');
-- Current Students' applicants
INSERT INTO applicant VALUES ('88888888', 'billieh', '0000', 'Billie', 'Holiday', 'wxyowen@gwu.edu' ,'1994-06-07', '888111111', '717 27th ST S RM 777');
INSERT INTO applicant VALUES ('99999999', 'dianak', '0000', 'Diana', 'Krall', 'wxyowen@gwu.edu' ,'1995-06-08', '999111111', '717 27th ST S RM 888');
INSERT INTO applicant VALUES ('23456789', 'ellaf', '0000', 'Ella', 'Fitzgerald', 'wxyowen@gwu.edu' ,'1993-06-09', '111000111', '717 27th ST S RM 999');
INSERT INTO applicant VALUES ('87654321', 'evac', '0000', 'Eva', 'Cassidy', 'wxyowen@gwu.edu' ,'1993-06-10', '222000111', '717 27th ST S RM 121');
INSERT INTO applicant VALUES ('45678901', 'jimih', '0000', 'Jimi', 'Hendrix', 'wxyowen@gwu.edu' ,'1993-06-11', '333000111', '717 27th ST S RM 212');
INSERT INTO applicant VALUES ('14444444', 'paulm', '0000', 'Paul', 'McCartney', 'wxyowen@gwu.edu' ,'1993-06-12', '444000111', '717 27th ST S RM 313');
INSERT INTO applicant VALUES ('16666666', 'georgeh', '0000', 'George', 'Harrison', 'wxyowen@gwu.edu' ,'1993-06-13', '555000111', '717 27th ST S RM 434');
INSERT INTO applicant VALUES ('12345678', 'stevien', '0000', 'Stevie', 'Nicks', 'wxyowen@gwu.edu' ,'1993-06-14', '666000111', '717 27th ST S RM 545');

-- Current Students' allusers
INSERT INTO allusers VALUES (88888888, 'hoBill', 8, 'masters', 'Billie', 'Holiday', 'bHoliday@gwu.edu', '717 27th ST S RM 777', 'Washington', 'DC', '1994-06-07', 'bHol.png', '888111111', 'Fall', 2018);
INSERT INTO allusers VALUES (99999999, 'krDiana', 8, 'masters', 'Diana', 'Krall', 'dKrall@gwu.edu', '717 27th ST S RM 888', 'Washington', 'DC', '1995-06-08', 'krall.png', '999111111', 'Fall', 2019);
INSERT INTO allusers VALUES (23456789, 'eFitz', 8, 'doctorate', 'Ella', 'Fitzgerald', 'eFitz@gwu.edu', '717 27th ST S RM 999', 'Washington', 'DC', '1993-06-09', 'eFitz.png', '111000111', 'Fall', 2019);
INSERT INTO allusers VALUES (87654321, 'eCass', 8, 'masters', 'Eva', 'Cassidy', 'eCass@gwu.edu', '717 27th ST S RM 121', 'Washington', 'DC', '1993-06-10', 'eCass.png', '222000111', 'Fall', 2017);
INSERT INTO allusers VALUES (45678901, 'jHen', 8, 'masters', 'Jimi', 'Hendrix', 'jHendrix@gwu.edu', '717 27th ST S RM 212', 'Washington', 'DC', '1993-06-11', 'jHen.png', '333000111', 'Fall', 2017);
INSERT INTO allusers VALUES (14444444, 'pCart', 8, 'masters', 'Paul', 'McCartney', 'pCart@gwu.edu', '717 27th ST S RM 313', 'Washington', 'DC', '1993-06-12', 'pCart.png', '444000111', 'Fall', 2017);
INSERT INTO allusers VALUES (16666666, 'gHarr', 8, 'masters', 'George', 'Harrison', 'gHarr@gwu.edu', '717 27th ST S RM 434', 'Washington', 'DC', '1994-06-13', 'gHar.png', '555000111', 'Fall', 2016);
INSERT INTO allusers VALUES (12345678, 'sNicks', 8, 'doctorate', 'Stevie', 'Nicks', 'sNicks@gwu.edu', '717 27th ST S RM 545', 'Washington', 'DC', '1993-06-14', 'sNic.png', '666000111', 'Fall', 2017);
-- Current Students
INSERT INTO students VALUES (88888888, 0, 1, NULL);
INSERT INTO students VALUES (99999999, 0, 0, NULL);
INSERT INTO students VALUES (23456789, 0, 0, 0);
INSERT INTO students VALUES (87654321, 1, 1, NULL);
INSERT INTO students VALUES (45678901, 0, 0, NULL);
INSERT INTO students VALUES (14444444, 1, 0, NULL);
INSERT INTO students VALUES (16666666, 0, 0, NULL);
INSERT INTO students VALUES (12345678, 1, 0, 0);

-- Current Alumni allusers
INSERT INTO allusers VALUES (77777777, 'eClap', 9, 'masters', 'Eric', 'Clapton', 'eClap@gwu.edu', '717 27th ST S RM 732', 'Washington', 'DC', '1945-03-30', 'eClap.png', '123000321', 'Fall', 2014);
INSERT INTO allusers VALUES (34567890, 'cKurt', 9, 'doctorate', 'Kurt', 'Colbain', 'cKurt@gwu.edu', '717 27th ST S RM 237', 'Washington', 'DC', '1967-02-27', 'cKurt.png', '321000123', 'Fall', 2015);

-- Past Courses for Alumni
INSERT INTO courses VALUES ('CSCI_6221', '1500', '1730', 3, 'Fall 2012', 'SW Paradigms', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6221, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6212', '1500', '1730', 3, 'Fall 2012', 'Algorithims', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6212, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6461', '1500', '1730', 3, 'Fall 2012', 'Computer Architecture', 'T', NULL, NULL, NULL, NULL, NULL, NULL,  6461, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6232', '1800', '2030', 3, 'Spring 2013', 'Networks 1', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6232, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6283', '1800', '2030', 3, 'Spring 2013', 'Security 1', 'T','CSCI_6212', NULL, 6212, 'CSCI',  NULL, NULL,  6283, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6233', '1800', '2030', 3, 'Fall 2013', 'Networks 2', 'T', 'CSCI_6232', NULL, 6232, 'CSCI', NULL, NULL,  6233, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6284', '1800', '2030', 3, 'Fall 2013', 'Cryptography', 'T', 'CSCI_6212', NULL, 6212, 'CSCI', NULL, NULL,  6284, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6241', '1800', '2030', 3, 'Fall 2013', 'Database 1', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6286', '1800', '2030', 3, 'Spring 2014', 'Network Security', 'T','CSCI_6283', 'CSCI_6232', 6283, 'CSCI', 6232, 'CSCI', 6286, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6242', '1800', '2030', 3, 'Spring 2014', 'Database 2', 'T','CSCI_6241', NULL, 6241, 'CSCI', NULL, NULL,  6242, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6251', '1500', '1730', 3, 'Fall 2015', 'Cloud Computing', 'T','CSCI_6461', NULL, 6461, 'CSCI', NULL, NULL,  6251, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6254', '1500', '1730', 3, 'Fall 2015', 'SW Engineering', 'T','CSCI_6221', NULL, 6221, 'CSCI', NULL, NULL,  6254, 'CSCI', 2);

-- Eric Clapton Transcript
INSERT INTO takes VALUES ('B', 'Fall 2012', 'CSCI_6221', 77777777, 6221, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2012', 'CSCI_6212', 77777777, 6212, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2012', 'CSCI_6461', 77777777, 6461, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2013', 'CSCI_6232', 77777777, 6232, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2013', 'CSCI_6283', 77777777, 6283, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2013', 'CSCI_6233', 77777777, 6233, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2013', 'CSCI_6284', 77777777, 6284, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2013', 'CSCI_6241', 77777777, 6241, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2014', 'CSCI_6286', 77777777, 6286, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2014', 'CSCI_6242', 77777777, 6242, 'CSCI');

-- Kurt Colbain Transcript
INSERT INTO takes VALUES ('A', 'Fall 2012', 'CSCI_6221', 34567890, 6221, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2012', 'CSCI_6212', 34567890, 6212, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2012', 'CSCI_6461', 34567890, 6461, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2013', 'CSCI_6232', 34567890, 6232, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2013', 'CSCI_6283', 34567890, 6283, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2013', 'CSCI_6233', 34567890, 6233, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2013', 'CSCI_6284', 34567890, 6284, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2013', 'CSCI_6241', 34567890, 6241, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2014', 'CSCI_6286', 34567890, 6286, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2014', 'CSCI_6242', 34567890, 6242, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2015', 'CSCI_6251', 34567890, 6251, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2015', 'CSCI_6254', 34567890, 6254, 'CSCI');

-- Application
INSERT INTO application VALUES ('30000001','15555555',TRUE,'30000001_Transcript.pdf','MS','B','Wild Rooster University','3.02','','',0,'None','Algorithms','2020','Fall',TRUE,TRUE, NULL,NULL,'', '2020-02-20','','','','','','');
INSERT INTO application VALUES ('30000002','66666666',FALSE,'30000002_Transcript.pdf','MS','B','Wild Chicken University','3.08','','',0,'None','Networks','2020','Fall',FALSE,TRUE, NULL,NULL,'','2020-01-31','','','','','','');
INSERT INTO application VALUES ('30000003','00001234',TRUE,'30000003_Transcript.pdf','MS','B','Wild Pig University','3.55','','',0,'None','EE','2017','Spring',TRUE,TRUE, 0,NULL,'D', '2016-11-30','','','','','','');
INSERT INTO application VALUES ('30000004','00001235',TRUE,'30000004_Transcript.pdf','MS','B','Wild Human University','3.84','','',0,'None','ME','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');
INSERT INTO application VALUES ('30000005','00001236',TRUE,'30000005_Transcript.pdf','PhD','B','Wild Dog University','3.99','MS','Smart Human University','3.98','None','CS','2017','Fall',TRUE,TRUE, 2,NULL,'','2017-01-22','','','','','','');
INSERT INTO application VALUES ('30000099','00001237',TRUE,'30000005_Transcript.pdf','PhD','B','Wild Dog University','3.99','MS','Smart Human University','3.98','None','CS','2020','Fall',FALSE,FALSE, NULL ,NULL,'','2019-01-22','','','','','','');
-- Current Students' applications
INSERT INTO application VALUES ('30000006','88888888',TRUE,'30000006_Transcript.pdf','MS','B','Wild Rooster University','3.48','','',0,'None','ME','2018','Fall',TRUE,TRUE, 1,NULL,'','2017-11-30','','','','','','');
INSERT INTO application VALUES ('30000007','99999999',TRUE,'30000007_Transcript.pdf','MS','B','Wild Chicken University','3.58','','',0,'None','CS','2019','Fall',TRUE,TRUE, 1,NULL,'','2018-11-30','','','','','','');
INSERT INTO application VALUES ('30000008','23456789',TRUE,'30000008_Transcript.pdf','PhD','B','Wild Pig University','3.68','MS','Smart Human University',0,'None','EE','2019','Fall',TRUE,TRUE, 1,NULL,'','2018-11-22','','','','','','');
INSERT INTO application VALUES ('30000009','87654321',TRUE,'30000009_Transcript.pdf','MS','B','Wild Rooster University','3.78','','',0,'None','BME','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');
INSERT INTO application VALUES ('30000010','45678901',TRUE,'30000010_Transcript.pdf','MS','B','Wild Chicken University','3.88','','',0,'None','CS','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');
INSERT INTO application VALUES ('30000011','14444444',TRUE,'30000011_Transcript.pdf','MS','B','Wild Pig University','3.98','','',0,'None','BME','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');
INSERT INTO application VALUES ('30000012','16666666',TRUE,'30000012_Transcript.pdf','MS','B','Wild Rooster University','3.08','','',0,'None','ES','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');
INSERT INTO application VALUES ('30000013','12345678',TRUE,'30000013_Transcript.pdf','PhD','B','Wild Chicken University','3.18','MS','Smart Human University',0,'None','CS','2017','Fall',TRUE,TRUE, 1,NULL,'','2017-01-30','','','','','','');

INSERT INTO gre VALUES ('30000003',130,140,2015);
INSERT INTO gre VALUES ('30000004',150,160,2015);
INSERT INTO gre VALUES ('30000005',150,160,2016);
INSERT INTO gre VALUES ('30000099',170,170,2016);
INSERT INTO gre VALUES ('30000006',155,165,2017);
INSERT INTO gre VALUES ('30000007',145,168,2018);
INSERT INTO gre VALUES ('30000008',165,155,2017);
INSERT INTO gre VALUES ('30000009',170,158,2015);
INSERT INTO gre VALUES ('30000010',150,140,2016);
INSERT INTO gre VALUES ('30000011',135,100,2016);
INSERT INTO gre VALUES ('30000012',150,120,2016);
INSERT INTO gre VALUES ('30000013',145,150,2015);

-- AID 30000001's exams
INSERT INTO gre VALUES ('30000001',150,160,2019);
INSERT INTO gresubject VALUES ('30000001','MATH', 100,2019);
INSERT INTO toefl VALUES ('30000001',115,2018);

-- AID 30000002's exams
INSERT INTO gre VALUES ('30000002',155,165,2019);
INSERT INTO gresubject VALUES ('30000002','CS', 100,2018);
INSERT INTO toefl VALUES ('30000002',110,2018);

-- Users insert
-- 1 = admin, 2 = GS, 3 = CAC, 4 = registrar, 5 = faculty reviewer
-- 6 = faculty advisor, 7 = instructor, 8 = students
INSERT INTO allusers VALUES (00000001,'admin',1,NULL,'a lot of','power','alotofpower@gmail.com','the white house','DC','DC','0000-01-01','newacc.png','000000000', NULL, NULL);
INSERT INTO allusers VALUES (00000011,'imgs',2,NULL,'Graduate','Secretary','graduateS@gmail.com','800 23rd ST NW','DC','DC','1950-05-05','newacc.png','000000033', NULL, NULL);
INSERT INTO allusers VALUES (00000002,'imcac',3,NULL,'I am','CAC','iamCAC@gmail.com','803 21st ST NW','DC','DC','1955-12-05','newacc.png','000000044', NULL, NULL);
INSERT INTO allusers VALUES (00000003,'imreg',4,NULL,'Some','Power','somepower@gmail.com','805 27th ST NW','DC','DC','1966-11-11','newacc.png','000000055', NULL, NULL);
INSERT INTO allusers VALUES (00000004,'4321',567,NULL,'Bhagi','Narahari','bnarahari@gwu.edu','555 12th ST S','Arlington','VA','1999-10-05','bhagi.png','000000066', NULL, NULL);
INSERT INTO allusers VALUES (00000005,'4321',7,NULL,'Hyeong-Ah','Choi','hchoi@gwu.edu','666 13th ST S','Arlington','VA','1988-10-05','choi.png','000000077', NULL, NULL);
INSERT INTO allusers VALUES (00000006,'4321',6,NULL,'Gabe','Parmer','gparmer@gwu.edu','777 14th ST S','Arlington','VA','1977-10-05','gabe.png','000000088', NULL, NULL);
INSERT INTO allusers VALUES (00000007,'4321',567,NULL,'Tim','Wood','timwood@gwu.edu','888 15th ST S','Arlington','VA','1989-10-05','wood.png','000000099', NULL, NULL);
INSERT INTO allusers VALUES (00000008,'4321',56,NULL,'Shelly','Heller','sheller@gwu.edu','999 16th ST E','Arlington','VA','1976-10-05','heller.png','000000011', NULL,NULL);
INSERT INTO allusers VALUES (00000009,'4321',6,NULL,'Sarah','Morin','smorin@gwu.edu','111 17th ST SE','Rockville','MD','1996-10-05','morin.png','000000022', NULL, NULL);
INSERT INTO allusers VALUES (00000010,'4321',7,NULL,'Kevin','Deems','kdeems@gwu.edu','222 18th ST SW','Fairfax','VA','1997-10-05','deems.png','000000111', NULL, NULL);

-- Insert GS
INSERT INTO GS VALUES (00000011);

-- Insert into faculty
INSERT INTO faculty VALUES (00000004);
INSERT INTO faculty VALUES (00000005);
INSERT INTO faculty VALUES (00000006);
INSERT INTO faculty VALUES (00000007);
INSERT INTO faculty VALUES (00000008);
INSERT INTO faculty VALUES (00000009);
INSERT INTO faculty VALUES (00000010);

-- Faculty Advisor relations
INSERT INTO advises VALUES (00000009, 88888888, 0);
INSERT INTO advises VALUES (00000006, 99999999, 0);
INSERT INTO advises VALUES (00000004, 23456789, 0);
INSERT INTO advises VALUES (00000008, 87654321, 0);
INSERT INTO advises VALUES (00000007, 45678901, 0);
INSERT INTO advises VALUES (00000004, 14444444, 0);
INSERT INTO advises VALUES (00000007, 16666666, 0);
INSERT INTO advises VALUES (00000009, 12345678, 0);

 -- Current Semester Courses
INSERT INTO courses VALUES ('CSCI_6221', '1500', '1730', 3, 'Spring 2021', 'SW Paradigms', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6221, 'CSCI', 0);

INSERT INTO courses VALUES ('CSCI_6461', '1500', '1730', 3, 'Spring 2021', 'Computer Architecture', 'T', NULL, NULL, NULL, NULL, NULL, NULL,  6461, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6212', '1500', '1730', 3, 'Spring 2021', 'Algorithims', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6212, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6220', '1500', '1730', 3, 'Spring 2021', 'Machine Learning', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6220, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6232', '1800', '2030', 3, 'Spring 2021', 'Networks 1', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6232, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6233', '1800', '2030', 3, 'Spring 2021', 'Networks 2', 'T', 'CSCI_6232', NULL, 6232, 'CSCI', NULL, NULL,  6233, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6241', '1800', '2030', 3, 'Spring 2021', 'Database 1', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6242', '1800', '2030', 3, 'Spring 2021', 'Database 2', 'Z','CSCI_6241', NULL, 6241, 'CSCI', NULL, NULL,  6242, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6246', '1500', '1730', 3, 'Spring 2021', 'Compilers', 'T','CSCI_6461', 'CSCI_6212', 6461, 'CSCI' , 6212, 'CSCI',  6246, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6260', '1800', '2030', 3, 'Spring 2021', 'Multimedia', 'Z', NULL, NULL, NULL, NULL, NULL, NULL,  6260, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6251', '1500', '1730', 3, 'Spring 2021', 'Cloud Computing', 'M','CSCI_6461', NULL, 6461, 'CSCI', NULL, NULL,  6251, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6254', '1500', '1730', 3, 'Spring 2021', 'SW Engineering', 'M','CSCI_6221', NULL, 6221, 'CSCI', NULL, NULL,  6254, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6262', '1800', '2030', 3, 'Spring 2021', 'Graphics 1', 'W',  NULL, NULL, NULL, NULL, NULL, NULL,  6262, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6283', '1800', '2030', 3, 'Spring 2021', 'Security 1', 'T','CSCI_6212', NULL, 6212, 'CSCI',  NULL, NULL,  6283, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6284', '1800', '2030', 3, 'Spring 2021', 'Cryptography', 'M', 'CSCI_6212', NULL, 6212, 'CSCI', NULL, NULL,  6284, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6286', '1800', '2030', 3, 'Spring 2021', 'Network Security', 'W','CSCI_6283', 'CSCI_6232', 6283, 'CSCI', 6232, 'CSCI', 6286, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6325', '1800', '2030', 3, 'Spring 2021', 'Algorithims 2', 'Z','CSCI_6212', NULL, 6212, 'CSCI', NULL, NULL,  6325, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6339', '1600', '1830', 3, 'Spring 2021', 'Embdedded Systems', 'Z','CSCI_6461', 'CSCI_6212', 6461, 'CSCI', 6212, 'CSCI',  6339, 'CSCI', 3);

INSERT INTO courses VALUES ('CSCI_6384', '1500', '1730', 3, 'Spring 2021', 'Cryptography 2', 'W','CSCI_6284', NULL, 6284, 'CSCI', NULL, NULL,  6384, 'CSCI', 2);

INSERT INTO courses VALUES ('ECE_6241', '1800', '2030', 3, 'Spring 2021', 'Communication Theory', 'M',  NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'ECE', 2);

INSERT INTO courses VALUES ('ECE_6242', '1800', '2030', 2, 'Spring 2021', 'Information Theory', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6242, 'ECE', 2);

INSERT INTO courses VALUES ('MATH_6210', '1800', '2030', 2, 'Spring 2021', 'Logic', 'W',  NULL, NULL, NULL, NULL, NULL, NULL,  6210, 'MATH', 2);
-- Past Courses
INSERT INTO courses VALUES ('CSCI_6221', '1500', '1730', 3, 'Fall 2017', 'SW Paradigms', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6221, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6212', '1500', '1730', 3, 'Fall 2017', 'Algorithims', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6212, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6461', '1500', '1730', 3, 'Fall 2017', 'Computer Architecture', 'T', NULL, NULL, NULL, NULL, NULL, NULL,  6461, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6232', '1800', '2030', 3, 'Spring 2018', 'Networks 1', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6232, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6283', '1800', '2030', 3, 'Spring 2018', 'Security 1', 'T','CSCI_6212', NULL, 6212, 'CSCI',  NULL, NULL,  6283, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6233', '1800', '2030', 3, 'Fall 2018', 'Networks 2', 'T', 'CSCI_6232', NULL, 6232, 'CSCI', NULL, NULL,  6233, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6286', '1800', '2030', 3, 'Fall 2018', 'Network Security', 'T','CSCI_6283', 'CSCI_6232', 6283, 'CSCI', 6232, 'CSCI', 6286, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6241', '1800', '2030', 3, 'Fall 2018', 'Database 1', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6246', '1500', '1730', 3, 'Spring 2019', 'Compilers', 'T','CSCI_6461', 'CSCI_6212', 6461, 'CSCI' , 6212, 'CSCI',  6246, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6262', '1800', '2030', 3, 'Spring 2019', 'Graphics 1', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6262, 'CSCI', 2);

INSERT INTO courses VALUES ('ECE_6241', '1800', '2030', 3, 'Spring 2019', 'Communication Theory', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'ECE', 2);
INSERT INTO courses VALUES ('ECE_6242', '1800', '2030', 2, 'Spring 2019', 'Information Theory', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6242, 'ECE', 2);
INSERT INTO courses VALUES ('MATH_6210', '1800', '2030', 2, 'Spring 2019', 'Logic', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6210, 'MATH', 2);

INSERT INTO courses VALUES ('CSCI_6241', '1800', '2030', 3, 'Spring 2018', 'Database 1', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6246', '1500', '1730', 3, 'Fall 2018', 'Compilers', 'T','CSCI_6461', 'CSCI_6212', 6461, 'CSCI' , 6212, 'CSCI',  6246, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6242', '1800', '2030', 3, 'Spring 2019', 'Database 2', 'T','CSCI_6241', NULL, 6241, 'CSCI', NULL, NULL,  6242, 'CSCI', 2);

INSERT INTO courses VALUES ('CSCI_6221', '1500', '1730', 3, 'Fall 2016', 'SW Paradigms', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6221, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6212', '1500', '1730', 3, 'Fall 2016', 'Algorithims', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6212, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6461', '1500', '1730', 3, 'Fall 2016', 'Computer Architecture', 'T', NULL, NULL, NULL, NULL, NULL, NULL,  6461, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6232', '1800', '2030', 3, 'Spring 2017', 'Networks 1', 'M', NULL, NULL, NULL, NULL, NULL, NULL,  6232, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6283', '1800', '2030', 3, 'Spring 2017', 'Security 1', 'T','CSCI_6212', NULL, 6212, 'CSCI',  NULL, NULL,  6283, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6233', '1800', '2030', 3, 'Fall 2017', 'Networks 2', 'T', 'CSCI_6232', NULL, 6232, 'CSCI', NULL, NULL,  6233, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6284', '1800', '2030', 3, 'Fall 2017', 'Cryptography', 'T', 'CSCI_6212', NULL, 6212, 'CSCI', NULL, NULL,  6284, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6241', '1800', '2030', 3, 'Fall 2017', 'Database 1', 'W', NULL, NULL, NULL, NULL, NULL, NULL,  6241, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6242', '1800', '2030', 3, 'Spring 2018', 'Database 2', 'T','CSCI_6241', NULL, 6241, 'CSCI', NULL, NULL,  6242, 'CSCI', 2);
INSERT INTO courses VALUES ('ECE_6242', '1800', '2030', 2, 'Spring 2018', 'Information Theory', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6242, 'ECE', 2);

INSERT INTO courses VALUES ('CSCI_6262', '1800', '2030', 3, 'Spring 2018', 'Graphics 1', 'T',  NULL, NULL, NULL, NULL, NULL, NULL,  6262, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6284', '1800', '2030', 3, 'Fall 2018', 'Cryptography', 'T', 'CSCI_6212', NULL, 6212, 'CSCI', NULL, NULL,  6284, 'CSCI', 2);
INSERT INTO courses VALUES ('CSCI_6286', '1800', '2030', 3, 'Spring 2019', 'Network Security', 'T','CSCI_6283', 'CSCI_6232', 6283, 'CSCI', 6232, 'CSCI', 6286, 'CSCI', 2);

-- Billie Holiday takes
INSERT INTO takes VALUES ('IP', 'Spring 2021', 'CSCI_6461', 88888888, 6461, 'CSCI');
INSERT INTO takes VALUES ('IP', 'Spring 2021', 'CSCI_6212', 88888888, 6212, 'CSCI');

-- Eva Cassidy takes
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6221', 87654321, 6221, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6212', 87654321, 6212, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6461', 87654321, 6461, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6232', 87654321, 6232, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6283', 87654321, 6283, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6233', 87654321, 6233, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6286', 87654321, 6286, 'CSCI');
INSERT INTO takes VALUES ('C', 'Fall 2018', 'CSCI_6241', 87654321, 6241, 'CSCI');
INSERT INTO takes VALUES ('C', 'Spring 2019', 'CSCI_6246', 87654321, 6246, 'CSCI');
INSERT INTO takes VALUES ('C', 'Spring 2019', 'CSCI_6262', 87654321, 6262, 'CSCI');

-- Eva Cassidy Form 1
INSERT INTO form1 VALUES (87654321, 6221, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6212, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6461, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6232, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6283, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6233, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6286, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6241, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6246, 'CSCI');
INSERT INTO form1 VALUES (87654321, 6262, 'CSCI');

-- Jimi Hendrix takes
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6221', 45678901, 6221, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6212', 45678901, 6212, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6461', 45678901, 6461, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6232', 45678901, 6232, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6283', 45678901, 6283, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6233', 45678901, 6233, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6286', 45678901, 6286, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6241', 45678901, 6241, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'ECE_6241', 45678901, 6241, 'ECE');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'ECE_6242', 45678901, 6242, 'ECE');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'MATH_6210', 45678901, 6210, 'MATH');

-- Paul McCartney takes
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6221', 14444444, 6221, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6212', 14444444, 6212, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6461', 14444444, 6461, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6232', 14444444, 6232, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2018', 'CSCI_6241', 14444444, 6241, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2018', 'CSCI_6283', 14444444, 6283, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6233', 14444444, 6233, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2018', 'CSCI_6246', 14444444, 6246, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'CSCI_6262', 14444444, 6262, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'CSCI_6242', 14444444, 6242, 'CSCI');

-- Paul McCartney Form 1
INSERT INTO form1 VALUES (14444444, 6221, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6212, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6461, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6232, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6241, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6283, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6233, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6246, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6262, 'CSCI');
INSERT INTO form1 VALUES (14444444, 6242, 'CSCI');

-- George Harrison takes
INSERT INTO takes VALUES ('B', 'Fall 2016', 'CSCI_6221', 16666666, 6221, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2016', 'CSCI_6212', 16666666, 6212, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2016', 'CSCI_6461', 16666666, 6461, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2017', 'CSCI_6232', 16666666, 6232, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2017', 'CSCI_6283', 16666666, 6283, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2017', 'CSCI_6233', 16666666, 6233, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2017', 'CSCI_6284', 16666666, 6284, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2017', 'CSCI_6241', 16666666, 6241, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2018', 'CSCI_6242', 16666666, 6242, 'CSCI');
INSERT INTO takes VALUES ('C', 'Spring 2018', 'ECE_6242', 16666666, 6242, 'ECE');

-- Stevie Nicks takes
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6221', 12345678, 6221, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6212', 12345678, 6212, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2017', 'CSCI_6461', 12345678, 6461, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2018', 'CSCI_6232', 12345678, 6232, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2018', 'CSCI_6283', 12345678, 6283, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2018', 'CSCI_6262', 12345678, 6262, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6233', 12345678, 6233, 'CSCI');
INSERT INTO takes VALUES ('A', 'Fall 2018', 'CSCI_6284', 12345678, 6284, 'CSCI');
INSERT INTO takes VALUES ('B', 'Fall 2018', 'CSCI_6241', 12345678, 6241, 'CSCI');
INSERT INTO takes VALUES ('A', 'Spring 2019', 'CSCI_6286', 12345678, 6286, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'CSCI_6246', 12345678, 6246, 'CSCI');
INSERT INTO takes VALUES ('B', 'Spring 2019', 'CSCI_6242', 12345678, 6242, 'CSCI');

-- Stevie Nicks Form 1
INSERT INTO form1 VALUES (12345678, 6221, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6212, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6461, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6232, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6283, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6262, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6233, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6284, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6241, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6286, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6246, 'CSCI');
INSERT INTO form1 VALUES (12345678, 6242, 'CSCI');

-- Teachers
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6221");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6461");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6212");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6220");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6232");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6241");
INSERT INTO teaches
VALUES('00000004', "Spring 2021", "CSCI_6242");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6246");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6260");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6233");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6251");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6254");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6262");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6283");
INSERT INTO teaches
VALUES('00000005', "Spring 2021", "CSCI_6284");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "CSCI_6286");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "CSCI_6325");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "CSCI_6339");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "CSCI_6384");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "ECE_6241");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "ECE_6242");
INSERT INTO teaches
VALUES('000000010', "Spring 2021", "MATH_6210");


