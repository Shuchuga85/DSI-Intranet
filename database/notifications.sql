-- Empty runtime database schema. Contains no accounts, tokens or preferences.
CREATE TABLE ntf
(
ntf_id INTEGER PRIMARY KEY,
origin TEXT,
category TEXT,
title TEXT,
body TEXT,
img TEXT

, destination TEXT, date TEXT, pedido_id INTEGER);

CREATE TABLE flag (
    flag_id INTEGER PRIMARY KEY,
    flag_type TEXT,
    destination TEXT,
    message TEXT,
    origin TEXT, 
    date DATETIME DEFAULT CURRENT_TIMESTAMP
, seen INTEGER CHECK (seen IN (0,1)));

CREATE TABLE userprefs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    block BOOLEAN
, lang TEXT);
