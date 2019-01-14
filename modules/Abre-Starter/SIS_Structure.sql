-- Database Manager 4.2.5 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Abre_AD`;
CREATE TABLE `Abre_AD` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` text NOT NULL,
  `StudentID` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_AIRData`;
CREATE TABLE `Abre_AIRData` (
  `Assessmenttype` text NOT NULL,
  `Administration` text NOT NULL,
  `SSID` text NOT NULL,
  `LocalID` text NOT NULL,
  `LastName` text NOT NULL,
  `FirstName` text NOT NULL,
  `MiddleName` text NOT NULL,
  `Dateofbirth` text NOT NULL,
  `Gender` text NOT NULL,
  `EnrolledGrade` text NOT NULL,
  `Ethnicity` text NOT NULL,
  `LEP` text NOT NULL,
  `Migrant` text NOT NULL,
  `GiftedandTalented` text NOT NULL,
  `504Plan` text NOT NULL,
  `IEP` text NOT NULL,
  `PrimaryDisability` text NOT NULL,
  `Accommodations-ELA` text NOT NULL,
  `Accommodations-Mathematics` text NOT NULL,
  `Accommodations-SocialStudies` text NOT NULL,
  `Accommodations-Science` text NOT NULL,
  `Attendingdistrictname` text NOT NULL,
  `AttendingdistrictIRN` text NOT NULL,
  `Attendingdistricttype` text NOT NULL,
  `Attendingdistrictcounty` text NOT NULL,
  `Attendingschoolname` text NOT NULL,
  `AttendingschoolIRN` text NOT NULL,
  `Attendingschooltype` text NOT NULL,
  `Homedistrictname` text NOT NULL,
  `HomedistrictIRN` text NOT NULL,
  `Homedistricttype` text NOT NULL,
  `Homedistrictcounty` text NOT NULL,
  `Homeschoolname` text NOT NULL,
  `HomeschoolIRN` text NOT NULL,
  `Homeschooltype` text NOT NULL,
  `ITCcode` text NOT NULL,
  `Homeroom` text NOT NULL,
  `StaffMember` text NOT NULL,
  `SectionNumberClass Name/Course Code---ELAorHSELA1` text NOT NULL,
  `SectionNumberClassNameCourseCode---HSELA2` text NOT NULL,
  `SectionNumberClassNameCourseCode---MathematicsorIntegrate` text NOT NULL,
  `SectionNumberClass Name/Course Code---Integrated Mathematics2` text NOT NULL,
  `SectionNumberClass Name/Course Code---Algebra1` text NOT NULL,
  `SectionNumberClass Name/Course Code---Geometry` text NOT NULL,
  `SectionNumberClass Name/Course Code---SocialStudiesorAmeric` text NOT NULL,
  `SectionNumberClassNameCourseCode---AmericanGovernment` text NOT NULL,
  `SectionNumberClassNameCourseCode---PhysicalScienceorScie` text NOT NULL,
  `SectionNumberClassNameCourseCode---Biology` text NOT NULL,
  `TeacherName---ELAorHSELA1` text NOT NULL,
  `TeacherName---HSELA2` text NOT NULL,
  `TeacherName---MathematicsorIntegratedMathematics1` text NOT NULL,
  `TeacherName---IntegratedMathematics2` text NOT NULL,
  `TeacherName---Algebra1` text NOT NULL,
  `TeacherName---Geometry` text NOT NULL,
  `TeacherName---SocialStudiesorAmericanHistory` text NOT NULL,
  `TeacherName---AmericanGovernment` text NOT NULL,
  `TeacherName---PhysicalScienceorScience` text NOT NULL,
  `TeacherName---Biology` text NOT NULL,
  `TeacherIdentificationNumber---ELAorHSELA1` text NOT NULL,
  `TeacherIdentificationNumber---HSELA2` text NOT NULL,
  `TeacherIdentificationNumber---MathematicsorIntegratedMathem` text NOT NULL,
  `TeacherIdentificationNumber---IntegratedMathematics2` text NOT NULL,
  `TeacherIdentificationNumber---Algebra 1` text NOT NULL,
  `TeacherIdentification Number---Geometry` text NOT NULL,
  `TeacherIdentificationNumber---SocialStudiesorAmericanHistory` text NOT NULL,
  `TeacherIdentificationNumber---AmericanGovernment` text NOT NULL,
  `TeacherIdentificationNumber---PhysicalScienceorScience` text NOT NULL,
  `TeacherIdentificationNumber---Biology` text NOT NULL,
  `TestName` text NOT NULL,
  `Overallscaledscore` text NOT NULL,
  `Overallperformancelevel` text NOT NULL,
  `Part1tested` text NOT NULL,
  `Part2tested` text NOT NULL,
  `Subscore1performanceband` text NOT NULL,
  `Subscore2performanceband` text NOT NULL,
  `Subscore3performanceband` text NOT NULL,
  `Subscore4performanceband` text NOT NULL,
  `Subscore5performanceband` text NOT NULL,
  `Grade3ReadingPromotionScore` text NOT NULL,
  `Grade3ReadingPromotionStatus` text NOT NULL,
  `OELPAOverallScaleScore` text NOT NULL,
  `OELPAOverallScaleScoreStandardError` text NOT NULL,
  `OELPAProficiencyStatus` text NOT NULL,
  `OELPAComprehensionScaleScore` text NOT NULL,
  `OELPAComprehensionScaleScoreStandardError` text NOT NULL,
  `OELPAListeningScaleScore` text NOT NULL,
  `OELPAListeningScaleScoreStandardError` text NOT NULL,
  `OELPAListeningPerformanceLevel` text NOT NULL,
  `OELPAReadingScaleScore` text NOT NULL,
  `OELPAReadingScale ScoreStandardError` text NOT NULL,
  `OELPAReadingPerformanceLevel` text NOT NULL,
  `OELPASpeakingScaleScore` text NOT NULL,
  `OELPASpeakingScaleScoreStandardError` text NOT NULL,
  `OELPASpeakingPerformanceLevel` text NOT NULL,
  `OELPAWritingScaleScore` text NOT NULL,
  `OELPAWritingScale ScoreStandardError` text NOT NULL,
  `OELPAWritingPerformanceLevel` text NOT NULL,
  `OverallRawScore` text NOT NULL,
  `Subscore1RawScore` text NOT NULL,
  `Subscore2RawScore` text NOT NULL,
  `Subscore3RawScore` text NOT NULL,
  `Subscore4RawScore` text NOT NULL,
  `Subscore5RawScore` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_AIRSubscore_Categories`;
