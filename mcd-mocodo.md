belongs to, 0N movies, 11 season
season: code, title, episode
own, 1N movies, 0N genre
genre: code, name
play, 1N movies, 0N actor : role
actor: code, firstname, lastname

movies: code, type, title, duration, summary, synopsis, rating, date, poster

user: code, firstname, lastname, password, email, role, status
write, 0N user, 11 review
review: code, title, decsription, date, rating
comment, 0N movies, 11 review