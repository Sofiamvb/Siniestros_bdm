'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Versiones disponibles para marca + modelo + año
    await q(`
      CREATE PROCEDURE sp_get_versiones_by_anio(
        IN p_marca   VARCHAR(60),
        IN p_modelo  VARCHAR(60),
        IN p_anio    INT
      )
      BEGIN
        SELECT DISTINCT version
        FROM vehiculos
        WHERE marca  = p_marca
          AND modelo = p_modelo
          AND anio   = p_anio
          AND status = TRUE
        ORDER BY version ASC;
      END
    `);

    // Recrea sp_cotizar_vehiculo con filtro de versión
    await q('DROP PROCEDURE IF EXISTS sp_cotizar_vehiculo');
    await q(`
      CREATE PROCEDURE sp_cotizar_vehiculo(
        IN p_marca    VARCHAR(60),
        IN p_modelo   VARCHAR(60),
        IN p_anio     INT,
        IN p_version  VARCHAR(100)
      )
      BEGIN
        SELECT
          id,
          marca,
          modelo,
          anio,
          version,
          tipo_vehiculo,
          numero_pasajeros,
          cilindros,
          precio_seguro
        FROM vehiculos
        WHERE marca   = p_marca
          AND modelo  = p_modelo
          AND anio    = p_anio
          AND version = p_version
          AND status  = TRUE
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q('DROP PROCEDURE IF EXISTS sp_get_versiones_by_anio');
    await q('DROP PROCEDURE IF EXISTS sp_cotizar_vehiculo');

    await q(`
      CREATE PROCEDURE sp_cotizar_vehiculo(
        IN p_marca   VARCHAR(60),
        IN p_modelo  VARCHAR(60),
        IN p_anio    INT
      )
      BEGIN
        SELECT id, marca, modelo, anio, version, tipo_vehiculo,
               numero_pasajeros, cilindros, precio_seguro
        FROM vehiculos
        WHERE marca = p_marca AND modelo = p_modelo AND anio = p_anio AND status = TRUE
        LIMIT 1;
      END
    `);
  }
};
