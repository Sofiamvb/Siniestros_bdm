'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Vista: incluye ajustador_id y usuario_id para filtrar por rol
    await q(`DROP VIEW IF EXISTS v_buscador_siniestros`);
    await q(`
      CREATE VIEW v_buscador_siniestros AS
      SELECT
        s.id                                          AS siniestro_id,
        s.ajustador_id                                AS ajustador_id,
        s.numero_reporte                              AS numero_reporte,
        s.fecha_hora_siniestro                        AS fecha_hora_siniestro,
        p.usuario_id                                  AS usuario_id,
        p.numero_poliza                               AS numero_poliza,
        p.placas                                      AS placas,
        v.marca                                       AS marca,
        v.modelo                                      AS modelo,
        v.anio                                        AS anio,
        CONCAT(u.nombre, ' ', u.apellidos)            AS duenio_nombre,
        ces.descripcion_interna                       AS estatus,
        ces.color_ui                                  AS estatus_color
      FROM siniestros s
      INNER JOIN polizas p                        ON p.id = s.poliza_id
      INNER JOIN vehiculos v                      ON v.id = p.catalogo_vehiculo_id
      INNER JOIN usuarios u                       ON u.id = p.usuario_id
      INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
    `);

    // SP: filtra por término de búsqueda y por rol/usuario
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
          p_rol_id = 3                                    -- supervisor: todos
          OR (p_rol_id = 2 AND ajustador_id = p_usuario_id)  -- ajustador: solo los suyos
          OR (p_rol_id = 1 AND usuario_id   = p_usuario_id)  -- asegurado: solo sus polizas
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
    await q(`DROP VIEW IF EXISTS v_buscador_siniestros`);
  }
};
