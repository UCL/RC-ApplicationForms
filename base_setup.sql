REPLACE INTO Consortia (short_name, full_name) VALUES
    ( "Astro"             , "Astrophysics and Remote Sensing"                                           ) ,
    ( "BioinfCompBio"     , "Bioinformatics and Computational Biology"                                  ) ,
    ( "BuiltEnv"          , "The Bartlett - Built Environment"                                          ) ,
    ( "Climate"           , "Climate Change and Earth Observation"                                      ) ,
    ( "digitalhumanities" , "Digital Humanities"                                                        ) ,
    ( "ENGFEAandCFD"      , "Engineering - Finite Element Analysis & Computational Fluid Dynamics"      ) ,
    ( "EngDAandMD"        , "Engineering Sciences – Optimisation, Data Analysis and Molecular Dynamics" ) ,
    ( "Economics"         , "Economics"                                                                 ) ,
    ( "Epidemiology"      , "Epidemiology"                                                              ) ,
    ( "gatsbyneuro"       , "The Gatsby Computational Neuroscience Unit"                                ) ,
    ( "HEP"               , "High Energy Physics"                                                       ) ,
    ( "ISD"               , "Information Services Division"                                             ) ,
    ( "NGS"               , "Next Generation Sequencing"                                                ) ,
    ( "TYCOrgPharmMat"    , "Thomas Young Centre - Organic and Pharmaceutical Materials"                ) ,
    ( "TYCEarthMat"       , "Thomas Young Centre - Earth Materials"                                     ) ,
    ( "TYCNano"           , "Thomas Young Centre - Nanoscience and Defects"                             ) ,
    ( "TYCCatSurf"        , "Thomas Young Centre - Surface Science and Catalysis"                       ) ,
    ( "Maths"             , "Mathematical Sciences"                                                     ) ,
    ( "MedImaging"        , "Medical Imaging"                                                           ) ,
    ( "MolQuantDynam"     , "Molecular Quantum Dynamics and Electronic Structure"                       ) ,
    ( "NeuroSci"          , "Neuroscience"                                                              ) ,
    ( "sochistsci"        , "Social and Historical Sciences"                                            ) ,
    ( "SysBioMed"         , "Systems Biomedicine"                                                       ) ,
    ( "PENDING"           , "[None of these fit my research area]"                                      )
;

REPLACE INTO Services (id, name) VALUES
    (1, 'Legion'),
    (2, 'Iridis'),
    (3, 'Emerald')
;

INSERT INTO Event_Types (event_type) VALUES
    ('submitted'),
    ('approved'),
    ('declined')
;

INSERT INTO User_Types (user_type) VALUES
    ('Principle Investigator'),
    ('Non-PI Researcher'),
    ('PhD/EngD Student'),
    ('Masters Student (Postgraduate)'),
    ('Masters Student (Undergraduate)'),
    ('Other Undergraduate')
;

INSERT INTO User_Experience_Levels (level_text) VALUES
    ("Novice with No Local Support"),
    ("Novice with Local Support"),
    ("Linux experience but no HPC"),
    ("Linux and HPC experience")
;

REPLACE INTO Privileged_Users 
    (id, username, full_name, 
        super_special_rainbow_pegasus_powers, 
        receives_emails, email_address) 
    VALUES
    (1, "ccaabaa", "Brian Alston", TRUE, TRUE, "b.alston@ucl.ac.uk"),
    (2, "ccaabcs", "Bruno Silva", TRUE, TRUE, "b.silva@ucl.ac.uk"),
    (3, "uccaiki", "Ian Kirker", TRUE, TRUE, "i.kirker@ucl.ac.uk"),
    (4, "uccaoke", "Owain Kenway", TRUE, TRUE, "o.kenway@ucl.ac.uk"),
    (5, "ccaacla", "Clare Gryce", FALSE, TRUE, "c.gryce@ucl.ac.uk")
;

#INSERT INTO Consortium_Permissions (privileged_user_id, approves_for_consortium) VALUES
#    (5, 22) -- Clare for PENDING
#;


