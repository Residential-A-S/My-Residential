<?php

global $db;
$sql = <<<'SQL'
CREATE TABLE issues (
id                  int(10) NOT NULL AUTO_INCREMENT,
rental_agreement_id int(10) NOT NULL,
payment_id          int(10),
name                varchar(255) NOT NULL,
description         varchar(255) NOT NULL,
status              varchar(255) NOT NULL,
created_at          datetime NOT NULL,
updated_at          datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id),
INDEX (rental_agreement_id),
INDEX (payment_id));

CREATE TABLE organizations (
id         int(10) NOT NULL AUTO_INCREMENT,
name       varchar(255) NOT NULL,
created_at datetime NOT NULL,
updated_at datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id));

CREATE TABLE password_resets (
user_id    int(10) NOT NULL,
token      varchar(255) NOT NULL,
expires_at datetime NOT NULL,
created_at datetime NOT NULL,
PRIMARY KEY (user_id),
INDEX (user_id),
UNIQUE INDEX (token));

CREATE TABLE payments (
id         int(10) NOT NULL AUTO_INCREMENT,
amount     float NOT NULL,
currency   varchar(255) NOT NULL,
created_at datetime NOT NULL,
due_at     datetime NOT NULL,
paid_at    datetime NULL,
PRIMARY KEY (id),
INDEX (id));

CREATE TABLE properties (
id              int(10) NOT NULL AUTO_INCREMENT,
organization_id int(10) NOT NULL,
street_name     varchar(255) NOT NULL,
street_number   varchar(255) NOT NULL,
zip_code        varchar(255) NOT NULL,
city            varchar(255) NOT NULL,
country         varchar(255) NOT NULL,
created_at      datetime NOT NULL,
updated_at      datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id),
INDEX (organization_id));

CREATE TABLE rental_agreements (
id               int(10) NOT NULL AUTO_INCREMENT,
rental_unit_id   int(10) NOT NULL,
start_date       datetime NOT NULL,
end_date         datetime NULL,
status           varchar(255) NOT NULL,
payment_interval varchar(255) NOT NULL,
created_at       datetime NOT NULL,
updated_at       datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id),
INDEX (rental_unit_id));

CREATE TABLE rental_agreements_documents (
id                  int(10) NOT NULL AUTO_INCREMENT,
rental_agreement_id int(10) NOT NULL,
file_name           varchar(255) NOT NULL,
file_path           varchar(255) NOT NULL,
file_type           varchar(255) NOT NULL,
uploaded_at         datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id),
INDEX (rental_agreement_id));

CREATE TABLE rental_agreements_payments (
rental_agreement_id int(10) NOT NULL,
payment_id          int(10) NOT NULL,
period_start        datetime NOT NULL,
period_end          datetime NOT NULL,
PRIMARY KEY (rental_agreement_id,
payment_id),
INDEX (rental_agreement_id),
INDEX (payment_id));

CREATE TABLE rental_units (
id          int(10) NOT NULL AUTO_INCREMENT,
property_id int(10) NOT NULL,
name        varchar(255) NOT NULL,
status      varchar(255),
created_at  datetime NOT NULL,
updated_at  datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id),
INDEX (property_id));

CREATE TABLE tenants (
id                  int(10) NOT NULL AUTO_INCREMENT,
first_name          varchar(255) NOT NULL,
last_name           varchar(255) NOT NULL,
email               varchar(255) NOT NULL,
phone               varchar(255) NOT NULL,
created_at          datetime NOT NULL,
updated_at          datetime NOT NULL,
PRIMARY KEY (id),
INDEX (id));

CREATE TABLE users (
id              int(10) NOT NULL AUTO_INCREMENT,
email           varchar(255) NOT NULL,
password_hash   varchar(255) NOT NULL,
name            varchar(255) NOT NULL,
created_at      datetime NOT NULL,
updated_at      datetime NOT NULL,
last_login_at   datetime NULL,
failed_attempts int(2) NOT NULL,
PRIMARY KEY (id),
INDEX (id),
UNIQUE INDEX (email));

CREATE TABLE users_organizations (
user_id         int(10) NOT NULL,
organization_id int(10) NOT NULL,
role            varchar(255) NOT NULL,
PRIMARY KEY (user_id,
organization_id),
INDEX (user_id),
INDEX (organization_id));

CREATE TABLE tenants_rental_agreements (
  tenant_id           int(10) NOT NULL, 
  rental_agreement_id int(10) NOT NULL, 
  PRIMARY KEY (tenant_id, 
  rental_agreement_id));

ALTER TABLE issues ADD CONSTRAINT FKissues962101 
    FOREIGN KEY (payment_id) REFERENCES payments (id) ON DELETE Cascade;
ALTER TABLE issues ADD CONSTRAINT FKissues224786 
    FOREIGN KEY (rental_agreement_id) REFERENCES rental_agreements (id) ON DELETE Cascade;
ALTER TABLE rental_agreements_documents ADD CONSTRAINT FKrental_agr882877 
    FOREIGN KEY (rental_agreement_id) REFERENCES rental_agreements (id) ON DELETE Cascade;
ALTER TABLE rental_agreements_payments ADD CONSTRAINT FKrental_agr29728 
    FOREIGN KEY (payment_id) REFERENCES payments (id) ON DELETE Cascade;
ALTER TABLE rental_agreements_payments ADD CONSTRAINT FKrental_agr204547 
    FOREIGN KEY (rental_agreement_id) REFERENCES rental_agreements (id) ON DELETE Cascade;
ALTER TABLE tenants_rental_agreements ADD CONSTRAINT FKtenants_re607709 
    FOREIGN KEY (rental_agreement_id) REFERENCES rental_agreements (id) ON DELETE Cascade;
ALTER TABLE tenants_rental_agreements ADD CONSTRAINT FKtenants_re384127 
    FOREIGN KEY (tenant_id) REFERENCES tenants (id) ON DELETE Cascade;
ALTER TABLE rental_agreements ADD CONSTRAINT FKrental_agr257074 
    FOREIGN KEY (rental_unit_id) REFERENCES rental_units (id) ON DELETE Cascade;
ALTER TABLE rental_units ADD CONSTRAINT FKrental_uni92844 
    FOREIGN KEY (property_id) REFERENCES properties (id) ON DELETE Cascade;
ALTER TABLE properties ADD CONSTRAINT FKproperties679510 
    FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE Cascade;
ALTER TABLE password_resets ADD CONSTRAINT FKpassword_r969087 
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE Cascade;
ALTER TABLE users_organizations ADD CONSTRAINT FKusers_orga405355 
    FOREIGN KEY (organization_id) REFERENCES organizations (id);
ALTER TABLE users_organizations ADD CONSTRAINT FKusers_orga479909 
    FOREIGN KEY (user_id) REFERENCES users (id);
SQL;
$db->exec($sql);
