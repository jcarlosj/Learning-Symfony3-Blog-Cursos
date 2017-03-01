# Entradas por defecto de la base de datos (D-DB-E/ED-BD)

#Insertar categorias
insert into categories ( id, name, description )
            values( null, 'Desarrollo web', 'Categoría de desarrollo web' ),
                  ( null, 'Desarrollo Android', 'Categoría de desarrollo Android' );

# Insertar tags por defecto
insert into tags ( id, name, description )
            values ( null, 'php', 'php tag'),
                   ( null, 'symfony', 'symfony tag'),
                   ( null, 'html5', 'html5 tag'),
                   ( null, 'zend', 'Apuntate al curso de Zend Framework 2');

# Insertar usuarios por defecto
insert into users ( id, role, name, surname, email, password, image )
            values ( null, 'admin', 'Juan Carlos', 'Jiménez Gutiérrez', 'jcjimenez29@misena.edu.co', 'password-de-prueba', null ),
                   ( null, 'user', 'Janeth Eva Sofía', 'Gutiérrez González', 'eva@correo.co', 'password-de-eva', null );

# Insertar entradas por defecto
insert into entries ( id, user_id, category_id, title, content, status, image )
            values ( null, 1, 1, 'Entrada de desarrollo con PHP', 'Este es el contenido del curso de desarrollo con PHP...', 'public', null ),
                   ( null, 2, 2, 'Entrada de desarrollo con Android', 'Este es el contenido del curso de desarrollo con Android...', 'public', null );

# Insertar relaciones entre entradas y tags
insert into entry_tag ( id, entry_id, tag_id )
            values( null, 1, 2 ),
                  ( null, 1, 1 ),
                  ( null, 2, 1 ),
                  ( null, 2, 3 );
