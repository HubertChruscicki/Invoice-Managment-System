create type permission_name as enum ('admin', 'moderator');

alter type permission_name owner to root;

create type invoice_info as enum ('no-invoice', 'contain-invoice');

alter type invoice_info owner to root;

create type role_enum as enum ('admin', 'moderator');

alter type role_enum owner to root;

create type invoice_status_enum as enum ('no-invoice', 'contain-invoice');

alter type invoice_status_enum owner to root;

create table if not exists user_role
(
    id        serial
        primary key,
    role_name role_enum not null
);

alter table user_role
    owner to root;

create table if not exists product_categories
(
    id         serial
        primary key,
    name       text          not null,
    vat        numeric(5, 2) not null,
    is_deleted boolean default false
);

alter table product_categories
    owner to root;

create table if not exists clients
(
    id         serial
        primary key,
    name       text not null,
    nip        text not null,
    address    text not null,
    city       text not null,
    zip_code   text not null,
    country    text not null,
    is_deleted boolean default false
);

alter table clients
    owner to root;

create table if not exists company
(
    id       serial
        primary key,
    name     text not null,
    nip      text not null,
    address  text not null,
    city     text not null,
    zip_code text not null,
    country  text not null
);

alter table company
    owner to root;

create table if not exists users
(
    id           serial
        primary key,
    id_user_role integer
                      references user_role
                          on delete set null,
    id_company   integer
                      references company
                          on delete set null,
    name         text not null,
    surname      text not null,
    email        text not null
        unique,
    password     text not null
);

alter table users
    owner to root;

create table if not exists products
(
    id           serial
        primary key,
    id_category  integer
                                references product_categories
                                    on delete set null,
    id_company   integer
        references company
            on delete cascade,
    name         text           not null,
    price_brutto numeric(10, 2) not null,
    price_netto  numeric(10, 2) not null,
    is_deleted   boolean default false
);

alter table products
    owner to root;

create table if not exists company_clients
(
    id         serial
        primary key,
    id_company integer
        references company
            on delete cascade,
    id_client  integer
        references clients
            on delete cascade
);

alter table company_clients
    owner to root;

create table if not exists invoice
(
    id         serial
        primary key,
    id_client  integer
        references clients
            on delete cascade,
    id_company integer
        references company
            on delete cascade,
    date       date    default CURRENT_DATE not null,
    is_deleted boolean default false
);

alter table invoice
    owner to root;

create table if not exists sales
(
    id         serial
        primary key,
    id_invoice integer
                                   references invoice
                                       on delete set null,
    id_company integer
        references company
            on delete cascade,
    status     invoice_status_enum not null
);

alter table sales
    owner to root;

create table if not exists sale_products
(
    id         serial
        primary key,
    id_sale    integer
        references sales
            on delete cascade,
    id_product integer
        references products
            on delete cascade,
    quantity   integer not null
);

alter table sale_products
    owner to root;

create table if not exists company_categories
(
    id          serial
        primary key,
    id_company  integer
        references company
            on delete cascade,
    id_category integer
        references product_categories
            on delete cascade
);

alter table company_categories
    owner to root;

create or replace view client_details
            (client_id, client_name, client_city, client_country, company_name, company_city, company_country) as
SELECT c.id        AS client_id,
       c.name      AS client_name,
       c.city      AS client_city,
       c.country   AS client_country,
       com.name    AS company_name,
       com.city    AS company_city,
       com.country AS company_country
FROM clients c
         JOIN company_clients cc ON c.id = cc.id_client
         JOIN company com ON cc.id_company = com.id;

alter table client_details
    owner to root;

create or replace view product_details
            (product_id, product_name, price_brutto, price_netto, category_name, company_name) as
SELECT p.id     AS product_id,
       p.name   AS product_name,
       p.price_brutto,
       p.price_netto,
       pc.name  AS category_name,
       com.name AS company_name
FROM products p
         JOIN product_categories pc ON p.id_category = pc.id
         JOIN company com ON p.id_company = com.id;

alter table product_details
    owner to root;

create or replace function set_product_as_deleted() returns trigger
    language plpgsql
as
$$
begin
    update public.products
    set is_deleted = true
    where id = old.id;
    return null; -- zatrzymanie faktycznego usuwania
end;
$$;

alter function set_product_as_deleted() owner to root;

create trigger trigger_set_product_as_deleted
    before delete
    on products
    for each row
execute procedure set_product_as_deleted();

create or replace function calculate_invoice_total(invoice_id integer) returns numeric
    language plpgsql
as
$$
declare
    total_brutto numeric(10, 2);
begin
    select
        coalesce(sum(sp.quantity * p.price_brutto), 0)
    into
        total_brutto
    from
        public.sale_products sp
            join
        public.products p on sp.id_product = p.id
            join
        public.sales s on sp.id_sale = s.id
    where
        s.id_invoice = invoice_id;

    return total_brutto;
end;
$$;

alter function calculate_invoice_total(integer) owner to root;

