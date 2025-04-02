# Documentation of CircuitForge


## Files

Datenbank export: [schema.sql](datamodel/schema.sql)


## Sprints

### Sprint 1. Projektantrag

[Projektantrag](projektantrag.md)

### Sprint 2. Figma Prototyp

Figma: https://www.figma.com/design/My7Y1fsFgrsLZDAbdR15ZG/CircuitForge?node-id=0-1&t=yzkKsVEjpbKWpXnm-1

Farben/Fonts: [Design.md](sysspec/frontend/design.md)


### Sprint 3. 

In Progress ...

search query for version:

```sql
SELECT *
FROM version
WHERE version LIKE CONCAT('%', ?, '%')
ORDER BY
    -- Exact match gets highest priority
    (version = ?) DESC,
    -- Then prefix match
    (version LIKE CONCAT(?, '%')) DESC,
    -- Then by Levenshtein distance (lower is better)
    LEVENSHTEIN(version, ?) ASC
    LIMIT 20;
```