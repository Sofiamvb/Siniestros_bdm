'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('usuarios', {
      id: {
        type:          Sequelize.INTEGER,
        primaryKey:    true,
        autoIncrement: true,
        allowNull:     false
      },
      nombre: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      apellidos: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      fecha_nacimiento: {
        type:      Sequelize.DATEONLY,
        allowNull: false
      },
      foto: {
        type:      Sequelize.STRING(255),
        allowNull: true
      },
      genero: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      email: {
        type:      Sequelize.STRING(255),
        allowNull: false,
        unique:    true
      },
      password: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      alias: {
        type:      Sequelize.STRING(255),
        allowNull: false
      },
      rol_id: {
        type:      Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: 'roles',
          key:   'id'
        },
        onDelete: 'NO ACTION',
        onUpdate: 'NO ACTION'
      }
    });
  },

  async down(queryInterface) {
    await queryInterface.dropTable('usuarios');
  }
};
