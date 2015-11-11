--
-- Entities Module PostgreSQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

--
-- Table structure for table "entities"
--

CREATE SEQUENCE entity_id_seq START 18001;

CREATE TABLE IF NOT EXISTS "[{prefix}]entities" (
  "id" integer NOT NULL DEFAULT nextval('entity_id_seq'),
  "name" varchar(255),
  PRIMARY KEY ("id")
) ;

ALTER SEQUENCE entity_id_seq OWNED BY "[{prefix}]entities"."id";

-- --------------------------------------------------------

--
-- Table structure for table "entity_types"
--

CREATE SEQUENCE entity_type_id_seq START 19001;

CREATE TABLE IF NOT EXISTS "[{prefix}]entity_types" (
  "id" integer NOT NULL DEFAULT nextval('entity_type_id_seq'),
  "name" varchar(255),
  PRIMARY KEY ("id")
) ;

ALTER SEQUENCE entity_type_id_seq OWNED BY "[{prefix}]entity_types"."id";
