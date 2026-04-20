'use strict';

/**
 * Cada compañía ofrece 3 niveles de seguro.
 * suma_asegurada es un texto descriptivo (no precio fijo).
 * La prima se calcula en PHP: precio_seguro_vehiculo * factor_nivel
 *   Básico   → 0.03 (3%)
 *   Estándar → 0.05 (5%)
 *   Premium  → 0.08 (8%)
 */

// Los IDs de companias_seguros se insertan en orden 1..5
// GNP=1, Qualitas=2, AXA=3, BBVA=4, HDI=5
const niveles = [
  {
    nivel: 'Básico',
    nombre_seguro: 'Cobertura Básica',
    suma_asegurada: 'Valor comercial del vehículo',
    deducible_porcentaje: '20.00',
    descripcion_cobertura: 'Daños a terceros (RC), robo total y gastos médicos básicos.',
  },
  {
    nivel: 'Estándar',
    nombre_seguro: 'Cobertura Estándar',
    suma_asegurada: 'Valor comercial del vehículo',
    deducible_porcentaje: '10.00',
    descripcion_cobertura: 'RC, robo total, daños materiales propios, asistencia vial 24h.',
  },
  {
    nivel: 'Premium',
    nombre_seguro: 'Cobertura Premium',
    suma_asegurada: 'Valor comercial del vehículo',
    deducible_porcentaje: '5.00',
    descripcion_cobertura: 'Cobertura amplia: RC, robo, daños, auto sustituto, cero deducible en cristales.',
  },
];

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    // Obtener los IDs reales de las compañías
    const [companias] = await queryInterface.sequelize.query(
      'SELECT id FROM companias_seguros ORDER BY id ASC'
    );

    const seguros = [];
    for (const compania of companias) {
      for (const nivel of niveles) {
        seguros.push({ compania_id: compania.id, ...nivel });
      }
    }

    await queryInterface.bulkInsert('seguros', seguros);
  },

  async down(queryInterface) {
    await queryInterface.bulkDelete('seguros', null, { truncate: true });
  }
};
