--
-- Entities Module PostgreSQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

--
-- Table structure for table "entity_types"
--

CREATE SEQUENCE entity_type_id_seq START 50001;

CREATE TABLE IF NOT EXISTS "[{prefix}]entity_types" (
  "id" integer NOT NULL DEFAULT nextval('entity_type_id_seq'),
  "name" varchar(255) NOT NULL,
  "field_num" integer NOT NULL,
  "order" integer,
  PRIMARY KEY ("id")
) ;

ALTER SEQUENCE entity_type_id_seq OWNED BY "[{prefix}]entity_types"."id";
CREATE INDEX "entity_type_name" ON "[{prefix}]entity_types" ("name");

-- --------------------------------------------------------

--
-- Table structure for table "entities"
--

CREATE SEQUENCE entity_id_seq START 51001;

CREATE TABLE IF NOT EXISTS "[{prefix}]entities" (
  "id" integer NOT NULL DEFAULT nextval('entity_id_seq'),
  "type_id" integer NOT NULL,
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_entity_type" FOREIGN KEY ("type_id") REFERENCES "[{prefix}]entity_types" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE entity_id_seq OWNED BY "[{prefix}]entities"."id";
CREATE INDEX "entity_type_id" ON "[{prefix}]entities" ("type_id");
CREATE INDEX "entity_name" ON "[{prefix}]entities" ("name");

