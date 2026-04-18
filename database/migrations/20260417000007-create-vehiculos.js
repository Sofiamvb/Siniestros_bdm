'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('vehiculos', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      marca: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      modelo: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      anio: {
        type:      Sequelize.INTEGER,
        allowNull: false
      },
      version: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      tipo_vehiculo: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      numero_pasajeros: {
        type:      Sequelize.INTEGER,
        allowNull: false
      },
      cilindros: {
        type:      Sequelize.INTEGER,
        allowNull: false
      },
      status: {
        type:         Sequelize.BOOLEAN,
        allowNull:    false,
        defaultValue: true
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('vehiculos');
  }
};
