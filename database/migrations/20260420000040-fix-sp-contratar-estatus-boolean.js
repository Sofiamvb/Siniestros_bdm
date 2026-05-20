'use strict';

/**
 * Fix: sp_contratar_poliza insertaba 'Vigente' (VARCHAR) en estatus_poliza
 * que es BOOLEAN/TINYINT desde la migración 005. Se reemplaza por 1 (TRUE).
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

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
          1,
          p_catalogo_vehiculo_id,
          v_precio_seguro
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q(`DROP PROCEDURE IF EXISTS sp_contratar_poliza`);
  }
};
