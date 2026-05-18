'use strict';

module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Incluir estatus_id en el detalle para que el controller pueda saber
    // en qué estado está el siniestro y mostrar el formulario correcto.
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
    await q(`
      CREATE PROCEDURE sp_get_siniestro_detalle(IN p_id INT)
      BEGIN
        SELECT
          s.id,
          s.estatus_id,
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
          ces.es_terminal                                 AS estatus_terminal,
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

    // SP para que el supervisor actualice el estado del siniestro.
    // Registra el cambio en seguimiento_siniestros automáticamente.
    await q(`DROP PROCEDURE IF EXISTS sp_actualizar_estatus_siniestro`);
    await q(`
      CREATE PROCEDURE sp_actualizar_estatus_siniestro(
        IN p_siniestro_id  INT,
        IN p_estatus_id    INT,
        IN p_supervisor_id INT,
        IN p_comentario    TEXT,
        IN p_fecha_evento  DATE
      )
      BEGIN
        UPDATE siniestros
        SET estatus_id = p_estatus_id
        WHERE id = p_siniestro_id;

        INSERT INTO seguimiento_siniestros (
          siniestro_id, usuario_id, estatus_nuevo_id,
          comentario_publico, notas_internas, fecha_movimiento
        ) VALUES (
          p_siniestro_id,
          p_supervisor_id,
          p_estatus_id,
          p_comentario,
          IF(p_fecha_evento IS NOT NULL, CONCAT('fecha_evento:', p_fecha_evento), NULL),
          NOW()
        );

        SELECT ROW_COUNT() AS actualizado;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_actualizar_estatus_siniestro`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
  }
};
