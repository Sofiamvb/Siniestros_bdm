'use strict';

/**
 * Fix v2: fuerza la collation del término en cada comparación individual
 * en lugar de en la declaración del parámetro, para que coincida con la
 * collation utf8mb4_general_ci de las columnas placas y numero_poliza.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_buscar_siniestros`);
    await q(`
      CREATE PROCEDURE sp_buscar_siniestros(
        IN p_termino    VARCHAR(100),
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
            WHEN CAST(siniestro_id AS CHAR) = p_termino COLLATE utf8mb4_general_ci
              THEN 'siniestro'
            WHEN placas        LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%')
              THEN 'placa'
            WHEN numero_poliza LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%')
              THEN 'poliza'
          END AS tipo_match
        FROM v_buscador_siniestros
        WHERE (
          CAST(siniestro_id AS CHAR) = p_termino COLLATE utf8mb4_general_ci
          OR placas        LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%')
          OR numero_poliza LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%')
        )
        AND (
          p_rol_id = 3
          OR (p_rol_id = 2 AND ajustador_id = p_usuario_id)
          OR (p_rol_id = 1 AND usuario_id   = p_usuario_id)
        )
        ORDER BY
          CASE
            WHEN CAST(siniestro_id AS CHAR) = p_termino COLLATE utf8mb4_general_ci        THEN 0
            WHEN placas        LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%') THEN 1
            WHEN numero_poliza LIKE CONCAT('%', p_termino COLLATE utf8mb4_general_ci, '%') THEN 2
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
