# SQL

Récupérer tous les films.

```sql
SELECT * FROM movie
```

Récupérer les acteurs et leur(s) rôle(s) pour un film donné.

```sql
SELECT person.*, casting.* FROM casting
INNER JOIN person ON person.id = casting.person_id
WHERE movie_id = 1

/*
select * from person
inner join casting on person.id = casting.person_id
where casting.movie_id = 15
*/
```

Récupérer les genres associés à un film donné.

```sql
select genre.* 
from genre
inner join movie_genre
on genre.id = movie_genre.genre_id
inner join movie
on movie.id = movie_genre.movie_id
where movie.id = 2 

/*
select genre.*
from genre
inner join movie_genre
on genre.id = movie_genre.genre_id
where movie_genre.movie_id = 2
*/

```

Récupérer les saisons associées à un film/série donné.

```sql
select season.*
from season
inner join movie
on movie.id = season.movie_id
where movie.id = 2 
```

Récupérer les critiques pour un film donné.

```sql
SELECT review.content FROM review
INNER JOIN movie ON movie.id = review.movie_id
WHERE movie.id = 1
```

Récupérer les critiques pour un film donné, ainsi que le nom de l'utilisateur associé.

```sql
SELECT review.content, user.nickname FROM review
INNER JOIN movie ON movie.id = review.movie_id
INNER JOIN user ON user.id = review.user_id
WHERE movie.id = 1
```

Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).

```sql
-- Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).
select AVG(review.rating), review.movie_id from review
-- where review.movie_id = 1
GROUP BY review.movie_id 
```

Idem pour un film donné.

```sql
-- Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).
select AVG(review.rating), review.movie_id from review
where review.movie_id = 1
-- GROUP BY review.movie_id 
```

Requêtes de recherche

```sql
SELECT 
```

Récupérer tous les films pour une année de sortie donnée.

```sql
SELECT 
```

Récupérer tous les films dont le titre est fourni (titre complet).

```sql
SELECT 
```

Récupérer tous les films dont le titre contient une chaîne donnée.

```sql
-- Récupérer tous les films dont le titre contient une chaîne donnée.

SELECT *
FROM movie
WHERE title LIKE 'Th%'
```

Bonus : Pagination

```sql
-- LIMIT 5 = je veux 5 résultats
select AVG(review.rating), review.movie_id from review
GROUP BY review.movie_id 
LIMIT 5

-- LIMIT 5, 10 = je veux 10 résultats en ommetant les 5 premiers (6 à 15)
select AVG(review.rating), review.movie_id from review
GROUP BY review.movie_id 
LIMIT 5, 10
```

Nombre de films par page : 10 (par ex.)

Récupérer la liste des films de la page 5 (grâce à LIMIT).
Testez la requête en faisant varier le nombre de films par page et le numéro de page.

```sql
-- Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).
select AVG(review.rating), review.movie_id from review
-- where review.movie_id = 1
GROUP BY review.movie_id 
-- calcul en PHP : 10xN 
--  N étant le numéro de la page-1 
--  10 étant le nombre de film par page
--  eg : page 5 =10x(5-1) = 40
-- le numéro de page viendrait de la route
LIMIT 40, 10
```
