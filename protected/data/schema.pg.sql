--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE categories (
    id integer NOT NULL,
    parent_id integer,
    title character varying(64) NOT NULL,
    description text,
    img_file character varying(128)
);


ALTER TABLE public.categories OWNER TO eshop;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: eshop
--

CREATE SEQUENCE categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO eshop;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: eshop
--

ALTER SEQUENCE categories_id_seq OWNED BY categories.id;


--
-- Name: order_history; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE order_history (
    order_id integer NOT NULL,
    status character varying(32) NOT NULL,
    description text,
    ordered character varying(32)
);


ALTER TABLE public.order_history OWNER TO eshop;

--
-- Name: order_items; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE order_items (
    order_id integer NOT NULL,
    product_id integer NOT NULL,
    price double precision,
    qty integer
);


ALTER TABLE public.order_items OWNER TO eshop;

--
-- Name: orders; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE orders (
    id integer NOT NULL,
    user_id integer NOT NULL,
    ordered character varying(32),
    approved character varying(32),
    customer character varying(32),
    phone character varying(32),
    email character varying(64),
    address text,
    amount double precision,
    qty integer,
    payment_system character varying(16) DEFAULT 'email'::character varying NOT NULL
);


ALTER TABLE public.orders OWNER TO eshop;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: eshop
--

CREATE SEQUENCE orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.orders_id_seq OWNER TO eshop;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: eshop
--

ALTER SEQUENCE orders_id_seq OWNED BY orders.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE products (
    id integer NOT NULL,
    title character varying(64) NOT NULL,
    description text,
    price double precision,
    img_file character varying(128),
    on_special integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.products OWNER TO eshop;

--
-- Name: products_categories; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE products_categories (
    product_id integer,
    category_id integer NOT NULL
);


ALTER TABLE public.products_categories OWNER TO eshop;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: eshop
--

CREATE SEQUENCE products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO eshop;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: eshop
--

ALTER SEQUENCE products_id_seq OWNED BY products.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: eshop; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    username character varying(32) NOT NULL,
    password character varying(64) NOT NULL,
    firstname character varying(64) NOT NULL,
    lastname character varying(64) NOT NULL,
    email character varying(128) NOT NULL,
    phone character varying(11),
    address text,
    salt character(10) NOT NULL,
    newpassword character varying(64),
    _roles text
);


ALTER TABLE public.users OWNER TO eshop;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: eshop
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO eshop;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: eshop
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY categories ALTER COLUMN id SET DEFAULT nextval('categories_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY orders ALTER COLUMN id SET DEFAULT nextval('orders_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY products ALTER COLUMN id SET DEFAULT nextval('products_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: categories_pkey; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: order_history_pkey; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY order_history
    ADD CONSTRAINT order_history_pkey PRIMARY KEY (order_id, status);


--
-- Name: order_items_pk; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_pk PRIMARY KEY (order_id, product_id);


--
-- Name: orders_pkey; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: products_categories_ui_0; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY products_categories
    ADD CONSTRAINT products_categories_ui_0 UNIQUE (product_id, category_id);


--
-- Name: products_pkey; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: eshop; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: categories_parent_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_parent_fk FOREIGN KEY (parent_id) REFERENCES categories(id);


--
-- Name: order_history_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY order_history
    ADD CONSTRAINT order_history_fk FOREIGN KEY (order_id) REFERENCES orders(id);


--
-- Name: order_items_order_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_order_fk FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: order_items_product_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_product_fk FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: orders_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_user_id_fk FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: products_categories_category_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY products_categories
    ADD CONSTRAINT products_categories_category_fk FOREIGN KEY (category_id) REFERENCES categories(id);


--
-- Name: products_categories_product_fk; Type: FK CONSTRAINT; Schema: public; Owner: eshop
--

ALTER TABLE ONLY products_categories
    ADD CONSTRAINT products_categories_product_fk FOREIGN KEY (product_id) REFERENCES products(id);


--
-- PostgreSQL database dump complete
--
