DROP TABLE IF EXISTS test;
CREATE TABLE test (
      nom varchar(255) NOT NULL,
      resultat varchar(5) NOT NULL
);


ALTER TABLE test DROP CONSTRAINT IF EXISTS ck_resultat_du_test;
ALTER TABLE test
ADD CONSTRAINT ck_resultat_du_test
CHECK (resultat IN ('OK', 'FAIL'));