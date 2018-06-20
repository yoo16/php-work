CREATE TABLE IF NOT EXISTS "countries" (
id SERIAL PRIMARY KEY NOT NULL
, created_at TIMESTAMP
, name VARCHAR(256) NOT NULL
, sort_order INT4
, updated_at TIMESTAMP
);


ALTER TABLE countries
      ADD CONSTRAINT countries_name_key
      UNIQUE (name);

