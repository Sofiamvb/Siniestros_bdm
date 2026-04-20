'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Nuevo estatus inicial que asigna el sistema al crear el siniestro
    await q(`
      INSERT INTO catalogo_estatus_siniestros
        (id, clave, descripcion_interna, color_ui, es_terminal, orden_flujo)
      VALUES
        (7, 'PENDIENTE_DICTAMEN', 'Pendiente de dictamen', '#F59E0B', 0, 0)
    `);

    // Recrear SP sin el parámetro de dictamen — usa estatus 7 por defecto
    await q(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
    await q(`
      CREATE PROCEDURE sp_registrar_siniestro(
        IN p_poliza_id             INT,
        IN p_ajustador_id          INT,
        IN p_fecha_hora_siniestro  DATETIME,
        IN p_ubicacion             VARCHAR(255),
        IN p_conductor_momento     VARCHAR(150),
        IN p_descripcion_hechos    TEXT,
        IN p_presupuesto           DECIMAL(12,2)
      )
      BEGIN
        DECLARE v_num_reporte VARCHAR(30);
        SET v_num_reporte = CONCAT('SIN-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0'));

        INSERT INTO siniestros (
          poliza_id, ajustador_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud,
          conductor_momento, descripcion_hechos,
          dictamen_ajustador, presupuesto_reparacion, estatus_id
        ) VALUES (
          p_poliza_id, p_ajustador_id, v_num_reporte,
          p_fecha_hora_siniestro, NULL, NULL,
          p_conductor_momento, p_descripcion_hechos,
          NULL, p_presupuesto, 7
        );

        SELECT LAST_INSERT_ID() AS id, v_num_reporte AS numero_reporte;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DELETE FROM catalogo_estatus_siniestros WHERE id = 7`);
    await q(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
  }
};
