# Reset the world.
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS Research_Themes;
DROP TABLE IF EXISTS Privileged_Users;
DROP TABLE IF EXISTS Status_Types;
DROP TABLE IF EXISTS Project_Request_Statuses;
DROP TABLE IF EXISTS User_Types;
DROP TABLE IF EXISTS Experience_Levels;
DROP TABLE IF EXISTS User_Profiles;
DROP TABLE IF EXISTS Project_Requests;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Publications;
DROP TABLE IF EXISTS Publication_Services;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE Research_Themes
(
  id INTEGER AUTO_INCREMENT,
  full_name TEXT NOT NULL,
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

CREATE TABLE Status_Types
(
  id INTEGER AUTO_INCREMENT,
  status_type TEXT,
  PRIMARY KEY (id)
);

CREATE TABLE Project_Request_Statuses
(
  id INTEGER AUTO_INCREMENT,
  project_request_id INTEGER,
  status_type_id INTEGER,
  acting_user VARCHAR(7),
  comment TEXT,
  update_time TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (status_type_id) REFERENCES Status_Types(id)
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

CREATE TABLE User_Profiles
(
  id INTEGER AUTO_INCREMENT,
  creation_time TIMESTAMP,
  username VARCHAR(7),
  user_upi VARCHAR(15),
  user_type_id INTEGER,
  user_email TEXT,
  user_contact_number TEXT,
  user_surname TEXT,
  user_forenames TEXT,
  user_forename_preferred TEXT,
  # TODO: Stop oppressing people and just request a full name and a preferred term of address
  user_dept TEXT,
  sponsor_username TEXT,
  experience_level_id INTEGER,
  experience_text TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (user_type_id) REFERENCES User_Types(id),
  FOREIGN KEY (experience_level_id) REFERENCES Experience_Levels(id)
);

CREATE TABLE Project_Requests
(
  id INTEGER AUTO_INCREMENT,
  user_profile_id INTEGER,
  research_theme_id INTEGER,
  is_funded BOOLEAN,
  work_type_basic BOOLEAN,
  work_type_array BOOLEAN,
  work_type_multithread BOOLEAN,
  work_type_all_the_ram BOOLEAN,
  work_type_small_mpi BOOLEAN,
  work_type_mid_mpi BOOLEAN,
  work_type_large_mpi BOOLEAN,
  work_type_small_gpu BOOLEAN,
  work_type_large_gpu BOOLEAN,
  is_collab_bristol BOOLEAN,
  is_collab_oxford BOOLEAN,
  is_collab_soton BOOLEAN,
  is_collab_other BOOLEAN,
  pi_email TEXT,
  weird_tech_description TEXT,
  work_description TEXT,
  applications_description TEXT,
  collab_bristol_name TEXT,
  collab_oxford_name TEXT,
  collab_soton_name TEXT,
  collab_other_institute TEXT,
  collab_other_name TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (user_profile_id) REFERENCES User_Profiles(id),
  FOREIGN KEY (research_theme_id) REFERENCES Research_Themes(id)
);

CREATE TABLE Services
(
  id INTEGER AUTO_INCREMENT,
  name varchar(255),
  PRIMARY KEY (id)
);

CREATE TABLE Publications
(
  id INTEGER AUTO_INCREMENT,
  user_profile_id INTEGER,
  url TEXT,
  notable BOOLEAN,
  time_added TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_profile_id) REFERENCES User_Profiles(id)
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

CREATE TABLE Research_Project_Codes
(
  id INTEGER AUTO_INCREMENT,
  user_profile_id INTEGER,
  code TEXT,
  time_added TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_profile_id) REFERENCES User_Profiles(id)
);
