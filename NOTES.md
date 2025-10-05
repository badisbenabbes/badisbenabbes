# Notes

## Choix techniques

### DTO
Les DTO servent d’interface entre l’API et la logique métier.  
Ils permettent de :
- découpler l’API des entités Doctrine
- centraliser la validation des entrées (avec `Symfony\Validator`)
- garantir la stabilité du contrat API même si le modèle interne évolue.

Chaque DTO (`CreateProduct`, `UpdateProduct`) contient uniquement les champs nécessaires à la requête, et aucune logique métier.

### Découplage de Doctrine
- Le contrôleur ne manipule jamais d’entités Doctrine directement.
- La couche `Service` s’occupe de la persistance et renvoie des tableaux “propres” via `ProductMapper`.
- Cela permet à l’API de rester indépendante de l’ORM et facilement testable (on pourrait remplacer Doctrine par un autre backend sans toucher l’API).

### Autres choix
- `SkuGenerator` : service stateless, testé unitairement.
- `ProductService` : centralise la logique métier.
- `ProductMapper` : transforme les entités en données JSON prêtes à retourner.
- `Validator` : valide systématiquement les DTO avant toute action.

## Préparer API pour la prod
- Rajouter un cache HTTP.
- Ajouter un cache applicatif
- Rajouter un doctrine result cache
- Rajouter des index dans certains champs fréquement recherchés
- Séparer la lecture/écriture
- Utiliser un load balancer

## Points d’amélioration
- Gérer la suppression (`DELETE /api/products/{id}`).
- Introduire des exceptions métiers propres (ex: ProductNotFoundException).

## Risques de la fonctionnalité telle que spécifiée
- Couplage fort avec Doctrine
- Pas de précision ni de structure pour gérer les erreurs
- Aucune sécurité (exemple: mécanisme d'authentification)