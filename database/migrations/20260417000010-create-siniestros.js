'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('siniestros', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      poliza_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'polizas',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      ajustador_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'perfiles_empleados',
          key:   'usuario_id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      supervisor_id: {
        type:      Sequelize.INTEGER,
        allowNull: true,
        references: {
          model: 'perfiles_empleados',
          key:   'usuario_id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      numero_reporte: {
        type:          Sequelize.INTEGER,
        autoIncrement: true,
        allowNull:     false,
        unique:        true
      },
      fecha_hora_siniestro: {
        type:      Sequelize.DATE,
        allowNull: true
      },
      latitud: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      longitud: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      conductor_momento: {
        type:      Sequelize.STRING(255),
        allowNull: true
      },
      descripcion_hechos: {
        type:      Sequelize.TEXT,
        allowNull: true
      },
      dictamen_ajustador: {
        type:      Sequelize.STRING(255),
        allowNull: true
      },
      presupuesto_reparacion: {
        type:      Sequelize.STRING(255),
        allowNull: true
      },
      estatus_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'catalogo_estatus_siniestros',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('siniestros');
  }
};
