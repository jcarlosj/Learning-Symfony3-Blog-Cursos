# Consultas de prueba
# use blog;

# Entrada por id = 1 y categoria a la que pertenece
select e.id, e.title, c.name from entries e, categories c
  where e.id = 1
    and c.id = e.id;

# Listar todos los tags que están relacionados con la entrada 1
select t.name from tags t
  where t.id in (
    select id from entry_tag
      where entry_id = 1
  )
  order by id desc;