CREATE TABLE `Abre_AIRSubscore_Categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TestName` text NOT NULL,
  `Subscore` text NOT NULL,
  `Name` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_Attendance`;
CREATE TABLE `Abre_Attendance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `AbsenceDate` text NOT NULL,
  `AbsenceReasonCode` text NOT NULL,
  `AbsenceReasonDescription` text NOT NULL,
  `AbsenceCategoryCode` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_Courses`;
CREATE TABLE `Abre_Courses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SchoolCode` text NOT NULL,
  `CourseCode` text NOT NULL,
  `SectionCode` text NOT NULL,
  `StaffID` text NOT NULL,
  `TermCode` text NOT NULL,
  `Period` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_OGT`;
CREATE TABLE `Abre_OGT` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `Grade` text NOT NULL,
  `TestPart` text NOT NULL,
  `Date` text NOT NULL,
  `Score` text NOT NULL,
  `Result` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_ParentContacts`;
CREATE TABLE `Abre_ParentContacts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `FirstName` text NOT NULL,
  `MiddleName` text NOT NULL,
  `LastName` text NOT NULL,
  `AddressLine1` text NOT NULL,
  `AddressLine2` text NOT NULL,
  `City` text NOT NULL,
  `State` text NOT NULL,
  `Zip` text NOT NULL,
  `Phone1` text NOT NULL,
  `Phone2` text NOT NULL,
  `Email1` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_Staff`;
CREATE TABLE `Abre_Staff` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `FirstName` text NOT NULL,
  `MiddleName` text NOT NULL,
  `LastName` text NOT NULL,
  `Gender` text NOT NULL,
  `DateOfBirth` text NOT NULL,
  `Address1` text NOT NULL,
  `AddressLine2` text NOT NULL,
  `City` text NOT NULL,
  `State` text NOT NULL,
  `Zip` text NOT NULL,
  `Phone1` text NOT NULL,
  `EMail1` text NOT NULL,
  `HiringDate` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StaffSchedules`;
