'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Devuelve todas las marcas únicas activas
    await q(`
      CREATE PROCEDURE sp_get_marcas_vehiculos()
      BEGIN
        SELECT DISTINCT marca
        FROM vehiculos
        WHERE status = TRUE
        ORDER BY marca ASC;
      END
    `);

    // Devuelve los modelos de una marca
    await q(`
      CREATE PROCEDURE sp_get_modelos_by_marca(
        IN p_marca VARCHAR(60)
      )
      BEGIN
        SELECT DISTINCT modelo
        FROM vehiculos
        WHERE marca  = p_marca
          AND status = TRUE
        ORDER BY modelo ASC;
      END
    `);

    // Devuelve los años disponibles para marca+modelo
    await q(`
      CREATE PROCEDURE sp_get_anios_by_modelo(
        IN p_marca   VARCHAR(60),
        IN p_modelo  VARCHAR(60)
      )
      BEGIN
        SELECT DISTINCT anio
        FROM vehiculos
        WHERE marca  = p_marca
          AND modelo = p_modelo
          AND status = TRUE
        ORDER BY anio DESC;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);
    await q('DROP PROCEDURE IF EXISTS sp_get_anios_by_modelo');
    await q('DROP PROCEDURE IF EXISTS sp_get_modelos_by_marca');
    await q('DROP PROCEDURE IF EXISTS sp_get_marcas_vehiculos');
  }
};
