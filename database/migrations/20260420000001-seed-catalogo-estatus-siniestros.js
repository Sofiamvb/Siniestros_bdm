'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`
      INSERT INTO catalogo_estatus_siniestros
        (id, clave, descripcion_interna, color_ui, es_terminal, orden_flujo)
      VALUES
        (1, 'RECHAZADO',             'Rechazado',                        '#EF4444', 1, 6),
        (2, 'ACEPTADO',              'Aceptado',                         '#22C55E', 0, 1),
        (3, 'ACEPTADO_CON_DED',      'Aceptado con pago de deducible',   '#3B82F6', 0, 2),
        (4, 'ACEPTADO_SIN_DED',      'Aceptado sin pago de deducible',   '#10B981', 0, 3),
        (5, 'APLICA_PAGO_REP',       'Aplica pago para reparación',      '#F59E0B', 0, 4),
        (6, 'PERDIDA_TOTAL',         'Pérdida total',                    '#6B7280', 1, 5)
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(
      `DELETE FROM catalogo_estatus_siniestros WHERE id IN (1,2,3,4,5,6)`
    );
  }
};
