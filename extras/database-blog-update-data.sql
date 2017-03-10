# Actualizamos los roles y las contraseñas en la base de datos
update users as u SET role = 'ROLE_ADMIN', password = '$2a$04$qh.VuHLc8aSq0NVDUp4Wdu76rhmNW9StdjOKDzqtvsEVwvWQndRaq' WHERE u.id = 2;
update users as u SET role = 'ROLE_USER', password = '$2a$04$2zIWfZUR21cFtkJsEOYGzeynaDUnhE/VhChvO4rhbvIEwGsEz3IES' WHERE u.id = 1;  
