<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>recette facile</title>
  <meta name="description" content="recette facile">
</head>
<body><pre><?php

  // séparer ses identifiants et les protéger, une bonne habitude à prendre
  include "RecetteFaciledbconf.php";

  try {

    // instancie un objet $connexion à partir de la classe PDO
    $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);

    // Requête de sélection 01
    $requete = "SELECT * FROM `recettes`";
    $prepare = $connexion->prepare($requete);
    $prepare->execute();
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    // / Requête de sélection 01
    $requete = "SELECT * FROM `hashtags`";
    $prepare = $connexion->prepare($requete);
    $prepare->execute();
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    
    // Requête d'insertion
    $requete = "INSERT INTO `recettes` (`recette_id`, `recette_titre`, `recette_contenu`, `recette_datetime`) 
    VALUES (NULL, 'cookies', 'tous les manger', NOW())";
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedrecetteId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedrecetteId]); // debug & vérification

    $requete = "INSERT INTO `recettes` (`recette_id`, `recette_titre`, `recette_contenu`, `recette_datetime`) 
    VALUES (NULL, 'Pancakes', 'sirop d\'erable', NOW())";
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedrecetteId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedrecetteId]); // debug & vérification

    // Requête de modification
    $requete = "UPDATE `recettes`
                SET `recette_titre` = :recette_titre
                WHERE `recette_id` = :recette_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":recette_id"   => 3,
      ":recette_titre" => "😺 cookies"
    ));
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête de suppression
    $requete = "DELETE FROM `recettes`
                WHERE ((`recette_id` = :recette_id));";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array($lastInsertedrecetteId)); // on lui passe l'id tout juste créé
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat, $lastInsertedrecetteId]); // debug & vérification

    $requete = "INSERT INTO `hashtags` (`hashtag_id`, `hashtag_nom`) VALUES (NULL, 'levain')";
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedhashtagId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedhashtagId]); // debug & vérification

    $requete = "INSERT INTO `assoc_hashtags_recettes` ( `assoc_hr_hashtag_id`, `assoc_hr_recette_id`)
                 VALUES ( :hashtag_id, :recette_id);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":hashtag_id" => "4",
      ":recette_id" => "1"
    ));
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedAssocId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedAssocId]); // debug & vérification

  } catch (PDOException $e) {

    // en cas d'erreur, on récup et on affiche, grâce à notre try/catch
    exit("❌🙀💀 OOPS :\n" . $e->getMessage());

  }

  ?></pre></body>
</html>