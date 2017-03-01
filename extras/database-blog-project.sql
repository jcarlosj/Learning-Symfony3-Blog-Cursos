# Modelo entidad relacion (MER) de la base de datos (DB-RDM/MER-BD)

# Crear la base de datos
create database if not exists blog default character set utf8 collate utf8_general_ci;

# Usar la base de datos blog
use blog;

# Crea tabla usuarios
create table if not exists users(
  # Define campos, dimensiones, tipos
  id        int( 255 ) auto_increment not null,
  name      varchar( 255 ),
  surname   varchar( 255 ),
  email     varchar( 255 ),
  password  varchar( 255 ),
  role      varchar( 20 ),
  image     varchar( 255 ),
  # Define restricciones
  constraint pk_users primary key( id )
) engine = innodb;

# Crea tabla categorias
create table if not exists categories(
  # Define campos, dimensiones, tipos
  id          int( 255 ) auto_increment not null,
  name        varchar( 255 ),
  description text,
  # Define restricciones
  constraint pk_categories primary key( id )
) engine = innodb;

# Crea tabla de entradas
create table if not exists entries(
  # Define campos, dimensiones, tipos
  id          int( 255 ) auto_increment not null,
  user_id     int( 255 ) not null,
  category_id int( 255 ) not null,
  title       varchar( 255 ),
  content     text,
  status      varchar( 20 ),
  image       varchar( 255 ),
  # Define restricciones
  constraint pk_entries primary key( id ),
  constraint fk_entries_users foreign key( user_id ) references users( id ),
  constraint fk_entries_categories foreign key( category_id ) references categories( id )
) engine = innodb;

# Crea tabla de Tags
create table if not exists tags(
  # Define campos, dimensiones, tipos
  id          int( 255 ) auto_increment not null,
  name        varchar( 255 ),
  description text,
  # Define restricciones
  constraint pk_tags primary key( id )
) engine = innodb;

# Tabla pivote entre las tablas entries y tags
create table if not exists entry_tag(
  # Define campos, dimensiones, tipos
  id          int( 255 ) auto_increment not null,
  entry_id    int( 255 ) not null,
  tag_id      int( 255 ) not null,
  # Define restricciones
  constraint pk_entry_tag primary key( id ),
  constraint fk_entry_tag_entries foreign key( entry_id ) references entries( id ),
  constraint fk_entry_tag_tags foreign key( tag_id ) references tags( id )
) engine = innodb;
