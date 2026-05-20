'use strict';

/**
 * View #4/8: v_siniestros_asegurado
 * SP para obtener siniestros del asegurado con filtro opcional de fechas.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP VIEW IF EXISTS v_siniestros_asegurado`);
    await q(`
      CREATE VIEW v_siniestros_asegurado AS
      SELECT
        s.id,
        s.ajustador_id,
        s.supervisor_id,
        s.numero_reporte,
        s.fecha_hora_siniestro,
        s.descripcion_hechos,
        s.presupuesto_reparacion,
        s.perdida_total,
        ces.descripcion_interna                       AS estatus,
        ces.color_ui                                  AS estatus_color,
        p.usuario_id,
        p.numero_poliza,
        p.placas,
        v.marca,
        v.modelo,
        v.anio,
        cs.nombre_comercial                           AS compania,
        CONCAT(aj.nombre, ' ', aj.apellidos)          AS ajustador_nombre,
        (SELECT e.archivo_multimedia
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia,
        (SELECT e.tipo_mime
         FROM evidencias_siniestro e
         WHERE e.siniestro_id = s.id
         ORDER BY e.fecha_subida ASC LIMIT 1)         AS primera_evidencia_mime
      FROM siniestros s
      INNER JOIN polizas p                        ON p.id  = s.poliza_id
      INNER JOIN vehiculos v                      ON v.id  = p.catalogo_vehiculo_id
      INNER JOIN seguros seg                      ON seg.id = p.seguro_id
      INNER JOIN companias_seguros cs             ON cs.id  = seg.compania_id
      INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
      INNER JOIN usuarios aj                      ON aj.id  = s.ajustador_id
    `);

    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_asegurado`);
    await q(`
      CREATE PROCEDURE sp_get_siniestros_asegurado(
        IN p_usuario_id INT,
        IN p_inicio     DATE,
        IN p_fin        DATE
      )
      BEGIN
        SELECT
          id, ajustador_id, supervisor_id, numero_reporte,
          fecha_hora_siniestro, descripcion_hechos,
          presupuesto_reparacion, perdida_total,
          estatus, estatus_color,
          usuario_id, numero_poliza, placas,
          marca, modelo, anio, compania, ajustador_nombre,
          primera_evidencia, primera_evidencia_mime
        FROM v_siniestros_asegurado
        WHERE usuario_id = p_usuario_id
          AND fn_en_rango_fecha(fecha_hora_siniestro, p_inicio, p_fin) = 1
        ORDER BY id DESC;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_asegurado`);
    await q(`DROP VIEW IF EXISTS v_siniestros_asegurado`);
  }
};
