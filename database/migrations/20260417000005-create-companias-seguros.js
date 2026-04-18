'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('companias_seguros', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      nombre_comercial: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      razon_social: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      rfc: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      telefono_cabina: {
        type:      Sequelize.STRING(255),
        allowNull: false
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('companias_seguros');
  }
};
