'use strict';

module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── Datos principales del siniestro ──────────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
    await q(`
      CREATE PROCEDURE sp_get_siniestro_detalle(IN p_id INT)
      BEGIN
        SELECT
          s.id,
          s.numero_reporte,
          s.fecha_hora_siniestro,
          s.latitud,
          s.longitud,
          s.conductor_momento,
          s.descripcion_hechos,
          s.dictamen_supervisor,
          s.presupuesto_reparacion,
          s.perdida_total,
          s.ajustador_id,
          s.supervisor_id,
          ces.descripcion_interna                         AS estatus,
          ces.color_ui                                    AS estatus_color,
          p.id                                            AS poliza_id,
          p.numero_poliza,
          p.placas,
          p.fecha_inicio,
          p.fecha_fin,
          p.usuario_id,
          v.marca,
          v.modelo,
          v.anio,
          v.version,
          seg.nombre_seguro,
          seg.nivel,
          seg.suma_asegurada,
          cs.nombre_comercial                             AS compania,
          CONCAT(aj.nombre,  ' ', aj.apellidos)           AS ajustador_nombre,
          CONCAT(dn.nombre,  ' ', dn.apellidos)           AS duenio_nombre,
          dn.email                                        AS duenio_email,
          CONCAT(sup.nombre, ' ', sup.apellidos)          AS supervisor_nombre
        FROM siniestros s
        INNER JOIN polizas p                        ON p.id  = s.poliza_id
        INNER JOIN vehiculos v                      ON v.id  = p.catalogo_vehiculo_id
        INNER JOIN seguros seg                      ON seg.id = p.seguro_id
        INNER JOIN companias_seguros cs             ON cs.id  = seg.compania_id
        INNER JOIN catalogo_estatus_siniestros ces  ON ces.id = s.estatus_id
        INNER JOIN usuarios aj                      ON aj.id  = s.ajustador_id
        INNER JOIN usuarios dn                      ON dn.id  = p.usuario_id
        LEFT  JOIN usuarios sup                     ON sup.id = s.supervisor_id
        WHERE s.id = p_id;
      END
    `);

    // ── Evidencias (BLOBs) del siniestro ─────────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_evidencias_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_evidencias_siniestro(IN p_id INT)
      BEGIN
        SELECT
          id,
          nombre_archivo,
          tipo_mime,
          archivo_multimedia,
          tipo_evidencia,
          fecha_subida
        FROM evidencias_siniestro
        WHERE siniestro_id = p_id
        ORDER BY fecha_subida ASC;
      END
    `);

    // ── Terceros involucrados ─────────────────────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_terceros_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_terceros_siniestro(IN p_id INT)
      BEGIN
        SELECT
          id,
          marca_tercero,
          modelo_tercero,
          placas_tercero,
          aseguradora_tercero,
          descripcion_danos
        FROM terceros_involucrados
        WHERE siniestro_id = p_id;
      END
    `);

    // ── Seguimiento / historial de estados ────────────────────────────────────
    await q(`DROP PROCEDURE IF EXISTS sp_get_seguimiento_siniestro`);
    await q(`
      CREATE PROCEDURE sp_get_seguimiento_siniestro(IN p_id INT)
      BEGIN
        SELECT
          ss.fecha_movimiento,
          ss.comentario_publico,
          ces.descripcion_interna                       AS estatus,
          ces.color_ui                                  AS estatus_color,
          CONCAT(u.nombre, ' ', u.apellidos)            AS usuario_nombre
        FROM seguimiento_siniestros ss
        INNER JOIN catalogo_estatus_siniestros ces ON ces.id = ss.estatus_nuevo_id
        INNER JOIN usuarios u                      ON u.id   = ss.usuario_id
        WHERE ss.siniestro_id = p_id
        ORDER BY ss.fecha_movimiento ASC;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_get_seguimiento_siniestro`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_terceros_siniestro`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_evidencias_siniestro`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
  }
};
