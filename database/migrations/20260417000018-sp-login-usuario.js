'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_login_usuario(
        IN p_email VARCHAR(255)
      )
      BEGIN
        SELECT
          u.id,
          u.nombre,
          u.apellidos,
          u.fecha_nacimiento,
          u.foto,
          u.genero,
          u.email,
          u.password,
          u.alias,
          u.rol_id
        FROM usuarios u
        WHERE u.email = p_email
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_login_usuario');
  }
};
