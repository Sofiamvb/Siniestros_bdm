'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('perfiles_asegurados', {
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
      rfc: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      licencia_conducir: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      direccion_facturacion: {
        type:      Sequelize.TEXT,
        allowNull: false
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('perfiles_asegurados');
  }
};
