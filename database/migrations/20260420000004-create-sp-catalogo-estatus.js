'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_get_catalogo_estatus_siniestros`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_get_companias_seguros`);

    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_get_catalogo_estatus_siniestros()
      BEGIN
        SELECT id, clave, descripcion_interna, color_ui
        FROM catalogo_estatus_siniestros
        ORDER BY orden_flujo ASC;
      END
    `);

    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_get_companias_seguros()
      BEGIN
        SELECT id, nombre_comercial
        FROM companias_seguros
        ORDER BY nombre_comercial ASC;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_get_catalogo_estatus_siniestros`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_get_companias_seguros`);
  }
};
