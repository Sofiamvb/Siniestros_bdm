'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('polizas', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      usuario_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'usuarios',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      seguro_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'seguros',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      numero_poliza: {
        type:      Sequelize.STRING(255),
        allowNull: false,
        unique:    true
      },
      placas: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      fecha_inicio: {
        type:      Sequelize.DATEONLY,
        allowNull: false
      },
      fecha_fin: {
        type:      Sequelize.DATEONLY,
        allowNull: false
      },
      estatus_poliza: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      catalogo_vehiculo_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'vehiculos',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('polizas');
  }
};
