/*
 TABLE Consortia
 TABLE Privileged_Users
 TABLE Consortium_Permissions
 TABLE Event_Types
 TABLE Request_Progress
 TABLE User_Types
 TABLE User_Experience_Levels
 TABLE Account_Requests
 TABLE Projects
 TABLE Services
 TABLE Service_Requests
 TABLE Publications
 TABLE Publication_Services
 */

CREATE TABLE Consortia
( -- should be the name of the consortium on Legion 
  -- e.g. TYCNano for the equivalent full-name "Thomas Young Centre - Nanoscience"
  id INTEGER AUTO_INCREMENT,
  full_name TEXT NOT NULL,
  short_name VARCHAR(23) NOT NULL, -- Consortium name in the Legion filesystem
  PRIMARY KEY (id)
);

CREATE TABLE Privileged_Users
(
  id INTEGER AUTO_INCREMENT,
  username VARCHAR(7),
  full_name TEXT,
  super_special_rainbow_pegasus_powers BOOLEAN,
  receives_emails BOOLEAN,
  email_address VARCHAR(255),
  PRIMARY KEY (id)
);

CREATE TABLE Consortium_Permissions
(
  id INTEGER AUTO_INCREMENT,
  privileged_user_id INTEGER,
  approves_for_consortium INTEGER,
  PRIMARY KEY (id),
  FOREIGN KEY (privileged_user_id) REFERENCES Privileged_Users(id),
  FOREIGN KEY (approves_for_consortium) REFERENCES Consortia(id)
);

CREATE TABLE Event_Types
(
  id INTEGER AUTO_INCREMENT,
  event_type TEXT,
  PRIMARY KEY (id)
);

CREATE TABLE Request_Progress
(
  id INTEGER AUTO_INCREMENT,
  request_type ENUM('Account', 'Project', 'Service'),
  request_id INTEGER,
  event_type_id INTEGER,
  acting_user VARCHAR(7),
  object TEXT,
  update_time TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (event_type_id) REFERENCES Event_Types(id)
);

CREATE TABLE User_Types
(
  id INTEGER AUTO_INCREMENT,
  user_type TEXT,
  PRIMARY KEY (id)
);

CREATE TABLE Experience_Levels
(
  id INTEGER AUTO_INCREMENT,
  level_text text,
  PRIMARY KEY (id)
);

CREATE TABLE Account_Requests
(
  id INTEGER AUTO_INCREMENT,
  username VARCHAR(7),
  user_upi VARCHAR(15),
  user_type_id INTEGER,
  user_email TEXT,
  user_contact_number TEXT,
  user_surname TEXT,
  user_forenames TEXT,
  user_forename_preferred TEXT,
  user_dept TEXT,
  supervisor_name TEXT,
  supervisor_email TEXT,
  experience_level_id INTEGER,
  experience_text TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (user_type_id) REFERENCES User_Types(id),
  FOREIGN KEY (experience_level_id) REFERENCES Experience_Levels(id)
);

CREATE TABLE Projects
(
  id INTEGER AUTO_INCREMENT,
  username VARCHAR(7),
  request_id INTEGER,
  award_number INTEGER,
  is_funded BOOLEAN,
  pi_username VARCHAR(7),
  consortium_id INTEGER,
  is_collab_bristol BOOLEAN,
  collab_bristol_person TEXT,
  is_collab_oxford BOOLEAN,
  collab_oxford_person TEXT,
  is_collab_soton BOOLEAN,
  collab_soton_person TEXT,
  is_collab_other BOOLEAN,
  collab_other_institution TEXT,
  collab_other_institution_person TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (request_id) REFERENCES Account_Requests(id),
  FOREIGN KEY (consortium_id) REFERENCES Consortia(id)
);

CREATE TABLE Services
(
  id INTEGER AUTO_INCREMENT,
  name varchar(255),
  PRIMARY KEY (id)
);

CREATE TABLE Service_Requests
(
  id INTEGER AUTO_INCREMENT,
  project_id INTEGER,
  service_id INTEGER,
  wants_cfi_because TEXT,
  cfi_impact TEXT,
  cfi_usage TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (project_id) REFERENCES Projects(id),
  FOREIGN KEY (service_id) REFERENCES Services(id)
);

CREATE TABLE Publications
(
  id INTEGER AUTO_INCREMENT,
  account_id INTEGER,
  url TEXT,
  notable BOOLEAN,
  PRIMARY KEY (id),
  FOREIGN KEY (account_id) REFERENCES Account_Requests(id)
);

CREATE TABLE Publication_Services
(
  id INTEGER AUTO_INCREMENT,
  publication_id INTEGER,
  service_used INTEGER,
  PRIMARY KEY (id),
  FOREIGN KEY (publication_id) REFERENCES Publications(id),
  FOREIGN KEY (service_used) REFERENCES Services(id)
);
