SELECT
    s.name,
    quality_1_name,
    quality_2_name,
    nom_thickness::varchar || ' × ' || nom_width AS cut,
    nom_length::real / 1000 AS length,
    count(1) AS count_board,
    sum(nom_length::real / 1000 * nom_width::real / 1000 * nom_length::real / 1000) AS volume_boards
FROM
    ds.board b
    LEFT JOIN dic.species AS s ON (b.species_id = s.id)
WHERE drec BETWEEN '2021-02-19T08:52:21+08:00' and '2021-02-19T17:54:21+08:00'
GROUP BY
    s.name,
    quality_1_name,
    quality_2_name,
    cut,
    nom_length
ORDER BY
    s.name,
    cut,
    -- nom_thickness,
    -- nom_width,
    length