'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('catalogo_estatus_siniestros', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      clave: {
        type:      Sequelize.STRING(50),
        allowNull: false,
        unique:    true
      },
      descripcion_interna: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      color_ui: {
        type:      Sequelize.STRING(50),
        allowNull: true
      },
      es_terminal: {
        type:         Sequelize.BOOLEAN,
        allowNull:    true,
        defaultValue: false
      },
      orden_flujo: {
        type:      Sequelize.TINYINT,
        allowNull: true
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('catalogo_estatus_siniestros');
  }
};
