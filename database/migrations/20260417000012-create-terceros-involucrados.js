'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('terceros_involucrados', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      siniestro_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'siniestros',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      marca_tercero: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      modelo_tercero: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      placas_tercero: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      aseguradora_tercero: {
        type:      Sequelize.STRING(255),
        allowNull: true
      },
      descripcion_danos: {
        type:      Sequelize.TEXT,
        allowNull: true
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('terceros_involucrados');
  }
};
