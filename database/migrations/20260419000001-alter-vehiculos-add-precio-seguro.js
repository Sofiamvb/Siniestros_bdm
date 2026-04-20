'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.addColumn('vehiculos', 'precio_seguro', {
      type:         Sequelize.DECIMAL(10, 2),
      allowNull:    false,
      defaultValue: 0.00,
      after:        'status'
    });
  },

  async down(queryInterface) {
    await queryInterface.removeColumn('vehiculos', 'precio_seguro');
  }
};
