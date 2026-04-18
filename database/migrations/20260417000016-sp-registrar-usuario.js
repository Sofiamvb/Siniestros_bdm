'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_usuario(
        IN p_nombre           VARCHAR(255),
        IN p_apellidos        VARCHAR(255),
        IN p_fecha_nacimiento DATE,
        IN p_genero           VARCHAR(255),
        IN p_email            VARCHAR(255),
        IN p_password         VARCHAR(255),
        IN p_alias            VARCHAR(255),
        IN p_rol_id           INT,
        IN p_foto             LONGBLOB NOT NULL
      )
      BEGIN
        INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, genero, email, password, alias, rol_id, foto)
        VALUES (p_nombre, p_apellidos, p_fecha_nacimiento, p_genero, p_email, p_password, p_alias, p_rol_id, p_foto);
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP PROCEDURE IF EXISTS sp_registrar_usuario');
  }
};
