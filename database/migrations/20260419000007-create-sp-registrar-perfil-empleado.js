'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_perfil_empleado(
        IN p_usuario_id      INT,
        IN p_numero_empleado VARCHAR(30),
        IN p_zona_cobertura  VARCHAR(100)
      )
      BEGIN
        INSERT INTO perfiles_empleados (usuario_id, numero_empleado, zona_cobertura)
        VALUES (p_usuario_id, p_numero_empleado, p_zona_cobertura);
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_registrar_perfil_empleado');
  }
};
