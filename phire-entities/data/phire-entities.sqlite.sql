--
-- Entities Module SQLite Database for Phire CMS 2.0
--

--  --------------------------------------------------------

--
-- Set database encoding
--

PRAGMA encoding = "UTF-8";
PRAGMA foreign_keys = ON;

-- --------------------------------------------------------

--
-- Table structure for table "entities"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]entities" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" varchar,
  UNIQUE ("id")
) ;

INSERT INTO sqlite_sequence ("name", "seq") VALUES ('[{prefix}]entities', 18000);

-- --------------------------------------------------------

--
-- Table structure for table "entity_types"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]entity_types" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" varchar,
  UNIQUE ("id")
) ;

INSERT INTO sqlite_sequence ("name", "seq") VALUES ('[{prefix}]entity_types', 19000);
