'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.addColumn('seguros', 'factor_prima', {
      type:         Sequelize.DECIMAL(5, 4),
      allowNull:    false,
      defaultValue: 0.0500,
      after:        'deducible_porcentaje'
    });
  },

  async down(queryInterface) {
    await queryInterface.removeColumn('seguros', 'factor_prima');
  }
};
