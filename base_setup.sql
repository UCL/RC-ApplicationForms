REPLACE INTO Consortia (id, short_name, full_name) VALUES
(
    ( 1, "Astro"          , "Astrophysics and Remote Sensing"                                           ) ,
    ( 2, "BioinfCompBio"  , "Bioinformatics and Computational Biology"                                  ) ,
    ( 3, "BuiltEnv"       , "The Bartlett - Built Environment"                                          ) ,
    ( 4, "Climate"        , "Climate Change and Earth Observation"                                      ) ,
    ( 5, "ENGFEAandCFD"   , "Engineering - Finite Element Analysis & Computational Fluid Dynamics"      ) ,
    ( 6, "EngDAandMD"     , "Engineering Sciences – Optimisation, Data Analysis and Molecular Dynamics" ) ,
    ( 7, "Economics"      , "Economics"                                                                 ) ,
    ( 8, "Epidemiology"   , "Epidemiology"                                                              ) ,
    ( 9, "HEP"            , "High Energy Physics"                                                       ) ,
    (10, "ISD"            , "Information Services Division"                                             ) ,
    (11, "NGS"            , "Next Generation Sequencing"                                                ) ,
    (12, "TYCOrgPharmMat" , "Thomas Young Centre - Organic and Pharmaceutical Materials"                ) ,
    (13, "TYCEarthMat"    , "Thomas Young Centre - Earth Materials"                                     ) ,
    (14, "TYCNano"        , "Thomas Young Centre - Nanoscience and Defects"                             ) ,
    (15, "TYCCatSurf"     , "Thomas Young Centre - Surface Science and Catalysis"                       ) ,
    (16, "Maths"          , "Mathematical Sciences"                                                     ) ,
    (17, "MedImaging"     , "Medical Imaging"                                                           ) ,
    (18, "MolQuantDynam"  , "Molecular Quantum Dynamics and Electronic Structure"                       ) ,
    (19, "NeuroSci"       , "Neuroscience"                                                              ) ,
    (20, "sochistsci"     , "Social and Historical Sciences"                                            ) ,
    (21, "SysBioMed"      , "Systems Biomedicine"                                                       ) ,
    (22, "PENDING"        , "(None of these fit my research area)"                                      ) 
);

REPLACE INTO Services (id, name) VALUES
(
    (1, 'Legion'),
    (2, 'Iridis'),
    (3, 'Emerald')
);

INSERT INTO Event_Types (event_type) VALUES
(
    ('submitted'),
    ('approved'),
    ('declined')
);

INSERT INTO User_Types (user_type) VALUES
(
    ('Principle Investigator'),
    ('Non-PI Researcher'),
    ('PhD/EngD Student'),
    ('Masters Student (Postgraduate)'),
    ('Masters Student (Undergraduate)'),
    ('Other Undergraduate')
);

INSERT INTO User_Experience_Levels (level_text) VALUES
(
    ("Novice with No Local Support"),
    ("Novice with Local Support"),
    ("Linux experience but no HPC"),
    ("Linux and HPC experience")
);

REPLACE INTO Privileged_Users 
    (id, username, full_name, 
        super_special_rainbow_pegasus_powers, 
        receives_emails, email_address) 
    VALUES
(
    (1, "ccaabaa", "Brian Alston", TRUE, TRUE, "b.alston@ucl.ac.uk"),
    (2, "ccaabcs", "Bruno Silva", TRUE, TRUE, "b.silva@ucl.ac.uk"),
    (3, "uccaiki", "Ian Kirker", TRUE, TRUE, "i.kirker@ucl.ac.uk"),
    (4, "uccaoke", "Owain Kenway", TRUE, TRUE, "o.kenway@ucl.ac.uk"),
    (5, "ccaacla", "Clare Gryce", FALSE, TRUE, "c.gryce@ucl.ac.uk")
);

INSERT INTO Consortium_Permissions (privileged_user_id, approves_for_consortium) VALUES
(
    (5, 22) -- Clare for PENDING
);


