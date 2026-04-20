'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.bulkInsert('companias_seguros', [
      { nombre_comercial: 'GNP Seguros',    razon_social: 'Grupo Nacional Provincial S.A.B.',          rfc: 'GNP9211043I0', telefono_cabina: '800-400-4000' },
      { nombre_comercial: 'Qualitas',        razon_social: 'Qualitas Compañía de Seguros S.A. de C.V.', rfc: 'QCS931209814', telefono_cabina: '800-288-9999' },
      { nombre_comercial: 'AXA Seguros',     razon_social: 'AXA Seguros S.A. de C.V.',                  rfc: 'ASE931209814', telefono_cabina: '800-900-1010' },
      { nombre_comercial: 'BBVA Seguros',    razon_social: 'BBVA Seguros S.A. de C.V.',                 rfc: 'BSE010101AAA', telefono_cabina: '800-226-2663' },
      { nombre_comercial: 'HDI Seguros',     razon_social: 'HDI Seguros S.A. de C.V.',                  rfc: 'HSE940101BBB', telefono_cabina: '800-290-4000' },
    ]);
  },

  async down(queryInterface) {
    await queryInterface.bulkDelete('companias_seguros', null, { truncate: true });
  }
};
