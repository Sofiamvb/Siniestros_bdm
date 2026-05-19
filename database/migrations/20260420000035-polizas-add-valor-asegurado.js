'use strict';

/**
 * Agrega valor_asegurado DECIMAL a polizas para tener el monto numérico real
 * del vehículo asegurado (tomado de vehiculos.precio_seguro al contratar).
 * Esto permite que fn_calcular_pago_asegurado reciba un DECIMAL correcto
 * en lugar del VARCHAR de seguros.suma_asegurada.
 */
module.exports = {
  async up(queryInterface, Sequelize) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // 1. Agregar columna
    await queryInterface.addColumn('polizas', 'valor_asegurado', {
      type:         Sequelize.DECIMAL(12, 2),
      allowNull:    true,
      defaultValue: null,
      comment:      'Valor comercial del vehículo al momento de contratar la póliza',
    });

    // 2. Llenar con precio_seguro del vehículo para pólizas existentes
    await q(`
      UPDATE polizas p
      INNER JOIN vehiculos v ON v.id = p.catalogo_vehiculo_id
      SET p.valor_asegurado = v.precio_seguro
      WHERE p.valor_asegurado IS NULL
    `);

    // 3. sp_contratar_poliza: guarda valor_asegurado al crear la póliza
    await q(`DROP PROCEDURE IF EXISTS sp_contratar_poliza`);
    await q(`
      CREATE PROCEDURE sp_contratar_poliza(
        IN p_usuario_id           INT,
        IN p_seguro_id            INT,
        IN p_catalogo_vehiculo_id INT,
        IN p_placas               VARCHAR(20),
        IN p_fecha_inicio         DATE,
        IN p_fecha_fin            DATE
      )
      BEGIN
        DECLARE v_precio_seguro DECIMAL(12,2) DEFAULT 0.00;

        SELECT precio_seguro INTO v_precio_seguro
        FROM vehiculos
        WHERE id = p_catalogo_vehiculo_id
        LIMIT 1;

        INSERT INTO polizas (
          usuario_id, seguro_id, numero_poliza, placas,
          fecha_inicio, fecha_fin, estatus_poliza,
          catalogo_vehiculo_id, valor_asegurado
        )
        VALUES (
          p_usuario_id,
          p_seguro_id,
          CONCAT('POL-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0')),
          p_placas,
          p_fecha_inicio,
          p_fecha_fin,
          'Vigente',
          p_catalogo_vehiculo_id,
          v_precio_seguro
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);

    // 4. sp_get_siniestro_detalle: usa p.valor_asegurado para fn_calcular_pago_asegurado
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
          p.valor_asegurado,
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
            IFNULL(p.valor_asegurado, 0.00),
            IFNULL(CAST(seg.deducible_porcentaje AS DECIMAL(5,2)), 0.00),
            IFNULL(s.presupuesto_reparacion, 0.00)
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

    await q(`DROP PROCEDURE IF EXISTS sp_get_siniestro_detalle`);
    await q(`DROP PROCEDURE IF EXISTS sp_contratar_poliza`);
    await queryInterface.removeColumn('polizas', 'valor_asegurado');
  }
};
