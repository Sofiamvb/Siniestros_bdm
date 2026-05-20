'use strict';

/**
 * View #8/8: v_seguros_disponibles
 * Encapsula el JOIN entre seguros y companias_seguros que repiten
 * sp_get_seguros_disponibles y sp_get_seguro_by_id.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP VIEW IF EXISTS v_seguros_disponibles`);
    await q(`
      CREATE VIEW v_seguros_disponibles AS
      SELECT
        s.id,
        s.compania_id,
        s.nombre_seguro,
        s.nivel,
        s.suma_asegurada,
        s.deducible_porcentaje,
        s.factor_prima,
        s.descripcion_cobertura,
        c.nombre_comercial  AS compania,
        c.razon_social,
        c.telefono_cabina
      FROM seguros s
      INNER JOIN companias_seguros c ON c.id = s.compania_id
    `);

    // Actualizar sp_get_seguros_disponibles para usar el view
    await q(`DROP PROCEDURE IF EXISTS sp_get_seguros_disponibles`);
    await q(`
      CREATE PROCEDURE sp_get_seguros_disponibles(IN p_vehiculo_id INT)
      BEGIN
        SELECT
          id, compania_id, nombre_seguro, nivel,
          suma_asegurada, deducible_porcentaje,
          descripcion_cobertura, compania
        FROM v_seguros_disponibles
        ORDER BY compania ASC, nivel ASC;
      END
    `);

    // Actualizar sp_get_seguro_by_id para usar el view
    await q(`DROP PROCEDURE IF EXISTS sp_get_seguro_by_id`);
    await q(`
      CREATE PROCEDURE sp_get_seguro_by_id(IN p_id INT)
      BEGIN
        SELECT
          id, compania_id, nombre_seguro, nivel,
          suma_asegurada, deducible_porcentaje,
          descripcion_cobertura, compania
        FROM v_seguros_disponibles
        WHERE id = p_id
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_seguro_by_id`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_seguros_disponibles`);
    await q(`DROP VIEW IF EXISTS v_seguros_disponibles`);

    // Restaurar SPs originales
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
        WHERE s.id = p_id
        LIMIT 1;
      END
    `);
  }
};
