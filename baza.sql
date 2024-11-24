-- Tworzenie typów ENUM
CREATE TYPE permission_name AS ENUM ('admin', 'moderator');
CREATE TYPE invoice_info AS ENUM ('no-invoice', 'contain-invoice');

-- Tworzenie tabel
CREATE TABLE permissions (
                             ID SERIAL PRIMARY KEY,
                             name permission_name NOT NULL
);

CREATE TABLE user_role (
                           ID SERIAL PRIMARY KEY,
                           role_name VARCHAR(50) NOT NULL
);

CREATE TABLE role_permissions (
                                  ID SERIAL PRIMARY KEY,
                                  id_role INT NOT NULL REFERENCES user_role(ID),
                                  id_permission INT NOT NULL REFERENCES permissions(ID)
);

CREATE TABLE "users" (
                         ID SERIAL PRIMARY KEY,
                         id_user_role INT NOT NULL REFERENCES user_role(ID),
                         name VARCHAR(50) NOT NULL,
                         surname VARCHAR(50) NOT NULL,
                         email VARCHAR(100) UNIQUE NOT NULL,
                         password VARCHAR(100) NOT NULL
);

CREATE TABLE clients (
                         ID SERIAL PRIMARY KEY,
                         client_company_name VARCHAR(100) NOT NULL,
                         nip VARCHAR(20) NOT NULL,
                         address VARCHAR(150),
                         city VARCHAR(50),
                         zip_code VARCHAR(20),
                         country VARCHAR(50)
);

CREATE TABLE product_categories (
                                    ID SERIAL PRIMARY KEY,
                                    name VARCHAR(50) NOT NULL,
                                    vat NUMERIC(5, 2) NOT NULL
);

CREATE TABLE invoice (
                         ID SERIAL PRIMARY KEY,
                         ID_client INT NOT NULL REFERENCES clients(ID),
                         ID_company INT NOT NULL REFERENCES company(ID)
);

CREATE TABLE sales (
                       ID SERIAL PRIMARY KEY,
                       ID_client INT REFERENCES clients(ID),
                       ID_company INT REFERENCES company(ID),
                       ID_invoice INT REFERENCES invoice(ID),
                       is_invoice invoice_info NOT NULL
);

CREATE TABLE company (
                         ID SERIAL PRIMARY KEY,
                         ID_user INT REFERENCES "users"(ID),
                         ID_sale INT REFERENCES sales(ID),
                         name VARCHAR(100) NOT NULL,
                         nip VARCHAR(20) NOT NULL,
                         address VARCHAR(150),
                         city VARCHAR(50),
                         zip_code VARCHAR(20),
                         country VARCHAR(50)
);

CREATE TABLE company_clients (
                                 ID SERIAL PRIMARY KEY,
                                 ID_company INT NOT NULL REFERENCES company(ID),
                                 ID_client INT NOT NULL REFERENCES clients(ID)
);

CREATE TABLE products (
                          ID SERIAL PRIMARY KEY,
                          ID_category INT NOT NULL REFERENCES product_categories(ID),
                          ID_company INT NOT NULL REFERENCES company(ID),
                          name VARCHAR(100) NOT NULL,
                          price_brutto NUMERIC(10, 2) NOT NULL,
                          price_netto NUMERIC(10, 2) NOT NULL,
                          discount NUMERIC(5, 2)
);

CREATE TABLE sale_products (
                               ID SERIAL PRIMARY KEY,
                               ID_sale INT NOT NULL REFERENCES sales(ID),
                               ID_product INT NOT NULL REFERENCES products(ID),
                               quantity INT NOT NULL
);
