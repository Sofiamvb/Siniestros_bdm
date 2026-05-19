'use strict';

/**
 * Función fn_calcular_pago_asegurado: calcula el monto que la aseguradora
 * pagará al asegurado según el dictamen del supervisor.
 *
 * Actualiza sp_get_siniestro_detalle para incluir deducible_porcentaje
 * y pago_calculado usando la función.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── Función: cálculo de pago según estatus ───────────────────────────────
    await q(`DROP FUNCTION IF EXISTS fn_calcular_pago_asegurado`);
    await q(`
      CREATE FUNCTION fn_calcular_pago_asegurado(
        p_estatus_id              INT,
        p_suma_asegurada          DECIMAL(12,2),
        p_deducible_porcentaje    DECIMAL(5,2),
        p_presupuesto_reparacion  DECIMAL(12,2)
      ) RETURNS DECIMAL(12,2)
      DETERMINISTIC
      BEGIN
        DECLARE v_deducible DECIMAL(12,2);
        SET v_deducible = ROUND(p_suma_asegurada * p_deducible_porcentaje / 100, 2);

        RETURN CASE p_estatus_id
          WHEN 1 THEN 0.00
          WHEN 2 THEN 0.00
          WHEN 3 THEN GREATEST(0.00, p_presupuesto_reparacion - v_deducible)
          WHEN 4 THEN p_presupuesto_reparacion
          WHEN 5 THEN p_presupuesto_reparacion
          WHEN 6 THEN p_suma_asegurada
          ELSE        0.00
        END;
      END
    `);

    // ── sp_get_siniestro_detalle actualizado: incluye deducible y pago ───────
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
          seg.deducible_porcentaje,
          fn_calcular_pago_asegurado(
            s.estatus_id,
            seg.suma_asegurada,
            seg.deducible_porcentaje,
            IFNULL(s.presupuesto_reparacion, 0)
          )                                               AS pago_calculado,
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
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Restaurar SP sin el campo pago_calculado
    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
    await q(`
      CREATE PROCEDURE sp_get_siniestro_detalle(IN p_id INT)
      BEGIN
        SELECT
          s.id, s.estatus_id, s.numero_reporte, s.fecha_hora_siniestro,
          s.latitud, s.longitud, s.conductor_momento, s.descripcion_hechos,
          s.dictamen_supervisor, s.presupuesto_reparacion, s.perdida_total,
          s.ajustador_id, s.supervisor_id,
          ces.descripcion_interna AS estatus, ces.color_ui AS estatus_color,
          ces.es_terminal AS estatus_terminal,
          p.id AS poliza_id, p.numero_poliza, p.placas, p.fecha_inicio, p.fecha_fin,
          p.usuario_id, v.marca, v.modelo, v.anio, v.version,
          seg.nombre_seguro, seg.nivel, seg.suma_asegurada,
          cs.nombre_comercial AS compania,
          CONCAT(aj.nombre, ' ', aj.apellidos) AS ajustador_nombre,
          CONCAT(dn.nombre, ' ', dn.apellidos) AS duenio_nombre,
          dn.email AS duenio_email,
          CONCAT(sup.nombre, ' ', sup.apellidos) AS supervisor_nombre
        FROM siniestros s
        INNER JOIN polizas p ON p.id = s.poliza_id
        INNER JOIN vehiculos v ON v.id = p.catalogo_vehiculo_id
        INNER JOIN seguros seg ON seg.id = p.seguro_id
        INNER JOIN companias_seguros cs ON cs.id = seg.compania_id
        INNER JOIN catalogo_estatus_siniestros ces ON ces.id = s.estatus_id
        INNER JOIN usuarios aj ON aj.id = s.ajustador_id
        INNER JOIN usuarios dn ON dn.id = p.usuario_id
        LEFT JOIN usuarios sup ON sup.id = s.supervisor_id
        WHERE s.id = p_id;
      END
    `);

    await q(`DROP FUNCTION IF EXISTS fn_calcular_pago_asegurado`);
  }
};
