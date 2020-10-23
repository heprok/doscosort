SELECT
    s.name,
    qualities,
    nom_thickness::varchar || ' × ' || nom_width AS cut,
    nom_length::real / 1000 AS length,
    count(1) AS count_board,
    sum(nom_length::real / 1000 * nom_width::real / 1000 * nom_length::real / 1000) AS volume_boards
FROM
    ds.board b
    LEFT JOIN dic.species AS s ON (b.species_id = s.id)
GROUP BY
    s.name,
    qualities,
    cut,
    nom_length
ORDER BY
    s.name,
    cut,
    -- nom_thickness,
    -- nom_width,
    length
