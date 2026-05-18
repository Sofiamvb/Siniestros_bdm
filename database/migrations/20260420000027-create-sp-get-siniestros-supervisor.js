'use strict';

module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_supervisor`);
    await q(`
      CREATE PROCEDURE sp_get_siniestros_supervisor()
      BEGIN
        SELECT
          s.id                                          AS id,
          s.supervisor_id                               AS supervisor_id,
          s.numero_reporte                              AS numero_reporte,
          s.fecha_hora_siniestro                        AS fecha_hora_siniestro,
          s.latitud                                     AS latitud,
          s.longitud                                    AS longitud,
          s.conductor_momento                           AS conductor_momento,
          s.descripcion_hechos                          AS descripcion_hechos,
          s.dictamen_supervisor                         AS dictamen_supervisor,
          s.presupuesto_reparacion                      AS presupuesto_reparacion,
          s.perdida_total                               AS perdida_total,
          ces.descripcion_interna                       AS estatus,
          ces.color_ui                                  AS estatus_color,
          CONCAT(duenio.nombre, ' ', duenio.apellidos)  AS duenio_nombre,
          duenio.email                                  AS duenio_email,
          v.marca                                       AS marca,
          v.modelo                                      AS modelo,
          v.anio                                        AS anio,
          p.placas                                      AS placas,
          p.numero_poliza                               AS numero_poliza,
          p.fecha_inicio                                AS fecha_inicio,
          p.fecha_fin                                   AS fecha_fin,
          cs.nombre_comercial                           AS compania,
          CONCAT(aj.nombre, ' ', aj.apellidos)          AS ajustador_nombre,
          (SELECT e.archivo_multimedia
           FROM evidencias_siniestro e
           WHERE e.siniestro_id = s.id
           ORDER BY e.fecha_subida ASC
           LIMIT 1)                                     AS primera_evidencia,
          (SELECT e.tipo_mime
           FROM evidencias_siniestro e
           WHERE e.siniestro_id = s.id
           ORDER BY e.fecha_subida ASC
           LIMIT 1)                                     AS primera_evidencia_mime
        FROM siniestros s
        INNER JOIN polizas p                        ON p.id = s.poliza_id
        INNER JOIN usuarios duenio                  ON duenio.id = p.usuario_id
        INNER JOIN vehiculos v                      ON v.id = p.catalogo_vehiculo_id
        INNER JOIN seguros seg                      ON seg.id = p.seguro_id
        INNER JOIN companias_seguros cs             ON cs.id = seg.compania_id
        INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
        INNER JOIN usuarios aj                      ON aj.id = s.ajustador_id
        ORDER BY s.id DESC;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestros_supervisor`);
  }
};
