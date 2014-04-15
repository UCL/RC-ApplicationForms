
REPLACE INTO Research_Themes (id, full_name) VALUES
  ( 1, 'Clinical Medicine'),
  ( 2, 'Public Health, Health Services and Primary Care'),
  ( 3, 'Allied Health Professions, Dentistry, Nursing and Pharmacy'),
  ( 4, 'Psychology, Psychiatry and Neuroscience'),
  ( 5, 'Biological Sciences'),
  ( 6, 'Agriculture, Veterinary and Food Science'),
  ( 7, 'Earth Systems and Environmental Sciences'),
  ( 8, 'Chemistry'),
  ( 9, 'Physics'),
  (10, 'Mathematical Sciences'),
  (11, 'Computer Science and Informatics'),
  (12, 'Aeronautical, Mechanical, Chemical and Manufacturing Engineering'),
  (13, 'Electrical and Electronic Engineering, Metallurgy and Materials'),
  (14, 'Civil and Construction Engineering'),
  (15, 'General Engineering'),
  (16, 'Architecture, Built Environment and Planning'),
  (17, 'Geography, Environmental Studies and Archaeology'),
  (18, 'Economics and Econometrics'),
  (19, 'Business and Management Studies'),
  (20, 'Law'),
  (21, 'Politics and International Studies'),
  (22, 'Social Work and Social Policy'),
  (23, 'Sociology'),
  (24, 'Anthropology and Development Studies'),
  (25, 'Education'),
  (26, 'Sport and Exercise Sciences, Leisure and Tourism'),
  (27, 'Area Studies'),
  (28, 'Modern Languages and Linguistics'),
  (29, 'English Language and Literature'),
  (30, 'History'),
  (31, 'Classics'),
  (32, 'Philosophy'),
  (33, 'Theology and Religious Studies'),
  (34, 'Art and Design: History, Practice and Theory'),
  (35, 'Music, Drama, Dance and Performing Arts'),
  (36, 'Communication, Cultural and Media Studies, Library and Information Management')
;

REPLACE INTO Services (id, name) VALUES
  (1, 'Legion'),
  (2, 'Iridis'),
  (3, 'Emerald')
;

REPLACE INTO Event_Types (id, event_type) VALUES
  (1, 'submitted'),
  (2, 'approved'),
  (3, 'rejected'),
  (4, 'broken'),
  (5, 'expired'),
  (6, 'deprecated')
;

REPLACE INTO User_Types (id, user_type) VALUES
  (1, 'Principle Investigator'),
  (2, 'Non-PI Researcher'),
  (3, 'PhD/EngD Student'),
  (4, 'Masters Student (Postgraduate)'),
  (5, 'Masters Student (Undergraduate)'),
  (6, 'Other Undergraduate')
;

REPLACE INTO Experience_Levels (id,level_text) VALUES
  (1, 'Novice with No Local Support'),
  (2, 'Novice with Local Support'),
  (3, 'Some Linux experience but no HPC'),
  (4, 'Some Linux and HPC experience'),
  (5, 'Extensive Linux experience'),
  (6, 'Extensive Linux and HPC experience')
;

REPLACE INTO Privileged_Users
(id, username, full_name,
 super_special_rainbow_pegasus_powers,
 receives_emails, email_address)
VALUES
  (1, 'ccaaxxx', 'Test Non-special user', FALSE, FALSE, 'ccaaxxx@ucl.ac.uk'),
  (2, 'ccaabaa', 'Brian Alston', TRUE, TRUE, 'b.alston@ucl.ac.uk'),
  (3, 'ccaabcs', 'Bruno Silva', TRUE, TRUE, 'b.silva@ucl.ac.uk'),
  (4, 'uccaiki', 'Ian Kirker', TRUE, TRUE, 'i.kirker@ucl.ac.uk'),
  (5, 'uccaoke', 'Owain Kenway', TRUE, TRUE, 'o.kenway@ucl.ac.uk'),
  (6, 'ccaacla', 'Clare Gryce', FALSE, TRUE, 'c.gryce@ucl.ac.uk')
;




