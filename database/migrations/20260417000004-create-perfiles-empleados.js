'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('perfiles_empleados', {
      usuario_id: {
        type:       Sequelize.INTEGER,
        primaryKey: true,
        allowNull:  false,
        references: {
          model: 'usuarios',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      numero_empleado: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      zona_cobertura: {
        type:      Sequelize.STRING(255),
        allowNull: false
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('perfiles_empleados');
  }
};
