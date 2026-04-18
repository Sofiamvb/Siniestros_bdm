'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('evidencias_siniestro', {
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
      ajustador_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'usuarios',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      },
      archivo_multimedia: {
        type:      Sequelize.BLOB('long'),
        allowNull: false
      },
      nombre_archivo: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      tipo_mime: {
        type:      Sequelize.STRING(100),
        allowNull: false
      },
      tipo_evidencia: {
        type:      Sequelize.STRING(50),
        allowNull: false
      },
      fecha_subida: {
        type:         Sequelize.DATE,
        allowNull:    false,
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP')
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('evidencias_siniestro');
  }
};
