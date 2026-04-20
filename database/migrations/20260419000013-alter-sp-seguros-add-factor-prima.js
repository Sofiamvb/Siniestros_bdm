'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q('DROP PROCEDURE IF EXISTS sp_get_seguros_disponibles');
    await q('DROP PROCEDURE IF EXISTS sp_get_seguro_by_id');

    await q(`
      CREATE PROCEDURE sp_get_seguros_disponibles(IN p_vehiculo_id INT)
      BEGIN
        SELECT s.id,
               s.nombre_seguro,
               s.nivel,
               s.suma_asegurada,
               s.deducible_porcentaje,
               s.factor_prima,
               s.descripcion_cobertura,
               c.id               AS compania_id,
               c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        ORDER BY c.nombre_comercial ASC, s.nivel ASC;
      END
    `);

    await q(`
      CREATE PROCEDURE sp_get_seguro_by_id(IN p_id INT)
      BEGIN
        SELECT s.id,
               s.nombre_seguro,
               s.nivel,
               s.suma_asegurada,
               s.deducible_porcentaje,
               s.factor_prima,
               s.descripcion_cobertura,
               c.id               AS compania_id,
               c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        WHERE s.id = p_id
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q('DROP PROCEDURE IF EXISTS sp_get_seguros_disponibles');
    await q('DROP PROCEDURE IF EXISTS sp_get_seguro_by_id');

    // Recrear sin factor_prima
    await q(`
      CREATE PROCEDURE sp_get_seguros_disponibles(IN p_vehiculo_id INT)
      BEGIN
        SELECT s.id, s.nombre_seguro, s.nivel, s.suma_asegurada,
               s.deducible_porcentaje, s.descripcion_cobertura,
               c.id AS compania_id, c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        ORDER BY c.nombre_comercial ASC, s.nivel ASC;
      END
    `);

    await q(`
      CREATE PROCEDURE sp_get_seguro_by_id(IN p_id INT)
      BEGIN
        SELECT s.id, s.nombre_seguro, s.nivel, s.suma_asegurada,
               s.deducible_porcentaje, s.descripcion_cobertura,
               c.id AS compania_id, c.nombre_comercial AS compania
        FROM seguros s
        JOIN companias_seguros c ON c.id = s.compania_id
        WHERE s.id = p_id LIMIT 1;
      END
    `);
  }
};
