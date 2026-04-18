'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.bulkInsert('roles', [
      {
        id:          1,
        nombre:      'Asegurado',
        descripcion: 'Cliente titular de una póliza. Puede contratar seguros, consultar sus pólizas y dar seguimiento a sus siniestros.'
      },
      {
        id:          2,
        nombre:      'Ajustador',
        descripcion: 'Empleado de campo. Levanta reportes de siniestro en sitio, carga evidencia multimedia y registra daños y terceros involucrados.'
      },
      {
        id:          3,
        nombre:      'Supervisor',
        descripcion: 'Empleado de oficina. Revisa dictámenes de ajustadores, valida presupuestos y determina el estatus final del siniestro.'
      }
    ]);
  },

  async down(queryInterface) {
    await queryInterface.bulkDelete('roles', { id: [1, 2, 3] });
  }
};