CREATE TABLE `Abre_StaffSchedules` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `CourseCode` text NOT NULL,
  `SectionCode` text NOT NULL,
  `TermCode` text NOT NULL,
  `Period` text NOT NULL,
  `CourseName` text NOT NULL,
  `TeacherName` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentACT`;
CREATE TABLE `Abre_StudentACT` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `LastName` text NOT NULL,
  `FirstName` text NOT NULL,
  `CategoryName` text NOT NULL,
  `TestingDate` text NOT NULL,
  `Score` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentAP`;
CREATE TABLE `Abre_StudentAP` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `LastName` text NOT NULL,
  `FirstName` text NOT NULL,
  `APExamSubject` text NOT NULL,
  `TestingDate` text NOT NULL,
  `Score` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentAssessments`;
CREATE TABLE `Abre_StudentAssessments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `GradeLevel` text NOT NULL,
  `Status` text NOT NULL,
  `AssessmentTestName` text NOT NULL,
  `TestDate` text NOT NULL,
  `AssessmentArea` text NOT NULL,
  `Accommodations` text NOT NULL,
  `ScaledScore` text NOT NULL,
  `PerformanceLevel` text NOT NULL,
  `SchoolCode` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentDiscipline`;
CREATE TABLE `Abre_StudentDiscipline` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentId` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `IncidentID` text NOT NULL,
  `MisconductIncidentDate` text NOT NULL,
  `IncidentLocation` text NOT NULL,
  `IncidentReportedBy` text NOT NULL,
  `IncidentReportedToPolice` text NOT NULL,
  `OffenseTypeCode` text NOT NULL,
  `OffenseTypeDescription` text NOT NULL,
  `DisciplineTypeCode` text NOT NULL,
  `DisciplineTypeDescription` text NOT NULL,
  `SuspensionTypeCode` text NOT NULL,
  `DisciplineDurationType` text NOT NULL,
  `DisciplineDurationLength` text NOT NULL,
  `DisciplineActionStartDate` text NOT NULL,
  `DisciplineActionEndDate` text NOT NULL,
  `SchoolYear` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentGrades`;
CREATE TABLE `Abre_StudentGrades` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `CourseCode` text NOT NULL,
  `SectionCode` text NOT NULL,
  `CourseName` text NOT NULL,
  `StaffID` text NOT NULL,
  `TeacherName` text NOT NULL,
  `MarkingPeriodCode` text NOT NULL,
  `MarkingPeriodGrade` text NOT NULL,
  `SchoolYear` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_Students`;
CREATE TABLE `Abre_Students` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentId` text NOT NULL,
  `FirstName` text NOT NULL,
  `MiddleName` text NOT NULL,
  `LastName` text NOT NULL,
  `Gender` text NOT NULL,
  `EthnicityCode` text NOT NULL,
  `EthnicityDescription` text NOT NULL,
  `DateOfBirth` text NOT NULL,
  `Email` text NOT NULL,
  `SSID` text NOT NULL,
  `IEP` text NOT NULL,
  `Gifted` text NOT NULL,
  `EconomicallyDisadvantaged` text NOT NULL,
  `Title1` text NOT NULL,
  `Title3` text NOT NULL,
  `ELL` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `SchoolName` text NOT NULL,
  `CurrentGrade` text NOT NULL,
  `Status` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentSchedules`;
