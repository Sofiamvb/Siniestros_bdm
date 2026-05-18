'use strict';

/**
 * Fix: conflicto de collations (utf8mb4_general_ci vs utf8mb4_0900_ai_ci)
 * en sp_buscar_siniestros. Se declara el parámetro de texto con collation
 * explícita para que MySQL no mezcle collations en los LIKE / =.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_buscar_siniestros`);
    await q(`
      CREATE PROCEDURE sp_buscar_siniestros(
        IN p_termino    VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
        IN p_rol_id     INT,
        IN p_usuario_id INT
      )
      BEGIN
        SELECT
          siniestro_id,
          numero_reporte,
          fecha_hora_siniestro,
          numero_poliza,
          placas,
          marca,
          modelo,
          anio,
          duenio_nombre,
          estatus,
          estatus_color,
          CASE
            WHEN CAST(siniestro_id AS CHAR) = p_termino             THEN 'siniestro'
            WHEN placas        LIKE CONCAT('%', p_termino, '%')      THEN 'placa'
            WHEN numero_poliza LIKE CONCAT('%', p_termino, '%')      THEN 'poliza'
          END AS tipo_match
        FROM v_buscador_siniestros
        WHERE (
          CAST(siniestro_id AS CHAR) = p_termino
          OR placas        LIKE CONCAT('%', p_termino, '%')
          OR numero_poliza LIKE CONCAT('%', p_termino, '%')
        )
        AND (
          p_rol_id = 3
          OR (p_rol_id = 2 AND ajustador_id = p_usuario_id)
          OR (p_rol_id = 1 AND usuario_id   = p_usuario_id)
        )
        ORDER BY
          CASE
            WHEN CAST(siniestro_id AS CHAR) = p_termino        THEN 0
            WHEN placas        LIKE CONCAT('%', p_termino, '%') THEN 1
            WHEN numero_poliza LIKE CONCAT('%', p_termino, '%') THEN 2
          END,
          siniestro_id DESC
        LIMIT 20;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_buscar_siniestros`);
  }
};
