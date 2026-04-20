'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_perfil_asegurado(
        IN p_usuario_id           INT,
        IN p_rfc                  VARCHAR(13),
        IN p_licencia_conducir    VARCHAR(20),
        IN p_direccion_facturacion VARCHAR(255)
      )
      BEGIN
        INSERT INTO perfiles_asegurados (usuario_id, rfc, licencia_conducir, direccion_facturacion)
        VALUES (p_usuario_id, p_rfc, p_licencia_conducir, p_direccion_facturacion);
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_registrar_perfil_asegurado');
  }
};
