CREATE TABLE Consortia
(
  id INTEGER AUTO_INCREMENT,
  full_name TEXT NOT NULL,
  short_name VARCHAR(23) NOT NULL,
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
  user_id INTEGER,
  approves_for_consortium INTEGER,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES Privileged_Users(id),
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
  request_id INTEGER,
  event_type_id INTEGER,
  acting_user INTEGER,
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

CREATE TABLE User_Experience_Levels
(
  id INTEGER AUTO_INCREMENT,
  level_text text,
  PRIMARY KEY (id)
);

CREATE TABLE Account_Request
(
  id INTEGER AUTO_INCREMENT,
  user_id VARCHAR(7),
  user_upi VARCHAR(15),
  supervisor_upi VARCHAR(15),
  user_type INTEGER,
  user_email_address TEXT,
  user_surname TEXT,
  user_forenames TEXT,
  user_forename_preferred TEXT,
  user_contact_number TEXT,
  user_dept TEXT,
  user_experience_dropdown INTEGER,
  user_experience TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (user_type) REFERENCES User_Types(id),
  FOREIGN KEY (user_experience_dropdown) REFERENCES User_Experience_Levels(id)
);

CREATE TABLE Project
(
  id INTEGER AUTO_INCREMENT,
  user_id VARCHAR(7),
  request_id INTEGER,
  grant_code VARCHAR(7),
  is_funded BOOLEAN,
  pi_user_id VARCHAR(7),
  consortium_id INTEGER,
  wants_cfi_because TEXT,
  cfi_impact TEXT,
  cfi_usage TEXT,
  is_collab_bristol BOOLEAN,
  collab_bristol_person TEXT,
  is_collab_oxford BOOLEAN,
  collab_oxford_person TEXT,
  is_collab_soton BOOLEAN,
  collab_soton_person TEXT,
  is_collab_other BOOLEAN,
  collab_other_institution TEXT,
  collab_other_institution_name TEXT,
  collab_other_institution_person TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (request_id) REFERENCES Account_Request(id),
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
  PRIMARY KEY (id),
  FOREIGN KEY (project_id) REFERENCES Projects(id),
  FOREIGN KEY (service_id) REFERENCES Services(id)
);
