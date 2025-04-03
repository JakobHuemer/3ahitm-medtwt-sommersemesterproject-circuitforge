# bin for Ideas


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