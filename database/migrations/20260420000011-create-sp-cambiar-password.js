'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_cambiar_password`);
    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_cambiar_password(
        IN p_id       INT,
        IN p_password VARCHAR(255)
      )
      BEGIN
        UPDATE usuarios SET password = p_password WHERE id = p_id;
        SELECT ROW_COUNT() AS actualizado;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_cambiar_password`);
  }
};
