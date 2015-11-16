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
-- Table structure for table "entity_types"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]entity_types" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" varchar NOT NULL,
  "field_num" integer NOT NULL,
  "order" integer,
  UNIQUE ("id")
) ;

INSERT INTO sqlite_sequence ("name", "seq") VALUES ('[{prefix}]entity_types', 50000);
CREATE INDEX "entity_type_name" ON "[{prefix}]entity_types" ("name");

-- --------------------------------------------------------

--
-- Table structure for table "entities"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]entities" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "type_id" integer NOT NULL,
  "title" varchar NOT NULL,
  UNIQUE ("id"),
  CONSTRAINT "fk_entity_type" FOREIGN KEY ("type_id") REFERENCES "[{prefix}]entity_types" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO sqlite_sequence ("name", "seq") VALUES ('[{prefix}]entities', 51000);
CREATE INDEX "entity_type_id" ON "[{prefix}]entities" ("type_id");
CREATE INDEX "entity_title" ON "[{prefix}]entities" ("title");
