'use strict';

/**
 * Actualiza sp_registrar_siniestro para que, al crear un siniestro,
 * inserte automáticamente el estado inicial en seguimiento_siniestros.
 * Esto hace que el timeline en la vista de detalle siempre tenga al menos
 * una entrada desde el momento del registro.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
    await q(`
      CREATE PROCEDURE sp_registrar_siniestro(
        IN p_poliza_id             INT,
        IN p_ajustador_id          INT,
        IN p_fecha_hora_siniestro  DATETIME,
        IN p_latitud               VARCHAR(255),
        IN p_longitud              VARCHAR(255),
        IN p_conductor_momento     VARCHAR(150),
        IN p_descripcion_hechos    TEXT,
        IN p_presupuesto           DECIMAL(12,2),
        IN p_perdida_total         TINYINT(1)
      )
      BEGIN
        DECLARE v_num_reporte  VARCHAR(30);
        DECLARE v_siniestro_id INT;
        DECLARE v_estatus_id   INT DEFAULT 7;

        SET v_num_reporte = CONCAT('SIN-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0'));

        INSERT INTO siniestros (
          poliza_id, ajustador_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud,
          conductor_momento, descripcion_hechos,
          dictamen_supervisor, presupuesto_reparacion, perdida_total, estatus_id
        ) VALUES (
          p_poliza_id, p_ajustador_id, v_num_reporte,
          p_fecha_hora_siniestro, p_latitud, p_longitud,
          p_conductor_momento, p_descripcion_hechos,
          NULL,
          IF(p_perdida_total = 1, NULL, p_presupuesto),
          p_perdida_total,
          v_estatus_id
        );

        SET v_siniestro_id = LAST_INSERT_ID();

        -- Registrar estado inicial en el historial de seguimiento
        INSERT INTO seguimiento_siniestros (
          siniestro_id, usuario_id, estatus_nuevo_id,
          comentario_publico, notas_internas, fecha_movimiento
        ) VALUES (
          v_siniestro_id, p_ajustador_id, v_estatus_id,
          'Siniestro registrado', NULL, NOW()
        );

        SELECT v_siniestro_id AS id, v_num_reporte AS numero_reporte;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
    await q(`
      CREATE PROCEDURE sp_registrar_siniestro(
        IN p_poliza_id             INT,
        IN p_ajustador_id          INT,
        IN p_fecha_hora_siniestro  DATETIME,
        IN p_latitud               VARCHAR(255),
        IN p_longitud              VARCHAR(255),
        IN p_conductor_momento     VARCHAR(150),
        IN p_descripcion_hechos    TEXT,
        IN p_presupuesto           DECIMAL(12,2),
        IN p_perdida_total         TINYINT(1)
      )
      BEGIN
        DECLARE v_num_reporte VARCHAR(30);
        SET v_num_reporte = CONCAT('SIN-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0'));

        INSERT INTO siniestros (
          poliza_id, ajustador_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud,
          conductor_momento, descripcion_hechos,
          dictamen_supervisor, presupuesto_reparacion, perdida_total, estatus_id
        ) VALUES (
          p_poliza_id, p_ajustador_id, v_num_reporte,
          p_fecha_hora_siniestro, p_latitud, p_longitud,
          p_conductor_momento, p_descripcion_hechos,
          NULL,
          IF(p_perdida_total = 1, NULL, p_presupuesto),
          p_perdida_total,
          7
        );

        SELECT LAST_INSERT_ID() AS id, v_num_reporte AS numero_reporte;
      END
    `);
  }
};
