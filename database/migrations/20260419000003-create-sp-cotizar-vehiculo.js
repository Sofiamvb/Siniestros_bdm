'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_cotizar_vehiculo(
        IN p_marca   VARCHAR(60),
        IN p_modelo  VARCHAR(60),
        IN p_anio    INT
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
        WHERE marca  = p_marca
          AND modelo = p_modelo
          AND anio   = p_anio
          AND status = TRUE
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_cotizar_vehiculo');
  }
};
