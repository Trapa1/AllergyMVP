CREATE TABLE IF NOT EXISTS medicines (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    affiliated_allergies TEXT NOT NULL
);

INSERT INTO medicines (name, affiliated_allergies)
VALUES
    ('Penicillin', 'Hives, Rash, Anaphylaxis'),
    ('Amoxicillin1', 'Rash, Swelling, Anaphylaxis'),
    ('Cefalexin', 'Rash, Cross-Reaction with Penicillin');


