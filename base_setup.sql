REPLACE INTO Consortia (short_name, full_name) VALUES
  ( 'Astro'             , 'Astrophysics and Remote Sensing'                                           ) ,
  ( 'BioinfCompBio'     , 'Bioinformatics and Computational Biology'                                  ) ,
  ( 'BuiltEnv'          , 'The Bartlett - Built Environment'                                          ) ,
  ( 'Climate'           , 'Climate Change and Earth Observation'                                      ) ,
  ( 'digitalhumanities' , 'Digital Humanities'                                                        ) ,
  ( 'ENGFEAandCFD'      , 'Engineering - Finite Element Analysis & Computational Fluid Dynamics'      ) ,
  ( 'EngDAandMD'        , 'Engineering Sciences – Optimisation, Data Analysis and Molecular Dynamics' ) ,
  ( 'Economics'         , 'Economics'                                                                 ) ,
  ( 'Epidemiology'      , 'Epidemiology'                                                              ) ,
  ( 'gatsbyneuro'       , 'The Gatsby Computational Neuroscience Unit'                                ) ,
  ( 'HEP'               , 'High Energy Physics'                                                       ) ,
  ( 'ISD'               , 'Information Services Division'                                             ) ,
  ( 'NGS'               , 'Next Generation Sequencing'                                                ) ,
  ( 'TYCOrgPharmMat'    , 'Thomas Young Centre - Organic and Pharmaceutical Materials'                ) ,
  ( 'TYCEarthMat'       , 'Thomas Young Centre - Earth Materials'                                     ) ,
  ( 'TYCNano'           , 'Thomas Young Centre - Nanoscience and Defects'                             ) ,
  ( 'TYCCatSurf'        , 'Thomas Young Centre - Surface Science and Catalysis'                       ) ,
  ( 'Maths'             , 'Mathematical Sciences'                                                     ) ,
  ( 'MedImaging'        , 'Medical Imaging'                                                           ) ,
  ( 'MolQuantDynam'     , 'Molecular Quantum Dynamics and Electronic Structure'                       ) ,
  ( 'NeuroSci'          , 'Neuroscience'                                                              ) ,
  ( 'sochistsci'        , 'Social and Historical Sciences'                                            ) ,
  ( 'SysBioMed'         , 'Systems Biomedicine'                                                       ) ,
  ( 'PENDING'           , '[None of these fit my research area]'                                      )
;

REPLACE INTO Services (id, name) VALUES
  (1, 'Legion'),
  (2, 'Iridis'),
  (3, 'Emerald')
;

REPLACE INTO Event_Types (id, event_type) VALUES
  (1, 'submitted'),
  (2, 'approved'),
  (3, 'declined')
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

SELECT id FROM Consortia WHERE short_name = 'PENDING' INTO @temp_consortium;
SELECT id FROM Privileged_Users WHERE username = 'ccaacla' INTO @temp_name;
INSERT INTO Consortium_Permissions (privileged_user_id, approves_for_consortium) VALUES
  (@temp_name, @temp_consortium)
;


