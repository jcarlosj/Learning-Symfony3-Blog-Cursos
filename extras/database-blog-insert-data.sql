# Insertar datos para realizar prueba de despliegue
insert into entries ( id , user_id, category_id, title, content, status, image )
             values ( null, '1', '1', 'Desarrollo SPA con Angular', 'Contenido del curso de Angular para el desarrollo de una "Simple Page Application"', 'public', null ),
                    ( null, '1', '1', 'Desarrollo con Bootstrap 4', 'Contenido del curso de Bootstrap 4 para el desarrollo ágil de Frontend Responsive Web Design', 'public', null ),
                    ( null, '2', '2', 'Material Desing para Android', 'Contenido del curso de Material Design para Android', 'public', null );

# Insertar nuevos tags
insert into tags ( id, name, description )
          values ( null , 'delphi', 'Aplicaciones desarrolladas en Delphi' ),
                 ( null, 'C++', 'C++');

# Insertar nuevas categorías
insert into categories ( id, name, description )
                values ( null, 'Desarrollo de aplicaciones para iOS', 'Desarrollo de aplicaciones para iPhone y iPads' ),
                       ( null, 'Mobile Apps', 'Desarrollo de aplicaciones móviles' );

# Insertar Entradas nuevas
insert into entries ( id, user_id, category_id, title, content, status, image )
             values ( null, 2, 4, 'Desarrollo de aplicaciones móviles con Delphi RAD Studio X8', 'Curso de desarrollo de aplicaciones para Android y iPhone', null, null ),
                    ( null, 2, 4, 'Desarrollo de aplicaciones móviles con C++ RAD Studio X8', 'Curso de desarrollo de aplicaciones para Android y iPhone', null, null );

# Insertar Entrada x Tag
insert into entry_tag ( id, entry_id, tag_id )
               values ( null, 9, 5 ), 
                      ( null, 9, 6 ),
                      ( null, 10, 7 );