CREATE TABLE `Abre_StudentSchedules` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `SchoolCode` text NOT NULL,
  `CourseCode` text NOT NULL,
  `SectionCode` text NOT NULL,
  `CourseName` text NOT NULL,
  `StaffId` text NOT NULL,
  `TeacherName` text NOT NULL,
  `TermCode` text NOT NULL,
  `Period` text NOT NULL,
  `RoomNumber` text NOT NULL,
  `StudentIEPStatus` text NOT NULL,
  `StudentGiftedStatus` text NOT NULL,
  `StudentELLStatus` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentUnweightedGPA`;
CREATE TABLE `Abre_StudentUnweightedGPA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentNumber` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `GPA` text NOT NULL,
  `SchoolBuilding` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_StudentWeightedGPA`;
CREATE TABLE `Abre_StudentWeightedGPA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentNumber` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `GPA` text NOT NULL,
  `SchoolBuilding` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_Student_Counselors`;
CREATE TABLE `Abre_Student_Counselors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentFirstName` text NOT NULL,
  `StudentLastName` text NOT NULL,
  `StudentId` text NOT NULL,
  `GradeLevel` text NOT NULL,
  `StudentStatus` text NOT NULL,
  `StudentIEPStatus` text NOT NULL,
  `StudentGiftedStatus` text NOT NULL,
  `StudentELLStatus` text NOT NULL,
  `CounselorFirstName` text NOT NULL,
  `CounselorLastName` text NOT NULL,
  `CounselorStaffId` text NOT NULL,
  `SchoolCode` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_VendorLink_SIS_District`;
CREATE TABLE `Abre_VendorLink_SIS_District` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RefId` text NOT NULL,
  `LocalId` text NOT NULL,
  `StateProvinceId` text NOT NULL,
  `LeaName` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_VendorLink_SIS_Schools`;
CREATE TABLE `Abre_VendorLink_SIS_Schools` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RefId` text NOT NULL,
  `LocalId` text NOT NULL,
  `StateProvinceId` text NOT NULL,
  `SchoolName` text NOT NULL,
  `LeaInfoRefId` text NOT NULL,
  `PrincipalContactName` text NOT NULL,
  `Street` text NOT NULL,
  `City` text NOT NULL,
  `State` text NOT NULL,
  `Country` text NOT NULL,
  `PostalCode` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_VendorLink_SIS_StudentPictures`;
CREATE TABLE `Abre_VendorLink_SIS_StudentPictures` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID` text NOT NULL,
  `SchoolYear` text NOT NULL,
  `OkToPublish` text NOT NULL,
  `Type` text NOT NULL,
  `Value` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Abre_VendorLink_SIS_Students`;
CREATE TABLE `Abre_VendorLink_SIS_Students` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RefId` text NOT NULL,
  `AlertMessages` text NOT NULL,
  `MedicalAlertMessages` text NOT NULL,
  `LocalId` text NOT NULL,
  `StateProvinceId` text NOT NULL,
  `LastName` text NOT NULL,
  `FirstName` text NOT NULL,
  `MiddleName` text NOT NULL,
  `HispanicLatino` text NOT NULL,
  `Gender` text NOT NULL,
  `BirthDate` text NOT NULL,
  `PlaceOfBirth` text NOT NULL,
  `CountryOfBirth` text NOT NULL,
  `CitizenshipStatus` text NOT NULL,
  `EnglishProficiency` text NOT NULL,
  `LanguageList` text NOT NULL,
  `AddressList` text NOT NULL,
  `PhoneNumberList` text NOT NULL,
  `EmailList` text NOT NULL,
  `ProjectedGraduationYear` text NOT NULL,
  `OnTimeGraduationYear` text NOT NULL,
  `IDEA` text NOT NULL,
  `Migrant` text NOT NULL,
  `EconomicDisadvantage` text NOT NULL,
  `Homeless` text NOT NULL,
  `Section504` text NOT NULL,
  `SifExtendedElements` text NOT NULL,
  `SnapshotSchoolLocalId` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2018-02-02 20:15:11
