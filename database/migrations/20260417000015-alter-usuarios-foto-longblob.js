'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.changeColumn('usuarios', 'foto', {
      type:      Sequelize.BLOB('long'),
      allowNull: true
    });
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.changeColumn('usuarios', 'foto', {
      type:      Sequelize.STRING(255),
      allowNull: true
    });
  }
};
