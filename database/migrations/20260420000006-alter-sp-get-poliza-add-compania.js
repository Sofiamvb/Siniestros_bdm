'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_by_numero`);
    await q(`
      CREATE PROCEDURE sp_get_poliza_by_numero(IN p_numero VARCHAR(50))
      BEGIN
        SELECT
          p.id                    AS poliza_id,
          p.numero_poliza,
          p.placas,
          p.estatus_poliza,
          p.fecha_inicio,
          p.fecha_fin,
          u.id                    AS usuario_id,
          CONCAT(u.nombre, ' ', u.apellidos) AS nombre_duenio,
          v.marca,
          v.modelo,
          v.anio,
          v.tipo_vehiculo,
          s.suma_asegurada,
          s.deducible_porcentaje,
          cs.id               AS compania_id,
          cs.nombre_comercial AS compania
        FROM polizas p
        INNER JOIN usuarios u          ON u.id = p.usuario_id
        INNER JOIN vehiculos v          ON v.id = p.catalogo_vehiculo_id
        INNER JOIN seguros s            ON s.id = p.seguro_id
        INNER JOIN companias_seguros cs ON cs.id = s.compania_id
        WHERE p.numero_poliza  = p_numero
          AND p.estatus_poliza = TRUE
        LIMIT 1;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Revertir a versión sin filtro de compañía
    await q(`DROP PROCEDURE IF EXISTS sp_get_poliza_by_numero`);
    await q(`
      CREATE PROCEDURE sp_get_poliza_by_numero(IN p_numero VARCHAR(50))
      BEGIN
        SELECT
          p.id                    AS poliza_id,
          p.numero_poliza,
          p.placas,
          p.estatus_poliza,
          p.fecha_inicio,
          p.fecha_fin,
          u.id                    AS usuario_id,
          CONCAT(u.nombre, ' ', u.apellidos) AS nombre_duenio,
          v.marca,
          v.modelo,
          v.anio,
          v.tipo_vehiculo,
          s.suma_asegurada,
          s.deducible_porcentaje,
          cs.nombre_comercial     AS compania
        FROM polizas p
        INNER JOIN usuarios u          ON u.id = p.usuario_id
        INNER JOIN vehiculos v          ON v.id = p.catalogo_vehiculo_id
        INNER JOIN seguros s            ON s.id = p.seguro_id
        INNER JOIN companias_seguros cs ON cs.id = s.compania_id
        WHERE p.numero_poliza = p_numero
          AND p.estatus_poliza = TRUE
        LIMIT 1;
      END
    `);
  }
};
