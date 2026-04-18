'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('seguimiento_siniestros', {
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
      estatus_nuevo_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'catalogo_estatus_siniestros',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      comentario_publico: {
        type:      Sequelize.TEXT,
        allowNull: true
      },
      notas_internas: {
        type:      Sequelize.TEXT,
        allowNull: true
      },
      fecha_movimiento: {
        type:         Sequelize.DATE,
        allowNull:    true,
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP')
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('seguimiento_siniestros');
  }
};
