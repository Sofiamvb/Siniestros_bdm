'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('seguros', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      compania_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'companias_seguros',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      nombre_seguro: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      nivel: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      suma_asegurada: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      deducible_porcentaje: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      descripcion_cobertura: {
        type:      Sequelize.TEXT,
        allowNull: true
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('seguros');
  }
};
