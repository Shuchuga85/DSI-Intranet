-- Empty runtime database schema. Contains no accounts, tokens or preferences.
CREATE TABLE sessions(
username TEXT PRIMARY KEY,
token TEXT
);
