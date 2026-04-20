'use strict';

/**
 * Asigna factor_prima distinto por compañía y nivel.
 * La prima anual = vehiculo.precio_seguro * factor_prima
 *
 * Compañía    | Básico | Estándar | Premium
 * ------------|--------|----------|--------
 * GNP         | 3.10%  |   5.10%  |  8.20%
 * Qualitas    | 2.90%  |   4.80%  |  7.90%
 * AXA         | 3.25%  |   5.30%  |  8.50%
 * BBVA        | 3.00%  |   5.00%  |  8.00%
 * HDI         | 2.80%  |   4.70%  |  7.75%
 */

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Obtener IDs de compañías por nombre
    const [companias] = await queryInterface.sequelize.query(
      "SELECT id, nombre_comercial FROM companias_seguros ORDER BY id ASC"
    );

    const factores = {
      'GNP Seguros':  { 'Básico': 0.0310, 'Estándar': 0.0510, 'Premium': 0.0820 },
      'Qualitas':     { 'Básico': 0.0290, 'Estándar': 0.0480, 'Premium': 0.0790 },
      'AXA Seguros':  { 'Básico': 0.0325, 'Estándar': 0.0530, 'Premium': 0.0850 },
      'BBVA Seguros': { 'Básico': 0.0300, 'Estándar': 0.0500, 'Premium': 0.0800 },
      'HDI Seguros':  { 'Básico': 0.0280, 'Estándar': 0.0470, 'Premium': 0.0775 },
    };

    for (const compania of companias) {
      const niveles = factores[compania.nombre_comercial];
      if (!niveles) continue;

      for (const [nivel, factor] of Object.entries(niveles)) {
        await q(
          `UPDATE seguros SET factor_prima = ${factor}
           WHERE compania_id = ${compania.id} AND nivel = '${nivel}'`
        );
      }
    }
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(
      'UPDATE seguros SET factor_prima = 0.0500'
    );
  }
};
